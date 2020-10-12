<?php
include "includes/mod_log.php";

session_start();
session_unset();
session_destroy();
header("Location: /");
die();