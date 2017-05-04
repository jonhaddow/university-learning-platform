<?php
// Destroy session data and return to login page.
session_destroy();
header("location: " . LOGIN);
exit();
