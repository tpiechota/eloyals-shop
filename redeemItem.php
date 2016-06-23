<?php

	session_start();
	
	define("eLoyals_Access_Code",1);
	
	require("./inc/session.inc.php");

	require("./inc/functions.inc.php");
	
	require("./getData.php");
	

	for ($row = 0; $row < $QtyItems; $row++ ){
					
		if ( $itemList[$row]->get_id() == $_GET['item'] ) {
						
			echo "	<td class=\"span1 shop-hint\">";
			echo "<a class=\"popup-marker shop-popover\" data-placement=\"top\" data-content=\"<div class='shop-pop-content'>";
			echo $itemList[$row]->get_conv_rate();
			
			if ( $itemList[$row]->get_conv_rate() > 1 ) 
				echo " ".$itemList[$row]->get_single_desc();
			else
				echo " ".$itemList[$row]->get_plural_desc();
			
			echo " = 1 reward</div><div class='hr'></div>\" rel=\"popover\"><img src=\"img/help1-orange.png\"></a></td>\n";
			echo "	<td class=\"span4\">".$itemList[$row]->get_description()."</td>\n";
			echo "	<td class=\"span3\"><span id=\"MRed-".$itemList[$row]->get_id()."\" class=\"changed\">1</span><span class=\"shop-qty-unit\">";
			echo "<img class=\"shop-stamp\" src=\"img/stamp-free.png\" alt=\"stamp\" /></span></td>\n";
			echo "	<td class=\"span4\">";
			echo "		<input type=\"hidden\" name=RQty-".$itemList[$row]->get_id()." id=RQty-".$itemList[$row]->get_id()." value=\"0\" />";
			echo "		<button id=\"RBTN-".$itemList[$row]->get_id()."\" onclick=\"RedeemItem( this );\" class=\"btn btn-warning shop-redeem-item\" type=\"button\">Redeem 0</button></td>\n";

		}
	}
?>