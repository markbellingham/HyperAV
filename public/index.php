<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	$page_title = 'Welcome to HyperAV';
	include ("../includes/layouts/header.php");
?>


<div id=main>
  <h3>Welcome to HyperAV</h3>

  <p>Have a browse through our extensive selection of home cinema products</p>
  <br />
  <p>Select <a href="products.php">Customer -> View Products</a> to browse our range of products and to order something</p>
  <p>Some of the features of this website require you to create an account or log in to an existing account before they become available</p>
</div> <!-- emds main -->


<?php
	include ("../includes/layouts/footer.php");
?>
