<?php


namespace MyServices;


use Interfaces\defService;
use Store\Codmod;
use Store\Kick;
use Store\Service;

class addLVL implements defService
{
    public function tryAdd(array $data, Service $service = null, int $serwer = null) : void
    {
        new Kick($serwer, $_SESSION['steamid']);

        $steamid = Service::usePropSteamID($_SESSION['steamid32']);
        $ilelvli = $data['dni'];
        $codmod = new Codmod();

        try
        {
            $level = $codmod->getLevel($steamid, $_POST['old']);
        }
        catch (Exception $e)
        {
            session_start();
            $_SESSION['error'] = $e->getMessage();
            header('Location: /usluga?id=' . $_POST['serviceid']);
            exit();
        }

        if($ilelvli+$level > 301)
        {
            $codmod->setLevel($steamid, $_POST['old'], 301);
        }
        else
        {
            $codmod->setLevel($steamid, $_POST['old'], $level+$ilelvli);
        }
    }
}