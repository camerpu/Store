<?php

namespace Store;

use \PDO;

class Service
{

    /**
     * Specjalan forma steamid32 z % do użycia w konstrukcji LIKE
     *
     * @param $steamid
     * @return array|string
     */
    public static function usePropSteamID(string $steamid) : string
    {
        $exploded = explode(":" , $steamid);
        $exploded =  trim($exploded[2]);
        $exploded = '%' . $exploded . '%';
        return $exploded;
    }

    /**
     * Zwraca kupione usługi gracza po steamid
     *
     * @param $steamid
     * @return array
     */
    public static function getServices($steamid) : ?array
    {
        $dane = [];
        $pdo = Db::createInstance('System');

        $sth = $pdo->prepare("SELECT * FROM system_flag WHERE steamid LIKE :steamid AND `typ`='vip'");
        $sth->bindParam(':steamid', $steamid, PDO::PARAM_STR);
        $sth->execute();
        while($row = $sth->fetch())
        {
            $serwer = null;
            for($i = 1; $i<=15; $i++)
            {
                if($row[$i] != null && $row[$i] != "")
                {
                    $serwer = $i;
                }
            }
            $serwer = Service::FormatujNazweSerwera($serwer);
            $dane[] = [
                'nick' => $row['nick'],
                'data' => $row['data'],
                'serwer' => $serwer,
            ];
        }
        $pdo = null;
        return $dane;
    }

    /**
     * Formatowanie nazwy serwera
     * @param $serwer
     * @return string
     *
     * TODO Jakoś bazodanowo to zrobić
     */
    public static function FormatujNazweSerwera(string $serwer) : string
    {
        switch($serwer)
        {
            case 2:
                $nazwa = "DM";
                break;
            case 4:
                $nazwa = "DM #2";
                break;				
            case 5:
                $nazwa = "ARENA #1";
                break;
            case 6:
                $nazwa = "SURF COMBAT";
                break;
            case 7:
                $nazwa = "SURF SKILL";
                break;
            case 3:
                $nazwa = "RETAKES";
                break;
            case 9:
                $nazwa = "AWP ONLY";
                break;
            case 10:
                $nazwa = "COD MOD";
                break;
            case 11:
                $nazwa = "ONLY DD2/MIR";
                break;
            case 12:
                $nazwa = "ARENA #2";
                break;
            case 13:
                $nazwa = "FFA";
                break;
            default:
                $nazwa = "Brak nazwy serwera";
                break;
        }
        return $nazwa;
    }

    /**
     * Pobiera dostępne usługi zwykłe(bez CODa)
     * @return array|null
     */
    public static function loadServices() : ?array
    {
        $dane = [];
        $pdo = Db::createInstance('System');

        $sth = $pdo->prepare("SELECT id, nazwa, inne, codmod FROM services");
        $sth->execute();
        while($row = $sth->fetch())
        {
            if($row['codmod'] == FALSE)
            {
                $dane[] = [
                    'id' => $row['id'],
                    'nazwa' => $row['nazwa'],
                    'inne' => $row['inne']
                ];
            }
        }
        $pdo = null;
        return $dane;
    }

    /**
     * Pobiera wszystkie usługi bez wyjątku
     * @return array|null
     */
    public static function loadAllServices() : ?array
    {
        $dane = [];
        $pdo = Db::createInstance('System');

        $sth = $pdo->prepare("SELECT id, nazwa, inne, codmod FROM services");
        $sth->execute();
        while($row = $sth->fetch())
        {
            $dane[] = [
                'id' => $row['id'],
                'nazwa' => $row['nazwa'],
                'inne' => $row['inne'],
                'codmod' => $row['codmod']
            ];
        }
        $pdo = null;
        return $dane;
    }

