<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");
	
	
	
	$page_title = 'Reports';
	include ("../includes/layouts/header.php");

	
	if (isset($_SESSION['staff'])) { //only displays page if logged in as staff member
		
		$query = "SELECT DISTINCT loName FROM hyperAV_location ORDER BY loName ASC"; //gets all locations
		$results = @mysqli_query($connection, $query);
		$num_rows = mysqli_num_rows($results);
?>


<div id ="main">
	<form action="staffBranchQuery.php" method="POST">						
<p>


<?php //1st report
	echo "List all staff at ";
	if($results) {
		if($num_rows > 0) { ?>
			<select name="location">
				<option>Select Location</option>
				<?php while($option = mysqli_fetch_array($results, MYSQLI_ASSOC)) { ?>
					<option><?php echo $option['loName']; ?></option>
			<?php } ?>
			</select> <?php
		}
	}
?>


<input type="submit" value="Submit"></form>

<!-- 2nd Report -->
<form action="supplierOrderQuery.php" method="POST">
<p>


<?php
	echo "List all supplier orders";
?>	


<input type="submit" value="Submit"></form>

<!-- 3rd Report -->
<form action="customerOrdersValueQuery.php" method="POST">
<p>


<?php 
	echo "List of customer orders with a total value of"
?>	


	<select name="totalValue">
		<option value="'0' AND '249.99'">&pound0 - &pound249.99</option>
		<option value="'250' AND '499.99'">&pound250 - &pound499.99</option>
		<option value="'500' AND '749.99'">&pound500 - &pound749.99</option>
		<option value="'750' AND '999'">&pound750 - &pound999.99</option>
		<option value="'1000' AND '9999990'">&pound1000+</option>
	<select>
	<input type="submit" value="Submit">
</form>

<!-- 4th Report -->
<form action="turnoverQuery.php" method="POST">
<p>


<?php
	echo "List total turnover";
?>


	<input type="submit" value="Submit">
</form>

<!-- 5th Report -->
<form action="avgOrderValQuery.php" method="POST">


<?php
	// Reset $results pointer back to the beginning so we can use the same data again
	mysqli_data_seek($results, 0);
	echo "List average order value at ";
	if($results) {
		if($num_rows > 0) { ?>
			<select name="location">
				<option>Select Location</option>
				<?php while($option = mysqli_fetch_array($results, MYSQLI_ASSOC)) { ?>
					<option><?php echo $option['loName']; ?></option>
			<?php } ?>
			</select> <?php
		}
	}
?>


	<input type="submit" value="Submit">
</form>

<!-- 6th Report -->
<form action="stockQuery.php" method="POST">


<?php
	// Reset $results pointer back to the beginning so we can use the same data again
	mysqli_data_seek($results, 0);
	echo "List stock levels at ";
	if($results) {
		if($num_rows > 0) { ?>
			<select name="location">
				<option>Select Location</option>
				<?php while($option = mysqli_fetch_array($results, MYSQLI_ASSOC)) { ?>
					<option><?php echo $option['loName']; ?></option>
			<?php } ?>
			</select> <?php
		}
	}
?>	


	<input type="submit" value="Submit">
</form>


</div> <!-- end of main div -->

<?php	
	} else {
		redirect_to("index.php"); //redirects to index.php if not logged in as staff
	}

	mysqli_free_result($results);
	mysqli_close($connection);

	include ("../includes/layouts/footer.php");
?>