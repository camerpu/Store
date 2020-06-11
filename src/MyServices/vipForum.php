<?php

namespace MyServices;

use Exception;
use Interfaces\defService;
use Store\Forum;
use Store\Service;
use Store\Log;

class vipForum implements defService
{
    public function tryAdd(array $data, Service $service = null, int $serwer = null) : void
    {
        $forum = new Forum();
        $member_id = null;
        try
        {
            $member_id = $forum->exist($_SESSION['steam_steamid']);
            $forum->setVIP($member_id);
            $forum->addToSystem($member_id);
        }
        catch (Exception $e)
        {
            session_start();
            $_SESSION['error'] = $e->getMessage();

            Log::logAction('On Try addVipOnForum Error: ' . $_SESSION['error'] . '     all data: ' . var_dump($_POST) . '       dane: ' . var_dump($data));

            header('Location: /usluga?id=' . $_POST['serviceid']);
            exit();
        }
    }
}