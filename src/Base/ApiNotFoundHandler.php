<?php


namespace Pars\Api\Base;


use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ApiNotFoundHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $responseData = new ResponseData();
        $responseData->error = 'Not Found';
        return (new JsonResponse($responseData, 404))->withHeader('Access-Control-Allow-Origin', '*');;
    }

}
