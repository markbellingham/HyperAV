<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	$page_title = 'Edit Customer Profile';
	include ("../includes/layouts/header.php");

	if (!isset($_SESSION['staff'])) {
	redirect_to("index.php");
	}
	
	$last = htmlspecialchars($_GET['lastname']);
	$post = htmlspecialchars($_GET['postcode']);
	
	// check that firstname/lastname fields are both filled in
 if ($last == '' || $post == '')
 {
	// generate error message
	$error = 'ERROR: Please fill in all required fields!';
 }
 
	$query = 'SELECT * FROM hyperAV_customer WHERE cuLName = "' . $last . '" AND cuPostcode="' . $post . '";';
	$results=@mysqli_query($connection,$query);
	$num_rows = mysqli_num_rows($results);
	
	//echo '<p>Num Rows: ' . $num_rows . '</p>';	
	//echo "Query: ". $query;
?>

<div id="main">
<?php	
	if ($results) {
		if ($num_rows > 0) {
				while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) 
				{ ?>					
					<form name="update" method="POST" action="updateCustomer.php">
						<?php $id=$row['customerID']; ?>
						<table cellspacing="30" style="margin-left:100">
							<input type="hidden" 	name="customerID" 	value="<?php echo $row['customerID'] ?>" />

							<tr><td align="right"><p class="label">First Name:</td><td>
		 					<input type="text" 		name="cuFName" 		value="<?php echo $row['cuFName'] ?>" /></p></td></tr>

		 					<tr><td align="right"><p class="label">Last Name:</td><td>
							<input type="text" 		name="cuLName" 		value="<?php echo $row['cuLName']?>" /></p></td></tr>

							<tr><td align="right"><p class="label">Address line 1:</td><td>
							<input type="text" 		name="address1"		value="<?php echo $row['cuAddress1']?>" /></p></td></tr>

							<tr><td align="right"><p class="label">Address line 2:</td><td>
							<input type="text" 		name="address2"		value="<?php echo $row['cuAddress2']?>" /></p></td></tr>

							<tr><td align="right"><p class="label">Town / City:</td><td>
							<input type="text" 		name="cuTown" 		value="<?php echo $row['cuTown']?>" /></p></td></tr>

							<tr><td align="right"><p class="label">Postcode:</td><td>
							<input type="text" 		name="cuPostcode" 	value="<?php echo $row['cuPostcode']?>" /></p></td></tr>

							<tr><td align="right"><p class="label">Telephone number:</td><td>
							<input type="text" 		name="cuTelephone" 	value="<?php echo $row['cuTelephone']?>" /></p></td></tr>

							<tr><td align="right"><p class="label">Email address:</td><td>
							<input type="email" 	name="cuEmail" 		value="<?php echo $row['cuEmail']?>" /></p></td></tr>
							
							<tr><td align="right">Confirm changes:</td><td><p><input type="submit" value="Save"></p></td></tr>
						</table>
					</form>
		<?php	}

			} else {
				echo '<p class="error">This customer does not exist.</p>';
				//echo '<p>' . mysqli_error($connection) . '</p>';
			}
		} else {
			echo '<h3 class="error">System Error</h3>
			<p class="error">customer information could not be retrieved.</p>';
			//echo '<p>' . mysqli_error($connection) . '</p>';
		}
	mysqli_free_result($results);
	mysqli_close($connection);
?>
</div> <!--ends main-->
<?php
	include ("../includes/layouts/footer.php");
?>