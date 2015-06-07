<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	/*This function accepts the base_url of the controller method, list of data, no. of 
	records per page and the segment of url. Before calling this function you need to 
	load url helper and pagination library.*/
	function addPages($base_url, $list, $per_page, $url_segment){
		$ci =& get_instance();
		
		$config = array();
		$config['base_url'] = $base_url;
		$config['total_rows'] = count($list);
		$config['per_page'] = $per_page;
		$config["uri_segment"] = $url_segment;
		$choice = $config["total_rows"] / $config["per_page"];
		$config["num_links"] = round($choice);
		
		$ci->pagination->initialize($config);
		$page = ($ci->uri->segment($url_segment)) ? $ci->uri->segment($url_segment) : 0;
		
		$list = partOfArray($page, $config['per_page'], $list);
		$links = $ci->pagination->create_links();
		
		return array($list, $links);
	}
	
	//This function returns a small section of input array
	function partOfArray($start, $length, $inputArray){
		return array_slice($inputArray, $start, $length);
	}
	
	//a function to place an image according to true and false value 
	function toglleImage($value){
		$result = '<img src=" '.base_url().'img/yes.png" height="20px" width="20px" alt="my Profile" />';
		if($value == 0)
			$result = '<img src=" '.base_url().'img/no.png" height="20px" width="20px" alt="my Profile" />';
		return $result;
	}
