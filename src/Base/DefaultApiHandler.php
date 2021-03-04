<?php


namespace Pars\Api\Base;


final class DefaultApiHandler extends AbstractApiHandler
{
    /**
     * @param string|null $finder
     */
    protected function initialize(?string $finder = null): void
    {
        $this->setBeanFinder(new $finder($this->adapter));
    }

}
