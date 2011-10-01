<script language="javascript">
	
</script>
<div style="width:100%;display: table">
	<div style="width:50%;float: left;">
		<fieldset style="height:300px;" class="ui-widget-content ui-corner-all">
		<legend class="ui-state-default ui-corner-all">KATEGORI</legend>
		<?php
			$data['kat_tipe'] = "bumbu";
			$data['tree_id'] = "bumbu_tree";
			$this->load->view($link_controller.'/bumbu_tree_view',$data);
		?>
		</fieldset>
	</div>
	<div style="width:48%;float: right;" id="grup_list_content">
		<fieldset style="height:300px;"class="ui-widget-content ui-corner-all">
		<legend class="ui-state-default ui-corner-all">FORM BUMBU</legend>
		<!--?php $this->load->view($link_view.'/bumbu_general_form_view');?-->
			<table>
			<tr>
				<td>Kategori</td>
				<td>:</td>
				<td id="kat_nama">
					<?php echo (isset($split_kat_nama))?($split_kat_nama):('');?>
				</td>
			</tr>
			<tr>
				<td>Nama Bumbu</td>
				<td>:</td>
				<td>
					<input type="hidden" id="kat_id" name="kat_id" value="<?php echo (isset($kat_id))?($kat_id):('')?>" class="required" title="Kategori">
					<input type="text" id="bumbu_nama" name="bumbu_nama" value="<?php echo (isset($bumbu_nama))?($bumbu_nama):('')?>" class="required uppercase bumbu_nama" title="Nama Bumbu">
				</td>
			</tr>
			</table>
		</fieldset>
	</div>
	<div id="grup_add_content" style="width:45%;height:340px;float: right; display:none" class="ui-widget-content ui-corner-all">		
	</div>
</div>