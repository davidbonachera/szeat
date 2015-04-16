<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/jquery-ui-1.8.21.custom.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/jquery.cookie.js"></script>
<script src='js/fullcalendar.min.js'></script>
<script src='js/jquery.dataTables.min.js'></script>
<script src="js/dataTables.fnGetHiddenNodes.js"></script>
<script src="js/excanvas.js"></script>
<script src="js/jquery.flot.min.js"></script>
<script src="js/jquery.flot.pie.min.js"></script>
<script src="js/jquery.flot.stack.js"></script>
<script src="js/jquery.flot.resize.min.js"></script>
<script src="js/jquery.chosen.min.js"></script>
<script src="js/jquery.uniform.min.js"></script>
<script src="js/jquery.cleditor.min.js"></script>
<script src="js/jquery.noty.js"></script>
<script src="js/jquery.elfinder.min.js"></script>
<script src="js/jquery.raty.min.js"></script>
<script src="js/jquery.iphone.toggle.js"></script>
<script src="js/jquery.uploadify-3.1.min.js"></script>
<script src="js/jquery.gritter.min.js"></script>
<script src="js/jquery.imagesloaded.js"></script>
<script src="js/jquery.masonry.min.js"></script>
<script src="js/jquery.knob.js"></script>
<script src="js/jquery.sparkline.min.js"></script>
<script src="js/custom.js"></script>
<!-- end: JavaScript-->

<script type="text/javascript">
$(document).ready(function(){
	<?php 
		$currentMonth 	= date("m");
		$currentYear 	= date("Y");
		$chartOrders	= array();
		$chartUsers		= array();
		
		$orders = $db->query("SELECT COUNT(*) AS total, DAYOFMONTH(date) AS day FROM orders WHERE MONTH(date)='$currentMonth' AND YEAR(date)='$currentYear' GROUP BY day LIMIT 30"); 
		while($or=$db->fetch_array($orders)) {
			$chartOrders[] = "[{$or['day']}, {$or['total']}]";
		}
		$users = $db->query("SELECT COUNT(*) AS total, DAYOFMONTH(date) AS day FROM users WHERE MONTH(date)='$currentMonth' AND YEAR(date)='$currentYear' GROUP BY day LIMIT 30"); 
		while($ur=$db->fetch_array($users)) {
			$chartUsers[] = "[{$ur['day']}, {$ur['total']}]";
		}
	?>
	
	var orders = [<?php echo implode(', ',$chartOrders); ?>];
	var users = [<?php echo implode(', ',$chartUsers); ?>];
	charts(orders, users);
	
	/*
	$('#checkPaid').click(function() {
		$('input:checkbox.paid').attr('checked','checked');
		$('input:checkbox.paid').parent('span').addClass('checked');
		$('input:checkbox.unpaid').attr('checked',false);
		$('input:checkbox.unpaid').parent('span').removeClass('checked');
		return false;
	});
	$('#checkUnpaid').click(function() {
		$('input:checkbox.unpaid').attr('checked','checked');
		$('input:checkbox.unpaid').parent('span').addClass('checked');
		$('input:checkbox.paid').attr('checked',false);
		$('input:checkbox.paid').parent('span').removeClass('checked');
		return false;
	});
	*/
	
	$('#generateInvoice').click(function() {
		var rid = getURLParameter("id");
		$("#invoiceForm").prop("target", "_blank");
		$("#invoiceForm").attr("action", "invoicing-generate.php?id="+rid);
		$('#invoiceForm').submit();
		return false;
	});
	
	$('#checkPaid').click(function() {
		var rid = getURLParameter("id");
		$("#invoiceForm").prop("target", "_self");
		$("#invoiceForm").attr("action", "invoicing-view.php?id="+rid+"&mark=paid");
		$('#invoiceForm').submit();
		return false;
	});
	
	$('#checkUnpaid').click(function() {
		var rid = getURLParameter("id");
		$("#invoiceForm").prop("target", "_self");
		$("#invoiceForm").attr("action", "invoicing-view.php?id="+rid+"&mark=unpaid");
		$('#invoiceForm').submit();
		return false;
	});

	$('#markAll').click(function() {
		$('input:checkbox.markAll').attr('checked','checked');
		$('input:checkbox.markAll').parent('span').addClass('checked');
		return false;
	});
	
	$('#cancelButton').click(function() {
		window.history.back();
		return false;
	});
});

function getURLParameter(name) {
    return decodeURI((RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]);
}
</script>