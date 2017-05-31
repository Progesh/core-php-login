<?php
session_start();
require("config/helper.php");

logout_user();
header( "Location: index.php" ); die;
?>