<?php


namespace Pars\Api\Base;


class ApiErrorResponseGeneratorFactory
{
    public function __invoke()
    {
        return new ApiErrorResponseGenerator();
    }

}
