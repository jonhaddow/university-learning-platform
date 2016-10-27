<?php
session_start();

if (isset($_SESSION['username'])) {
    // Redirect to Welcome page
    header("Location: " . $domain_name);
    die();
}
