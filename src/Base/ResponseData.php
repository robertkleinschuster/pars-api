<?php

namespace Pars\Api\Base;

use Pars\Bean\Type\Base\AbstractBaseBean;

/**
 * Class ResponseData
 * @package Pars\Api\Base
 */
class ResponseData extends AbstractBaseBean
{
    public ?array $data = null;
    public ?string $error = null;
    public ?array $links = [];
    public ?int $count = null;
    public ?int $page = null;
    public ?int $pageCount = null;
    public ?int $limit = null;

}
