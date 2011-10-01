<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

/*
 @author		Achri
 @date creation	
 @library
	- 
 @comment
	- 
*/

class Tbl_report extends CI_Model {

	function __construct() 
	{
		parent::__construct();
	}	
	
	function get_order($select_date,$harian) {
		if ($harian == 1)
			$sql = "
				select ord.order_id,ord.no_meja,bill.tot_jml,bill.tot_harga,
				date_format(ord.order_tgl,'%d-%m-%Y %H:%i:%s') as tanggal
				from order_menu as ord 
				inner join order_bill as bill on bill.bill_id = ord.bill_id 
				where ord.bill_id != 0 and ord.order_tgl like '$select_date%'
				order by ord.order_tgl
			";
		else
			$sql = "
				select 
				date_format(ord.order_tgl,'%d-%m-%Y') as tanggal,
				date_format(ord.order_tgl,'%m-%Y') as bln_thn,
				sum(bill.tot_jml) as tot_jml,
				sum(bill.tot_harga) as tot_harga
				from order_menu as ord
				inner join order_bill as bill on bill.bill_id = ord.bill_id
				where ord.bill_id != 0 and ord.status = 3 and ord.order_tgl like '$select_date%'
				group by tanggal
				order by ord.order_tgl 
			";
		return $this->db->query($sql);
	}
	
	function get_order_detail($order_id,$harian) {
		if ($harian == 1)
			$sql = "
				select menu.menu_nama, ordet.jml,
				date_format(ordet.menu_tgl,'%H:%i:%s') as order_jam,
				(ordet.jml * menu.harga) as tot_harga
				from order_menu_detail as ordet
				inner join master_menu as menu on menu.menu_id = ordet.menu_id 			
				where ordet.order_id = $order_id
			";
		else
			$sql = "
			select ord.order_id, ord.no_meja, bill.tot_jml, bill.tot_harga,
			date_format(ord.order_tgl,'%H:%i:%s') as order_jam
			from order_menu as ord
			inner join order_bill as bill on bill.bill_id = ord.bill_id
			where ord.order_tgl like '$order_id%'
			order by order_jam
			";
		return $this->db->query($sql);
	}

}

/* End of file .php */
/* Location: ./../.php */