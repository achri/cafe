<script type="text/javascript">
/*
	for (no_meja = 1; no_meja <= jml_meja; no_meja++) {
		table_content(no_meja);
		table_icon(no_meja,0);
	}
	
	if (refresh_type == 'kitchen') {
		$('li.meja-item').hide();
	} else {
		$('li.meja-item').show();
	}
	*/
</script>
<style type="text/css">
	.meja-menu { float: left; width: 100%; min-height: 12em; } * html .meja-menu { height: 12em; } /* IE6 */
	.meja-menu.custom-state-active { background: #eee; }
	.meja-menu li { float: left; min-height:95px; max-height:95px; width: 96px; padding: 0.4em; margin: 0 0.4em 0.4em 0; text-align: center; }
	.meja-menu li h5 { margin: 0 0 0.4em; cursor: move; }
	.meja-menu li a { float: right; }
	.meja-menu li a.ui-icon-zoomin { float: left; }
	.meja-menu li div div{ font-size: 11px }
</style>

<ul class="meja-menu ui-helper-reset ui-helper-clearfix"></ul>