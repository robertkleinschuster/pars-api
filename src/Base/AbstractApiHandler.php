<?php

namespace Pars\Api\Base;

use Laminas\Db\Adapter\Adapter;
use Laminas\Diactoros\Response\JsonResponse;
use Niceshops\Bean\Finder\BeanFinderAwareInterface;
use Niceshops\Bean\Finder\BeanFinderAwareTrait;
use Pars\Helper\Parameter\IdParameter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class AbstractApiHandler
 * @package Pars\Api\Base
 */
abstract class AbstractApiHandler implements RequestHandlerInterface, BeanFinderAwareInterface
{
    use BeanFinderAwareTrait;

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
     * @return void
     */
    abstract protected function initialize(?string $finder = null): void;

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws ApiException
     * @throws \Niceshops\Core\Exception\AttributeExistsException
     * @throws \Niceshops\Core\Exception\AttributeLockException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->initialize($this->config['api']['finder'][$request->getAttribute(self::ATTRIBUTE_TABLE)]);
        if (!$this->hasBeanFinder()) {
            throw new ApiException('bean finder not set');
        }
        $id = new IdParameter();
        if ($request->getAttribute($id::name(), false)) {
            $id->fromString($request->getAttribute($id::name()));
        }
        $finder = $this->getBeanFinder();
        $finder->filter($id->getAttributes());
        return new JsonResponse($finder->getBeanList(true)->toArray(true));
    }

    /**
     * @return string
     */
    public static function getRoute()
    {
        return "/{" . self::ATTRIBUTE_TABLE . "}[/{" . IdParameter::name() . "}]";
    }
}
