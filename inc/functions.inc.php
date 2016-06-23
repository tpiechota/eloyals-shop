<?php

	defined('eLoyals_Access_Code') or die('Access to this file is restricted');
	
	function authenticate($shop, $username, $password) {   
		
		require("./inc/db.inc.php");
			
		$boolAuthenticated = false;
		
		/*** BUSINESS ****/
		
		// Get items for the business
		$sql = "SELECT tbl_business.id FROM tbl_business WHERE
					   tbl_business.username = :shop_id";
		
		$items = $dbConnection->prepare("$sql");
		
		$items->bindParam(':shop_id', $shop, PDO::PARAM_INT); 
		
		$items->execute();
	
		if ( $items->rowCount() == 0 ){ // business not found 
			
			$boolAuthenticated = false;
		
		}  
		
		if ( $items->rowCount() == 1 ){ // business found
		
			$boolAuthenticated = true;
		
		}
		
		else{ // something weird - such as two business found 
			
			$boolAuthenticated = false;
		
		}
		
		/*** Business found, look for user ***/
		if ( $boolAuthenticated ) {
		
			foreach ($items as $row) {
				
				$business = $row[0];
			
			}
			
			/*** USER ***/
			$sql = "SELECT tbl_user.admin FROM tbl_user WHERE 
   						   tbl_user.business_id = :shop_id   AND 
						   tbl_user.username    = :username  AND 
						   tbl_user.password    = :password"; // password has not yet been encrypted
			
			$items = $dbConnection->prepare("$sql");
			
			$items->bindParam(':shop_id',  $business, PDO::PARAM_INT); 
			$items->bindParam(':username', $username, PDO::PARAM_INT); 
			$items->bindParam(':password', $password, PDO::PARAM_INT); 
			
			$items->execute();
			
			if ( $items->rowCount() == 0 ){ // user not found or password does not match
				
				$boolAuthenticated = false;
			
			}  
			
			if ( $items->rowCount() == 1 ){ // user found and password matches
			
				$boolAuthenticated = true;
			
			}
			
			else{ // something weird - such as two users found 
				
				$boolAuthenticated = false;
			
			}
			
		}
		
		return $boolAuthenticated;
	}
	
	function decode($barcode){ 
	
		$eLoyals = strcmp(substr($barcode,0,7),"eLoyals");
		
		// Gets the ID from the barcode
		// strlen($barcode) - 11 to get the ID in the barcode
		$id  = substr($barcode,9,strlen($barcode) - 11);
		
		// Gets the check sum of the barcode
		$digit = substr($barcode,strlen($barcode) - 2,2);
		
		// check whether barcode starts with eLoyals
		if ( (bool) $eLoyals) {
			
			return -1;
			
		}
		
		// Gets the checksum from the ID to compare with the one in the barcode
		$chk = checksum($id);
		
		// Check sum of the barcode not valid
		if ($digit != $chk) {
		
			return -1;
			
		}
		
		return $id;
			
	}	

	// function to generate the checksum of the id
	// parameter: $id - ID of the customer
	function checksum($id){

		$position = 10 - strlen($id) + 1;
		
		$sum2 = 0;
		$sum3 = 0;

		for( $loop = 0; $loop < strlen($id); $loop++ ) {
		
			$sum2 = $sum2 + pow(intval(substr($id,$loop,1)) + $position,2);
			
			$sum3 = $sum3 + pow(intval(substr($id,$loop,1)) + $position,3);
			
			$position++;
			
		}
		
		$chk1 = ($sum3 - $sum2) % 10;

		$chk2 = chr(($sum3 % 26) + 65);
		
		return $chk1.$chk2;
		
	}	