<script language="javascript">

function content_bumbu(bumbu_id,status) {
	var $bumbu = $('#bumbu_content'),
		baris = $('tbody tr',$bumbu).length + 1;
		
	$.ajax({
		url : 'index.php/<?php echo $link_controller?>/tambah_bumbu_ajax/'+bumbu_id+'/'+baris+'/'+status,
		type: 'POST',
		success: function(data) {
			$bumbu.append(data)
			.fadeIn()
			.find('input').focus();
			item_id.push(bumbu_id);
		}
	});
	return false;
}

$("#daftar_tambah_bumbu").droppable({
	accept: "#bumbu_list tr",
	activeClass: 'ui-state-hover',
	hoverClass: 'ui-state-active',
	cursor: 'move',
	drop: function(event, ui) {
		var bumbu_id = ui.draggable.attr("id"),
			$item = ui.draggable;
		
		$item.fadeOut(function(){
			content_bumbu(bumbu_id,'tambah');			
		});
		
		return false;
	}
});

<?php
if ($status == 'edit'):
	$i = 1;
	if ($list_menu_bumbu->num_rows() > 0):
		foreach ($list_menu_bumbu->result() as $rows):
?>
	content_bumbu(<?php echo $rows->bumbu_id?>,'edit');
<?php 
		endforeach;
	endif;
endif;
?>
</script>
<div style="width:100%;display: table">
	<div style="width:50%;float: left;">
		<fieldset style="height:130px;" class="ui-widget-content ui-corner-all">
		<legend class="ui-state-default ui-corner-all">KATEGORI</legend>
		<?php 
			$data['kat_tipe'] = "bumbu";
			$data['tree_id'] = "bumbu_tree";
			$data['height'] = 125;
			$this->load->view($link_controller.'/menu_tree_view',$data);
		?>
		</fieldset>
	</div>
	<div style="width:48%;float: right;">
		<fieldset style="height:130px;" class="ui-widget-content ui-corner-all">
		<legend class="ui-state-default ui-corner-all">DAFTAR BUMBU</legend>
			<div id="daftar_bumbu" style=""></div>
		</fieldset>
	</div>

	<div class="ui-helper-reset ui-helper-clearfix" style="width:100%;display: table;margin-top: 10px;">
		<fieldset id="drop-content" style="height:132px;"class="ui-widget-content ui-corner-all">
		<legend class="ui-state-default ui-corner-all">MENU BUMBU</legend>
			<div id="daftar_tambah_bumbu" style="height:100%; overflow:auto">
				
				<table class="ui-widget-content ui-corner-bottom"  width="100%">
				<thead>
					<tr class="ui-widget-header" style="color:white">
						<td>Kategori</td>
						<td>Nama</td>
						<td>Jumlah</td>
						<td>Satuan</td>
					</tr>
				</thead>
				<tbody id="bumbu_content">
				
				</tbody>
				</table>
			</div>
		</fieldset>
	</div>
</div>