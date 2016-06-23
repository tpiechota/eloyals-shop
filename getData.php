<?php

	defined('eLoyals_Access_Code') or die('Access to this file is restricted');
	
	require("./inc/db.inc.php");
	
	class product{ 
	
		private $id          = "";
		private $description = "";
		private $single_desc = "";
		private $plural_desc = "";
		private $conv_rate   = 0;
		private $qty         = 0;
		private $qty_rew     = 0;
		
		function __construct( $id, 
							$description, 
							$single_desc,
							$plural_desc,
							$conv_rate,
							$qty,
							$qty_rew){
							
			$this->id          = $id;
			$this->description = $description;
			$this->single_desc = $single_desc;
			$this->plural_desc = $plural_desc;
			$this->conv_rate   = round( $conv_rate , 0);
			$this->qty         = $qty;
			$this->qty_rew     = $qty_rew;
			
		}
		
		function get_id(){
			
			return $this->id;
			
		}
		
		function get_description(){
		
			return $this->description;
			
		}
		
		function get_single_desc(){
		
			return $this->single_desc;
			
		}
		
		function get_plural_desc(){
		
			return $this->plural_desc;
			
		}
		
		function get_conv_rate(){
		
			return $this->conv_rate;
			
		}
		
		function get_qty(){
		
			return $this->qty;
			
		}
		
		function get_qty_rew(){
		
			return $this->qty_rew;
			
		}
		
		function set_qty( $qty ){
		
			$this->qty = round( $qty , 0);
			
		}
		
		function set_qty_rew( $qty ){
		
			$this->qty_rew = round( $qty , 0);
		
		}
	}
			
	// Get items for the business
	$sql = "SELECT  tbl_bus_item.id,
					tbl_bus_item.description,
					tbl_unit.sing_descr,
					tbl_unit.plur_descr,
					tbl_bus_item.convert_rate
				FROM tbl_bus_item, 
					 tbl_unit,
					 tbl_business
				WHERE tbl_business.username    = :business_id    AND
					  tbl_bus_item.business_id = tbl_business.id AND
					  tbl_unit.id              = tbl_bus_item.unit_id";
	
	$items = $dbConnection->prepare("$sql");
	
	$items->bindParam(':business_id',$_SESSION['eLoyals_business_id'], PDO::PARAM_INT); 
	
	$items->execute();
	
	// transfer data to the object and add to an array
	$QtyItems = 0;

	foreach ($items as $row) {
	
		$item = new product($row[0], $row[1], $row[2], $row[3], $row[4], 0, 0);
		
		$itemList[$QtyItems] = $item;
		
		$QtyItems++;
		
	}
	
	$QtyRewards = 0;
	
	$eLoyalsBarcode = $_GET['barcode'];
	
	$valid = decode( $eLoyalsBarcode );
	
	// Get items for the business
	$sql = "SELECT  tbl_cus_item.item_id, 
					tbl_cus_item.balance,
					tbl_cus_item.reward
				FROM tbl_cus_item,
					 tbl_business
				WHERE tbl_business.username    = :business_id    AND
					  tbl_cus_item.business_id = tbl_business.id AND
					  tbl_cus_item.customer_id = :barcode        AND
					  tbl_cus_item.card_id      = 0;";
	
	$items = $dbConnection->prepare("$sql");
	
	$items->bindParam(':business_id', $_SESSION['eLoyals_business_id'], PDO::PARAM_INT); 
	$items->bindParam(':barcode',     $valid,                           PDO::PARAM_INT); 
	
	$items->execute();
	
	if ( $items->rowCount() > 0 ) {
		
		foreach ($items as $row) {
		
			// look through the products to populate the quantities
			for ( $product = 0; $product < $QtyItems ; $product++ ) {
			
				// found product with the same id !!!
				if ( $itemList[ $product ] -> get_id() == $row[0] ) {
				
					$itemList[ $product ] -> set_qty( $row[1] );
					$itemList[ $product ] -> set_qty_rew( $row[2] );
					
					if ( $itemList[ $product ] -> get_qty_rew() > 0 )
						$QtyRewards++;
					
				}
			}
		}
	}
