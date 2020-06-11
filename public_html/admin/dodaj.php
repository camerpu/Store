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
if(!isSet($_POST['id']) || !isSet($_POST['name']))
{
    exit('Brak danych');
}
$admin->addServer($_POST['id'], $_POST['name']);
$_SESSION['info'] = "Serwer dodany pomyślnie.";
header('Location: serwery.php');
?>