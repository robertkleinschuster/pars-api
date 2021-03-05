<?php


namespace Pars\Api\Base;


use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ApiErrorResponseGenerator
{
    public function __invoke(
        \Throwable $e,
        ServerRequestInterface $request,
        ResponseInterface $response
    ) : ResponseInterface {
        $responseData = new ResponseData();
        $responseData->error  = $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        return (new JsonResponse($responseData, 500))->withHeader('Access-Control-Allow-Origin', '*');
    }
}
