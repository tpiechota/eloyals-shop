<?php
	
	defined('eLoyals_Access_Code') or die('Access to this file is restricted');

	require("config.inc.php");
	
	$dbConnection = new PDO('mysql:dbname='.DB_DATABASE.';host='.DB_HOST.';charset=utf8', DB_USER, DB_PASSWORD);

	$dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	
	$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
