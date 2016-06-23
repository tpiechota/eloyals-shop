<?php
	
	session_start();
	
	define('eLoyals_Access_Code', 1);
	
	if ( isset($_SESSION['eLoyals_loggedIn']) && $_SESSION['eLoyals_loggedIn'] != 0 ) {
	
		header("Location: shop.php");
		
	}
	
	require("./inc/startup.inc.php");

?>

<!doctype html>
<html id="htmlLogin">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="eLoyals">
		<meta name="author" content="Tomasz Piechota">
		<title>eLoyals - Your one stop Loyalty System</title>
		<link href="css/bootstrap.css" rel="stylesheet">
		<link href="css/bootstrap-responsive.css" rel="stylesheet">
		<link href="css/custom-shop.css" rel="stylesheet">
		<!-- HTML5 shim for IE backwards compatibility -->
		<!--[if lt IE 9]>
			  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->		
		<link rel="stylesheet" href="css/shop-login.css" type="text/css" media="screen" />

		<!-- Fav and touch icons -->
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/ico/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="img/ico/apple-touch-icon-57-precomposed.png">
        <link rel="shortcut icon" href="img/ico/favicon.png">
		<script src="js/main.js"></script>
	</head>

<body id="login">
	<div class="swirls">
		<div id="wrap">
			<div class="container header">
				<div class="row-fluid">
					<div id="shop-login" class="divFormWidthLogin">
						<ul id="shop-login-form">
							<li>
								<form action='validate.php' method="POST" name="login">
									<div class="shop-login-form-fields">

										<div class="shop-form">
											<div class="row-fluid shop-form-brand">
												<div class="span3"><img src="img/logo_v8.jpg" alt="eLoyals Logo"></div>
												<div class="span9"><span>eLoyals</span></div>
											</div>
											<hr />
											
											<?php 
							
												if ( isset($_GET['errno'])) {
						
													require( "./inc/errno.inc.php" );
													
													echo "<div class=\"row-fluid\" >";
													
													echo "<div class=\"span10 offset2\" id=\"msg\"><br />";
													
													echo errno( $_GET['errno'] );
													
													echo "</div>";
													
													echo "</div>";
												
												}
										
											?>											
											<div class="block">
												<label for="business_id">Shop&nbsp;ID:&nbsp;</label>
												<input type="text" id="shopid" name="business_id" placeholder="Enter the Shop ID"
													<?php 
														if ( isset( $_SESSION['eLoyals_business_id'] )) {
															echo " value=\"".$_SESSION['eLoyals_business_id']."\" readonly=\"readonly\" ";
														}
													?> />
											</div>
											<div class="block">
												<label for="shop_user_id">Username:&nbsp;</label>
												<input type="text" id="username" name="shop_user_id" placeholder="Enter your username"/>
											</div>
											<div class="block">
											<label for="shop_user_password">Password:&nbsp;</label>
											<input type="password" id="password" name="shop_user_password" placeholder="Enter your password"/></div>
										</div>
										<div class="shop-footer">
											<div>
												<button class="btn btn-success btn-go" onclick="return checkLogin();">Sign In</button>
												<!--Remember me
												<input type="checkbox" id="remember" name="remember" class="regular-checkbox" /><label for="remember"></label>-->
											</div>
										</div>
									</div>
								</form>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>
<!--/.swirls --> 

<!-- Le javascript ================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 

<script src="js/jquery.js"></script> 
<script src="js/bootstrap.min.js"></script> 
</body>
</html>