<?php

	session_start();
	
	$_SESSION['eLoyals_loggedIn']     = 0;
	$_SESSION['eLoyals_shop_user_id'] = "";
	
	session_destroy();
	
	header("Location: ./index.php");
	
?>