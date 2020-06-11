<?php

namespace Store;

class Forum
{
    /**
     * Check if player exist on forum
     * @param $steamid
     * @return int
     * @throws Exception
     */
    public function exist(string $steamid) : ?int
    {
        $pdo = Db::createInstance('Forum');

        $sth = $pdo->prepare("SELECT `member_id` FROM core_members WHERE `steamid` = :steamid");
        $sth->bindParam(':steamid', $steamid, PDO::PARAM_STR);
        $sth->execute();
        if($row = $sth->fetch())
        {
            return $row['member_id'];
        }
        else
        {
            throw new Exception("Member doesn't exist");
        }
    }

    /**
     * @param $memberid
     * Set VIP on Forum
     */
    public function setVIP(int $memberid) : void
    {
        if($memberid == null )
        {
            throw new Exception('Memberid null');
        }
        $pdo = Db::createInstance('Forum');

        try {
            $sth = $pdo->prepare("UPDATE `core_members` SET `member_group_id`=9, `mgroup_others`=3 WHERE `member_id`=:memberid");
            $sth->bindParam(':memberid', $memberid, PDO::PARAM_INT);
            $sth->execute();
        }
        catch(Exception $e)
        {
            echo 'Some problem, check logs';
        }
    }

    /**
     * @param $memberid
     * Add to system_flag data
     */
    public function addToSystem(int $memberid) : void
    {
        $pdo = Db::createInstance('System');

        $sth = $pdo->prepare("INSERT INTO `forum_vips` (`id`, `member_id`, `date_start`, `date_end`) VALUES (NULL, :memberid, NOW(), DATE_ADD(NOW(), INTERVAL 1 MONTH))");
        $sth->bindParam(':memberid', $memberid, PDO::PARAM_INT);
        $sth->execute();
    }
}