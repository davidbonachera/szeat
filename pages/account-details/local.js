$("select#area").change(function(){
$.getJSON("ajaxSelect.php",{area: $(this).val(), ajax: 'true'}, function(j){
  var options = '';
  for (var i = 0; i < j.length; i++) {
	options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
  }
  $("select#building").html(options);
})
})




$('#myTab a').click(function (e) {
	e.preventDefault();
	$(this).tab('show');
})
$(document).ready(function(){
	var param = getURLParameter('tab');
	if (param!="") {
		$('#myTab a[href=#'+param+']').tab('show') ;
	}
});
$('#change_password').click(function() {
	window.location = "change-password.php";
});
function getURLParameter(name) {
	return decodeURI(
		(RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]
	);
}
