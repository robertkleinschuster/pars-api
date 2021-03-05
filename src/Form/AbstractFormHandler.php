<?php


namespace Pars\Api\Form;


use Laminas\Db\Adapter\Adapter;
use Laminas\Diactoros\Response\JsonResponse;
use Pars\Api\Base\ResponseData;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class AbstractFormHandler implements RequestHandlerInterface
{
    protected array $config;
    protected Adapter $adapter;

    /**
     * AbstractFormHandler constructor.
     * @param array $config
     * @param Adapter $adapter
     */
    public function __construct(array $config, Adapter $adapter)
    {
        $this->config = $config;
        $this->adapter = $adapter;
    }


    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse(new ResponseData());
    }

}
