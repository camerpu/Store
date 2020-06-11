<?php


namespace Store;

use Exception;
use \PDO;

class addWithFlags
{
    private $steamid;
    private $nick;
    private $idserver;
    private $date;
    private $flags;
    private $type;
    private $length;

    public function setSteamId(string $steamid) : self
    {
        $this->steamid = $steamid;
        return $this;
    }
    public function setNick(string $nick) : self
    {
        $this->nick = $nick;
        return $this;
    }
    public function setIdserver(int $idserver) : self
    {
        $this->idserver = $idserver;
        return $this;
    }
    public function setDate(string $date) : self
    {
        $this->date = $date;
        return $this;
    }
    public function setFlags(string $flags) : self
    {
        $this->flags = $flags;
        return $this;
    }
    public function setType(string $type) : self
    {
        $this->type = $type;
        return $this;
    }
    public function setLength(string $length) : self
    {
        $this->length = $length;
        return $this;
    }

    /**
     * @throws Exception
     * Dodaje VIPa/MegaPremium/coś z flagami
     */
    public function AddService() : void
    {
        if(empty($this->steamid))
        {
            throw new Exception("Brak ustawionego steamid");
        }
        else if(empty($this->nick))
        {
            throw new Exception("Brak ustawionego nicku");
        }
        else if(empty($this->idserver))
        {
            throw new Exception("Brak ustawionego ID Serwera!");
        }
        else if(empty($this->date))
        {
            throw new Exception("Brak ustawionej daty!");
        }
        else if(empty($this->flags))
        {
            throw new Exception("Brak ustawionych flag!");
        }
        else if(empty($this->type))
        {
            throw new Exception("Brak ustawionego typu!");
        }
        else if(empty($this->length))
        {
            throw new Exception("Brak ustawionej dlugosci trwania VIPa!");
        }
        else
        {
            $pdo = Db::createInstance('System');

            try{
                $sth = $pdo->prepare("SELECT data, id FROM system_flag WHERE steamid=:steamid AND `{$this->idserver}` = :flagi");
                $sth->bindParam(':steamid', $this->steamid, PDO::PARAM_STR);
                $sth->bindParam(':flagi', $this->flags, PDO::PARAM_STR);
                $sth->execute();
                $num = $sth->rowCount();
                if($num != 0)
                {
                    $row = $sth->fetch();
                    $adminid = $row['id'];
                    $service = new Service();
                    $newdate = $service->detDate($row['data'], $this->length);

                    $update = $pdo->prepare("UPDATE `system_flag` SET `data`='{$newdate}' WHERE id='{$adminid}'");
                    $update->execute();
                }
                else
                {
                    $insert = $pdo->prepare("INSERT INTO `system_flag`(`id`, `steamid`, `nick`, `{$this->idserver}`, `data`, `typ`) VALUES (NULL, :steamid, :nick, :flags, :date, :type)");
                    $insert->bindParam(':steamid', $this->steamid, PDO::PARAM_STR);
                    $insert->bindParam(':nick', $this->nick, PDO::PARAM_STR);
                    $insert->bindParam(':date', $this->date, PDO::PARAM_STR);
                    $insert->bindParam(':flags', $this->flags, PDO::PARAM_STR);
                    $insert->bindParam(':type', $this->type, PDO::PARAM_STR);
                    $insert->execute();
                }
            }
            catch(Exception $e)
            {
                throw new Exception("SQL Error");
            }
            $pdo = null;

            new Reload($this->steamid, $this->idserver);
        }
    }
}