var xmlhttp;

// code for IE7+, Firefox, Chrome, Opera, Safari
if (window.XMLHttpRequest){
	xmlhttp=new XMLHttpRequest();
}

// code for IE6, IE5
else{
	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}

// what needs to be done when the page is loaded 
xmlhttp.onreadystatechange = function () {

    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

        // Reload the popover event handler after the ajax call
        $("#wrapper").load("shop.php", function () { addPopoverEvent() });

        document.getElementById("wrapper").outerHTML = xmlhttp.responseText;

    }
}

function keyCheck( e ) {

	if (e.keyCode == 13) {
	
		validateID();

	}
	
	return true;
}

// validate eloyals id
function validateID(){
	
	if ( $('#barcode').val().trim() == "" ){			
		
		alert("INVALID eLoyals IDENTIFICATION !!!");
		
		document.getElementById('barcode').focus();
		
	}
	
	else {
	
		var request = $.ajax({
			url: "./inc/decode.inc.php?barcode="+$('#barcode').val(),
			type: "GET",            
			dataType: "text"
		});

		request.done( function() {
		
			if ( parseInt( request.responseText ) == -1 ) {
			
				alert("INVALID eLoyals IDENTIFICATION !!!");
				
				$('#barcode').val("");
				
				document.getElementById('barcode').focus(); 
			}
			
			else {
			
				loadData();
				
				// enable Reset button
				$('#BTN-Reset').attr("class","btn btn-large");
				$('#BTN-Reset').removeAttr("disabled");
				
				// enable Cancel button
				$('#BTN-Cancel').attr("class","btn btn-large btn-danger");
				$('#BTN-Cancel').removeAttr("disabled");
				
				// disable Search button
				$('#BTN-Search').attr("class","btn btn-warning btn-large btn-search disabled"); 
				$('#BTN-Search').attr("disabled","disabled");
				
				// disable logout
				$('#BTN-Logout').attr("class","btn btn-large btn-inverse disabled");
				$('#BTN-Logout').attr("disabled","disabled");
				
				// disable admin
				$('#BTN-Admin').attr("class","btn btn-info btn-admin disabled");
				$('#BTN-Admin').attr("disabled","disabled");

				// readonly barcode
				$('#barcode').attr("readonly","readonly");
			}
		} );
	}
}

function reset() {

	var answer = confirm("Reset Transaction ?")

	if (answer){
		
		loadData();
		
		return false;
	}
	else{
		
		return false;
	
	}
}

function cancel() {

	var answer = confirm("Cancel Transaction ?")

	if (answer){
		
		window.location.href="index.php";
		
		return false;
	}
	else{
		
		return false;
	
	}
}

function loadData() {

	xmlhttp.open("GET","./searchData.php?barcode=" + $('#barcode').val() +  "&rand="+Math.random(),true);
	
	xmlhttp.send();
	
}

function addToItem( o ) {

	$btn   = o.id;
	$field = "Qty-" + $btn.substr(4, $btn.length ); 
	
	$val = $( "#" + $field ).val();
	
	$val++;
	
	$( "#" + $field ).val( $val );

	document.getElementById( $btn ).innerHTML = "Add " + $val;
	
	// convert to redeem
	$conv_field      = "Max-" + $btn.substr(4, $btn.length );
	$converted_field = "Red-" + $field;
	$balance_field   = "Bal-" + $btn.substr(4, $btn.length );
	
	$conv_qty        = parseInt( $( "#" + $conv_field ).val() );
	$qty_converted   = parseInt( $( "#" + $converted_field ).val() );
	$balance_item    = parseInt( $( "#" + $balance_field ).val() );
	
	$balance = $balance_item + $val - $qty_converted;

	if ( $balance >= $conv_qty ){
		
		$red_item     = "MRed-" + $btn.substr(4, $btn.length );
				
		$Redeem = $("#Redeem").val();
		
		// the item is already in the redeem table
		if ( document.getElementById( $red_item ) != null && document.getElementById( $red_item ).innerHTML > 0 ) {
		
			$max_red_item = parseInt( document.getElementById( $red_item ).innerHTML );
			
			$max_red_item++;
			
			document.getElementById( $red_item ).innerHTML = $max_red_item;
			
			$('#' + $red_item ).attr("class", "changed");
			
		} else {
			
			$Redeem++;
		
			$( "#TR-Red-" + $Redeem).load("redeemItem.php?barcode=" + $('#barcode').val() + "&item=" + $btn.substr(4, $btn.length ), function () { addPopoverEvent() });
			
		}

		$( "#" + $converted_field ).val( $qty_converted + $conv_qty );
		
		$( "#Redeem" ).val( $Redeem );
		
	}
	
	// Enable GO
	$('#GO').attr("class","btn btn-success btn-go");
	$('#GO').removeAttr("disabled");

}

function RedeemItem( o ) {

	$btn   = o.id;
	$field = "RQty-" + $btn.substr(5, $btn.length );
	$max   = "MRed-" + $btn.substr(5, $btn.length );
	
	$Qty_Max = parseInt( document.getElementById( $max ).innerHTML );
	
	$val = parseInt( $( "#" + $field ).val() );
	
	$val++;
	
	if ( $val > $Qty_Max ) {
	
		alert("Maximum number of Redeems for the item has been reached !!! \n Maximum = " + $Qty_Max );
		
	} else {
		
		$( "#" + $field ).val( $val );

		document.getElementById( $btn ).innerHTML = "Redeem " + $val;

	} 
	
	// Enable GO
	$('#GO').attr("class","btn btn-success btn-go");
	$('#GO').removeAttr("disabled");
	
}

function checkForm(){
	
	$("#transactForm").submit();
	
	return true;
	
}