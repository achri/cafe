<?php if (isset($extraSubHeaderContent))
	echo $extraSubHeaderContent;
?>

<script type="text/javascript">
//var order_id,no_meja,kat_id,jml_meja = <?php echo $this->config->item('jml_meja')?>;

function daftar_menu() {
	var kat_id = $('#kat_id').val();
	
	$.ajax({
		url : 'index.php/<?php echo $link_controller_menu;?>/daftar_menu_all/'+kat_id,
		type: 'POST',
		success: function(data){
			
			$dlg_content.html(data)
			.dialog('option',{
				title : 'DAFTAR MENU',
				buttons: {
					
					'Batal' : function() {
						$(this).dialog('close');
					}
				},
				close: function() {
					$('#jml').focus();
				}
			}).dialog('open');
			return false;
		}
	});
	return false;
}

function pilih_menu(menu_id,menu_nama) {
	var kat_id = $('#kat_id').val();

	$.ajax({
		url : 'index.php/<?php echo $link_controller?>/list_autocomplate/nama/'+kat_id,
		type : 'GET',
		data: 'q='+menu_nama,
		success: function(data) {
			var item = data.split("|");
			$('#menu_nama').val(item[0]);
			$('#menu_kode').val(item[1]);
			$('#menu_id').val(item[2]);
			$('#harga').val(item[3]);
			$dlg_content.dialog('close');
		}
	});
	return false;
}

// TAMBAH ORDER
function tambah_order(no_meja) {
	$.ajax({
		url : 'index.php/<?php echo $link_controller?>/tambah_order/'+no_meja,
		type: 'POST',
		success: function(order_id) {
			
			if (order_id){
				daftar_order(order_id,no_meja);	
			}
		
		}
	});	
	return false;
}

function daftar_order(order_id,no_meja) {
	$('div#ajax-form-order').load('index.php/<?php echo $link_controller?>/form_order/'+order_id+'/'+no_meja);
	$('div#ajax-daftar-order').load('index.php/<?php echo $link_controller?>/daftar_order/'+order_id+'/'+no_meja);
	return false;
}


function tambah_menu(order_id,no_meja) {
	daftar_order(order_id,no_meja);	
	return false;
}

function buat_menu() {
	refresh_stop();
	if (validasi('form#form-menu')){
		$('form#form-menu').ajaxSubmit({
			url : 'index.php/<?php echo $link_controller?>/tambah_menu',
			type: 'POST',
			success: function(data) { 
				
				if (data == 'sukses') {
					form_clear();
					refresh_all();
					
				} else if (data == 'duplikasi') {
					alert('Menu ini sudah di pesan sebelumnya !!!');
					form_clear();
				}
				
				refresh_now();
			}
		});
	}
	return false;
}

function hapus_order(order_id,no_meja) {
	var dlg_button = {
		'HAPUS' : function() {
			$.ajax({
			   type: "POST",
			   url: "index.php/<?php echo $link_controller;?>/hapus_order/"+order_id,
			   success: function(data){
					$dlg_content.dialog('close');
			   }
			});
		},
			'BATAL' : function() {
				$dlg_content.dialog('close');
			}	
		}, 
		dlg_close = function(){
			set_meja();
			daftar_order_awal();
		};
		
	konfirmasi("Order Meja ke-<strong>"+no_meja+"</strong> akan dihapus ???",dlg_button,dlg_close);	
}

function form_clear() {
	$('#menu_nama').val('').focus();
	$('#menu_id').val('');
	$('#harga').val('');
	$('#jml').val('');
	return false;
}

function set_meja() {
	$('div#ajax-meja-order').load('index.php/<?php echo $link_controller_notifikasi?>/meja_order',function(data){
		table_prepare();
		refresh_now();
		refresh_content();
	});
	return false;
}

// DAFTAR CONTENT
function daftar_order_awal() {
	$('div#ajax-daftar-order').html('<table width="100%" height="100%"><tr align="center" valign="middle"><td>PILIH MEJA !!!</td></tr></table>');
	$('div#ajax-form-order').html('<table width="100%" height="100%"><tr align="center" valign="middle"><td>PILIH MEJA !!!</td></tr></table>');
	return false;
}

// REFRESH AJAX
function refresh_kalkulasi() {
	var order_id = $('#order_id').val()
	$.ajax({
		url : 'index.php/<?php echo $link_controller?>/kalkulasi_total/'+order_id+'/true',
		type: 'POST',
		success: function(data) {
			if (data){
				var arrTotal = data.split('|');
				
				$('span#jml_order').text(arrTotal[0]);
				$('span#tot_harga').text(arrTotal[1]);
			}
		}
	});	
	return false;
}

function refresh_all() {
	refresh_stop();
	refresh_kalkulasi();
	refresh_flexilist();
	refresh_now();
	refresh_content();
	return false;
}

// NOTIFIKASI MEJA AJAX
var controller = '<?php echo $link_controller_notifikasi?>', 
	jml_meja = <?php echo $this->config->item('jml_meja')?>,
	refresh_type = 'order';
	
$(document).ready(function() {
	set_meja();
	daftar_order_awal();
	
	$('#drop_here').droppable({
		accept: ".meja-menu li",
		activeClass: "ui-state-default",
		drop: function( event, ui ) {
			var no_meja = ui.draggable.attr('no_meja');
			tambah_order(no_meja);
		}
	});
});

</script>
<center>
	<div id="drag_box" class="ui-helper-reset ui-helper-clearfix" style="width:840px">
		<div style="float:left">
			<fieldset id="drop_here" class="ui-widget-content ui-corner-all" style="height:265px;width:280px;">
			<legend class="ui-state-default ui-corner-all" style="font-size:14px">FORM MENU</legend>
				<div id="ajax-form-order"></div>
			</fieldset>
			<!--?php $this->load->view($link_view.'/order_form_view');?-->
		</div>	
		
		<div style="width:520px;float:right;">
			<fieldset class="ui-widget-content ui-corner-all">
			<legend class="ui-state-default ui-corner-all" style="font-size:14px">DAFTAR MEJA</legend>
				<div style="height:245px;overflow:auto;" id="ajax-meja-order">
					<!--?php $this->load->view($link_view.'/order_table_view');?-->
				</div>
			</fieldset>
		</div>
	</div>
	<br>
	<div style="width:840px; height:240px;" >
		<fieldset class="ui-widget-content ui-corner-all" style="height:240px;">
		<legend class="ui-state-default ui-corner-all" style="font-size:14px">DAFTAR ORDER</legend>
			<div id="ajax-daftar-order"></div>
		</legend>
		</fieldset>
	</div>
</center>