<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	$page_title = 'Edit Customer Profile';
	include ("../includes/layouts/header.php");

	if (!isset($_SESSION['staff'])) {
		redirect_to("index.php");
	}
?>

<div id="main">
<?php	
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

<h3>Search Customer and Edit </h3>

 <html>
	<body>
		 <form action="staffEditCustomer.php" method="GET">
		 <div>
		 <p><strong>Last Name: *</strong> <input type="text" name="lastname" /></p>
		 <p><strong>Postcode: *</strong> <input type="text" name="postcode" /></p>
		 <p>* required</p>
		 <input type="submit" name="submit" value="Edit Customer Details">
		 </div>
		 </form> 
	</body>
 </html>
 </div> <!-- ends main -->

 <?php
 	include ("../includes/layouts/footer.php");
 ?>