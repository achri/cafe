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

class Entry_order extends CI_Controller {
	public static $link_view, $link_controller, $link_controller_menu, $link_controller_notifikasi;
	function __construct() 
	{
		parent::__construct();	
		
		$this->load->library(array("jqcontent","flexigrid","flexi_engine"));
		$this->load->model(array("flexi_model","metadata","tbl_order","tbl_menu","tbl_kategori","tbl_bill"));
		$this->load->helper(array('flexigrid'));
		$this->config->load("flexigrid");
		$this->config->load('cafe');
		
		// EXTRA SUB HEADER ==>
		$css = array (
		'asset/js/plugins/form/autocomplete/jquery.autocomplete.css',
		'asset/js/plugins/flexigrid/css/flexigrid.css',
		);
		
		$js = array (
		'asset/js/plugins/form/jquery.form.js',
		'asset/js/plugins/form/jquery.autoNumeric.js',
		'asset/js/plugins/form/autocomplete/jquery.autocomplete.js',
		'asset/js/plugins/flexigrid/js/flexigrid.js',
		'asset/js/helper/autoNumeric.js',
		'asset/js/helper/validasi.js',
		'asset/js/helper/dialog.js',
		'asset/js/general/notifikasi_meja.js',
		//'asset/js/general/dragndrop_order.js',
		);
		
		$data['extraSubHeaderContent'] = $this->jqcontent->cssplugins($css).$this->jqcontent->jsplugins($js);		
		// <== END EXTRA HEADER
		
		// LINK CONTROLLER & VIEW ==>
		self::$link_controller = 'mod_entry/entry_order';
		self::$link_controller_menu = 'mod_master/master_menu';
		self::$link_controller_notifikasi = 'mod_notifikasi/notifikasi_meja';
		self::$link_view = 'mod_entry/entry_order';
		
		$data['link_view'] = self::$link_view;
		$data['link_controller'] = self::$link_controller;
		$data['link_controller_menu'] = self::$link_controller_menu;
		$data['link_controller_notifikasi'] = self::$link_controller_notifikasi;
		// <== END LINK CONTROLLER & VIEW
		
		// PAGE TITLE ==>
		$data['page_title'] = "ORDER";
		// <== END PAGE TITLE
		
		$data['list_kategori'] = $this->tbl_kategori->data_kategori();
		
		$this->load->vars($data);
	}
	
	function flexigrid_ajax($order_id=false)
	{		
		// SQL FLEXIGRID
		$sql = "select *, 
				(ordet.jml*menu.harga) as tot_harga,
				date_format(ordet.menu_tgl,'%d-%m-%Y')as order_tgl,
				date_format(ordet.menu_tgl,'%H:%i:%s')as order_jam
				{COUNT_STR}
				from order_menu as ord
				inner join order_menu_detail as ordet on ordet.order_id = ord.order_id
				inner join master_menu as menu on menu.menu_id = ordet.menu_id
				inner join master_kategori as kat on kat.kat_id = menu.kat_id
				where ord.status in (0,1,2,3) and ord.bill_id = 0 ";
		if ($order_id != false)
			$sql .= "and ord.order_id = ".$order_id;
		$sql .=	" {SEARCH_STR}";
		
		// VALIDATE SORT, SEARCH FIELD ETC
		$valid_fields = $this->flexi_model->generate_sql($sql,'ord.order_id',TRUE,FALSE);
		$this->flexigrid->validate_post('ord.order_id','asc',$valid_fields->list_fields());
		
		// POPULATE SQL FLEXIGRID RECORD DATA
		$records = $this->flexi_model->generate_sql($sql,'ord.order_id',TRUE);
				
		$this->output->set_header($this->config->item('json_header'));
		
		// POPULATE FLEXIGRID RECORD DATA
		if ($records['count'] > 0):		
			$no = $this->flexigrid->row_number();
			$arrStatus = $this->config->item('kitchen_status');
			foreach ($records['result']->result() as $row)
			{
				$done = element($row->done,$arrStatus);
				//$opsi = "<a alt='Bayar' style='cursor:pointer' onclick='form_bill(\"".$row->order_id."\",\"".$row->no_meja."\")'><img border='0' src='".base_url()."asset/images/icons/checkin.png'></a> | ";
				if ($no > 1)
				$opsi = "<a alt='Hapus Menu Order' style='cursor:pointer' onclick='hapus_menu(\"".$row->order_id."\",\"".$row->menu_id."\")'><img border='0' src='".base_url()."asset/images/icons/trash.png'></a>";
				else
				$opsi = "-";
						
				$record_items[] = array(
					$row->order_id, // TABLE ID
					$row->order_detail_id,
					$row->no_meja,
					$no,
					$row->order_tgl,
					$row->order_jam,
					$row->kat_nama,
					$row->menu_nama,
					$row->jml,
					$done,
					//'Rp.'.number_format($row->harga,2),
					//'Rp.'.number_format($row->tot_harga,2),
					//$opsi
				);

				$no++;
			}
		else: 
			$record_items[] = array('0','null');
		endif;
		
		$this->output->set_output($this->flexigrid->json_build($records['count'],$record_items));
		
	}
	
