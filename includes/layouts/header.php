<!DOCTYPE html>
<html>
<head>
	<title><?php echo $page_title; ?></title>
	<link rel="stylesheet" href="stylesheets/style.css" type="text/css" />
</head>
<body>
<div id="wrapper">
	<header>
		<h1>HyperAV Home Cinema</h1>
		<h2>for all your home entertainment needs</h2>
	</header>
<div id="menu">		
	<div class="dropdown">
		<ul>
			<button class="dropbtn">Home</button>
			<div class="dropdown-content">
				<ul>				
					<li><a href="index.php">Home Page</a></li>
				</ul>		
			</div>
	</div>
	<div class="dropdown">		
			<button class="dropbtn">Staff</button>
			<div class="dropdown-content">
			<?php if (isset($_SESSION['staff'])) { ?>
				<ul>
					<li><a href="staffChangeCustomer.php">Change Customer Details</a></li>
					<li><a href="customersToDelete.php">Remove Customer</a></li>
					<li><a href="supplierProducts.php">Place Order</a></li>
					<li><a href="supplierOrders.php">Current Supplier Order</a></li>
					<li><a href="prevSupplierOrders.php">Previous Supplier Orders</a></li>
				</ul> <?php
			} ?>
			</div>
	</div>		
	<div class="dropdown">				
					
			<button class="dropbtn">Customer</button>
			<div class="dropdown-content">
				<ul>
					<li><a href="products.php">View Products</a></li>
					<li><a href="orders.php">View Basket</a></li>
					<li><a href="show_my_orders.php">Show all my orders</a></li>
				</ul>
			</div>
	</div>
	<div class="dropdown">				
			<button class="dropbtn">Account</button>
			<div class="dropdown-content">
				<ul>
					<li><a href="register.php">Sign Up</a></li>
					<li><a href="password.php">Change Password</a></li>
					<li><?php
						if ((isset($_SESSION['first_name'])) && (!strpos($_SERVER['PHP_SELF'], 'logout.php')) ) 
						{
							echo '<a href="logout.php">Logout</a>';
						} 
						else 
						{
							echo '<a href="login.php">Login</a>';
						}
						?>
					</li>
				</ul>
			</div>
	</div>
	<div class="dropdown">		
			<button class="dropbtn">Reports</button>
			<div class="dropdown-content">
			<?php if(isset($_SESSION['staff'])) { ?>
				<ul>
					<li><a href="reports.php">View Reports</a></li>					
				</ul> <?php
			} ?>
			</div>
		</ul>
	</div>
</div> <!-- ends menu -->
	<section id="maincontent">