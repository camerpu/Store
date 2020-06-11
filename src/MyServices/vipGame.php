<?php


namespace MyServices;


use Exception;
use Interfaces\defService;
use Store\addWithFlags;
use Store\Log;
use Store\Service;

class vipGame implements defService
{
    public function tryAdd(array $data, Service $servicename = null, int $serwer = null) : void
    {
        $addWithFlags = new addWithFlags();
        $dateNow = date("Y-m-d");
        $dateNow = $servicename->detDate($dateNow, $data['dni']);

        $addWithFlags->setFlags($data['flagi']);
        $addWithFlags->setDate($dateNow);
        $addWithFlags->setType($data['typ']);
        $addWithFlags->setLength($data['dni']);
        $addWithFlags->setSteamId($_SESSION['steamid32']);
        $addWithFlags->setNick($_SESSION['steam_personaname']);
        $addWithFlags->setIdserver($serwer);

        try
        {
            $addWithFlags->AddService();
        }
        catch (Exception $e)
        {
            session_start();
            $_SESSION['error'] = $e->getMessage();

            Log::logAction('On Try AddService Error: ' . $_SESSION['error'] . '     all data: ' . var_dump($_POST) . '       dane: ' . var_dump($data));

            header('Location: /usluga?id=' . $_POST['serviceid']);
            exit();
        }


    }
}