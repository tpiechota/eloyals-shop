<?php

	defined('eLoyals_Access_Code') or die('Access to this file is restricted');
	
	if (!isset($_SESSION['eLoyals_loggedIn']) || $_SESSION['eLoyals_loggedIn'] != 1) {

		//back to the login page	
		header("Location: index.php");

	}