<?php
include_once("config/Configuration.php");
session_start();



$controller = isset($_GET["controller"]) ? $_GET["controller"] : "home" ;
$method = isset($_GET["method"]) ? $_GET["method"] : "show" ;

$configuration = new Configuration();
$router = $configuration->createRouter( "createHomeController", "show");

$router->executeActionFromModule($controller,$method);