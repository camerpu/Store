<?php
    use Store\TwigHelper;
    use Store\Service;
    use Store\Admin;

    $twig = TwigHelper::getTwig();
    $twig->addGlobal('session', $_SESSION);
    $services = Service::loadServices();
    $codservices = Service::loadCodModServices();

    $isAdmin = null;
    $steamprofile = null;

    if(isSet($_SESSION["steamid"]))
    {
        require 'steamauth/userInfo.php';
        $admin = new Admin();
        $isAdmin = $admin->isAdmin($_SESSION['steamid']);
    }
    echo $twig->render('nav.html.twig', ['services'=>$services, 'codservices'=>$codservices, 'isAdmin'=>$isAdmin, 'steamprofile'=>$steamprofile]);

?>