	function flexigrid_builder($id,$title,$width,$height,$rp,$resize=false,$order_id=false) 
	{
	
		// COLUMNS
		
		$colModel['order_detail_id'] = array('Order_detail_id',5,TRUE,'right',0,TRUE);
		$colModel['no_meja'] = array('no_meja',5,TRUE,'right',0,TRUE);
		$colModel['no'] = array('No',30,TRUE,'right',0);
		$colModel['order_tgl'] = array('Tanggal',100,TRUE,'center',1);
		$colModel['order_jam'] = array('Jam',80,TRUE,'center',1);
		$colModel['kat_nama'] = array('Kategori',150,TRUE,'Left',2);
		$colModel['menu_nama'] = array('Menu',200,TRUE,'Left',2);
		$colModel['jml'] = array('Jml',50,TRUE,'center',2);
		$colModel['done'] = array('Status',100,TRUE,'left',1);
		//$colModel['harga'] = array('Harga',100,TRUE,'left',1);
		//$colModel['tot_harga'] = array('Total',100,TRUE,'left',1);
		//$colModel['opsi'] = array('Opsi',50,FALSE,'center',0);
		
		// BUTTONS
		
		//$buttons[] = array('Tambah Menu','add','test');
		//$buttons[] = array('separator');
		$buttons[] = array('Edit Menu','edit','test');
		$buttons[] = array('Hapus Menu','trash','test');
		$buttons[] = array('separator');
		$buttons[] = array('Hapus Order','delete','test');
		
		$ajax_model = site_url(self::$link_controller."/flexigrid_ajax/".$order_id);
		
		return build_grid_js($id,$ajax_model,$colModel,'ord.order_id','asc',$this->flexi_engine->flexi_params($width,$height,$rp,$title,false,$resize,true),$buttons);
	}
	
	function index() 
	{
		$data[''] = '';
		$this->load->view(self::$link_view.'/order_main_view',$data);
	}
	
	function daftar_order($order_id,$no_meja)
	{
		$data['flexi_id'] = "daftar_order";
		$data['js_grid'] = $this->flexigrid_builder($data['flexi_id'],"ORDER MEJA KE-$no_meja",815,110,4,false,$order_id);
		$this->load->view(self::$link_view.'/order_flexilist_view',$data);
	}
	
	function form_order($order_id,$no_meja)
	{
		$data = $this->kalkulasi_total($order_id);
		
		$data['order_id'] = $order_id;
		$data['no_meja'] = $no_meja;
		
		$this->load->view(self::$link_view.'/order_form_view',$data);
	}
	
