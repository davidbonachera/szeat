<div class="span3 cuisine-types">
    <h2>Cuisine types</h2>
    <p>What do you fancy?<br />
	Pick a cuisine:</p>
    <script>
		$(document).ready(function(){
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
			<?php if (checkFeild($cuisines)) { ?>
				$("#<?php echo $cuisines; ?>").trigger('click');
			<?php } ?>
		});
    </script>
    <ul>
    	<li><a id="all" class="types-selected">All <strong><?php echo countCuisines('all'); ?></strong></a></li>
        <?php $cuisines = $db->query("SELECT * FROM cuisines WHERE status=1 ORDER BY title"); ?>
        <?php while ($cr=$db->fetch_array($cuisines)) { ?>
        	<li><a id="<?php echo $cr['id']; ?>"><span><?php __($cr['title']); ?></span> <strong><?php echo countCuisines($cr['id']); ?></strong></a></li>
        <?php } // while $cuisines loop ?>
        <!--
        <li><a id="afghanistani">Afghanistani <strong>1</strong></a></li>
        <li><a id="american">American <strong>10</strong></a></li>
        <li><a id="bangladeshi">Bangladeshi <strong>1</strong></a></li>
        <li><a id="brazilian-food">Brazilian food <strong>1</strong></a></li>
        <li><a id="caribbean">Caribbean <strong>3</strong></a></li>
        <li><a id="chicken">Chicken <strong>5</strong></a></li>
        <li><a id="chinese">Chinese <strong>41</strong></a></li>
        <li><a id="egyptian">Egyptian <strong>1</strong></a></li>
        <li><a id="english">English <strong>17</strong></a></li>
        <li><a id="ethiopian">Ethiopian <strong>2</strong></a></li>
        <li><a id="side-orders">Side Orders <strong>1</strong></a></li>
        <li><a id="indian">Indian <strong>79</strong></a></li>
        -->
     </ul>
</div>