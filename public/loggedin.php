<?php 
session_start();


if (!isset($_SESSION['email'])) {
	redirect_to("index.php");
	exit(); 
}

$page_title = 'Logged In';
include ('../includes/layouts/header.php');

echo "<h3>Successfully logged in.</h3><p>Welcome  " . $_SESSION['first_name'] . "  , you are now logged in.</p><p><a href=\"logout.php\">Logout</a></p>";

include ('../includes/layouts/footer.php');

?> 
