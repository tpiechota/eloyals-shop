<?php

	session_start();
	
	define("eLoyals_Access_Code",1);
	
	require("./inc/session.inc.php");
	
	require("./inc/functions.inc.php");
	
?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="eLoyals">
		<meta name="author" content="Tomasz Piechota">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>eLoyals - Shop</title>
		<link href="css/bootstrap.css" rel="stylesheet" type="text/css">
		<link href="css/bootstrap-responsive.css" rel="stylesheet" type="text/css">

		<!-- HTML5 shim for IE backwards compatibility -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<link href="css/custom-shop.css" rel="stylesheet" type="text/css">
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script src="js/shop.js">                                                     </script>

		<!-- Fav and touch icons -->
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/ico/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72"   href="img/ico/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed"                 href="img/ico/apple-touch-icon-57-precomposed.png">
		<link rel="shortcut icon"                                href="img/ico/favicon.png">

		<script src="./js/main.js"></script>
	</head>

	<body>
		<div class="container-fluid wrapper">
			<div class="row-fluid"> 
				<fieldset>
				  <div class="span3 logo pull-left">
					<div class="container-fluid shop-logo-container">
					  <div><img src="img/logo_v8.jpg" alt="eLoyals Logo"></div>
					</div>
				  </div>
				  <div class="span9">
					<div class="container-fluid">
					  <div class="row-fluid">
						<div class="span12 pull-right">
						  <fieldset class="shop-menu-fieldset">
							<div class="span3 shop-menu">
								<a href="#" onclick="return logout(); ">
									<button class="btn btn-large btn-inverse" id="BTN-Logout">Logout</button>
								</a>
							</div>
							<div class="span3 shop-menu"><button id="BTN-Reset"  class="btn btn-large disabled" disabled="disabled" onclick="reset(); return false;">Reset</button></div>
							<div class="span3 shop-menu"><button id="BTN-Cancel" class="btn btn-large btn-danger disabled" disabled="disabled" onclick="cancel(); return false;">Cancel</button></div>
							<div class="span3 shop-menu"><button id="BTN-Admin"  class="btn btn-info btn-admin"><img src="img/settings.png" alt="Admin Panel" /></button></div>
						  </fieldset>
						</div>
					  </div>
					  <div class="row-fluid">
						<div class="span12 shop-search">
						  <fieldset class="shop-menu-fieldset">
						   <!-- <legend><i class="icon-qrcode"></i> Barcode</legend>-->
							<div id="shop-search-form">
							  <input class="shop-search-query" onkeypress="keyCheck( event );" id="barcode" name="barcode" type="text" value="" autofocus />
							  <button class="btn btn-warning btn-large btn-search" id="BTN-Search" onclick="validateID(); return false;">SEARCH <i class="icon-search icon-white"></i></button>
							</div>
						  </fieldset>
						</div>
					  </div>
					</div>
				  </div>
				</fieldset>
			</div>
		</div>
		
		<div class="container-fluid wrapper" id="wrapper" name="wrapper">

			<form action="#" METHOD="POST" name="transactForm" id="transactForm">
			
			<?php echo "	<input type=\"hidden\" id=\"barcode\" value=\"\" />"; ?>
			
			<!--  <hr />-->
			  <div class="row-fluid">
				<div class="span12 shop-balance">
				  <fieldset class="blue">
					<legend>BALANCE</legend>
					<table class="table table-striped">
						<?php

							for ($loop = 0 ; $loop < 5; $loop++){
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
			 <!-- <hr />-->
			  <div class="row-fluid">
				<div class="span12 shop-rewards">
				  <fieldset class="orange">
					<legend class="orange">REWARDS</legend>
					<table class="table table-striped">
						<?php
							
							for ($loop = 0 ; $loop < 5; $loop++){
							
								echo "<tr>\n";
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
				  <button class="btn btn-success btn-go disabled" disabled="disabled">GO</button>
				</div>
			  </div>
			</form>
		</div>
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script src="js/bootstrap.js">        </script>
		<script src="js/bootstrap-popover.js"></script> 
		<script src="js/bootstrap-modal.js"></script>
	</body>
</html>