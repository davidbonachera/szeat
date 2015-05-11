$(".chosen-select").chosen({"disable_search_threshold": 5,search_contains: true});


$("select#area").change(function(){
    $.getJSON("ajaxSelect.php",{area: $(this).val(), ajax: 'true'}, function(j){
        var Options = '';
        for (var i = 0; i < j.length; i++) {
            Options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
        }
        $("select#building").html(Options);
        $("select#building").trigger("chosen:updated");
    });
});

$('#searchForm').submit(function() {
    $(".HomePageError").hide();
    if ($("#area").val()=="") {
        $(".HomePageError").html("Please select an area and building.");
        $(".HomePageError").fadeIn("slow");
        return false;
    }
    if ($("#building").val()=="") {
        $(".HomePageError").html("Please select an area and building.");
        $(".HomePageError").fadeIn("slow");
        return false;
    }
});

