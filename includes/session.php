<?php
session_start();

if (basename(__FILE__) == 'logout.php') {
	$_SESSION=array();
	session_destroy();
	setcookie ('PHPSESSID', null);
}

if (isset($_SESSION['first_name'])) {
	echo "<p class='hello'>Hello {$_SESSION['first_name']}!</p>";
} else {
	echo "<p class='hello'>Hello Stranger!</p>";
}
?>