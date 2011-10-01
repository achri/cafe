<script language="javascript">
function refresh_table() {
	$('#<?php echo $flexi_id?>').flexReload();
	return false;
}	
</script>
<?php
echo $js_grid;
?>
<div align="left">
<table id="<?php echo $flexi_id?>" style="display:none" class=""></table>
</div>