<?php

	session_start();
	
	define("eLoyals_Access_Code",1);
	
	require("./inc/session.inc.php");

	require("./inc/functions.inc.php");
	
	require("./getData.php");
	
?>
<div class="container-fluid wrapper" id="wrapper" name="wrapper">
	
	<form action="transaction.php" method="POST" id="transactForm" name="transactForm" >
	
		<?php echo "	<input type=\"hidden\" id=\"barcode\" name=\"barcode\" value=\"$eLoyalsBarcode\" />"; ?>
	
		<div class="row-fluid">
			<div class="span12 shop-balance">
			  <fieldset class="blue">
				<legend>BALANCE</legend>
				<table class="table table-striped">
					<?php

						for ($row = 0; $row < $QtyItems; $row++ ){
							echo "<tr>\n";
							echo "	<td class=\"span1 shop-hint\">\n";
							echo "		<a class=\"popup-marker shop-popover\" data-placement=\"top\" rel=\"popover\" data-original-title=\"\" title=\"\" data-content=\"<div class='shop-pop-content'>";
							echo $itemList[$row]->get_conv_rate();

							if ( $itemList[$row]->get_conv_rate() > 1 ) 
								echo " ".$itemList[$row]->get_plural_desc();
							else
								echo " ".$itemList[$row]->get_single_desc();
								
							echo " = 1 reward</div><div class='hr'></div>\"><img src=\"img/help1.png\"></a></td>\n";
							
							echo "	<td class=\"span4\">".$itemList[$row]->get_description()."</td>\n";
							echo "	<td class=\"span3\"><span>".$itemList[$row]->get_qty()."</span><span class=\"shop-qty-unit\">";
							
							if ( $itemList[$row]->get_qty() > 0 ) 
								echo "<img class=\"shop-stamp\" src=\"img/stamps.png\" alt=\"stamp\" /></span></td>\n";
							else
								echo "<img class=\"shop-stamp\" src=\"img/stamp.png\" alt=\"stamp\" /></span></td>\n";
								
							echo "	<td class=\"span4\">";
							echo "		<input type=\"hidden\" id=Red-Qty-".$itemList[$row]->get_id()." value=\"0\" />";
							echo "		<input type=\"hidden\" id=Bal-".$itemList[$row]->get_id()." value=\"".$itemList[$row]->get_qty()."\" />";
							echo "		<input type=\"hidden\" name=Qty-".$itemList[$row]->get_id()." id=Qty-".$itemList[$row]->get_id()." value=\"0\" />";
							echo "		<input type=\"hidden\" id=Max-".$itemList[$row]->get_id()." value=\"".$itemList[$row]->get_conv_rate()."\" />";
							echo "		<button id=\"BTN-".$itemList[$row]->get_id()."\" onclick=\"addToItem( this );\" class=\"btn btn-info shop-add-item\" type=\"button\" >Add 0</button></td>\n";
							echo "</tr>\n";
						}
						
						for ($loop = $QtyItems ; $loop < 5; $loop++){
							echo "<tr>\n";
							echo "	<td class=\"span1 shop-hint\">&nbsp;</td>\n";
							echo "	<td class=\"span4\">&nbsp;</td>\n";
							echo "	<td class=\"span3\">&nbsp;</td>\n";
							echo "	<td class=\"span4\">&nbsp;</td>";
							echo "</tr>\n";							
						}
					?>
				</table>
			  </fieldset>
			</div>
		</div>
	 
		<div class="row-fluid">
			<div class="span12 shop-rewards">
			  <fieldset class="orange">
				<legend class="orange">REWARDS</legend>
				<table class="table table-striped">
					<?php
						
						echo "<input type=\"hidden\" id=\"Redeem\" value=\"".$QtyRewards."\" />";
						
						$qty_items = 0;
						
						for ($row = 0; $row < $QtyItems; $row++ ){
						
							if ( $itemList[$row]->get_qty_rew() > 0 ) {
							
								echo "<tr id=\"TR-Red-".($row + 1)."\">\n";
								echo "	<td class=\"span1 shop-hint\">";
								echo "<a class=\"popup-marker shop-popover\" data-placement=\"top\" data-content=\"<div class='shop-pop-content'>";
								echo $itemList[$row]->get_conv_rate();
								
								if ( $itemList[$row]->get_conv_rate() > 1 ) 
									echo " ".$itemList[$row]->get_single_desc();
								else
									echo " ".$itemList[$row]->get_plural_desc();
								
								echo " = 1 reward</div><div class='hr'></div>\" rel=\"popover\"><img src=\"img/help1-orange.png\"></a></td>\n";
								echo "	<td class=\"span4\">".$itemList[$row]->get_description()."</td>\n";
								echo "	<td class=\"span3\"><span id=\"MRed-".$itemList[$row]->get_id()."\">".$itemList[$row]->get_qty_rew()."</span><span class=\"shop-qty-unit\">";
								echo "<img class=\"shop-stamp\" src=\"img/stamp-free.png\" alt=\"stamp\" /></span></td>\n";
								echo "	<td class=\"span4\">";
								echo "		<input type=\"hidden\" name=RQty-".$itemList[$row]->get_id()." id=RQty-".$itemList[$row]->get_id()." value=\"0\" />";
								echo "		<button id=\"RBTN-".$itemList[$row]->get_id()."\" onclick=\"RedeemItem( this );\" class=\"btn btn-warning shop-redeem-item\" type=\"button\">Redeem 0</button></td>\n";
								echo "</tr>\n";
									
								$qty_items++;
								
							}
						}
						
						for ($loop = $qty_items ; $loop < 5; $loop++){
						
							echo "<tr id=\"TR-Red-".($loop + 1)."\">\n";
							echo "	<td class=\"span1 shop-hint\">&nbsp;</td>\n";
							echo "	<td class=\"span4\">&nbsp;</td>\n";
							echo "	<td class=\"span3\">&nbsp;</td>\n";
							echo "	<td class=\"span4\">&nbsp;</td>\n";
							echo "</tr>\n";
							
						}
					?>
					
				</table>
			  </fieldset>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">

				<button id="GO" class="btn btn-success btn-go disabled" disabled="disabled">GO</button>
	
			</div>
		</div>
	</form>
</div>