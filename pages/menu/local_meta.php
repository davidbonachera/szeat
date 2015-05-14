<script>
$(document).ready(function(){
	<?php if ($deliveryAvailable==false) { ?>
		$.prettyPhoto.open('#inlineContent','','');
	<?php } ?>
});
</script>

<?php if (strstr($_SERVER['HTTP_USER_AGENT'],"Chrome")) { ?>
	<style type="text/css">
        .menu ul strong {margin-left:50px;}
    </style>
<?php } ?>