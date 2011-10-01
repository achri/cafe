<script language="javascript">
<?php if ($status=='edit'):?>
get_menu_json(<?php echo $kat_id?>);
<?php endif;?>
</script>
<div style="width:100%;display: table">
	<div style="width:50%;float: left;">
		<fieldset style="height:300px;" class="ui-widget-content ui-corner-all">
		<legend class="ui-state-default ui-corner-all">KATEGORI</legend>
		<?php
			$data['kat_tipe'] = "menu";
			$data['tree_id'] = "menu_tree";
			$data['height'] = 300;
			$this->load->view($link_controller.'/menu_tree_view',$data);
		?>
		</fieldset>
	</div>
	<div style="width:48%;float: right;" id="grup_list_content">
		<fieldset style="height:300px;"class="ui-widget-content ui-corner-all">
		<legend class="ui-state-default ui-corner-all">FORM MENU</legend>
		<?php $this->load->view($link_view.'/menu_general_form_view');?>
		</fieldset>
	</div>
	<div id="grup_add_content" style="width:45%;height:340px;float: right; display:none" class="ui-widget-content ui-corner-all">		
	</div>
</div>