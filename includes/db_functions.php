<?php
	function dropdown_box($column, $table) {
		global $connection;

		$query = "SELECT DISTINCT {$column} FROM {$table} ORDER BY {$column} ASC";
		$results = @mysqli_query($connection, $query);
		$num_rows = mysqli_num_rows($results);
		if($results) {
			if($num_rows > 0) {?>
				<select name = <?php echo $column ?> >
					<option>Select</option>
					<?php while($option = mysqli_fetch_array($results, MYSQLI_ASSOC)) { ?>
						<option><?php echo $option[$column]; ?></option>
				<?php } ?>
				</select><?php
			}
		}

	}

?>