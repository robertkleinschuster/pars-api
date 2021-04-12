<?php

namespace Pars\Api\Base;

use Laminas\Db\Adapter\Adapter;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\Helper\UrlHelper;
use Pars\Bean\Finder\BeanFinderAwareInterface;
use Pars\Bean\Finder\BeanFinderAwareTrait;
use Pars\Bean\Processor\BeanProcessorAwareInterface;
use Pars\Bean\Processor\BeanProcessorAwareTrait;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\InvalidParameterException;
use Pars\Helper\Parameter\PaginationParameter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class AbstractApiHandler
 * @package Pars\Api\Base
 */
abstract class AbstractApiHandler implements RequestHandlerInterface, BeanFinderAwareInterface, BeanProcessorAwareInterface
{
    use BeanFinderAwareTrait;
    use BeanProcessorAwareTrait;

    protected const ATTRIBUTE_TABLE = 'table';

    /**
     * @var Adapter
     */
    protected Adapter $adapter;

    /**
     * @var array
     */
    protected array $config;

    /**
     * @var UrlHelper
     */
    protected UrlHelper $urlHelper;

    /**
     * @var ResponseData
     */
    protected ResponseData $responseData;

    /**
     * AbstractApiHandler constructor.
     * @param Adapter $adapter
     * @param array $config
     * @param UrlHelper $urlHelper
     */
    public function __construct(Adapter $adapter, array $config, UrlHelper $urlHelper)
    {
        $this->adapter = $adapter;
        $this->config = $config;
        $this->urlHelper = $urlHelper;
        $this->responseData = new ResponseData();
    }

