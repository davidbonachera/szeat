$(".cuisine-types a").click(function () {
	$(".cuisine-types a").removeClass("types-selected");
	$(this).addClass("types-selected");
	var categoryName = $(this).attr("id");
	
	var cuisineName = $(this).find("span").html();
	var cuisineCount = $(this).find("strong").html();
	$("#cuisineName").html(cuisineName);
	$("#cuisineCount").html(cuisineCount);
	
	if(categoryName == "all"){
		$(".product-list-items > li").show();
		return true;
	}
	var categoryInteger = "."+categoryName;
	$(".product-list-items > li").hide();
	$(categoryInteger).show();
});


// $(".chosen-select").chosen({"disable_search_threshold": 100,search_contains: true});