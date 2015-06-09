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