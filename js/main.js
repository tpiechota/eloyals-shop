function logout() {

	var answer = confirm("Confirm Logout ?")

	if (answer){
		
		window.location.href="logout.php";
		
		return false;
	}
	else{
		
		return false;
	
	}
}

function checkLogin() {

	box = document.login;

	if ( box.business_id.value == "" ) {
		alert("Shop ID must be filled in!");
		box.business_id.value.focus();
		return false;
	}
	
	else if ( document.login.shop_user_id.value == "" ){
		alert("Username must be filled in!");
		box.shop_user_id.focus();
		return false;
	}
	
	else if ( document.login.shop_user_password.value == "" ){
		alert("Password must be filled in!");
		box.shop_user_password.focus();
		return false;
	}
	
	else {
		document.login.submit();
		return true;
	}
}