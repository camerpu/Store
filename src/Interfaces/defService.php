<?php

namespace Interfaces;

use Store\Service;

interface defService
{
    /**
     * Try to add service
     * @param array $data
     * @param Service|null $service
     * @param string|null $serwer
     */
    public function tryAdd(array $data, Service $servicename = null, int $serwer = null) : void;
}