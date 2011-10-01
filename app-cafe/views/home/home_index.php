<?php if (isset($extraSubHeaderContent))
	echo $extraSubHeaderContent;
?>

<script type="text/javascript">
// NOTIFIKASI MEJA AJAX
var controller = '<?php echo $link_controller_notifikasi?>', 
	jml_meja = '<?php echo $this->config->item('jml_meja')?>',
	refresh_type = 'show_order';

$(document).ready(function() {
	$('div#notifikasi-order').load('index.php/<?php echo $link_controller_notifikasi?>/meja_order',function(data){	
		table_prepare();
		refresh_now();
		refresh_content();
	});
});
	
</script>

<div align="right">
<H3>SELAMAT DATANG DI PROGRAM RESTORAN<H3>
</div>
<HR>

<div id="notifikasi-order"></div>