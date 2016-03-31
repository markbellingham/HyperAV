<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	$page_title = 'My Orders | HyperAV';
	include ("../includes/layouts/header.php");
?>

<h3>Here are all your previous orders <?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] ?></h3>

<?php

// Check if staff or customer is logged in.
// If staff, ask for the customer's email. If customer, get the email from the SESSION
// If neither staff nor customer is logged in, redirect to the index page
if (!isset($_SESSION['customerID']) && (isset($_SESSION['staff']))) {
	echo 'Please input the customer\'s email';
	echo '<input type="email" name="cuEmail" required>';
	echo '<input type="submit" name="submit" value="Submit">';
	include ("../includes/layouts/footer.php");
	exit;
} else if (!isset($_SESSION['customerID']) && (!isset($_SESSION['staff']))) {
	redirect_to("index.php");
} else {
	$cuEmail = $_SESSION['cuEmail'];
}

$query = 'SELECT o.orderID, prName, prPrice, odQuantity, orDate, orTotal, orDeliverDate, orPaymentMethod FROM hyperav_orders o JOIN hyperav_orderdetails od ON o.orderID = od.orderID JOIN hyperav_stock st ON od.stockID = st.stockID JOIN hyperav_products pr ON st.prModelNo = pr.prModelNo WHERE o.customerID = ' . $_SESSION['customerID'] . ' ORDER BY o.orDate';
$results = @mysqli_query($connection, $query);
$num_rows = mysqli_num_rows($results);

if ($results) {
	if ($num_rows > 0) {
		$i = 0;		
		while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
			if ($i != $row['orderID']) {
				echo '</table><br/><br/><br/>';
				echo '<table border="1"><tr><td>Order Date: ' . $row['orDate'] . '</td><td>Payment Method: ' . $row['orPaymentMethod'] . '</td><td><b>Order Total: ' . $row['orTotal'] . '</b></td></tr></table><br/>';
				echo '<table><tr><td></td><td><b>Product Name</b></td><td><b>Price</b></td><td><b>Quantity</b></td><td><b>Delivery Date</b></td></tr>';
			}
			echo '<tr><td><img src="images/' . $row['prName'] . '.jpg" id="product_images"></td><td>' . $row['prName'] . '</td><td>' . $row['prPrice'] . '</td><td>' . $row['odQuantity'] . '</td><td>' . $row['orDeliverDate'] . '</td></tr>';			
			$i = $row['orderID'];
		}
	}
}

?>



<?php
	include ("../includes/layouts/footer.php");
?>