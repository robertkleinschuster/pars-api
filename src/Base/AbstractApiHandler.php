<?php

namespace Pars\Api\Base;

use Laminas\Db\Adapter\Adapter;
use Laminas\Diactoros\Response\JsonResponse;
use Niceshops\Bean\Finder\BeanFinderAwareInterface;
use Niceshops\Bean\Finder\BeanFinderAwareTrait;
use Niceshops\Bean\Processor\BeanProcessorAwareInterface;
use Niceshops\Bean\Processor\BeanProcessorAwareTrait;
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
     * AbstractApiHandler constructor.
     * @param Adapter $adapter
     * @param array $config
     */
    public function __construct(Adapter $adapter, array $config)
    {
        $this->adapter = $adapter;
        $this->config = $config;
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
     * @throws \Niceshops\Core\Exception\AttributeExistsException
     * @throws \Niceshops\Core\Exception\AttributeLockException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->initialize(
            $this->config['api']['finder'][$request->getAttribute(self::ATTRIBUTE_TABLE)] ?? null,
            $this->config['api']['processor'][$request->getAttribute(self::ATTRIBUTE_TABLE)] ?? null
        );
        if (!$this->hasBeanFinder()) {
            return new JsonResponse(array_keys($this->config['api']['finder']));
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

        $this->handleFinder($id, $pagination);
        $this->handleRequest($request);
        return new JsonResponse($this->getBeanFinder()->getBeanList(true)->toArray(true));
    }

    /**
     * @param IdParameter $id
     * @throws \Niceshops\Core\Exception\AttributeNotFoundException
     */
    protected function handleFinder(IdParameter $id, PaginationParameter $paginationParameter)
    {
        $this->getBeanFinder()->filter($id->getAttributes());
        if ($paginationParameter->hasLimit() && $paginationParameter->hasPage()) {
            $this->getBeanFinder()->limit($paginationParameter->getLimit(), $paginationParameter->getPage());
        }
    }

    /**
     * @param ServerRequestInterface $request
     */
    protected function handleRequest(ServerRequestInterface $request)
    {

    }

    /**
     * @return string
     */
    public static function getRoute()
    {
        return "[/[{" . self::ATTRIBUTE_TABLE . "}[/{" . IdParameter::name() . "}]]]";
    }
}
