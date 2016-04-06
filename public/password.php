<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	$page_title = 'Products | HyperAV';
	include ("../includes/layouts/header.php");

if (!isset($_SESSION['customerID']) && !isset($_SESSION['staff'])) {
	redirect_to("login_page.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$errors = array();

	if (empty($_POST['pass'])) {
		$errors[] = 'You forgot to enter your current password.';
	} else {
		$pass = mysqli_real_escape_string($connection, trim($_POST['pass']));
	}

	if (!empty($_POST['pass1'])) {
		if ($_POST['pass1'] != $_POST['pass2']) {
			$errors[] = 'Your new passwords did not match.';
		} else {
			$newpass = mysqli_real_escape_string($connection, trim($_POST['pass1']));
		}
	} else {
		$errors[] = 'You forgot to enter your new password.';
	}

	if (empty($errors)) {

		if ($_SESSION['staff']) {
			$query1 = "SELECT stPassword FROM hyperav_staff WHERE staffID = {$_SESSION['staffID']}";
		} else {
			$query1 = "SELECT cuPassword FROM hyperav_customer WHERE customerID = {$_SESSION['customerID']}";
		}
		$results1 = @mysqli_query($connection, $query1);
		
		if (!$results1) {

		}

		$row = mysqli_fetch_array($results1, MYSQLI_ASSOC);

		if ($_SESSION['staff']) {
			$current_password = $row['stPassword'];
		} else {
			$current_password = $row['cuPassword'];
		}

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
	mysqli_free_result($results);
	mysqli_close($connection);

}
?>

<h3>Change Your Password <?php echo "{$_SESSION['first_name']}" ?></h3>
<form action="password.php" method="post">
	<table class="no_border>"
		<tr><td><p>Current Password:</td><td><input type="password" name="pass" size="40" maxlength="40" /> </p></td></tr>
		<tr><td><p>New Password:</td><td><input type="password" name="pass1" size="40" maxlength="40" /> </p></td></tr>
		<tr><td><p>Confirm New Password:</td><td><input type="password" name="pass2" size="40" maxlength="40" /> </p></td></tr>
		<tr><td colspan="2"><center><p><input type="submit" name="submit" value="Change Password" /> </p><center></td></tr>
	</table>
</form>
<?php
include ("../includes/layouts/footer.php");
?>




































