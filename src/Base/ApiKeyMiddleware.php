<?php


namespace Pars\Api\Base;


use Laminas\Db\Adapter\Adapter;
use Laminas\Diactoros\Response\JsonResponse;
use Pars\Model\Authentication\ApiKey\ApiKeyBeanFinder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ApiKeyMiddleware implements MiddlewareInterface
{
    protected array $config;
    protected Adapter $adapter;

    public const ATTRIBUTE_CORS = 'cors';

    /**
     * ApiKeyMiddleware constructor.
     * @param array $config
     * @param Adapter $adapter
     */
    public function __construct(array $config, Adapter $adapter)
    {
        $this->config = $config;
        $this->adapter = $adapter;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!($this->config['api']['key']['enabled'] ?? false)) {
            return $handler->handle($request);
        }
        $responseData = new ResponseData();
        $responseData->error = 'Unauthorized';

        $finder = new ApiKeyBeanFinder($this->adapter);
        $serverParams = $request->getServerParams();
        $referrer = $serverParams['HTTP_REFERER'] ?? '';
        $finder->setApiKey_Active(true);
        $finder->setApiKey_Host($referrer);
        $finder->limit(1, 0);
        if ($finder->count()) {
            $apiKeyBean = $finder->getBean();
            $sentkey = $request->getHeaderLine('api-key');
            if ($apiKeyBean->ApiKey_Key == $sentkey) {
                return $handler->handle($request->withAttribute(self::ATTRIBUTE_CORS, $apiKeyBean->ApiKey_Host));
            } else {
                $responseData->error = 'Invalid API Key';
            }
        }
        return (new JsonResponse($responseData, 403))->withHeader('Access-Control-Allow-Origin', '*');
    }

}
