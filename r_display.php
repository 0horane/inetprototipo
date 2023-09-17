<?php function displafy ($rows,$columns){ ?>
	<table border=1>
	<tr>
	<?php
	while ($row=mysqli_fetch_assoc($columns)){
	?>
		
		<th><?= $row['Field'] ?></th>
	<?php } ?> 
	</tr> 
	<?php

	mysqli_data_seek($columns, 0);
	while ($row=mysqli_fetch_assoc($rows)){
		?>
		<tr>
		<?php
			while ($i=mysqli_fetch_assoc($columns)){
				?>
				<td><?php echo $row[$i['Field']]; ?></td>
				<?php
			}
			mysqli_data_seek($columns, 0);
		?>
		</tr>
	<?php
	}

}


?>



