<?php
	if (isset($extraSubHeaderContent)) {
		echo $extraSubHeaderContent;
	}
?>
<script type="text/javascript">
//$(document).ready(function() {
var ajax_refresh;

function hit_total(order_id) {
	$.ajax({
		url: 'index.php/<?php echo $link_controller?>/cek_meja_data/'+order_id,
		type: 'POST',
		success: function(data) {
			$('span#flex_total').text(data);
			$('input#tflex_total').val(data);
		}
	});
	return false;
}

function view_order(order_id,no_meja) {
	refresh_stop();
	var $dlg = $('#dlg_bill');
	$dlg.load('index.php/<?php echo $link_controller?>/daftar_order/'+order_id+'/'+no_meja,function(){
		hit_total(order_id);
		$dlg.dialog({
			title : 'ORDER MEJA KE-'+no_meja,
			modal : true,
			width : 700,
			buttons: {
				'Bayar' : function() {
					bayar_order($dlg,order_id);
				},
				'Batal' : function() {
					$(this).dialog('close');
				}
			},
			close: function() {
				refresh_now();
			}
		});
	});
	return false;
}

function bayar_order($dlg,order_id) {
	unmasking('.number');
	if (validasi('form#bill_form')){
		$('#bill_form').ajaxSubmit({
			url: 'index.php/<?php echo $link_controller?>/bayar_order/'+order_id,
			type: 'POST',
			success: function(data){
				//alert(data);
				if (data) {
					flexi_reload();
					$dlg.dialog('close');
				}
			}
		});
	}
	//masking('.number');
	return false;
}
	
function flexi_reload() {
	$('#<?php echo $flexi_id?>').flexReload();
	//$('div#debug').append(Date()+'<br>');
	//return false;
}
	
function refresh_now() {
	refresh_stop();
	ajax_refresh = window.setInterval(flexi_reload,10000);
	return false;
}

function refresh_stop() {
	clearInterval(ajax_refresh);
	return false;
} 
	
refresh_now();
masking('.number');
//});
</script>

<?php echo $this->load->view($link_view.'/bill_flexlist_view')?>

<div id="dlg_bill" style="display:none"></div>
<div id="debug"></div>
