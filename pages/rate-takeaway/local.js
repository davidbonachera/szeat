		$('#star1').click(function() {
            $('#rating').val(1);
			$("#stars span").removeClass("starred");
			$("#stars #star1").addClass("starred");
            return false;
        });
		$('#star2').click(function() {
            $('#rating').val(2);
			$("#stars span").removeClass("starred");
			$("#stars #star1, #stars #star2").addClass("starred");
            return false;
        });
		$('#star3').click(function() {
            $('#rating').val(3);
			$("#stars span").removeClass("starred");
			$("#stars #star1, #stars #star2, #stars #star3").addClass("starred");
            return false;
        });
		$('#star4').click(function() {
            $('#rating').val(4);
			$("#stars span").removeClass("starred");
			$("#stars #star1, #stars #star2, #stars #star3, #stars #star4").addClass("starred");
            return false;
        });
		$('#star5').click(function() {
            $('#rating').val(5);
			$("#stars span").removeClass("starred");
			$("#stars #star1, #stars #star2, #stars #star3, #stars #star4, #stars #star5").addClass("starred");
            return false;
        });