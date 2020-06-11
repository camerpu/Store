<?php
require_once '../../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/' . 'autoload.php';

use Store\Admin;

session_start();
$admin = new Admin();
if(!($admin->isAdmin($_SESSION['steamid'])))
{
    exit('Brak dostępu!');
}
if(!isSet($_GET['id']))
{
    exit('Brak ID');
}
$admin->deleteServer($_GET['id']);
$_SESSION['info'] = "Serwer usunięty pomyślnie";
header('Location: serwery.php');
?>