<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

/*
 @author		Achri
 @date creation	
 @library
	- 
 @comment
	- 
*/

class Tbl_bill extends CI_Model {

	function __construct() 
	{
		parent::__construct();
	}	
	
	function data_bill($where) {
		if (is_array($where)):
			foreach($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		endif;
		
		return $this->db->get('order_bill');
	}
	
	function data_order_detail($where) {
		if (is_array($where)):
			foreach($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		endif;
		
		return $this->db->get('order_menu_detail');
	}
	
	function data_order_menu($where) {
		if (is_array($where)):
			foreach($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		endif;
		
		$this->db->from('order_menu_detail as ordet');
		$this->db->join('master_menu as menu','menu.menu_id=ordet.menu_id');
		
		return $this->db->get();
	}
	
	function cek_status_meja($no_meja,$status=0) {
		$this->db->where('no_meja',$no_meja);
		//$this->db->where('status',$status);
		return $this->db->get('order_menu');
	}
	
	function cek_meja_order($no_meja) {
		$sql = "select sum(ordet.jml) as jumlah, ord.status, ord.order_id
		from order_menu as ord
		left join order_menu_detail as ordet on ordet.order_id = ord.order_id
		where ord.no_meja = $no_meja and ord.status in (0,1,2,3)";
		
		return $this->db->query($sql);
	}
	
	function cek_order_baru($status=0) {
		$sql = " select distinct(ord.no_meja) 
		from order_menu_detail as ordet 
		inner join order_menu as ord on ord.order_id = ordet.order_id 
		where ord.status = $status order by ord.no_meja
		";
		return $this->db->query($sql);
	}
	
	function kalkulasi_total($order_id) {
		$sql = "select sum(ordet.jml * menu.harga) as tot_harga,
			sum(ordet.jml) as tot_jml
			from order_menu as ord
			inner join order_menu_detail as ordet on ordet.order_id = ord.order_id
			inner join master_menu as menu on menu.menu_id = ordet.menu_id
			where ord.order_id = $order_id			
		";
		
		return $this->db->query($sql);
	}
	
	function tambah_bill($data)
	{
		$this->db->insert("order_bill",$data);
		return $this->db->insert_id();
	}
	
	function tambah_order_detail($data)
	{
		return $this->db->insert("order_menu_detail",$data);
	}
	
	function ubah_bill($where,$data) {
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		endif;
		
		return $this->db->update('order_bill',$data);
	}
	
	function ubah_order_detail($where,$data) {
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		endif;
		
		return $this->db->update('order_menu_detail',$data);
	}
	
	function hapus_order($where) {
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		endif;
		
		return $this->db->delete('order_menu');
	}
	
	function hapus_order_detail($where) {
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		endif;
		
		return $this->db->delete('order_menu_detail');
	}

}

/* End of file .php */
/* Location: ./../.php */