    /**
     * @return array
     * Pobiera tylko usługi CODa
     */
    public static function loadCodModServices() : ?array
    {
        $dane = [];
        $pdo = Db::createInstance('System');

        $sth = $pdo->prepare("SELECT id, nazwa, inne, codmod FROM services");
        $sth->execute();
        while($row = $sth->fetch())
        {
            $codmod = $row['codmod'];
            if($codmod == 1 || $codmod == 2 || $codmod == 3 || $codmod == 4)
            {
                $dane[] = [
                    'id' => $row['id'],
                    'nazwa' => $row['nazwa'],
                    'inne' => $row['inne']
                ];
            }
        }
        $pdo = null;
        return $dane;
    }

    /**
     * @param $idsrv
     * @return array|null
     * Wczytuje dane konkretnej usługi
     */
    public static function loadService(int $idsrv) : ?array
    {
        $pdo = Db::createInstance('System');

        $sth = $pdo->prepare("SELECT * FROM services WHERE `id` = :idsrv");
        $sth->bindParam(':idsrv', $idsrv, PDO::PARAM_INT);
        $sth->execute();
        $dane = null;
        if($row = $sth->fetch())
        {
            $dane = [
                'id' => $row['id'],
                'nazwa' => $row['nazwa'],
                'typ' => $row['typ'],
                'flagi' => $row['flagi'],
                'typkupna' => $row['typkupna'],
                'dni' => $row['dni'],
                'inne' => $row['inne'],
                'codmod' => $row['codmod'],
                'disabled' => $row['disabled']
            ];
        }
        $pdo = null;
        return $dane;
    }

    /**
     * Sprawdza czy ID serwera jest poprawne(przychodzi z $_POST)
     * @param int $serwer
     * @return int
     * @throws Exception
     */
    public static function getServerId(int $serwer) : int
    {
        if(is_int($serwer) && ($serwer < 20 && $serwer > 0))
        {
            return $serwer;
        }
        else
        {
            throw new Exception("Brak takiego ID serwera");
        }
    }

    /**
     * @return array
     * Zwraca funkcje VIPa z bazy
     *
     * TODO - Dodać w panelu admina mozliwosc edycji
     */
    public static function loadVipPrivileges() : array
    {
        $tablica = [];
        $pdo = Db::createInstance('System');

        $sth = $pdo->prepare('SELECT * FROM funkcjevipa');
        $sth->execute();
        while($row = $sth->fetch())
        {
            $tablica[] = [
                'banner' => $row['banner'],
                'tekst' => $row['funkcje']
            ];
        }

        $pdo = null;
        return $tablica;
    }

    /**
     * @return array|null
     * Zwraca dane serwerów
     */
    public static function getServers()
    {
        $pdo = Db::createInstance('System');

        $sth = $pdo->prepare("SELECT * FROM servers");
        $sth->execute();
        $dane = null;
        while($row = $sth->fetch())
        {
            $dane[] = [
                'id' => $row['id'],
                'idserwera' => $row['idserwera'],
                'nazwa' => $row['nazwa']
            ];
        }
        $pdo = null;
        return $dane;
    }


    /**
     * @param $type
     * @return int|null
     * Zwraca sposób platnosci, null to przelew
     */
    public function CheckBuyType(string $type) : ?int
    {
        if($type == 'sms7')
                return 7;
        else if($type == 'sms30')
                return 30;
        else
            return null;
    }

