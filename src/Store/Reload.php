<?php

namespace Store;

class Reload
{
    /**
     * Reload constructor.
     * @param $steamid
     * @param $srvid
     *
     * Przeładowywuje usługi gracza jeśli jest na serwerze
     */
    public function __construct(string $steamid, int $srvid)
    {
        $plik = "../config/servers/{$srvid}.php";
        require_once $_SERVER['DOCUMENT_ROOT'] . '/' . $plik;

        $polecenie = "sm_istniejegracz {$steamid}";
        DoReload($polecenie);
    }
}