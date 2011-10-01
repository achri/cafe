<script type="text/javascript">
$(document).ready(function(){
	$('body').layout({ applyDefaultStyles: true });
});
</script>

<DIV class="ui-layout-center"><?php $this->load->view('layout/layout_content');?></DIV>
<DIV class="ui-layout-north"><?php $this->load->view('layout/layout_header');?></DIV>
<DIV class="ui-layout-south"><?php $this->load->view('layout/layout_footer');?></DIV>
<DIV class="ui-layout-east"><?php $this->load->view('layout/layout_sidebar-right');?></DIV>
<DIV class="ui-layout-west"><?php $this->load->view('layout/layout_sidebar-left');?></DIV>

<div class="dialog-content" title="DIALOG"></div>
<div class="dialog-validasi" title="DIALOG"></div>