    /**
     * @param $code
     * @param $type
     * @return bool
     *
     * Sprawdzanie po API MicroSms kodu
     */
    public function checkSmsCode(string $code, int $type) : bool
    {
        switch($type)
        {
            case 7:
                $settings = array(
                    'userid' => '111111111111',
                    'serviceid' => '111111111111111',
                    'text' => 'MSMS.MAXPLAY',
                );

                $data[] = array("netto" => 6,"number" => 76480,"product" => "vip7dni");
                $code = addslashes($code);

                if (preg_match("/^[A-Za-z0-9]{8}$/", $code)) {

                    $a = array();
                    $b = array();

                    foreach ($data as $cfg) {
                        array_push($a, $cfg['number']);
                        $b[$cfg['number']] = $cfg['product'];
                    }

                    $api = @file_get_contents("http://microsms.pl/api/v2/multi.php?userid=" . $settings['userid'] . "&code=" . $code . '&serviceid=' . $settings['serviceid']);

                    if (!isset($api)) {
                        return false;
                    } else {

                        $api = json_decode($api);

                        if (!is_object($api)) {
                            return false;
                        } else if (isset($api->error) && $api->error) {
                            return false;
                        } else if ($api->connect == FALSE) {
                            return false;
                        } else if (!isset($b[$api->data->number])) {
                            return false;
                        }
                    }

                    if (!isset($errormsg) && isset($api->connect) && $api->connect == TRUE) {
                        if ($api->data->status == 1) {
                            return true;

                        } else {
                            return false;
                        }
                    }

                } else {
                    return false;
                }
                break;
            case 30:
                $settings = array(
                    'userid' => '1111111111111111111',
                    'serviceid' => '1111111111111111',
                    'text' => 'MSMS.MAXPLAY',
                );

                $data[] = array("netto" => 10,"number" => 79480,"product" => "vip30dni");
                $code = addslashes($code);

                if (preg_match("/^[A-Za-z0-9]{8}$/", $code)) {

                    $a = array();
                    $b = array();

                    foreach ($data as $cfg) {
                        array_push($a, $cfg['number']);
                        $b[$cfg['number']] = $cfg['product'];
                    }

                    $api = @file_get_contents("http://microsms.pl/api/v2/multi.php?userid=" . $settings['userid'] . "&code=" . $code . '&serviceid=' . $settings['serviceid']);

                    if (!isset($api)) {
                        return false;
                    } else {

                        $api = json_decode($api);

                        if (!is_object($api)) {
                            return false;
                        } else if (isset($api->error) && $api->error) {
                            return false;
                        } else if ($api->connect == FALSE) {
                            return false;
                        } else if (!isset($b[$api->data->number])) {
                            return false;
                        }
                    }

                    if (!isset($errormsg) && isset($api->connect) && $api->connect == TRUE) {
                        if ($api->data->status == 1) {
                            return true;

                        } else {
                            return false;
                        }
                    }

                } else {
                    return false;
                }
        }
    }

    /**
     * @param $code
     * @return bool
     * Sprawdzanie kodu przelewu w bazie danych
     * TODO kody trzeba generować, przydałoby się przerobić na użycie api dotpaya
     */
    public function checkTransferCode(string $code) : bool
    {
        $kod = trim($code);
        $kod = htmlentities($kod, ENT_QUOTES, "UTF-8");

        $pdo = Db::createInstance('Sklep');

        $sth = $pdo->prepare('SELECT * FROM kodyprzel WHERE kod = :kod');
        $sth->bindParam(':kod', $kod, PDO::PARAM_STR);
        $sth->execute();
        $num = $sth->rowCount();
        if($num > 0)
        {
            $sth = $pdo->prepare('DELETE FROM `kodyprzel` WHERE `kodyprzel`.`kod` = :kod');
            $sth->bindParam(':kod', $kod, PDO::PARAM_STR);
            $sth->execute();
            $pdo = null;
            return true;
        }
        $pdo = null;
        return false;
    }

    /**
     * @param $startdate
     * @param $days
     * @return false|string
     *
     * Dodaje dni do daty
     * TODO przerobić na obkietowe
     */
    public function detDate(string $startdate, int $days) : string
    {
        $d1 = strtotime($startdate);
        $d2 = $days*86400;
        $d8 = $d1 + $d2;
        $nju = date("Y-m-d", $d8);
        return $nju;
    }

    /**
     * Usuwa wygaśnięte VIPy
     */
    public static function deleteOld() : void
    {
        $pdo = Db::createInstance('System');
        $update = $pdo->prepare("DELETE FROM `system_flag` WHERE typ='vip' and data<now()");
        $update->execute();
        $pdo = null;
    }
}