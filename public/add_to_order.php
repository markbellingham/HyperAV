<?php
require_once ("../includes/session.php");
require_once ("../includes/db_connection.php");
require_once ("../includes/functions.php");

$page_title = 'Added to your order | HyperAV';
include ("../includes/layouts/header.php");
?>

<div id="main">

<?php
/* Check if the cart already exists and add the item clicked into it.
 If the cart does not already exist, create a new one. */
if (isset($_POST['prModelNo'])) {
	$modelNo = $_POST['prModelNo'];
	if (isset($_SESSION['cart'])) {
		$cart = $_SESSION['cart'];
		$cart[$modelNo] = 1;
		$_SESSION['cart'] = $cart;
	} else {
		$cart[$modelNo] = 1;
		$_SESSION['cart'] = $cart;
	}

	// The user is shown a message with details of the item they just clicked on
	echo '<p>The following item was added to your order:</p>';

	$query = 'SELECT * FROM hyperAV_products WHERE prModelNo = "' . $modelNo . '"';
	$results = @mysqli_query($connection, $query);
	$num_rows = mysqli_num_rows($results);

	if ($results) {
		if ($num_rows > 0) {
			echo '<table><tr><th></th><th>Name</th><th>Price</th></tr>';
			while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
				echo '<p><tr>
					<td><img src="images/' . $row['prName'] . '.jpg" id="product_images"></td>
					<td>' . $row['prName'] . '</td><td>&pound' . $row['prPrice'] . '</td></tr></p>';
					$name = $row['prName'];
			}

			echo '</table>';

			// Set message in the SESSION of what was added to the order and redirect back to products.php
			$_SESSION['message'] 	= "added";
			$_SESSION['prName']		= $name;
			redirect_to("products.php");
		}
	}
}

mysqli_free_result($results);
mysqli_close($connection);
?>

<p><a href="orders.php">Show all items on my order</a></p>
<p><a href="products.php">Show all products</a></p>
</div> <!-- ends main -->

<!-- <?php
	print_r($cart);
?> -->



<?php
	include ("../includes/layouts/footer.php");
?>