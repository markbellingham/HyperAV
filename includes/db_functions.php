<?php

	// Creates a dropdown list populated from the db using supplied parameters for 
	// column and table, which can then be used in a form
	function dropdown_box($column, $table) {
		global $connection;

		$query = "SELECT DISTINCT {$column} FROM {$table} ORDER BY {$column} ASC";
		$results = @mysqli_query($connection, $query);
		$num_rows = mysqli_num_rows($results);
		if($results) {
			if($num_rows > 0) {?>
				<select name = <?php echo $column ?> onchange = "<?php echo $onchange ?>" >
					<option>Select</option>
					<?php while($option = mysqli_fetch_array($results, MYSQLI_ASSOC)) { ?>
						<option><?php echo $option[$column]; ?></option>
				<?php } ?>
				</select><?php
			}
		}
	}

	// Creates a dropdown list populated from the db using supplied parameters for 
	// column and table, and then reloads the page filtered with the selected option
	function dropdown_js_reload($column, $table) {
		global $connection, $url_parameter;

		$query = "SELECT DISTINCT {$column} FROM {$table} ORDER BY {$column} ASC";
		$results = @mysqli_query($connection, $query);
		$num_rows = mysqli_num_rows($results);
		if($results) {
			if($num_rows > 0) { ?>
				<form action = <?php echo $_SERVER['PHP_SELF']; ?> method = "GET">
					<select name = <?php echo $column ?> onchange = "this.form.submit()">
						<option>Select</option>
						<?php while ($option = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
							if ($option[$column] === $url_parameter) { ?>
								<option selected><?php echo $option[$column]; ?></option><?php
							} else { ?>
								<option><?php echo $option[$column]; ?></option><?php
							} 
						} ?>
					</select>
				</form><?php
			}
		}
	}

	function get_an_ID_from_the_database($id, $table, $column, $reference) {
		global $connection;
		$query = "SELECT {$id} FROM {$table} WHERE {$column} LIKE '{$reference}'";
		$result = @mysqli_query($connection, $query);
		$num_rows = mysqli_num_rows($result);
		if ($result) {
			if ($num_rows == 1) {
				while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
					$requestedID = $row[$id];
				}
			}
		}
		return $requestedID;
	}
?>