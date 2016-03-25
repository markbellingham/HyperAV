<?php
require_once ("../includes/session.php");
require_once ("../includes/db_connection.php");
require_once ("../includes/functions.php");

$page_title = 'My Orders | HyperAV';
include ("../includes/layouts/header.php");

if (!isset($_SESSION['staff'])) {
	redirect_to("index.php");
}

if (isset($_SESSION['stockcart'])) {
	$stockcart = $_SESSION['stockcart'];
} else {
	$stockcart = array();
}

$modelNo = $_POST['productID'];
echo 'You clicked on ' . $modelNo;
?>

<h3>Updating Stock</h3>
<?php
if (count($stockcart) > 0) {
	// First get the productIDs from the array
	$cart_keys = array_keys($stockcart);
	$query = 'SELECT * FROM hyperav_products WHERE ';
	// Build up a SELECT  statement from all the items in the array
	for ($i = 0; $i < count($stockcart); $i++) {
		if ($i != 0) {
			$query .= ' OR ';
		}
		$query .= 'prModelNo = "' . $cart_keys[$i] . '"';
	
		$query = 'INSERT INTO hyperav_stockorderdetails
		(stockID, supplierID, stOrderDate, stDeliveryDate, stOrderQuantity)
		VALUES('.$stockID.',' . $supplierID.',"' . $stOrDate.'","' . $stDelDate.'",' .$stQty.') ';
	}
	}


	include ("../includes/layouts/footer.php");
?>