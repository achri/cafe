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

class Entry_bill extends CI_Controller {
	public static $link_view, $link_controller;
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
		'asset/js/general/notifikasi.js',
		'asset/js/general/notifikasi_order_lama.js',
		//'asset/js/mod_entry/entry_order.js',
		);
		
		$data['extraSubHeaderContent'] = $this->jqcontent->cssplugins($css).$this->jqcontent->jsplugins($js);		
		// <== END EXTRA HEADER
		
		// LINK CONTROLLER & VIEW ==>
		self::$link_controller = 'mod_entry/entry_bill';
		self::$link_view = 'mod_entry/entry_bill';
		
		$data['link_view'] = self::$link_view;
		$data['link_controller'] = self::$link_controller;
		// <== END LINK CONTROLLER & VIEW
		
		// PAGE TITLE ==>
		$data['page_title'] = "ORDER";
		// <== END PAGE TITLE
		
		$data['list_kategori'] = $this->tbl_kategori->data_kategori();
		
		$this->load->vars($data);
	}
	
	function flexigrid_ajax($type='list',$order_id=false,$no_meja=false)
	{		
		// SQL FLEXIGRID
		switch ($type):
			case 'list': $sql = "select ord.order_id,ord.no_meja,
				(select sum(ordet.jml) 
				from order_menu_detail as ordet
				where ordet.order_id = ord.order_id
				) as tot_jml,
				(select sum(ordet.jml * menu.harga) 
					from order_menu_detail as ordet
					inner join master_menu as menu on menu.menu_id = ordet.menu_id
					where ordet.order_id = ord.order_id
				) as tot_harga,
				date_format(ord.order_tgl,'%d-%m-%Y')as order_tgl,
				date_format(ord.order_tgl,'%h:%i:%s')as order_jam
				{COUNT_STR}
				from order_menu as ord
				where ord.status = 3 and ord.bill_id = 0
				{SEARCH_STR}";
				break;
			case 'detail': $sql = "select ord.order_id,orddet.jml,menu.menu_id,menu.menu_nama,
				(menu.harga * orddet.jml) as harga,
				kat.kat_nama {COUNT_STR} from order_menu as ord 
				inner join order_menu_detail as orddet on orddet.order_id = ord.order_id 
				inner join master_menu as menu on menu.menu_id = orddet.menu_id 
				inner join master_kategori as kat on kat.kat_id = menu.kat_id 
				where ord.order_id = $order_id and ord.no_meja = $no_meja";
				break;
		endswitch;
		
		// VALIDATE SORT, SEARCH FIELD ETC
		$valid_fields = $this->flexi_model->generate_sql($sql,'ord.order_id',TRUE,FALSE);
		$this->flexigrid->validate_post('ord.order_id','asc',$valid_fields->list_fields());
		
		// POPULATE SQL FLEXIGRID RECORD DATA
		$records = $this->flexi_model->generate_sql($sql,'ord.order_id',TRUE);
				
		$this->output->set_header($this->config->item('json_header'));
		
		// POPULATE FLEXIGRID RECORD DATA
		if ($records['count'] > 0):		
			$no = $this->flexigrid->row_number();;
			foreach ($records['result']->result() as $row)
			{
				switch ($type):
					case 'list':
						//$opsi = "<a alt='Bayar' style='cursor:pointer' onclick='form_bill(\"".$row->order_id."\",\"".$row->no_meja."\")'><img border='0' src='".base_url()."asset/images/icons/checkin.png'></a> | ";
						$opsi = "<a alt='Lihat Menu Order' style='cursor:pointer' onclick='view_order(\"".$row->order_id."\",\"".$row->no_meja."\")'><img border='0' src='".base_url()."asset/images/icons/content.png'></a>";
						
						$record_items[] = array(
							$row->order_id, // TABLE ID
							$no,
							$row->no_meja,
							$row->order_tgl,
							$row->order_jam,
							$row->tot_jml,
							'Rp.'.number_format($row->tot_harga,2),
							$opsi
						);
					break;
					case 'detail':
						//$opsi = "<a alt='EDIT' style='cursor:pointer' onclick='edit_menu_order(\"".$row->order_id."\",\"".$row->menu_id."\",\"".$no_meja."\")'><img border='0' src='".base_url()."asset/images/icons/edit.png'></a> | ";
						//$opsi .= "<a alt='Delete' style='cursor:pointer' onclick='hapus_menu_order(\"".$row->order_id."\",\"".$row->menu_id."\",\"".$no_meja."\")'><img border='0' src='".base_url()."asset/images/icons/trash.png'></a>";
						$record_items[] = array(
							$row->order_id, // TABLE ID
							$no,
							$row->menu_nama,
							$row->kat_nama,
							$row->jml,
							'Rp.'.number_format($row->harga,2),
							//$opsi
						);
					break;
				endswitch;
				$no++;
			}
		else: 
			$record_items[] = array('0','null');
		endif;
		
		$this->output->set_output($this->flexigrid->json_build($records['count'],$record_items));
		
	}
	
	function flexigrid_builder($id,$title,$width,$height,$rp,$resize=false,$type='list',$order_id=false,$no_meja=false) 
	{
	
		// COLUMNS
		
		switch ($type):
			case 'list':
			$colModel['no'] = array('No',35,TRUE,'right',0);
			$colModel['no_meja'] = array('Meja',50,TRUE,'center',1);
			$colModel['order_tgl'] = array('Tanggal',100,TRUE,'center',2);
			$colModel['order_jam'] = array('Jam',100,TRUE,'center',2);
			$colModel['tot_jml'] = array('Jumlah',50,TRUE,'center',1);
			$colModel['tot_harga'] = array('Harga',100,TRUE,'left',1);
			$colModel['opsi'] = array('Opsi',50,FALSE,'center',0);
			break;
			case 'detail':
			$colModel['no'] = array('No',35,TRUE,'right',0);
			$colModel['menu_nama'] = array('Menu',200,TRUE,'left',1);
			$colModel['kat_nama'] = array('Kategori',100,TRUE,'center',2);
			$colModel['jml'] = array('Jumlah',50,TRUE,'center',1);
			$colModel['harga'] = array('Harga',100,TRUE,'left',1);
			//$colModel['opsi'] = array('Opsi',50,FALSE,'center',0);
			break;
		endswitch;
		
		$ajax_model = site_url(self::$link_controller."/flexigrid_ajax/".$type."/".$order_id."/".$no_meja);
		
		return build_grid_js($id,$ajax_model,$colModel,'order_id','asc',$this->flexi_engine->flexi_params($width,$height,$rp,$title,false,$resize));
	}
	
	function index() 
	{
		$data['flexi_id'] = "daftar_bill";
		$data['js_grid'] = $this->flexigrid_builder($data['flexi_id'],"Daftar Bill",'auto',252,8,false);
		$this->load->view(self::$link_view.'/bill_main_view',$data);
	}
	
	function daftar_order($order_id,$no_meja) 
	{
		$data['order_id'] = $order_id;
		$data['flexi_id'] = "daftar_order";
		$data['js_grid'] = $this->flexigrid_builder($data['flexi_id'],"Daftar Order",'auto',252,8,false,'detail',$order_id,$no_meja);
		$this->load->view(self::$link_view.'/bill_form_view',$data);
		$this->load->view(self::$link_view.'/bill_flexlist_view',$data);
		//$this->load->view(self::$link_view.'/bill_total_view',$data);
	}

	function cek_meja_data($order_id)
	{
		$sql = "select sum(ordet.jml*menu.harga) as jum_total from order_menu as ord
		inner join order_menu_detail as ordet on ordet.order_id = ord.order_id
		inner join master_menu as menu on menu.menu_id = ordet.menu_id
		where ord.order_id = $order_id
		";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0):
			$total = number_format($query->row()->jum_total);
		else:
			$total = number_format(0);
		endif;
		
		echo $total;
	}
	
	function bayar_order($order_id)
	{
		$where['order_id'] = $order_id;
		$where['status'] = 3;
		$get_data = $this->tbl_order->data_order($where);
		if ($get_data->num_rows() > 0):
			$get_order = $this->tbl_order->kalkulasi_total($order_id);
			if ($get_order->num_rows() > 0):
				$data_bill['tot_jml'] = $get_order->row()->tot_jml;
				$data_bill['tot_harga'] = $get_order->row()->tot_harga;
			endif;
			
			$data_bill['bill_tgl'] = date('Y-m-d H:i:s');
			$data_bill['bayar'] = $this->input->post('bayar');
			$data_bill['sisa'] = $this->input->post('sisa');
			$data_bill['status'] = 1;
			if ($bill_id = $this->tbl_bill->tambah_bill($data_bill)):
				$where_ord['order_id'] = $order_id;
				$data_ord['bill_id'] = $bill_id;
				$data_ord['order_session'] = '';
				if ($this->tbl_order->ubah_order($where_ord,$data_ord))
					echo 'sukses';
			endif;
		endif;
	}
	
}

/* End of file .php */
/* Location: ./../.php */