	function kalkulasi_total($order_id,$output=false)
	{
		$jml = 0;
		$harga = 0;
		
		$get_order = $this->tbl_order->kalkulasi_total($order_id);
		if ($get_order->num_rows() > 0):
			$jml = $get_order->row()->tot_jml;
			$harga = $get_order->row()->tot_harga;
		endif;
		
		$data['tot_jml'] = number_format($jml,0);
		$data['tot_harga'] = 'Rp. '.number_format($harga,2);
		
		if ($output):
			echo implode('|',$data);
		else:
			return $data;
		endif;
	}
	
	function tambah_order($no_meja) 
	{
		$data['order_session'] = $this->session->userdata('session_id');
		
		// CHECK SESSION MEJA ORDER
		$where['no_meja'] = $no_meja;
		//$where['order_session'] = $data['order_session'];
		$where['bill_id'] = 0;
		$get_order = $this->tbl_order->data_order($where);
		if ($get_order->num_rows() > 0):
			$data_order = $get_order->row();
			if ($data_order->order_session != '' AND $data_order->order_session != $data['order_session']):
				$where_exp['order_id'] = $data_order->order_id;
				$data_exp['order_session'] = $data['order_session'];
				$this->tbl_order->ubah_order($where_exp,$data_exp);
			endif;
			
			// GET CURRENT ORDER ID
			$order_id = $get_order->row()->order_id;
		else:				
			// INSERT AND GET ORDER ID
			$data['no_meja'] = $no_meja;
			$data['order_tgl'] = date('Y-m-d H:i:s');
			//$data['status'] = 1;
			if ($this->tbl_order->tambah_order($data)):
				$order_id = $this->db->insert_id();
			endif;
		endif;
				
		echo $order_id;
	}
	
	function tambah_menu()
	{
		$order_id = $this->input->post('order_id');
		$data['order_id'] = $order_id;
		$data['menu_id'] = $this->input->post('menu_id');
		$data['menu_tgl'] = date('Y-m-d H:i:s');
		$data['jml'] = $this->input->post('jml');
		$harga = $this->input->post('harga');
		$data['total'] = $harga * $data['jml'];
		
		if ($this->tbl_order->tambah_order_detail($data)):
			$data_ord['status'] = 2;
			$where_uord['order_id'] = $order_id;
			if ($this->tbl_order->ubah_order($where_uord,$data_ord)):
				echo 'sukses';
			endif;
		endif;
		
	}
	
	function hapus_order($order_id)
	{
		$where['order_id'] = $order_id;
		$this->tbl_order->hapus_order($where);
		$this->tbl_order->hapus_order_detail($where);
	}
	
	function hapus_menu($order_detail_id)
	{
		$where['order_detail_id'] = $order_detail_id;
		//$where['menu_id'] = $menu_id;
		$this->tbl_order->hapus_order_detail($where);
	}
	
	function list_autocomplate($stat='nama',$kat_id=0) 
	{		
		$q = $this->input->get('q');
		
		if ($stat == 'kode')
			$like['menu_kode']=$q;
		else
			$like['menu_nama']=$q;
			
		//$kat_id = $this->input->get('kat_id');
		if ($kat_id != 0):
			$where['kat_id'] = $kat_id;
			$qres = $this->tbl_menu->data_menu($where,$like);
		else:
			$qres = $this->tbl_menu->data_menu(false,$like);
		endif;
		if ($qres->num_rows() > 0):
			foreach ($qres->result() as $rows):
				if ($stat == 'kode'):
					if (strpos(strtoupper($rows->menu_kode), strtoupper($q)) !== false):
							echo "$rows->menu_kode|$rows->menu_nama|$rows->menu_id|$rows->harga\n";
					endif;
				else:
					if (strpos(strtolower($rows->menu_nama), strtolower($q)) !== false):
							echo "$rows->menu_nama|$rows->menu_kode|$rows->menu_id|$rows->harga\n";
					endif;
				endif;
			endforeach;
		endif;	
	}
	
}

/* End of file .php */
/* Location: ./../.php */
