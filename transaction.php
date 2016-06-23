<?php
	
	session_start();
	
	define("eLoyals_Access_Code",1);
	
	require("./inc/session.inc.php");

	require("./inc/functions.inc.php");
	
	require("./inc/db.inc.php");	

	// Gets barcode of customer
	$eLoyalsBarcode = $_POST['barcode'];
	
	// Decode barcode to get ID
	$valid = decode( $eLoyalsBarcode );
	
	/*** BUSINESS ***/
	$sql = "SELECT tbl_business.id FROM tbl_business WHERE
				   tbl_business.username = :shop_id";
	
	$items = $dbConnection->prepare("$sql");
	
	$items->bindParam(':shop_id', $_SESSION['eLoyals_business_id'], PDO::PARAM_INT); 
	
	$items->execute();	
	
	$business = $items->fetch(PDO::FETCH_ASSOC);

	/*** USER ***/
	$sql = "SELECT tbl_user.id FROM tbl_user WHERE 
				   tbl_user.business_id = :shop_id   AND 
				   tbl_user.username    = :username;";
				   
	$items = $dbConnection->prepare("$sql");
	
	$items->bindParam(':shop_id',  $business['id'], PDO::PARAM_INT); 
	$items->bindParam(':username', $_SESSION['eLoyals_shop_user_id'], PDO::PARAM_INT); 
	
	$items->execute();
				   
	$user = $items->fetch(PDO::FETCH_ASSOC);
		
	$description = "SHOP TRANSACTION";
	$branch      = 0;
	$card        = 0;
	
	// Get items for the business
	$sql = "INSERT INTO tbl_transaction
					( tbl_transaction.description,
					  tbl_transaction.user_id,
					  tbl_transaction.customer_id,
					  tbl_transaction.business_id,
					  tbl_transaction.branch_id,
					  tbl_transaction.card_id,
					  tbl_transaction.processed_at )
				VALUES (:description,
						:user,
						:customer,
						:business,
						:branch,
						:card,
						NOW());";
	
	try{
		
		$dbConnection->beginTransaction(); 
		
		$items = $dbConnection->prepare("$sql");
	
		$items->bindParam(':description', $description   , PDO::PARAM_STR); 
		$items->bindParam(':user'       , $user['id']    , PDO::PARAM_INT); 
		$items->bindParam(':customer'   , $valid         , PDO::PARAM_INT); 
		$items->bindParam(':business'   , $business['id'], PDO::PARAM_INT); 
		$items->bindParam(':branch'     , $branch        , PDO::PARAM_INT); 
		$items->bindParam(':card'       , $card          , PDO::PARAM_INT); 
	
		$items->execute();

		$transaction_id = $dbConnection->lastInsertId();
		
		$dbConnection->commit();
	
	} catch(PDOExecption $e) {
        
		$dbConnection->rollback();
		
        print "Error!: " . $e->getMessage() . "</br>";
    }

	/** TRANSACTION ITEMS ***/
	$sql = "SELECT  tbl_bus_item.id,
					tbl_bus_item.convert_rate
				FROM tbl_bus_item 
				WHERE tbl_bus_item.business_id = :business";
	
	$items = $dbConnection->prepare("$sql");
	
	$items->bindParam(':business',$business['id'], PDO::PARAM_INT); 
	
	$items->execute();
	
	foreach ($items as $row) {
	
		$product = $row[0];
		$convert = $row[1];
		$qty     = 0;
		$reward  = 0;
		
		/*** STAMPS ***/
		if ( $_POST['Qty-'.$product] > 0 ) {
		
			$qty = $_POST['Qty-'.$product];
			
		}
		
		/*** REWARDS ***/
		if ( isset($_POST['RQty-'.$product]) && $_POST['RQty-'.$product] > 0 ){
		
			$reward = $_POST['RQty-'.$product];
			
		}
		
		// only update items that have been changed
		if ( $qty != 0 || $reward != 0 ){
		
			$sql = "INSERT INTO tbl_trans_item
					( tbl_trans_item.trans_id,
					  tbl_trans_item.business_id,
					  tbl_trans_item.item_id,
					  tbl_trans_item.qty_item,
					  tbl_trans_item.qty_reward )
				VALUES (:transaction,
						:business,
						:item,
						:qty,
						:reward);";
		
			try{
				
				$dbConnection->beginTransaction(); 
				
				$items = $dbConnection->prepare("$sql");
			
				$items->bindParam(':transaction', $transaction_id, PDO::PARAM_INT); 
				$items->bindParam(':business'   , $business['id'], PDO::PARAM_INT); 
				$items->bindParam(':item'       , $product       , PDO::PARAM_STR); 
				$items->bindParam(':qty'        , $qty           , PDO::PARAM_INT); 
				$items->bindParam(':reward'     , $reward        , PDO::PARAM_INT); 
			
				$items->execute();
				
				$dbConnection->commit();
			
			} catch(PDOExecption $e) {
				
				$dbConnection->rollback();
				
				print "Error!: " . $e->getMessage() . "</br>";
			}
			
			/*** UPDATE BALANCE ***/
			$sql = "SELECT  tbl_cus_item.item_id,
							tbl_cus_item.balance,
							tbl_cus_item.reward
						FROM tbl_cus_item
						WHERE tbl_cus_item.business_id = :business AND
							  tbl_cus_item.customer_id = :barcode  AND
							  tbl_cus_item.card_id     = 0         AND
							  tbl_cus_item.item_id     = :item";
		
			$items = $dbConnection->prepare("$sql");
		
			$items->bindParam(':business', $business['id'], PDO::PARAM_INT); 
			$items->bindParam(':barcode' , $valid         , PDO::PARAM_INT); 
			$items->bindParam(':item'    , $product       , PDO::PARAM_STR); 
		
			$items->execute();
		
			/*** BALANCE FOUND - UPDATE ***/
			if ( $items->rowCount() > 0 ) {		
				
				$balance_item = $items->fetch(PDO::FETCH_ASSOC);
				
				// Calculate balance
				$qty    = $qty    + $balance_item['balance'];
				
				$balance_item['balance'] = $qty % $convert;
				
				// Reward
				$balance_item['reward'] = $balance_item['reward'] + ( ( $qty - $balance_item['balance'] ) / $convert );
				
				$balance_item['reward'] = $balance_item['reward'] - $reward;
				
				// UPDATE
				$sql = "UPDATE tbl_cus_item 
							SET tbl_cus_item.balance = :balance,
								tbl_cus_item.reward  = :reward
							WHERE tbl_cus_item.business_id = :business AND
								  tbl_cus_item.customer_id = :barcode  AND
								  tbl_cus_item.card_id     = 0         AND
								  tbl_cus_item.item_id     = :item";
			
				$items = $dbConnection->prepare("$sql");
			
				$items->bindParam(':balance' , $balance_item['balance'], PDO::PARAM_INT); 	
				$items->bindParam(':reward'  , $balance_item['reward'] , PDO::PARAM_INT); 
				$items->bindParam(':business', $business['id']         , PDO::PARAM_INT); 
				$items->bindParam(':barcode' , $valid                  , PDO::PARAM_INT); 
				$items->bindParam(':item'    , $product                , PDO::PARAM_STR); 
			
				$items->execute();
				
			}
				
			/*** BALANCE NOT FOUND - INSERT ***/	
			else {
			
				$balance_qty = $qty % $convert;
				
				$balance_reward = ( $qty - $balance_qty ) / $convert;
				
				$balance_reward = $balance_reward - $reward;
				
				$sql = "INSERT INTO tbl_cus_item 
							( 	tbl_cus_item.customer_id,
								tbl_cus_item.business_id,
								tbl_cus_item.card_id,
								tbl_cus_item.item_id,
								tbl_cus_item.balance,
								tbl_cus_item.reward ) 
							VALUES ( 	:barcode,
										:business,
										:card,
										:item,
										:balance,
										:reward );";
			
				$items = $dbConnection->prepare("$sql");
			
				$items->bindParam(':barcode' , $valid         , PDO::PARAM_INT); 
				$items->bindParam(':business', $business['id'], PDO::PARAM_INT); 
				$items->bindParam(':card'    , $card          , PDO::PARAM_INT); 
				$items->bindParam(':item'    , $product       , PDO::PARAM_INT); 
				$items->bindParam(':balance' , $balance_qty   , PDO::PARAM_INT); 	
				$items->bindParam(':reward'  , $balance_reward, PDO::PARAM_INT); 
			
				$items->execute();
				
			}
		} // end if
	}
	
	// Join shop automatically
	$sql = "SELECT tbl_bus_cus.active 
				FROM tbl_bus_cus    WHERE
				   tbl_bus_cus.customer_id = :barcode  AND
				   tbl_bus_cus.business_id = :business";
		
	$items = $dbConnection->prepare("$sql");
		
	$items->bindParam(':barcode' , $valid         , PDO::PARAM_INT); 
	$items->bindParam(':business', $business['id'], PDO::PARAM_INT); 
		
	$items->execute();
	
	$active = $items->fetch(PDO::FETCH_ASSOC);
	
	if ( $items->rowCount() == 0 ){ // Not found then insert
	
		$sql = "SELECT tbl_customer.not_txt_msg,
					   tbl_customer.not_emails
					FROM tbl_customer WHERE
						 tbl_customer.id = :barcode";
			
		$items = $dbConnection->prepare("$sql");
			
		$items->bindParam(':barcode' , $valid, PDO::PARAM_INT); 
			
		$items->execute();	
		
		$customer = $items->fetch(PDO::FETCH_ASSOC);
		
		$sql = "INSERT INTO tbl_bus_cus
						(tbl_bus_cus.business_id,
						 tbl_bus_cus.customer_id,
						 tbl_bus_cus.activation_on,
						 tbl_bus_cus.not_txt_msg,
						 tbl_bus_cus.not_emails,
						 tbl_bus_cus.active)
					VALUES (:business,
							:barcode,
							NOW(),
							:txtMsg,
							:email,
							1)";

		try{
			
			$dbConnection->beginTransaction(); 
							
			$items = $dbConnection->prepare("$sql");

			$items->bindParam(':business', $business['id']         , PDO::PARAM_INT); 		
			$items->bindParam(':barcode' , $valid                  , PDO::PARAM_INT); 
			$items->bindParam(':txtMsg'  , $customer['not_txt_msg'], PDO::PARAM_INT); 
			$items->bindParam(':email'   , $customer['not_emails'] , PDO::PARAM_INT); 

			$items->execute();	
			
			$dbConnection->commit();
			
		} catch(PDOExecption $e) {
			
			$dbConnection->rollback();
			
			print "Error!: " . $e->getMessage() . "</br>";
		}

	}
	
	// Customer exist but it is not active
	else if ( $active['active'] == 0 ) { 
	
		$sql = "UPDATE tbl_bus_cus
					SET tbl_bus_cus.activation_on = NOW(),
						tbl_bus_cus.ative         = 1
					WHERE tbl_bus_cus.customer_id = :barcode  AND
						  tbl_bus_cus.business_id = :business";

		try{
			
			$dbConnection->beginTransaction(); 
							
			$items = $dbConnection->prepare("$sql");

			$items->bindParam(':business', $business['id']         , PDO::PARAM_INT); 		
			$items->bindParam(':barcode' , $valid                  , PDO::PARAM_INT); 

			$items->execute();	
			
			$dbConnection->commit();
			
		} catch(PDOExecption $e) {
			
			$dbConnection->rollback();
			
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
	
	header("Location: shop.php");
	
?>