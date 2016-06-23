<?php

	defined('eLoyals_Access_Code') or die('Access to this file is restricted');
	
	if (isset($_COOKIE['shopeLoyalscom'])) {
		
		$_SESSION['eLoyals_business_id'] = $_COOKIE['shopeLoyalscom'];
		
	}
	
