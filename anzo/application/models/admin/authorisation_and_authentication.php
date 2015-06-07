<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authorisation_and_authentication extends CI_Model {
	
	public function __construct()
	{	
		//manually connecting to database
		$this->load->database();
	}
	
	
	/*This function retrieves username and id for specific credentials*/
	public function userExist(){
		$data = array(
			'userName' => $this->input->post('userName'),
			'password' => base64_encode($this->input->post('password'))
		);
		
		$this->db->select('userName, id, CAST( `role` AS UNSIGNED ) AS "role"');
		$this->db->from('user');
		$this->db->where('status',1);
		$this->db->where($data); 
		
		$query = $this->db->get();
		return $query->result_array();
	}
	
	/*This function retrieves the privileges given to a specific user*/
	public function userAuthorised($userId){
		$result = array();
		
		$this->db->select('titleId');
		$this->db->from('accesslayer');
		$this->db->where('userId', $userId);
		
		$query = $this->db->get();
		$mediate = $query->result_array();
		
		foreach($mediate as $key => $element )
			$result[] = $element['titleId'];
		
		return $result;
	}
	
	
	
}