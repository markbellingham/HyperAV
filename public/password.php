<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	$page_title = 'Products | HyperAV';
	include ("../includes/layouts/header.php");

// If the user opens this page without being logged in, they are redirected to the login page
if (!isset($_SESSION['customerID']) && !isset($_SESSION['staff'])) {
	redirect_to("login_page.php");
}

// Check that the information is coming in through POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	// Array to store any errors to be displayed later
	$errors = array();

	// User has to enter their current password in order to change it
	if (empty($_POST['pass'])) {
		$errors[] = 'You forgot to enter your current password.';
	} else {
		$pass = mysqli_real_escape_string($connection, trim($_POST['pass']));
	}

	// The new password is entered twice and checked to ensure they match
	if (!empty($_POST['pass1'])) {
		if ($_POST['pass1'] != $_POST['pass2']) {
			$errors[] = 'Your new passwords did not match.';
		} else {
			$newpass = mysqli_real_escape_string($connection, trim($_POST['pass1']));
		}
	} else {
		$errors[] = 'You forgot to enter your new password.';
	}

	// If there are no errors (the form has all the data) go ahead and process it
	if (empty($errors)) {

		// The form can handle changing either the customer or staff passwords
		// First get their current password from the database so it can be checked with the one entered in the form
		// Checking the ID against the ID in the SESSION because to change their password, the user must be logged in and can only change their own password
		if ($_SESSION['staff']) {
			$query1 = "SELECT stPassword FROM hyperav_staff WHERE staffID = {$_SESSION['staffID']}";
		} else {
			$query1 = "SELECT cuPassword FROM hyperav_customer WHERE customerID = {$_SESSION['customerID']}";
		}
		$results1 = @mysqli_query($connection, $query1);
		$row = mysqli_fetch_array($results1, MYSQLI_ASSOC);
		if ($_SESSION['staff']) {
			$current_password = $row['stPassword'];
		} else {
			$current_password = $row['cuPassword'];
		}

		// If the user entered their current password correctly, go ahead and update it with the new one
		if (SHA1($_POST['pass']) == $current_password) {

			if ($_SESSION['staff']) {
				$query2 = "UPDATE hyperav_staff SET stPassword = SHA1('$newpass') WHERE staffID = {$_SESSION['staffID']}";
			} else {
				$query2 = "UPDATE hyperav_customer SET cuPassword = SHA1('$newpass') WHERE customerID = {$_SESSION['customerID']}";
			}

			$results2 = @mysqli_query($connection, $query2);

			if (mysqli_affected_rows($connection) == 1) {
				echo '<h3>Thank you!</h3>
				<p>Your password has been changed.</p>';
			} else {
				echo '<h3 class="error">System Error</h3>
				<p class="error">Your password could not be changed due to a system error.</p>';
				// DEBUGGING <P class="error">mysqli_error($connection) . '</p>
				// DEBUGGING <P class="error">Query: ' . $query . '</p>';
			}
		} else {
			echo '<p class="error">Your old password is not correct</p>';
			// DEBUGGING <P class="error">mysqli_error($connection) . '</p>
			// DEBUGGING <P class="error">Query: ' . $query . '</p>';
		}
		include ("../includes/layouts/footer.php");
		exit();
	} else {
		echo '<h3>Error</h3>
			<p class="error">The following error(s) occurred:</p>';
		foreach ($errors as $message) {
			echo "<p class='error'>$message</p>";
		}
		echo '<p>Please try again.</p>';
	}
	mysqli_free_result($results1);
	mysqli_free_result($results2)
	mysqli_close($connection);

}
?>

<h3>Change Your Password <?php echo "{$_SESSION['first_name']}" ?></h3>
<form action="password.php" method="post">
	<table class="no_border>"
		<tr><td><p>Current Password:</td><td><input type="password" name="pass" size="40" maxlength="40" required/> </p></td></tr>
		<tr><td><p>New Password:</td><td><input type="password" name="pass1" size="40" maxlength="40" required/> </p></td></tr>
		<tr><td><p>Confirm New Password:</td><td><input type="password" name="pass2" size="40" maxlength="40" required/> </p></td></tr>
		<tr><td colspan="2"><center><p><input type="submit" name="submit" value="Change Password" /> </p><center></td></tr>
	</table>
</form>
<?php
include ("../includes/layouts/footer.php");
?>




































