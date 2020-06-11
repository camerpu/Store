<?php

namespace Store;

use \PDO;

class Stats
{
    /**
     * Zwraca ilość sprzedanych usług w obecnym miesiącu
     * @return array|null
     */
    public function getForMonths() : ?array
    {
        $pdo = Db::createInstance('Sklep');

        $year = date('Y');
        $data = [];
        for($m=1; $m<=12; $m++)
        {
            $query = "SELECT COUNT(*) AS `max` FROM logi WHERE MONTH(`data kupna`)={$m} AND YEAR(`data kupna`)={$year}";
            $sth = $pdo->prepare($query);
            $sth->execute();
            $row = $sth->fetch();

            $data[] = $row['max'];
        }
        return $data;
    }

    /**
     * Zwraca ilość sprzedanych VIPów dla poszczególnych serwerów
     * @return array|null
     */
    public function forServers() : ?array
    {
        $serwery = ['Arena #1', 'Arena #2', 'AWP Only', 'Cod Mod', 'FFA', 'DM', 'Only DD2/MIR', 'Surf Combat'];
        $pdo = Db::createInstance('Sklep');

        $data = [];
        foreach($serwery as $serwer)
        {
            $serwer = strtoupper($serwer);
            $sth = $pdo->prepare("SELECT COUNT(*) AS `max` FROM logi WHERE `serwer`=:serwer");
            $sth->bindParam(':serwer', $serwer, PDO::PARAM_STR);

            $sth->execute();
            $row = $sth->fetch();

            $data[] = $row['max'];
        }
        return $data;
    }

    /**
     * Zwraca listę klas na CodModzie
     * @return array|null
     */
    public function codClassNames() : ?array
    {
        $pdo = Db::createInstance('CodMod');
        $data = [];
        $query = "SELECT DISTINCT `class` FROM codmod_steamid ORDER BY `class`";
        $sth = $pdo->prepare($query);
        $sth->execute();
        while($row = $sth->fetch())
        {
            $data[] = $row['class'];
        }

        return $data;
    }

    /**
     * Zwraca ilość poszczególnych klas
     * @return array|null
     */
    public function codClassCount() : ?array
    {
        $pdo = Db::createInstance('CodMod');
        $data = [];
        $query = "SELECT COUNT(`steamid`) AS ile FROM codmod_steamid GROUP BY `class` ORDER BY `class`";
        $sth = $pdo->prepare($query);
        $sth->execute();
        while($row = $sth->fetch())
        {
            $data[] = $row['ile'];
        }

        return $data;
    }

    /**
     * Zwraca ilość dla typów płatności(sms i przelew)
     * @return array|null
     */
    public function forPayments() : ?array
    {
        $payments = ['PRZELEW', 'SMS'];
        $pdo = Db::createInstance('Sklep');

        $data = [];
        foreach($payments as $payment)
        {
            $query = "SELECT COUNT(*) AS `max` FROM logi WHERE `typ` LIKE '%{$payment}%'";
            $sth = $pdo->prepare($query);
            $sth->execute();
            $row = $sth->fetch();

            $data[] = $row['max'];
        }
        return $data;
    }

    /**
     * Liczba sprzedanych VIPów od początku
     * @return int|null
     */
    public function allVIPS() : ?int
    {
        $pdo = Db::createInstance('Sklep');

        $sth = $pdo->prepare("SELECT COUNT(*) AS `all` FROM logi");
        $sth->execute();
        $row = $sth->fetch();
        return $row['all'];
    }

    /**
     * Liczba sprzedanych VIPów w tym roku
     * @return int|null
     */
    public function thisYear() : ?int
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/' . 'src/Db.php';
        $pdo = Db::createInstance('Sklep');
        $year = date('Y');
        $sth = $pdo->prepare("SELECT COUNT(*) AS `all` FROM logi WHERE YEAR(`logi`.`data kupna`)=:yr");
        $sth->bindParam(':yr', $year, PDO::PARAM_INT);
        $sth->execute();
        $row = $sth->fetch();
        return $row['all'];
    }

    /**
     * Liczba sprzedanych VIPów w poprzednim roku
     * @return int|null
     */
    public function previousYear() : ?int
    {
        $pdo = Db::createInstance('Sklep');
        $year = (int)date("Y");
        $year -= 1;
        $sth = $pdo->prepare("SELECT COUNT(*) AS `all` FROM logi WHERE YEAR(`logi`.`data kupna`)=:yr");
        $sth->bindParam(':yr', $year, PDO::PARAM_INT);
        $sth->execute();
        $row = $sth->fetch();
        return $row['all'];
    }

    /**
     * Liczba sprzedanych VIPów w tym miesiącu
     * @return int|null
     */
    public function thisMonth() : ?int
    {
        $pdo = Db::createInstance('Sklep');
        $month = date('m');
        $mn = (int)date("Y");;
        $sth = $pdo->prepare("SELECT COUNT(*) AS `all` FROM logi WHERE MONTH(`logi`.`data kupna`)=:yr AND YEAR(`logi`.`data kupna`)=:mn");
        $sth->bindParam(':yr', $month, PDO::PARAM_INT);
        $sth->bindParam(':mn', $mn, PDO::PARAM_INT);
        $sth->execute();
        $row = $sth->fetch();
        return $row['all'];
    }
}