<?php
	function redirect_to($new_location) {
	  header("Location: " . $new_location);
	  exit;
	}

	function staff_member() {
		return isset($_SESSION['staff']);
	}

?>