<?php
require_once ("../includes/session.php");
require_once ("../includes/db_connection.php");
require_once ("../includes/functions.php");

$page_title = 'Stock added to your order | HyperAV';
include ("../includes/layouts/header.php");

if (!isset($_SESSION['staff'])) {
	redirect_to("index.php");
}


if (isset($_POST['prModelNo'])) {

	$modelNo = $_POST['prModelNo'];

	if (isset($_SESSION['stockcart'])) {
		$stockcart = $_SESSION['stockcart'];
		$stockcart[$modelNo] = 1;
		$_SESSION['stockcart'] = $stockcart;
	} else {
		$stockcart[$modelNo] = 1;
		$_SESSION['stockcart'] = $stockcart;
	}

	echo '<p>Model No. : ' . $modelNo . ' was added to your order</p>';

	$query = 'SELECT * FROM hyperav_products WHERE prModelNo = "' . $modelNo . '"';
	$results = @mysqli_query($connection, $query);
	$num_rows = mysqli_num_rows($results);

	if ($results) {
		if ($num_rows > 0) {

			echo '<table><tr><th>Name</th><th>Price</th></tr>';

			while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
				echo '<tr><td>' . $row['prName'] . '</td><td>&pound' . $row['prPrice'] . '</td></tr>';
			}

			echo '</table>';
		}
	}
}
?>

<p><a href="supplierOrders.php">Show my order</a></p>
<p><a href="supplierProducts.php">Show all products</a></p>


<?php
	//print_r($stockcart);

	include ("../includes/layouts/footer.php");
?>