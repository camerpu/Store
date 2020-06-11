<?php

use Store\TwigHelper;
use Store\Admin;

$twig = TwigHelper::getTwig();
$twig->addGlobal('session', $_SESSION);
$isAdmin = null;
$steamprofile = null;

if(isSet($_SESSION["steamid"]))
{
    require 'steamauth/userInfo.php';
    $admin = new Admin();
    $isAdmin = $admin->isAdmin($_SESSION['steamid']);
}
echo $twig->render('nav_admin.html.twig', ['isAdmin'=>$isAdmin, 'steamprofile'=>$steamprofile]);
