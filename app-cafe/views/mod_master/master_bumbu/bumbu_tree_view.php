<script type="text/javascript">
	function auto_expand(kat_id) {
		var tree = $("#<?php echo $tree_id?>").dynatree("getTree");
		tree.loadKeyPath(kat_id, function(node, status){
			if(status == "loaded") {
				node.expand();
			}else if(status == "ok") {
				node.activate();
			}
		});
		return false;
	}
	
	function tree_click(dtnode,kat_tipe) {
		switch(kat_tipe) {
			case 'bumbu' :
				var kat_id = dtnode.data.key;
				get_kat_nama(kat_id);
			break;
		}
		return false;
	}
	
	$("#<?php echo $tree_id?>").dynatree({
		//title: "KATEGORI",
		//rootVisible: true,
		persist: false,
		selectMode: 1,
		keyboard: true,
		autoFocus: false,
		activeVisible: true,
		//autoCollapse: true,
		fx: { height: "toggle", duration: 200 },
		onLazyRead: function(dtnode){
				dtnode.appendAjax({
					url: "index.php/<?php echo $link_controller_kategori?>/dynatree_lazy",
					data: {
						key: dtnode.data.key,
						tipe: dtnode.data.tipe,
						lvl: dtnode.data.lvl,
						jenis: 'all',
						mode: "branch"
					}
				});
		},
		initAjax: {
			url: "index.php/<?php echo $link_controller_kategori?>/dynatree_lazy",
			data: {
				key: "root",
				tipe: "<?php echo $kat_tipe?>",
				jenis: 'all',
				mode: "baseFolders"
			}
		},
		onActivate: function (dtnode) {
			tree_click(dtnode,'<?php echo $kat_tipe?>');	
			//alert(dtnode.data.key);
			return false;
		},
		dnd: {
			autoExpandMS: 1000,
			preventVoidMoves: true, 
			onDragStart: function(node) {
				return true;
			},
			onDragEnter: function(node, sourceNode) {
				if(node.parent !== sourceNode.parent)
					return false;
				
				return ["before", "after"];
			},
			onDrop: function(node, sourceNode, hitMode, ui, draggable) {
				sourceNode.move(node, hitMode);
			},
			helper: 'clone'
		},
		onPostInit: function(isReloading, isError) {
			<?php if ($status=='edit'):?>
				auto_expand("<?php echo $split_kat_id?>");	
			<?php endif;?>
			this.reactivate();
		}
	});
	
</script>

<div id="<?php echo $tree_id?>" style="overflow: auto;height: 300px;margin-top:-10px;"></div>