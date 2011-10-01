<?php

class Tbl_menu extends CI_Model {

	function __construct() 
	{
		parent::__construct();
	}	
	
	function data_menu($where=false,$like=false,$field_sort='menu_id',$sort='ASC')
	{
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where!=false):
			$this->db->where("menu_id",$where);
		endif;
		
		if (is_array($like)):
			foreach ($like as $field=>$value):
				$this->db->like($field,$value,"after");
			endforeach;
		elseif ($like!=false):
			$this->db->like("menu_kode",$like,"after");
		endif;
		
		if (is_array($field_sort)):
			foreach ($field_sort as $field):
				$this->db->order_by($field,$sort);
			endforeach;
		else:
			$this->db->order_by($field_sort,$sort);
		endif;
		
		return $this->db->get("master_menu");
	}
	
	function cek_menu($menu_id,$menu_nama="") 
	{
		$this->db->select("menu_id");
		if ($menu_nama!="")
			$this->db->where("menu_nama",$menu_nama);
		$this->db->where("menu_id",$menu_id);
		return $this->db->get("master_menu")->num_rows();
	}
	
	function nomor_kategori($kat_master) {
		$this->db->select_max('kat_kode','nomor');
		$this->db->where('kat_master',$kat_master);
		$query = $this->db->get('master_kategori');
		$query_rows = $query->row();
		return $query_rows->nomor;
	}
	
	function tambah_menu($data)
	{
		return $this->db->insert("master_menu",$data);
	}
	
	function hapus_menu($where=false)
	{
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where!=false):
			$this->db->where("menu_id",$where);
		endif;
		return $this->db->delete("master_menu");
	}
	
	function ubah_menu($where=false,$data)
	{
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where!=false):
			$this->db->where("menu_id",$where);
		endif;
		return $this->db->update("master_menu",array("menu_nama"=>$nama));
	}

}