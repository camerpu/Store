<?php
use Store\Stats;

require_once __DIR__ . '/../../vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if($_GET['key'] != 'jasd7698asdnah8asd287!')
    exit();
if(!isSet($_GET['type']))
    exit();

$type = $_GET['type'];
$tab = null;
$stats = new Stats();

if($type == 1)
    $tab = $stats->getForMonths();
else if($type == 2)
    $tab = $stats->previousYear();
else if($type == 3)
    $tab = $stats->allVIPS();
else if($type == 4)
    $tab = $stats->thisMonth();
else if($type == 5)
    $tab = $stats->thisYear();
else if($type == 6)
    $tab = $stats->forServers();
else if($type == 7)
    $tab = $stats->forPayments();
else if($type == 8)
    $tab = $stats->codClassNames();
else if($type == 9)
    $tab = $stats->codClassCount();

echo json_encode($tab);