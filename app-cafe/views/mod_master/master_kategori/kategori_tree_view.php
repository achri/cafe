<?php
	if (isset($extraSubHeadContent)) {
		echo $extraSubHeadContent;
	}
?>
<script language="javascript">
$(document).ready(function() {
	$("#kategori_tree").dynatree({
		title: "KATEGORI",
		rootVisible: true,
		persist: false,
		selectMode: 1,
		keyboard: true,
		autoFocus: false,
		activeVisible: true,
		//autoCollapse: true,
		fx: { height: "toggle", duration: 200 },
		onLazyRead: function(dtnode){
				dtnode.appendAjax({
					url: "index.php/<?php echo $link_controller?>/dynatree_lazy_all/"+dtnode.data.key,
					data: {
						key: dtnode.data.key,
						mode: "branch"
					}
				});
		},
		initAjax: {
			url: "index.php/<?php echo $link_controller?>/dynatree_lazy_all",
			data: {
				key: "root",
				mode: "baseFolders"
			}
		},
		onActivate: function (dtnode) {
			pilih_kategori(dtnode.data.key);
			return false;
		}
	});

});
</script>

<div id="kategori_tree">
</div>