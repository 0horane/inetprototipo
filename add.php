<?php require('i_menu.php');
require_once('r_sqlinit.php');
?>
<a style="padding:5px; border-width:1p; border-radius=2px;" href="index.php">Busqueda</a>
	<form method='post'>

officeCode <input type='text' name='officeCode' value='<?php echo $officeCode;  ?>' readonly>  <br>
city	<input type='text' name='city' >  <br>
phone	<input type='text' name='phone' > <br>
addressLine1	<input type='text' name='addressLine1' ><br> 
addressLine2	<input type='text' name='addressLine2' > <br>
state	<input type='text' name='state' > <br>
country	<input type='text' name='country' > <br>
postalCode	<input type='text' name='postalCode' > <br>
territory<input type='text' name='territory' > <br>
		<input type='submit'><br>
	</form>
<?php
	
if (next($_POST)){
	
	


if(mysqli_query($link, "INSERT INTO offices VALUES('" . $officeCode . "','" . $_POST['city'] . "','" . $_POST['phone'] . "','" . $_POST['addressLine1'] . "','" . $_POST['addressLine2'] . "','" . $_POST['state'] . "','" . $_POST['country'] . "','" . $_POST['postalCode'] . "','" . $_POST['territory'] ."') ")){
	echo "Insertado correctamente";
}

}





?>
