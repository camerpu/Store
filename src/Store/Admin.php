<?php
/**
 * Created by PhpStorm.
 * User: Camer
 * Date: 30.12.2017
 * Time: 14:25
 */
namespace Store;

use \PDO;

class Admin
{
    /**
     * Check if he's admin - by steamid(session)
     * @param string $steamid
     * @return bool
     */
    public function isAdmin(string $steamid) : bool
    {
        $pdo = Db::createInstance('System');

        $sth = $pdo->prepare("SELECT `steamid` FROM admins WHERE `steamid`=:steamid");
        $sth->bindParam(':steamid', $steamid, PDO::PARAM_STR);
        $sth->execute();
        if($sth->rowCount() == 1)
        {
            $pdo = null;
            return true;
        }
        $pdo = null;
        return false;
    }

    /**
     * Delete server from store
     * @param int $id
     */
    public function deleteServer(int $id) : void
    {
        $pdo = Db::createInstance('System');

        $sth = $pdo->prepare("DELETE FROM `servers` WHERE `servers`.`id` = :id");
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();
        $pdo = null;
    }

    /**
     * Add server to store
     * @param int $id
     * @param string $nazwa
     */
    public function addServer(int $id, string $nazwa) : void
    {
        $pdo = Db::createInstance('System');

        $sth = $pdo->prepare("INSERT INTO `servers` (`id`, `idserwera`, `nazwa`) VALUES (NULL, :id, :nazwa)");
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->bindParam(':nazwa', $nazwa, PDO::PARAM_STR);
        $sth->execute();
        $pdo = null;
    }

    /**
     * List data of server from store
     * @return array|null
     */
    public function listServers() : ?array
    {
        $pdo = Db::createInstance('System');
        $sth = $pdo->prepare("SELECT * FROM servers");
        $sth->execute();
        $dane = [];
        while($row = $sth->fetch())
        {
            $id = $row['id'];
            $idserwera = $row['idserwera'];
            $nazwa = $row['nazwa'];
            $dane[] = ['id' => $id, 'idserwera' => $idserwera, 'nazwa' => $nazwa];
        }
        $pdo = null;
        return $dane;
    }

    /**
     * List data for specific server from store
     * @param int $id
     * @return array|null
     */
    public function listServer(int $id) :?array
    {
        $pdo = Db::createInstance('System');
        $sth = $pdo->prepare("SELECT * FROM servers WHERE `id` = :id");
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();
        if($row = $sth->fetch())
        {
            $dane = [
                'id' => $row['id'],
                'idserwera' => $row['idserwera'],
                'nazwa' => $row['nazwa'],
            ];
        }
        $pdo = null;
        return $dane;
    }

    /**
     * Edit server data
     * @param int $id
     * @param int $serverid
     * @param string $name
     */
    public function editServer(int $id, int $serverid, string $name) : void
    {
        $pdo = Db::createInstance('System');
        $sth = $pdo->prepare("UPDATE `servers` SET `nazwa` = :name, `idserwera` = :serverid WHERE `servers`.`id` = :id");
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->bindParam(':name', $name, PDO::PARAM_STR);
        $sth->bindParam(':serverid', $serverid, PDO::PARAM_INT);
        $sth->execute();
        $pdo = null;
    }

    public function generateCodes(int $count, int $length) : array
    {
        $pdo = Db::createInstance('Sklep');
        $kody = [];
        for($i = 0; $i < $count; $i++)
        {
            $kod =  substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
            $kody[] = $kod;

            $sth = $pdo->prepare("INSERT INTO `kodyprzel` (`ID`, `kod`) VALUES (NULL, :kod)");
            $sth->bindParam(':kod', $kod, PDO::PARAM_STR);
            $sth->execute();
        }
        $pdo = null;
        return $kody;
    }
}