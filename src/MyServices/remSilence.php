<?php


namespace MyServices;


use Interfaces\defService;
use Store\Db;
use Store\Log;
use Store\Service;
use \PDO;

class remSilence implements defService
{
    public function tryAdd(array $data, Service $service = null, int $serwer = null) : void
    {
        try
        {
            $this->doUnsilence(Service::usePropSteamID($_SESSION['steamid32']));
        }
        catch (Exception $e)
        {
            session_start();
            $_SESSION['error'] = $e->getMessage();

            Log::logAction('On Try removeSilence Error: ' . $_SESSION['error'] . '     all data: ' . var_dump($_POST) . '       dane: ' . var_dump($dane));

            header('Location: /usluga?id=' . $_POST['serviceid']);
            exit();
        }
    }


    /**
     * @param $steamid
     * @throws Exception
     *
     * UnSilence, zmienia wartości w tabeli sb_comms
     */
    private function doUnsilence(string $steamid) : void
    {
        if(empty($steamid))
        {
            throw new Exception("Brak ustawionego steamid");
        }
        else
        {
            $pdo = Db::createInstance('SourceBans');
            try {
                $sthUpdate = $pdo->prepare("UPDATE `sb_comms` SET `RemovedBy` = 0, `RemoveType`= 'E', `RemovedOn`=UNIX_TIMESTAMP(),
                                                 `ureason`='Zakupione poprzez sklep' WHERE `authid` LIKE :steamid AND `reason` LIKE 'Silence%' AND `RemovedBy` IS NULL");
                $sthUpdate->bindParam(':steamid', $steamid, PDO::PARAM_STR);
                $sthUpdate->execute();
            }
            catch(Exception $e)
            {
                throw new Exception("SQL Error");
            }
        }
    }
}