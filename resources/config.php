<?php

// Paths to public pages
$domain_name = "http://localhost";
$login_page = $domain_name . "/login";
$register_page = $domain_name . "/register";
$dashboard = $domain_name . "/dashboard";
$modify_map = $domain_name . "/dashboard/modify-map";
$logoff = $domain_name . "/logoff";
$jsConfig = $domain_name . "/js/jsConfig.js";

// Path to resources
define("RESOURCES", dirname(__FILE__));
define("VIEWS", RESOURCES . "/views");
define("CONTROLLERS", RESOURCES . "/controllers");
define("COMMON_RESOURCES", RESOURCES . "/common");
define("API", RESOURCES . "/api");

// Db config
$server = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'test';

$db_conn = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
$db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

