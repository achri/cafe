<script type="text/javascript">
$(document).ready(function(){
	//$('.menu-content').click(function() {
	//	var uri_class = $(this).attr('uri');
	//	$('#ajax-content').load('index.php/'+uri_class);
	//});
	
	// ACCORDION
	$("#accordion").accordion({
		autoHeight: false,
		navigation: true
	});
	
	// SELECTABLE
	$(".selectable").selectable({
		selected: function(event, ui) {
			var id = $(".ui-selected", this).attr("id"), controller;
			switch (id) {
				case "home" : controller = "index.php/cafe/home"; break;
				// MASTER
				case "master-satuan" : controller = "index.php/mod_master/master_satuan"; break;
				case "master-kategori" : controller = "index.php/mod_master/master_kategori"; break;
				case "master-bumbu" : controller = "index.php/mod_master/master_bumbu"; break;
				case "master-menu" : controller = "index.php/mod_master/master_menu"; break;
				// ORDER
				case "order-pesanan" : controller = "index.php/mod_entry/entry_order"; break;
				case "order-dapur" : controller = "index.php/mod_entry/entry_kitchen"; break;
				case "order-kasir" : controller = "index.php/mod_entry/entry_bill"; break;
				// LAPORAN
				case "laporan-order-harian" : controller = "index.php/mod_report/report_order/index/1"; break;
				case "laporan-order-bulanan" : controller = "index.php/mod_report/report_order/index/2"; break;
			}
			$("#content-ajax").load(controller);
			
			$(".selectable > li").removeClass("ui-selected");
			$(ui.selected).addClass("ui-selected");
		}
	});
	
});
</script>
<br>
<ol style="width:184px;margin-left:-3px;margin-bottom:-2px" class="selectable">
	<li class="ui-widget-content ui-corner-all" id="home" style="font-size:15px">HOME</li>
</ol>
<div id="accordion">
	<h3><a href="#">MASTER</a></h3>
	<div>
		<ol class="selectable">
			<li class="ui-widget-content ui-corner-all" id="master-satuan">SATUAN</li>
			<li class="ui-widget-content ui-corner-all" id="master-kategori">KATEGORI</li>
			<li class="ui-widget-content ui-corner-all" id="master-bumbu">BUMBU</li>
			<li class="ui-widget-content ui-corner-all" id="master-menu">MENU</li>
			<li class="ui-widget-content ui-corner-all" id="master-gudang">GUDANG</li>
		</ol>
	</div>
	<h3><a href="#">ORDER</a></h3>
	<div>
		<ol class="selectable">
			<li class="ui-widget-content ui-corner-all" id="order-pesanan">PESANAN</li>
			<li class="ui-widget-content ui-corner-all" id="order-dapur">DAPUR</li>
			<li class="ui-widget-content ui-corner-all" id="order-kasir">KASIR</li>
		</ol>
	</div>
	<h3><a href="#">LAPORAN</a></h3>
	<div>
		<ol class="selectable">
			<li class="ui-widget-content ui-corner-all" id="laporan-order-harian">HARIAN</li>
			<li class="ui-widget-content ui-corner-all" id="laporan-order-bulanan">BULANAN</li>
		</ol>
	</div>
</div>
