<?php
define("DB_SERVER", "mudfoot.doc.stu.mmu.ac.uk");
define("DB_USER", "bellingm");
define("DB_PASS", "Lerkmant3");
define("DB_NAME", "bellingm");

	// 1. Create a database connection
$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	// Test if connection occured
if (mysqli_connect_errno()) {
	die("Database connection failed: " .
		mysqli_connection_error() .
		" (" . mysqli_connection_errno() . ")"
		);
}
?>