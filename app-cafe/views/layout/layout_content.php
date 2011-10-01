<div id="content-ajax">
<?php 
	$set_content = 'home/home_index';
	if (isset($content))
		$set_content = $content;
	$this->load->view($set_content);
?>
</div>