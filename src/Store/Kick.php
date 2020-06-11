<?php

namespace Store;

class Kick
{
    /**
     * Kicking player on server, mainly to set lvl on Codmod
     * Kick constructor.
     * @param int $srvid
     * @param string $steamid
     */
    public function __construct(int $srvid, string $steamid)
    {
        $plik = "../config/servers/{$srvid}.php";
        require_once $_SERVER['DOCUMENT_ROOT'] . '/' . $plik;

        $polecenie = "sm_istniejenaserwerze678234123456 {$steamid}";
        DoReload($polecenie);
        sleep(3);
    }
}