<?php
require_once("../includes/session.php");
require_once("../includes/functions.php");
$page_title='Login';
include('../includes/layouts/header.php');
		
	if(!empty($errors))
	{
		echo '<h3 class="error">Error</h3> <p class="error"> The following error(s) occurred;</p>';
		foreach ($errors as $message)
		{ 
			echo "<p class='error'>$message</p>";
		}
	echo '<p>Please try again.</p>';
	} 

?>

<div id="login page" style="margin-left: 40px;">
	<h3>Login</h3>
	<form action="login.php" method="post">
		<p>Email Address: <br><input type="text" name="email" size="50" 
		maxlength="50" /> </p>
		<p>Password: <br><input type="password" name="pass" size="50" 
		maxlength="50" /></p>
		<p>Are you a member of staff?
		<input type="hidden" name="staff" value="0"/>
		<input type="checkbox" name="staff" value="1" /></p> 
		<p><input type="submit" name="submit" value="Login" /></p>
	</form>
</div> <!-- ends login page -->