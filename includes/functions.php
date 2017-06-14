<?php
	function redirect_to($new_location) {
	  header("Location: " . $new_location);
	  exit;
	}

	function staff_member() {
		return isset($_SESSION['staff']);
	}

	function get_SESSION_value_or_redirect($key, $page) {
		if (isset($_SESSION[$key])) {
			$cart = $_SESSION[$key];
			return $cart;
		} else {
			redirect_to($page);
		}
	}

?>