$(function(){
	$("select#area").change(function(){
		$.getJSON("ajaxSelect.php",{area: $(this).val(), ajax: 'true'}, function(j){
			var options = '';
			for (var i = 0; i < j.length; i++) {
				options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
			}
		$("select#building").html(options);
	})
})
})


$("#notesDummy").keyup(function(){
	$(".notesbox").val(this.value);
});