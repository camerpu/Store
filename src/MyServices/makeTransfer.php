<?php


namespace MyServices;


use Exception;
use Interfaces\defService;
use Store\Codmod;
use Store\Kick;
use Store\Service;

class makeTransfer implements defService
{
    public function tryAdd(array $data, Service $service = null, int $serwer = null) : void
    {
        new Kick($serwer, $_SESSION['steamid']);

        $steamid = Service::usePropSteamID($_SESSION['steamid32']);
        $codmod = new Codmod();

        try
        {
            $oldlevel = $codmod->getLevel($steamid, $_POST['old']);
            $newlevel = $codmod->getLevel($steamid, $_POST['new']);
        }
        catch (Exception $e)
        {
            session_start();
            $_SESSION['error'] = $e->getMessage();
            header('Location: /usluga?id=' . $_POST['serviceid']);
            exit();
        }

        $ilelvli = $oldlevel + $newlevel;

        if($ilelvli > 301)
            $ilelvli = 301;


        $codmod->setLevel($steamid, $_POST['old'], 0);
        $codmod->setStats($steamid, $_POST['old']);
        $codmod->setLevel($steamid, $_POST['new'], $ilelvli);
    }
}