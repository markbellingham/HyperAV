<?php
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

	function dropdown_js_reload($column, $table) {
		global $connection, $category;

		$query = "SELECT DISTINCT {$column} FROM {$table} ORDER BY {$column} ASC";
		$results = @mysqli_query($connection, $query);
		$num_rows = mysqli_num_rows($results);
		if($results) {
			if($num_rows > 0) { ?>
				<form action = <?php echo $_SERVER['PHP_SELF']; ?> method = "GET">
					<select name = <?php echo $column ?> onchange = "this.form.submit()">
						<option>Select</option>
						<?php while ($option = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
							if ($option[$column] === $category) { ?>
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
?>