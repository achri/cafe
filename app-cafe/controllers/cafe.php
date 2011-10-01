<?php if ( ! defined('BASEPATH')) exit('Direct URL not allowed');

// 

class Cafe extends CI_Controller 
{
	public static $link_controller, $link_controller_notifikasi;
	function __construct() 
	{
		parent::__construct();
		$this->load->library('jqcontent');
		$this->config->load('cafe');
		
		$data['header'] = $this->jqcontent->header();
		
		$plugins = array (
			'asset/js/plugins/layout/jquery.layout.js',
			'asset/js/general/notifikasi_meja.js',
		);
		
		$data['extraHeader'] = $this->jqcontent->jsplugins($plugins);
		
		// LINK CONTROLLER & VIEW ==>
		self::$link_controller = 'cafe';
		self::$link_controller_notifikasi = 'mod_notifikasi/notifikasi_meja';
		
		$data['link_controller'] = self::$link_controller;
		$data['link_controller_notifikasi'] = self::$link_controller_notifikasi;
		// <== END LINK CONTROLLER & VIEW
	
		$this->load->vars($data);
	}
	
	function index() 
	{
		$data['title'] = 'CAFE';
		$this->load->view('index.php',$data);
	}
	
	function home() {
		$data[''] = '';
		$this->load->view('home/home_index',$data);
	}
}

// End of file First.php
// Location : ./application/controller/first.php