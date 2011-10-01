<script type="text/javascript">
$('input#temp_dibayar').keyup(function(event) {
	masking('.number');
	$('.dibayar').val($(this).val());
	unmasking('.unmasking');
	var $sisa = $('#sisa'),
	sisa = $('.dibayar').val() - $('#tflex_total').val();
	if (sisa > 0) {
		$sisa.val(sisa).blur();
		$('span#fsisa').text('Rp. '+$sisa.val());
	} 
	else if (sisa == 0) {
		$sisa.val(0);
		$('span#fsisa').html('<font color="green">Lunas</font>');
	}
	else {
		$sisa.val('-');
		$('span#fsisa').html('<font color="red">Pembayaran kurang !!!</font>');
	}
	
	return false;
}).focus();
</script>
<form id="bill_form" onsubmit="return bill_order();">
<div class="ui-widget-content ui-corner-all" align="center">
	<table >
	<tr>
		<td>Total Harga</td><td>:</td>
		<td>
			Rp. <span id='flex_total'></span>
			<input  id="tflex_total" class="unmasking" type="hidden">
		</td>
	</tr>
	<tr>
		<td>Jumlah Dibayar</td><td>:</td>
		<td>
			Rp. <input class="number required" type="text" id="temp_dibayar" title="Jumlah Dibayar">
			<input class="dibayar unmasking" type="hidden" name="bayar">
		</td>
	</tr>
	<tr>
		<td>Sisa Pembayaran</td><td>:</td>
		<td>
			<span id="fsisa">-</span>
			<input class="number" type="hidden" id="sisa" name="sisa" title="Jumlah Sisa">
		</td>
	</tr>
	</table>
</div>
</form>