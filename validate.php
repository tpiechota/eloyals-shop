<?php
	
	session_start();
	
	define("eLoyals_Access_Code",1);

	require("./inc/functions.inc.php");
			
	$business_id        = $_POST['business_id'];
	$shop_user_id       = $_POST['shop_user_id'];
	$shop_user_password = $_POST['shop_user_password'];
	
	// valid data
	if ( authenticate( $business_id, $shop_user_id, $shop_user_password ) == true ){
		
		$_SESSION['eLoyals_loggedIn']        = 1;
		$_SESSION['eLoyals_business_id']     = $business_id;
		$_SESSION['eLoyals_shop_user_id']    = $shop_user_id;
		
		setcookie("shopeLoyalscom", "$business_id", time() + ( 60*60*24*365*5 )); // 5 year
	
		header("Location: shop.php");
		
	}
	// invalid data
	else {
	
		header("Location: index.php?errno=101");
		
	}
