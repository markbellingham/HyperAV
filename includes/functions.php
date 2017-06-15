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
			$session_value = $_SESSION[$key];
			return $session_value;
		} else {
			redirect_to($page);
		}
	}

	function get_POST_value_or_redirect($key, $page) {
		if (isset($_POST[$key])) {
			$post_value = $_POST[$key];
			return $post_value;
		} else {
			redirect_to($page);
		}
	}

?>