<?php

namespace Store;

use \PDO;

class Log
{
    /**
     * Log constructor.
     * @param $typ
     * @param $nick
     * @param $steamid
     * @param $data
     * @param $nowa_data
     * @param $nazwaserwera
     *
     * Normalne logowanie po udanym zakupie
     */
    public function __construct(string $typ, string $nick, string $steamid, ?string $data, ?string $nowa_data, ?string $nazwaserwera)
    {
        $pdoSklep = Db::createInstance('Sklep');


        $q = $pdoSklep->prepare("INSERT INTO `logi` (`id`, `typ`, `nick`, `steamid`, `data kupna`, `data upływu`, `serwer`, `kod_zwrotny`) VALUES (NULL, :typ, :login, :steamid, :data, :nowa_data, :nazwaserwera, :kodzwrotny)");
        $q->bindParam( ':typ', $typ, PDO::PARAM_STR );
        $q->bindParam( ':login', $nick, PDO::PARAM_STR );
        $q->bindParam( ':steamid', $steamid, PDO::PARAM_STR );
        $q->bindParam( ':data', $data, PDO::PARAM_STR );
        $q->bindParam( ':nowa_data', $nowa_data, PDO::PARAM_STR );
        $q->bindParam( ':nazwaserwera', $nazwaserwera, PDO::PARAM_STR );
        $q->bindParam( ':kodzwrotny', $_POST['code'], PDO::PARAM_STR );
        $q->execute();

        $pdoSklep = null;
    }

    /**
     * @param $desc
     *
     * Logowanie błędów
     */
    public static function logAction(string $desc) : void
    {
        $pdoSklep = Db::createInstance('Sklep');

        $q = $pdoSklep->prepare("INSERT INTO `log_data` (`id`, `description`) VALUES (NULL, :desc)");
        $q->bindParam( ':desc', $desc, PDO::PARAM_STR );
        $q->execute();

        $pdoSklep = null;
    }

    /**
     * @param $code
     * @param $status
     * @param $nazwa
     * @param $typkupna
     *
     * Logowanie próby kupienia
     */
	public static function logAttempt(string $code, bool $status, string $nazwa, string $typkupna) : void
	{
		if($status == false)
		{
			$success = "False";
		}
		else
		{
			$success = "True";
		}
		
		if($typkupna != null)
		{
			$typ = "SMS";
		}
		else
		{
			$typ = "Przelew";
		}

        $pdoSklep = Db::createInstance('Sklep');

        $q = $pdoSklep->prepare("INSERT INTO `log_pr` (`id`, `kod`, `status`, `nazwa`, `typ_kupna`) VALUES (NULL, :kodzwrotny, :success, :nazwa, :typ)");
		$q->bindParam( ':kodzwrotny', $code, PDO::PARAM_STR );
        $q->bindParam( ':success', $success, PDO::PARAM_STR );
        $q->bindParam( ':nazwa', $nazwa, PDO::PARAM_STR );
        $q->bindParam( ':typ', $typ, PDO::PARAM_STR );
        $q->execute();

        $pdoSklep = null;
	}

}