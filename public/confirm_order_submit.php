<?php
require_once ("../includes/session.php");
require_once ("../includes/db_connection.php");
require_once ("../includes/functions.php");

$page_title = 'Order confirmed | HyperAV';
include ("../includes/layouts/header.php");

// If the user somehow got here without being logged in, they are redirected to the login page
if(!isset($_SESSION['cuEmail']) && !isset($_SESSION['staff'])) {
	redirect_to("login_page.php");
}

// Gets the cart from the SESSION
if (isset($_SESSION['cart'])) {
	$cart = $_SESSION['cart'];
} else {
	// If the user somehow got here without making an order, they are redirected to the products page
	redirect_to("products.php");
}

/* Get the selected location name or redirect back to confirm_order.php
 Effectively the page only submits if the location is selected or can be retrieved from the staff details */
if (!isset($_SESSION['staff']) && ($_POST['location']) == "Select Location") {
	redirect_to("confirm_order.php");
} else if (isset($_SESSION['staff'])) {
	$locationID = $_SESSION['location'];
} else {
	$location = $_POST['location'];
	// Get the locationID from the location name
	$query3 = 'SELECT locationID from hyperav_location WHERE loName = "' . $location . '"';
	$results3 = @mysqli_query($connection, $query3);
	$num_rows3 = mysqli_num_rows($results3);
	if ($results3) {
		if ($num_rows3 == 1) {
			while ($row = mysqli_fetch_array($results3, MYSQLI_ASSOC)) {
				$locationID = $row['locationID'];
			}
		}

		mysqli_free_result($results3);
	}
}

/* If the customer created the order, their email is present in the SESSION.
 If a staff member created the order, they get the email address from the customer.
 Then this page is redirected back so that the staff member can verify the address. */
if (isset($_SESSION['staff']) && !isset($_SESSION['cuEmail'])) {
	if (isset($_POST['cuEmail']) && !empty($_POST['cuEmail'])) {
		$_SESSION['cuEmail'] = $_POST['cuEmail'];
		redirect_to("confirm_order.php");
	} else {
		echo '<p>You forgot to enter the email address</p>';
		echo '<p><a href="confirm_order.php">Click here to go back</a></p>';
		exit;
	}
} else {
	$email = $_SESSION['cuEmail'];
}

// Collect all the information needed to insert into the orders table
// Get the date
$orDate = date('Y-m-d');

// Get the total
$orTotal = $_SESSION['grandTotal'];

// Set the payment method
$orPaymentMethod = $_POST['payment'];

// Get the customer ID from the database
$query1 = 'SELECT customerID FROM hyperav_customer WHERE cuEmail = "' . $email . '"';
$results1 = @mysqli_query($connection, $query1);
$num_rows1 = mysqli_num_rows($results1);
if ($results1) {
	if ($num_rows1 == 1) {
		while ($row = mysqli_fetch_array($results1, MYSQLI_ASSOC)) {
			$customerID = $row['customerID'];
		}
	} else {
		echo '<p>Error: There was a problem with the data</p>';
		exit;
	}

	mysqli_free_result($results1);
} else {
	echo '<p>Error: There was a problem with the database connection';
	//DEBUGGING	 echo '<p class="error">'.mysqli_error($connection).'</p>';
	//DEBUGGING	 echo '<p class="error">Query:'. $query . '</p>';
	exit;
}

// Get the staff ID if it exists or give it a NULL value
if (isset($_SESSION['staff'])) {
	$staffID = $_SESSION['staffID'];
} else {
	$staffID = NULL;
}

/* INSERT basic order information such as customerID, order date and payment method into the orders table
 The INSERT statement is created using Prepared Statement because there is a possibility of a NULL value in the staffID field. */
$query2 = mysqli_prepare($connection, "INSERT INTO hyperav_orders (orDate, orTotal, orDeliverDate, orPaymentMethod, customerID, staffID) VALUES (?, ?, ?, ?, ?, ?)");
if ($query2 === false) {
	trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($connection)), E_USER_ERROR);
}
// Bind the values
$bind2 = mysqli_stmt_bind_param($query2, "sdssii", $orDate, $orTotal, $orDate, $orPaymentMethod, $customerID, $staffID);
if ($bind2 === false) {
	trigger_error('Bind param failed!', E_USER_ERROR);
}
// Execute the query
$exec2 = mysqli_stmt_execute($query2);
if ($exec2 === false) {
	trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($query2)), E_USER_ERROR);	
}


// Get the automatically generated orderID from the last insert
$orderID = mysqli_insert_id($connection);


// Create arrays from the cart
$productIDs = array_keys($cart);
// $quantities = array_values($cart); ----> First I created two arrays from the cart (which is an assoc array) but this is not a reliable way to match both values


/* The Order Details table is where each item that is on our order is stored.
 In this way the orderID can be reused for each item on that order.
 A For Loop is used to loop though all the items in the cart */
for ($i = 0; $i < count($cart); $i++) {
	$ID = $productIDs[$i]; // Store the id in a variable to use later
	// Get the stockID that corresponds to each model number and the location
	$query4 = 'SELECT * FROM hyperav_stock WHERE prModelNo = "' . $ID . '" AND locationID = "' . $locationID . '"';
	$results4 = @mysqli_query($connection, $query4);
	$num_rows4 = mysqli_num_rows($results4);
	if ($results4) {
		if ($num_rows4 == 1) {
			while ($row = mysqli_fetch_array($results4, MYSQLI_ASSOC)) {
				$stockID = $row['stockID'];
				$stQuantity = $row['stQuantity']; // store the current stock quantity so that it can be updated later
			}
		}

		mysqli_free_result($results4);
	}

	// Create an INSERT statement for each item in the cart
	$query5 = mysqli_prepare($connection, "INSERT INTO hyperav_orderdetails (orderID, stockID, odQuantity) VALUES (?, ?, ?)");
	if ($query5 === false) { trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($connection)), E_USER_ERROR); }

	$bind5 = mysqli_stmt_bind_param($query5, "iii", $orderID, $stockID, $cart[$ID]);
	if ($bind5 === false) { trigger_error('Bind failed!' . E_USER_ERROR); }

	$exec5 = mysqli_stmt_execute($query5);
	if ($exec5 === false) { 
		trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($query5)), E_USER_ERROR);
		echo '<p>insert into hyperav_orderdetails not successful</p>';
	} else {
		unset($_SESSION['cart']);
	}

	// if (mysqli_query($connection, $query5)) {
	// 	unset($_SESSION['cart']);
	// 	//echo 'insert into hyperav_orderdetails successful';
	// } else {
	// 	echo '<p>insert into hyperav_orderdetails not successful</p>';
	// 	// echo '<p>Query5: ' . $query5 . '</p>';
	// 	// echo '<p>' . mysqli_error($connection) . '</p>';
	// }

	// Now reduce the current stock quantity by the amount that was just bought.
	$stQuantity = $stQuantity - (int)$cart[$ID];
	$query6 = 'UPDATE hyperav_stock SET stQuantity = ' . $stQuantity . ' WHERE stockID = ' . $stockID;
	mysqli_query($connection, $query6);
}

mysqli_close($connection);

// Provide confirmation message to the user
echo '<p><center>Your order has been successfully submitted</center></p>';
// echo $query6;
?>