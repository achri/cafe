<?php
	if (isset($extraSubHeaderContent)) {
		echo $extraSubHeaderContent;
	}
?>
<script type="text/javascript">

var $tab = $('#tabs'), tab_pos = $tab.tabs('option', 'selected');

$tab.tabs('disable',0);

/*
if ($('li',$tab).length == 4)
	$tab.tabs('add' ,'index.php/<?php echo $link_controller_kategori;?>/','LINK KATEGORI',2);
*/

function tambah_kategori() {
	$dlg_content.load('index.php/<?php echo $link_controller_kategori;?>/index')
	.dialog('option','buttons',{
		"OK" : function() {
		
		}
	}).dialog('open');
	return false;
}

function get_kat_nama(kat_id) {
	$.ajax({ 
		url : 'index.php/<?php echo $link_controller?>/tree_kat_nama/'+kat_id,
		type: 'POST',
		success: function(kat_nama) {
			if (kat_nama) {
				$('#kat_id').val(kat_id);
				$('#kat_nama').text(kat_nama);
			} else {
				$('#kat_id').val('');
				$('#kat_nama').text('-');
			}
			
			$('#bumbu_nama').focus();
		}
	});
	
	$('input#bumbu_nama').autocomplete('index.php/<?php echo $link_controller?>/list_autocomplate/'+kat_id,{
		minChars: 2,
		matchCase: true,
		max: 10,
	}).result(function(event,item) {
		
	});
	return false;
}

</script>
<form id="form_bumbu" onsubmit="return <?php echo ($status=='edit')?('edit_bumbu()'):('buat_bumbu()')?>">
<input type="hidden" name="bumbu_id" value="<?php echo (isset($bumbu_id))?($bumbu_id):('')?>">

<div id="tabs">	
	<ul>
		<li><a href="#form_general_bumbu">GENERAL</a></li>
		<li><a href="#form_satuan_bumbu">SATUAN</a></li>
		<!--li><a href="#form_shortcut">OPSI</a></li-->
	</ul>
	
	<div id="form_general_bumbu">
		<?php $this->load->view($link_view.'/bumbu_general_view')?>
	</div>
	<div id="form_satuan_bumbu">
		<?php $this->load->view($link_view.'/bumbu_satuan_view')?>
	</div>
	<!--div id="form_shortcut" align="center">
		<input type="button" value="Tambah Data Kategori" onclick="tambah_kategori();">
		<input type="button" value="Tambah Data Satuan" onclick="tambah_satuan();">
	</div-->
</div>
<br>
<div class="ui-widget-content ui-corner-all" style="height:30px" align="center">
	<input type="submit" value="Tambah Bumbu">
	<input type="button" value="Batal" onclick="batal_tambah_bumbu();">
</div>

</form>