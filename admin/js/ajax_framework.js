/* ---------------------------- */
/* XMLHTTPRequest Enable */
/* ---------------------------- */
function createObject() {
	var request_type;
	var browser = navigator.appName;
	if(browser == "Microsoft Internet Explorer"){
		request_type = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		request_type = new XMLHttpRequest();
	}
	return request_type;
}

var http = createObject();
var http2 = createObject();

/* Required: var nocache is a random number to add to request. This value solve an Internet Explorer cache issue */
var nocache = 0;

/*
window.setInterval('time()',1000);
function time() {
	var currentTime = new Date()
	var hours = currentTime.getHours()
	var minutes = currentTime.getMinutes()
	var seconds = currentTime.getSeconds()
	if (seconds < 10) { seconds = "0"+seconds; }
	if (minutes < 10) { minutes = "0"+minutes; }
	if (hours < 10) { hours = "0"+hours; }
	document.getElementById("time").innerHTML = hours+":"+minutes+":"+seconds ; 
}
*/

function call_user_form(uid,div) {
	url = "form.php?uid="+uid;
	// Pass the login variables like URL variable
	http.open('get', url);
	http.onreadystatechange = goNext;
	http.send(null);
	function goNext() {
		if(http.readyState == 4){
			var response = http.responseText;
			// else if login is ok show a message: "Site added+ site URL".
			document.getElementById(div).innerHTML = response;
		} else if(http.readyState == 4) {
			document.getElementById(div).innerHTML = "<img src='images/ajax-loader.gif' />";
		}
	}
}

function edit_user_form(uid,div) {
	status = document.getElementById("status").value;
	url = "form.php?uid="+uid+"&status="+status+"&change";
	// Pass the login variables like URL variable
	http.open('get', url);
	http.onreadystatechange = goNext;
	http.send(null);
	function goNext() {
		if(http.readyState == 4){
			var response = http.responseText;
			// else if login is ok show a message: "Site added+ site URL".
			document.getElementById(div).innerHTML = response;
			if (status==0) {
				document.getElementById("user_status_"+uid).innerHTML="Blocked";
			} else if (status==1) {
				document.getElementById("user_status_"+uid).innerHTML="Active";
			} else if (status==2) {
				document.getElementById("user_status_"+uid).innerHTML="Inactive";
			}
		} else if(http.readyState == 4) {
			document.getElementById(div).innerHTML = "<img src='images/ajax-loader.gif' />";
		}
	}
}