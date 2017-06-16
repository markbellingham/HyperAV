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

	function get_GET_value_or_redirect($key, $page) {
		if (isset($_GET[$key])) {
			$get_value = htmlspecialchars($_GET[$key]);
			return $get_value;
		} else {
			redirect_to($page);
		}
	}

	function get_or_create_cart($cart) {
		if (isset($_SESSION[$cart])) {
			$session_cart = $_SESSION[$cart];
		} else {
			$session_cart = array();
		}
		return $session_cart;
	}

?>