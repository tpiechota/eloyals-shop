<?php	

	define("eLoyals_Access_Code",1);
	
	require("./functions.inc.php");
	
	$eLoyalsBarcode = $_GET['barcode'];
	
	$valid = decode( $eLoyalsBarcode );
	
	echo $valid;
	
?>