    /**
     * @param string|null $finder
     * @param string|null $processor
     * @return void
     */
    protected function initialize(?string $finder = null, ?string $processor = null): void
    {
        if ($finder) {
            $this->setBeanFinder(new $finder($this->adapter));
        }
        if ($processor) {
            $this->setBeanProcessor(new $processor($this->adapter));
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws ApiException
     * @throws \Pars\Pattern\Exception\AttributeExistsException
     * @throws \Pars\Pattern\Exception\AttributeLockException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->initialize(
            $this->config['api']['finder'][$request->getAttribute(self::ATTRIBUTE_TABLE)] ?? null,
            $this->config['api']['processor'][$request->getAttribute(self::ATTRIBUTE_TABLE)] ?? null
        );
        $this->getResponseData()->links['resources'] = array_map(function($x){return $this->urlHelper->generate(null, [self::ATTRIBUTE_TABLE => $x, IdParameter::name() => null]);}, array_keys($this->config['api']['finder']));

        $this->getResponseData()->links['templates'] = [
            'list' => $this->urlHelper->generate(null, [self::ATTRIBUTE_TABLE => '{resource}', IdParameter::name() => null]),
            'entry' => $this->urlHelper->generate(null, [self::ATTRIBUTE_TABLE => '{resource}', IdParameter::name() => '{field}:{value}']),
            'pagination' => $this->urlHelper->generate(null, [self::ATTRIBUTE_TABLE => '{resource}', IdParameter::name() => null], [PaginationParameter::name() => PaginationParameter::ATTRIBUTE_LIMIT . ':{limit};' . PaginationParameter::ATTRIBUTE_PAGE . ':{page}']),
            'create' => $this->urlHelper->generate(null, [self::ATTRIBUTE_TABLE => '{resource}', IdParameter::name() => 'create']),
        ];

        if (!$this->hasBeanFinder()) {
            return $this->createResponse($request);
        }
        $id = new IdParameter();
        if ($request->getAttribute($id::name(), false)) {
            try {
                $id->fromString($request->getAttribute($id::name()));
            } catch (InvalidParameterException $exception) {

            }
        }
        $data = $request->getQueryParams();
        $pagination = new PaginationParameter();
        if (isset($data[$pagination::name()])) {
            $pagination->fromString($data[$pagination::name()]);
        }
        $this->initFinder($id, $pagination);
        $this->loadData($request);
        $this->submitData($request);
        return $this->createResponse($request);
    }

    /**
     * @return ResponseData
     */
    public function getResponseData(): ResponseData
    {
        return $this->responseData;
    }

    /**
     * @param IdParameter $id
     * @param PaginationParameter $paginationParameter
     * @throws \Pars\Pattern\Exception\AttributeNotFoundException
     */
    protected function initFinder(IdParameter $id, PaginationParameter $paginationParameter)
    {
        $this->getBeanFinder()->filter($id->getAttributes());
        $finder = $this->getBeanFinder();
        if ($id->hasAttribute('Locale_Code')) {
            if (method_exists($finder, 'filterLocale_Code')) {
                $finder->filterLocale_Code($id->getAttribute('Locale_Code'));
            }
        }
        $this->getResponseData()->count = $this->getBeanFinder()->count();
        $routeParams = [];
        $queryParams = [];
        if (count($id->getAttributes())) {
            $routeParams[$id::name()] = $id->toString();
        }
        if ($paginationParameter->hasLimit() && $paginationParameter->hasPage()) {
            $queryParams[$paginationParameter::name()] = $paginationParameter->toString();
            $this->getResponseData()->limit = $paginationParameter->getLimit();
            $this->getResponseData()->page = $paginationParameter->getPage();
            $this->getResponseData()->pageCount = ceil(
                $this->getResponseData()->count / $this->getResponseData()->limit
            );
            $this->getBeanFinder()->limit(
                $paginationParameter->getLimit(),
                ($paginationParameter->getPage() - 1) * $paginationParameter->getLimit()
            );
        }
        $this->getResponseData()->links['self'] = $this->urlHelper->generate(
            null,
            $routeParams,
            $queryParams
        );
        for ($i = 1; $i <= $this->getResponseData()->pageCount; $i++) {
            $paginationParameterList = clone $paginationParameter;
            $paginationParameterList->setPage($i);
            $queryParams[$paginationParameterList::name()] = $paginationParameterList->toString();
            $this->getResponseData()->links['pages'][$i] = $this->urlHelper->generate(
                null,
                $routeParams,
                $queryParams
            );
        }
        if ($paginationParameter->hasPage() && $paginationParameter->getPage() > 1) {
            $paginationParameterPrev = clone $paginationParameter;
            $paginationParameterPrev->setPage($paginationParameter->getPage() - 1);
            $queryParams[$paginationParameterPrev::name()] = $paginationParameterPrev->toString();
            $this->getResponseData()->links['prev'] = $this->urlHelper->generate(
                null,
                $routeParams,
                $queryParams
            );
        }
        if ($paginationParameter->hasPage() && $paginationParameter->getPage() < $this->getResponseData()->pageCount) {
            $paginationParameterNext = clone $paginationParameter;
            $paginationParameterNext->setPage($paginationParameter->getPage() + 1);
            $queryParams[$paginationParameterNext::name()] = $paginationParameterNext->toString();
            $this->getResponseData()->links['next'] = $this->urlHelper->generate(
                null,
                $routeParams,
                $queryParams
            );
        }
    }

    /**
     * @param ServerRequestInterface $request
     */
    protected function loadData(ServerRequestInterface $request)
    {
        if ($request->getAttribute(IdParameter::name()) == 'create') {
            $this->getResponseData()->data = $this->getBeanFinder()->getBeanFactory()->getEmptyBean([])->toArray(true);
        } else {
            if ($this->getResponseData()->count == 1) {
                $this->getResponseData()->data = $this->getBeanFinder()->getBean(true)->toArray(true);
            } else {
                $beanList = $this->getBeanFinder()->getBeanList(true);
                $this->getResponseData()->data = $beanList->toArray(true);
            }
        }
    }

    /**
     * @param ServerRequestInterface $request
     */
    protected function submitData(ServerRequestInterface $request)
    {

    }

    /**
     * @param ServerRequestInterface $request
     * @return JsonResponse
     */
    protected function createResponse(ServerRequestInterface $request): JsonResponse
    {
        return (new JsonResponse($this->getResponseData()))->withHeader(
            'Access-Control-Allow-Origin',
            $request->getAttribute(ApiKeyMiddleware::ATTRIBUTE_CORS) ?? '*'
        );
    }

    /**
     * @return string
     */
    public static function getRoute()
    {
        return "[/[{" . self::ATTRIBUTE_TABLE . "}[/{" . IdParameter::name() . "}]]]";
    }
}
