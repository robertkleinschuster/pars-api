<?php


namespace Pars\Api\Base;


class ApiNotFoundHandlerFactory
{
    public function __invoke()
    {
        return new ApiNotFoundHandler();
    }

}
