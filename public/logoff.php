<?php

require_once "../resources/config.php";

session_start();
session_destroy();
header("location: " . $login_page);
