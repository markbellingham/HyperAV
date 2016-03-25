<?php
require_once ("../includes/session.php");
require_once ("../includes/db_connection.php");
require_once ("../includes/functions.php");

if (!isset($_SESSION['first_name'])) {
	redirect_to("index.php");
	exit();
} else {
	$_SESSION=array();
	session_destroy();
	setcookie ('PHPSESSID', null);
}

$page_title = 'Logged Out';
include ("../includes/layouts/header.php");

echo "<h3>Successfully logged out.</h3>
	<p>You are now logged out.</p>";

include ("../includes/layouts/footer.php");
?>