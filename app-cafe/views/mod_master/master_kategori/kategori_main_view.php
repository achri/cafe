<?php
	if (isset($extraSubHeaderContent)) {
		echo $extraSubHeaderContent;
	}
?>
<!-- Tabs -->
<div id="tabs">
	<ul>
		<li><a href="index.php/<?php echo $link_controller;?>/daftar_kategori">DAFTAR KATEGORI</a></li>
		<li><a href="index.php/<?php echo $link_controller_kelas;?>/daftar_kelas">DAFTAR KELAS</a></li>
		<li><a href="index.php/<?php echo $link_controller_grup;?>/daftar_grup">DAFTAR GRUP</a></li>
	</ul>
</div>
