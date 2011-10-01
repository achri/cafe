<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

/*
 @author		Achri
 @date creation	
 @model
	- 
 @view
	- 
 @library
    - JS		
    - PHP
 @comment
	- 
*/

class Master_satuan extends CI_Controller {
	public static $link_view, $link_controller;
	function __construct() 
	{
		parent::__construct();
		$this->load->model(array('flexi_model','metadata','tbl_satuan'));
		$this->load->library(array('flexigrid','flexi_engine'));
		$this->load->helper(array('flexigrid'));
		$this->config->load('flexigrid');		
		
		// EXTRA SUB HEADER ==>
		$arrayCSS = array (
		'asset/js/plugins/flexigrid/css/flexigrid.css',
		);
		
		$arrayJS = array (
		'asset/js/plugins/form/jquery.form.js',
		'asset/js/plugins/flexigrid/js/flexigrid.js',
		'asset/js/plugins/form/jquery.jeditable.js',
		'asset/js/general/tabs.js',
		'asset/js/helper/dialog.js',
		'asset/js/helper/validasi.js',
		);
		
		$data['extraSubHeadContent'] = '';
		
		if (is_array($arrayCSS))
			foreach ($arrayCSS as $css):
				$data['extraSubHeadContent'] .= '<link type="text/css" rel="stylesheet" href="'.base_url().$css.'"/>';
			endforeach;
		
		if (is_array($arrayJS))
			foreach ($arrayJS as $js):
				$data['extraSubHeadContent'] .= '<script type="text/javascript" src="'.base_url().$js.'"/></script>';
			endforeach;
		
		// <== END EXTRA SUB HEADER
		
		// LINK CONTROLLER & VIEW ==>
		self::$link_controller = 'mod_master/master_satuan';
		self::$link_view = 'mod_master/master_satuan';
		
		$data['link_view'] = self::$link_view;
		$data['link_controller'] = self::$link_controller;
		// <== END LINK CONTROLLER & VIEW
		
		// PAGE TITLE ==>
		$data['page_title'] = "";
		// <== END PAGE TITLE
		
		$this->load->vars($data);
	}
	
	function index() 
	{
		$data[""]="";
		$this->load->view(self::$link_view."/satuan_main_view",$data);
	}
	
	function flexigrid_ajax()
	{		
		
		$valid_fields = $this->metadata->list_field('master_satuan');
		$this->flexigrid->validate_post('satuan_id','asc',$valid_fields);
		
		$sql = "select * {COUNT_STR} 
		from master_satuan {SEARCH_STR}";
		$records = $this->flexi_model->generate_sql($sql,'satuan_id',TRUE);
				
		$this->output->set_header($this->config->item('json_header'));
		
		if ($records['count'] > 0):		
			$no = $this->flexigrid->row_number();
			foreach ($records['result']->result() as $row)
			{
				// EDITABLE SPAN
				$satuan_nama = "<span width='97%' id='".$row->satuan_id."' class='change_name' >".$row->satuan_nama."</span>";
				$record_items[] = array(
				$row->satuan_id, // TABLE ID
				$no,
				$satuan_nama
				);
				$no++;
			}
		else: 
			$record_items[] = array('0','null');
		endif;
		
		$this->output->set_output($this->flexigrid->json_build($records['count'],$record_items));
	}
	
	function flexigrid_builder($id,$title,$width,$height,$rp) {
	
		// COLUMNS
		$colModel['no'] = array('No',30,TRUE,'right',0);
		$colModel['satuan_nama'] = array('Satuan',325,TRUE,'left',1,false,'flexEdit');
			
		// BUTTONS
		/*$buttons[] = array('Edit','edit','test');
		$buttons[] = array('separator');
		$buttons[] = array('Select All','add','test');
		$buttons[] = array('DeSelect All','delete','test');
		$buttons[] = array('separator');
		$buttons[] = array('Delete','trash','test');
		*/
		
		$ajax_model = site_url(self::$link_controller."/flexigrid_ajax");
		
		return build_grid_js($id,$ajax_model,$colModel,'satuan_nama','asc',$this->flexi_engine->flexi_params($width,$height,$rp,$title,false,true));
	}
	
	function daftar_satuan() {
		$data['flexi_id'] = "daftar_satuan";
		$data['js_grid'] = $this->flexigrid_builder($data['flexi_id'],"Daftar Satuan",399,252,10);
		$this->load->view(self::$link_view."/satuan_list_view",$data);
	}
	
	function view_satuan($status,$satuan_id = 1) {
		$data["status"] = $status;
		if ($status == "edit") {
			$where["satuan_id"] = $satuan_id;
			$data['satuan_list'] = $this->tbl_satuan->data_satuan($where);
		} 
		$this->load->view(self::$link_view.'/satuan_form_view',$data);
	}
	
	function ubah_nama_satuan() {
		$where['satuan_id'] =  $this->input->post('id');
		$data['satuan_nama'] =  strtoupper($this->input->post('value'));
		$this->tbl_satuan->ubah_satuan($where,$data);
		echo $data['satuan_nama'];
	}
	
	function tambah_satuan() {
		$satuan_nama =  $this->input->post('value');
		$data["satuan_nama"] = strtoupper($satuan_nama);
		$data["satuan_format"] =  $this->input->post('format');
		$cek = $this->tbl_satuan->data_satuan(array("satuan_nama"=>$data["satuan_nama"]));
		if ($cek->num_rows() > 0){
			echo "ada";
		} else{
			if ($this->tbl_satuan->tambah_satuan($data)){
				echo "sukses";
			}
		}
	}
	
	function hapus_satuan($satuan_id) {
		$this->tbl_satuan->hapus_satuan($satuan_id);
		echo "ok";
	}
}

/* End of file .php */
/* Location: ./../.php */