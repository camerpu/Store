<?php
/**
 * Created by PhpStorm.
 * User: Camer
 * Date: 16.12.2017
 * Time: 18:01
 */

namespace Store;

use \PDO;

class Codmod
{
    /**
     * Get list of src on cod server
     * @param string $steamid
     * @return array|null
     */
    public function getClasses(string $steamid) : ?array
    {
        $pdo = Db::createInstance('CodMod');

        $sth = $pdo->prepare('SELECT class, level FROM `codmod_steamid` WHERE steamid LIKE :steamid');
        $sth->bindParam(':steamid', $steamid, PDO::PARAM_STR);
        $sth->execute();
        $num = $sth->rowCount();

        if ($num > 0)
        {
            $data = [];
            while($row = $sth->fetch())
            {
                $data[] = [
                    'class' => $row['class'],
                    'level' => $row['level']
                ];
            }
            $pdo = null;
            return $data;
        }
        else
        {
            $pdo = null;
            return null;
        }
    }

    /**
     * Return value of ZloteNaboje for player, return 0 if player doesnt exist
     * @param string $steamid
     * @return int
     */
    public function getZN(string $steamid) : int
    {
        $pdo = Db::createInstance('CodMod');
        $sth = $pdo->prepare('SELECT `count` FROM `zlotenaboje` WHERE steamid LIKE :steamid');
        $sth->bindParam(':steamid', $steamid, PDO::PARAM_STR);
        $sth->execute();
        if($sth->rowCount() == 1)
        {
            $row = $sth->fetch();
            $zn = $row['count'];
            $pdo = null;
            return $zn;
        }
        else
        {
            return 0;
        }
    }

    /**
     * Set ZN for player, insert if doesnt exist
     * @param string $steamid
     * @param int $ile
     */
    public function setZN(string $steamid, int $ile) : void
    {
        $pdo = Db::createInstance('CodMod');
        $gracz = $this->getZN($steamid);
        if($gracz == -1)
        {
            str_replace("%","",$steamid);
            $sthInsert = $pdo->prepare('INSERT INTO `zlotenaboje` (`id`, `steamid`, `count`, `name`) VALUES (NULL, :steamid, :ile, :nick)');
            $sthInsert->bindParam(':ile', $ile, PDO::PARAM_INT);
            $sthInsert->bindParam(':steamid', $steamid, PDO::PARAM_STR);
            $sthInsert->bindParam(':nick', $_SESSION['steam_personaname'], PDO::PARAM_STR);
            $sthInsert->execute();
        }
        else
        {
            $sthUpdate = $pdo->prepare('UPDATE `zlotenaboje` SET `count` = :ile WHERE steamid LIKE :steamid');
            $sthUpdate->bindParam(':ile', $ile, PDO::PARAM_INT);
            $sthUpdate->bindParam(':steamid', $steamid, PDO::PARAM_STR);
            $sthUpdate->execute();
        }
        $pdo = null;
    }

    /**
     * Set level for Class
     * @param string $steamid
     * @param string $klasa
     * @param int $level
     */
    public function setLevel(string $steamid, string $klasa, int $level) : void
    {
        $level_exp = $level * 500;

        $pdo = Db::createInstance('CodMod');

        $sthUpd = $pdo->prepare('UPDATE `codmod_steamid` SET `level` = :level, `xp`= :level_exp WHERE steamid LIKE :steamid AND `class` = :klasa');
        $sthUpd->bindParam(':level', $level, PDO::PARAM_INT);
        $sthUpd->bindParam(':level_exp', $level_exp, PDO::PARAM_INT);
        $sthUpd->bindParam(':steamid', $steamid, PDO::PARAM_STR);
        $sthUpd->bindParam(':klasa', $klasa, PDO::PARAM_STR);
        $sthUpd->execute();
        $pdo = null;
    }

    /**
     * Resetting stats after setting 1lvl
     * @param string $steamid
     * @param string $klasa
     */
    public function setStats(string $steamid, string $klasa) : void
    {
        $pdo = Db::createInstance('CodMod');

        $sthUpd = $pdo->prepare('UPDATE `codmod_steamid` SET `intelligence`=0, `health`=0, `damage`=0, `stamina`=0, `trim`=0 WHERE steamid LIKE :steamid AND `class` = :klasa');
        $sthUpd->bindParam(':steamid', $steamid, PDO::PARAM_STR);
        $sthUpd->bindParam(':klasa', $klasa, PDO::PARAM_STR);
        $sthUpd->execute();
        $pdo = null;
    }

    /**
     * Get level of class, throw exception if class for this player doesnt exist
     * @param string $steamid
     * @param string $klasa
     * @return int|null
     * @throws Exception
     */
    public function getLevel(string $steamid, string $klasa) : ?int
    {
        $pdo = Db::createInstance('CodMod');
        $sth = $pdo->prepare('SELECT level FROM `codmod_steamid` WHERE steamid LIKE :steamid AND class=:klasa');
        $sth->bindParam(':klasa', $klasa, PDO::PARAM_STR);
        $sth->bindParam(':steamid', $steamid, PDO::PARAM_STR);
        $sth->execute();
        if($sth->rowCount() == 1)
        {
            $row = $sth->fetch();
            $lvl = $row['level'];
            $pdo = null;
            return $lvl;
        }
        else
        {
            throw new Exception('Brak takiej klasy');
        }

    }
}