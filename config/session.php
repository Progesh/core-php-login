<?php
session_start();
 
//function to check if the session variable member_id is on set
function logged_in() {
	return isset($_SESSION['MEMBER_ID']);
}

//Check user already logged in or not
function confirm_logged_in() {
	if (!logged_in()) {
		header( "Location: index.php" ); die;
	}
}
?>