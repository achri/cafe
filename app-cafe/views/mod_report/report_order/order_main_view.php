<?php if (isset($extraSubHeaderContent))
	echo $extraSubHeaderContent;
?>
<script type="text/javascript">
function cetak() {
	//var tanggal = $('#tanggal').val();
	//if (tanggal != '')
	//	showModalDialog("index.php/<?php echo $link_controller?>/ajax_load/"+tanggal);
	window.print();
	return false;
}

function ajax_load() {
	$('form#report-search').ajaxSubmit({
		url: 'index.php/<?php echo $link_controller?>/ajax_load/<?php echo $harian;?>',
		type: 'POST',
		success: function(data) {
			//alert(data);
			$('#print-view').html(data);
		}
	
	});
	return false;
}

function detail(row,order_id) {
	var komponen = $('#detail_'+row), display = komponen.css('display');
	if (display == 'none') {
		$('#show_detail_'+row).load('index.php/<?php echo $link_controller?>/ajax_detail_load/<?php echo $harian;?>/'+order_id,function(data){
			//alert(data);
			komponen.show();
		});
	} else {
		komponen.hide();
	}
	//alert(status);
	return false;
}

ajax_load();
</script>
<h3><?php echo $arrStatus[$harian]?><span style="float:right"><input class="" onclick="cetak();" type="button" value="Cetak"></span></h3>
<hr>
<center>
<form id="report-search" onsubmit="return ajax_load();">
<div align="center" style="width:80%" class="ui-widget-content ui-corner-all">
<table align="center" border=0 cellpadding=2 cellspacing=2>
<tr>
	<?php 
	switch ($harian):
		case 1:
	?>
	<td>Tanggal</td><td>:</td>
	<td><input class="kalender" id="tanggal" name="tanggal" readonly value="<?php echo $current_date?>"></td>
	<?php 
		break;
		case 2:
	?>
	<td>Bulan</td><td>:</td>
	<td>
		<select name="bulan">
		<?php for ($bln = 1; $bln <= 12; $bln++):?>
			<option value="<?php echo $bln?>" <?php echo ($bln == date('m'))?('SELECTED'):('')?>><?php echo $bln?></option>
		<?php endfor;?>
		</select>
	</td>
	<td>Tahun</td><td>:</td>
	<td>
		<select name="tahun">
		<?php for ($thn = 2005; $thn <= date('Y'); $thn++):?>
			<option value="<?php echo $thn?>" <?php echo ($thn == date('Y'))?('SELECTED'):('')?>><?php echo $thn?></option>
		<?php endfor;?>
		</select>
	</td>
	<?php 
		break;
	endswitch;?>
	<td colspan=3><input type="submit" value="Cari"></td>
</tr>
</table>
</div>
</form>
<br>
<div id="print-view" style="width:80%" align="center">

</div>
</center>