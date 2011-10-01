<?php

class Dynatree_model extends CI_Model{

	function __construct() {
		parent::__construct();
	}
	
	function set_kategori($kat_id = 1,$kat_level = 1,$kat_tipe=false) {		
		$this->db->select('kat_id, kat_kode, kat_level, kat_nama');
		$this->db->where('kat_master',$kat_id);
		
		if ($kat_tipe!=false)
			$this->db->where('kat_tipe',$kat_tipe);
		
		$this->db->where_in('kat_level',$kat_level);
		$this->db->order_by ('kat_nama', 'ASC');
		return $this->db->get('master_kategori');
	}
	
}