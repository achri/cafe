<?php if (isset($extraSubHeaderContent))
	echo $extraSubHeaderContent;
?>
<script type="text/javascript">
function bill_order(order_id,no_meja) {	
	
	refresh_stop();
	var $dlg = $('#dlg-meja');
	
	$dlg.html('Order selesai untuk meja ke-'+no_meja+' ???')
	.dialog({
		title : 'KONFIRMASI',
		modal : true,
		width : 'auto',
		buttons: {
			'Selesai' : function() {
				
				$.ajax({
					url : 'index.php/<?php echo $link_controller?>/kitchen_done/'+order_id,
					type: 'POST',
					success: function(data) {
						if (data) {
							// notifikasi.js
							table_set(false,no_meja);
							$dlg.dialog('close');
						}
					}
				});
				
			},
			'Batal' : function() {
				$(this).dialog('close');
			}
		},
		close: function() {
			refresh_content();
			refresh_now();
		}
	});

	return false;
}

function daftar_meja() {
	$('div#ajax-meja-kitchen').load('index.php/<?php echo $link_controller_notifikasi?>/meja_kitchen',function(data){
		table_prepare();
		refresh_now();
		refresh_content();
	});
	return false;
}

// NOTIFIKASI MEJA AJAX
var controller = '<?php echo $link_controller_notifikasi?>', 
	jml_meja = <?php echo $this->config->item('jml_meja')?>,
	refresh_type = 'kitchen';

$(document).ready(function(){
	daftar_meja();
});

</script>

<fieldset style="height:97%" class="ui-widget-content ui-corner-all">
	<legend class="ui-state-default ui-corner-all">DAFTAR ORDER</legend>
	<div style="height:100%;overflow:auto" id="ajax-meja-kitchen"></div>
</fieldset>
<div id="dlg-meja"></div>