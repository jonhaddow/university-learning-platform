<?php

// Paths to public pages
$domain_name = "http://localhost";
$login_page = $domain_name . "/login";
$register_page = $domain_name . "/register";
$dashboard = $domain_name . "/dashboard";
$logoff = $domain_name . "/logoff.php";
$jsConfig = $domain_name . "/jsConfig.js";

// Path to resources
$resources = dirname(__FILE__);
$views = $resources . "/views";
defined("COMMON_RESOURCES") or define("COMMON_RESOURCES", $views . "/common");
defined("LOGIN_RESOURCES") or define("LOGIN_RESOURCES", $views . "/login");
defined("REGISTER_RESOURCES") or define("REGISTER_RESOURCES", $views . "/register");
defined("DASHBOARD_RESOURCES") or define("DASHBOARD_RESOURCES", $views . "/dashboard");

// Db config
$server = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'test';

$db_conn = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
$db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

