<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php";

session_start();
session_destroy();
header("location: " . $domain_name . "Project/Login");
