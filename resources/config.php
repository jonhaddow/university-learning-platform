<?php

// Paths to public pages
define("DOMAIN_NAME", "http://localhost");
define("LOGIN", DOMAIN_NAME . "/login");
define("REGISTER", DOMAIN_NAME . "/register");
define("DASHBOARD", DOMAIN_NAME . "/dashboard");
define("LOGOFF", DOMAIN_NAME . "/logoff");
define("MODIFY_MAP", DASHBOARD . "/lecturerModifyMap");
define("LECTURER_VIEW", DASHBOARD . "/lecturerView");
define("STUDENT_VIEW", DASHBOARD . "/studentView");

// Path to resources
define("RESOURCES", dirname(__FILE__));
define("VIEWS", RESOURCES . "/views");
define("CONTROLLERS", RESOURCES . "/controllers");
define("COMMON_RESOURCES", VIEWS . "/common");
define("API", RESOURCES . "/api");

// Db config
$server = 'localhost';
$user = 'root';
$pass = '';
$db_name = 'test';

$db_conn = new PDO("mysql:host=$server;dbname=$db_name", $user, $pass);
$db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);