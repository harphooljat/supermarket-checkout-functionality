<?php

require "../bootstrap.php";
use Src\Controller\SuperMarketController;

$controller = new SuperMarketController($dbConnection);
$controller->checkout();