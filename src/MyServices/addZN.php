<?php


namespace MyServices;


use Interfaces\defService;
use Store\Codmod;
use Store\Log;
use Store\Service;

class addZN implements defService
{
    public function tryAdd(array $data, Service $service = null, int $serwer = null) : void
    {
        $service = new Codmod();
        $ile = $service->getZN(Service::usePropSteamID($_SESSION['steamid32']));
        if($ile == -1)
            $ile = 0;

        $service->setZN(Service::usePropSteamID($_SESSION['steamid32']), $ile+$data['dni']);
    }

}