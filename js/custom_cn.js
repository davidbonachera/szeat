        $('#searchForm').submit(function() {
            $(".HomePageError").hide();
            if ($("#area").val()=="") {
                $(".HomePageError").html("请选择您的所在区域和小区楼栋.");
                $(".HomePageError").fadeIn("slow");
                return false;
            }
            if ($("#building").val()=="") {
                $(".HomePageError").html("请选择您的所在区域和小区楼栋.");
                $(".HomePageError").fadeIn("slow");
                return false;
            }
        });