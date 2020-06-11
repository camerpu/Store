<?php
require_once '../../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/' . 'autoload.php';

use Store\Page;
use Store\TwigHelper;
use Store\Admin;
?>
<!DOCTYPE html>
<html>
<head>
    <?php
    Page::buildTitle('Panel Administracyjny');
    ?>
    <link href="../css/style_v2.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
</head>

<body>
<div class="container">
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/' . 'navAdmin.php';?>

    <br /> <br /><br />
    <?php
    $twig = TwigHelper::getTwig();

    if(!isSet($_SESSION["steamid"]))
    {
        echo $twig->render('unlogged.html.twig');
    }
    else
    {
        $admin = new Admin();
        if (!($admin->isAdmin($_SESSION['steamid']))) {
            exit('<div class="alert alert-danger" role="alert">Brak dostępu!</div>');
        }

        $kody = null;
        if (isSet($_POST['count']))
        {
            $kody = $admin->generateCodes($_POST['count'], $_POST['length']);
        }
        echo $twig->render('/admin/kody.html.twig', ['kody'=>$kody]);

    }
    ?>


</div>
<?php
Page::buildFooter();
?>
</body>

</html>