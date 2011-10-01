<?php echo $extraSubHeaderContent;
if ($bumbu_list->num_rows() > 0):
?>
<style media="screen" type="text/css">
.daftar_list .ui-selecting { background: white; }

.daftar_list .ui-selected { background: orange; color: white; !important}

.daftar_list { list-style-type: none; margin: 0; padding: 0;}

.daftar_list li { margin: 3px; padding: 0.4em; font-size:12px; cursor:move;}

.daftar_list li:hover { background: lightyellow; }
</style>

<script language="javascript">
$(".bumbu_item").draggable({
	revert:true, 
	helper: 'clone', 
	opacity: 0.7, 
	cursorAt: { cursor: 'move', top: 0, left: 0 },
});
		
</script>
<center>
<div class="ui-widget-header" style="color:white;">Kategori <strong>(<?php echo $kat_bumbu?>)</strong></div>
<div style="height:90px; overflow:auto">
<table id='bumbu_list' class="daftar_list ui-widget-content ui-corner-bottom" width="100%">
<?php
	foreach($bumbu_list->result() as $rows):
		echo "<tr id='".$rows->bumbu_id."' class='bumbu_item ui-state-default'><td>".$rows->bumbu_nama."</td></tr>";
	endforeach;
?>
</table>
</div>
</center>
<?php
else:
?>
<table height="100%" width="100%"><tr><td align="center" valign="middle">
<div class="ui-widget-content ui-corner-all" style="text-align:center;margin-left:10px"><font color="red">TIDAK ADA DAFTAR BUMBU</font></div>
</td></tr></table>
<?php 
endif;
?>