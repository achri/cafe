<?php
	if (isset($extraSubHeaderContent)) {
		echo $extraSubHeaderContent;
	}
?>
<script type="text/javascript">

$('#tabs').tabs('disable',0);

var item_id = new Array();

function daftar_bumbu(kat_id) { 

	if (item_id.length <= 0){
		item_ids = 0;
	} else {
		item_ids = item_id.toString();
	}
		
	$.ajax ({
		url : 'index.php/<?php echo $link_controller?>/daftar_bumbu/'+kat_id,
		type: 'POST',
		data: "item_id="+item_ids,
		success: function(data) {
			$('#daftar_bumbu').html(data);
		}
	});
	
	return false;
}

function get_menu_json(id) {
	var kat_kode;
	$.getJSON('index.php/<?php echo $link_controller?>/menu_node_katid/'+id, function(data) {
		//alert(data);
		$.each(data, function(entryIndex, entry) {
			for (lvl = 1; lvl <= 4; lvl++) {
				if (lvl < 4) {
					if (entry['lv'+lvl+'_kode'] != null) {
						$('.kat_kode_lvl'+lvl).text(entry['lv'+lvl+'_kode']);
						$('.kat_nama_lvl'+lvl).text(entry['lv'+lvl+'_nama']);
						kat_kode = entry['lv'+lvl+'_katkode'];
					} else {
						$('.kat_kode_lvl'+lvl).text('-');
						$('.kat_nama_lvl'+lvl).text('-');
						kat_kode = '';
					}
				} else {
					if (entry['menu_id_kode'] != null) {
						$('.menu_kode').text(entry['menu_id_kode']);
						$('#kat_id').val(id);
						$('#menu_kode').val(kat_kode);
						$('#menu_nama').focus();
						//$('.kode_menu').show();
					} else {
						$('.menu_kode').text('-');
						$('#kat_id').val('');
						$('#menu_kode').val('');
						//$('.kode_menu').hide();
					}
				}
			}
		});
	});
	return false;
}

masking('.number');
</script>
<form id="form_menu" onsubmit="return buat_menu()">
<div id="tabs">	
	<ul>
		<li><a href="#form_general_menu">GENERAL</a></li>
		<li><a href="#form_detail_menu">DETAIL</a></li>
		<li><a href="#form_bumbu_menu">BUMBU</a></li>
		<!--li><a href="#form_satuan_menu">SATUAN</a></li-->
	</ul>
	
	<div id="form_general_menu">
		<?php $this->load->view($link_view.'/menu_general_view')?>
	</div>
	<div id="form_detail_menu">
		<?php $this->load->view($link_view.'/menu_detail_view')?>
	</div>
	<div id="form_bumbu_menu">
		<?php $this->load->view($link_view.'/menu_bumbu_view')?>
	</div>
	<!--div id="form_satuan_menu">
		<//?php $this->load->view($link_view.'/menu_satuan_view')?>
	</div-->
</div>
<br>
<div class="ui-widget-content ui-corner-all" style="height:30px" align="center">
<input type="submit" value="Tambah Menu">
<input type="button" value="Batal" onclick="batal_tambah_menu();">
</div>
</form>