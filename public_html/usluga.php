<?php
if(!isSet($_GET['id'])) header('Location: index.php');

require_once '../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/' . 'autoload.php';

use Store\TwigHelper;
use Store\Service;
use Store\Page;
?>
<!DOCTYPE html>
<html>
<head>
    <?php
        Page::buildTitle('Kup');
    ?>
    <link href="css/style_v2.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
</head>

<body>
<div class="container">
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/' . 'nav.php';?>

    <br /> <br /><br />
    <?php
    if(!isSet($_SESSION["steamid"]))
    {
        $twig = TwigHelper::getTwig();
        echo $twig->render('unlogged.html.twig');
    }
    else
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

        if(!$id)
        {
            echo '<div class="alert alert-danger" role="alert">Usługa nie działa!</div>';
            exit();
        }
        $dane = Service::loadService($_GET['id']);
        if($dane['disabled'] == 1)
        {
            echo '<div class="alert alert-danger" role="alert">Ta usługa jest tymczasowo wyłączona!</div>';
            exit();
        }
        $page = new Page($dane['typ']);
        $page->build();
    }
    ?>
<?php
    Page::buildFooter();
?>
</div>

</body>

</html>