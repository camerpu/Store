<?php
namespace Store;

use \PDO;

class Db

{
    //TODO: doing types - consts
    /**
     * Create instantion of PDO
     * @param string $typ
     * @return PDO
     */
    public static function createInstance(string $typ) : PDO
    {
        try
        {
            $zwrot = Db::getType($typ);
        }
        catch(Exception $e)
        {
            echo 'Problem: ',  $e->getMessage(), "\n";
            exit();
        }

        if($zwrot == NULL)
        {
            echo 'Brak typu bazy';
            exit();
        }


        require  __DIR__ . '/../../' . $zwrot;


        try
        {
            $pdo = new PDO('mysql:host=' . $host . ';dbname=' . $db_name . ';encoding=utf8mb4;charset=utf8mb4', $db_user, $db_password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );
        }
        catch( PDOException $e )
        {
            echo 'PDO Connect Error';
            exit();
        }
        return $pdo;
    }

    /**
     * Return path to config of db type
     * @param string $typ
     * @return string|null
     * @throws Exception
     */
    public function getType(string $typ) : ?string
    {
        $path = NULL;
        switch($typ)
        {
            case 'System':
                $path = 'config/dbs/connect_systemflag.php';
                break;
            case 'Sklep':
                $path = 'config/dbs/connect_sklep.php';
                break;
            case 'CodMod':
                $path = 'config/dbs/connect_codmod.php';
                break;
            case 'SourceBans':
                $path = 'config/dbs/connect_sourcebans.php';
                break;
            case 'Forum':
                $path = 'config/dbs/connect_forum.php';
                break;
            default:
               throw new Exception("Brak takiego typu bazy");
               break;
        }
        return $path;
    }
}