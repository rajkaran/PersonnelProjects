<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {
	
	public function __construct()
	{	
		//manually connecting to database
		$this->load->database();
	}
	
	/*Get the list of all users except admin*/
	public function listUser(){
		$this->db->select('id, userName, password, empId, emailId, CAST(status AS UNSIGNED) status');
		$this->db->from('user');
		$this->db->where_not_in('userName', 'admin');
		$this->db->order_by('id','DESC');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	//get the title associated to a user
	public function getTitlesForUser($userId){
		$this->db->select('t.name as title');
		$this->db->from("accesslayer as a");
		$this->db->join('title as t', 'a.titleId = t.id');
		$this->db->where('a.userId',$userId);
		$query = $this->db->get();
		return  $query->result_array();
	}
	
	//get info for a given user
	public function getUserData($userId){
		$this->db->select('id, userName, password, empId, emailId, CAST(status AS UNSIGNED) status');
		$this->db->from("user");
		$this->db->where('id',$userId);
		$query = $this->db->get();
		$mediate = $query->result_array();
		return $mediate[0];
	}
	
	//create new user
	public function addNewUser($data){
		$this->db->insert("user", $data);
		return $this->db->insert_id();
	}
	
	//updating user info
	public function updateUser($userId, $data){
		$this->db->where('id',$userId);
		return $this->db->update("user",$data);
	}
	
	//remove the title and user association in access layer
	public function removeUserTitleMapping($userId){
		$this->db->where('userId', $userId);
		return $this->db->delete('accesslayer');
	}
	
	//create new association between users and titles
	public function mapTitlesToUser($userId, $titleList){
		$insertedRows = 0;
		$this->removeUserTitleMapping($userId);
		for($i=0; $i<count($titleList); $i++){
			
			$data = array("userId" => $userId, "titleId" => $titleList[$i]);
			$insertedRows += $this->db->insert("accesslayer", $data);
		}
		return ( $insertedRows == count($titleList) )?true:false;
	}
	
	//enabling user
	public function enableUser($userId, $data){
		$this->db->where('id',$userId);
		return $this->db->update("user",$data);
	} 
	
	//disabling user
	public function disableUser($userId, $data){
		$this->db->where('id',$userId);
		return $this->db->update("user",$data);
	}
	
	//matching given string with the stored password for a particular user id
	public function matchPassword($userId, $data){
		$this->db->select('password');
		$this->db->from("user");
		$this->db->where('id',$userId);
		$this->db->where('password',$data);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	//updating password of an user
	public function updatePassword($userId, $data){
		$this->db->where('id',$userId);
		return $this->db->update("user",$data);
	}
	
	/*matching given string with the stored email ids and return 
	user id and user name for that matched email id*/
	public function matchEmail($email){
		$this->db->select('id, userName');
		$this->db->from("user");
		$this->db->where('emailId',$email);
		$this->db->where('status',1);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	
	
}