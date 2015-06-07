<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home_page_model extends CI_Model {
	
	public function __construct()
	{	
		//manually connecting to database
		$this->load->database();
	}
	
	
	/*This function retrieves the events name and their ids.*/
	public function listEvent($userId){
		$this->db->select('name, id, CAST(status AS UNSIGNED) AS status');
		$this->db->from('event');
		$this->db->where('userId',$userId);
		$this->db->order_by('creationDate', 'DESC'); 
		$query = $this->db->get();
		return $query->result_array();
	}
	
	/*This function retrieves all the events from the database.*/
	public function dumpEventTable(){
		$this->db->select('name, id, CAST(status AS UNSIGNED) AS status');
		$this->db->from('event');
		$this->db->order_by('creationDate', 'DESC');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	/*This function retrieves the an event for event id.*/
	public function getEvent($id){
		$this->db->select('id,name,description');
		$this->db->from('event');
		$this->db->where('id',$id); 
		
		$query = $this->db->get();
		$mediate = $query->result_array();
		return $mediate[0];
	}
	
	//updating the old event
	public function updateEvent($id, $eventData){
		$this->db->where('id', $id);
		return $this->db->update('event', $eventData); 
	}
	
	//creating new event
	public function createEvent($eventData){
		return $this->db->insert('event', $eventData);
	}
	
	//updating the old event
	public function enableEvent($id, $eventData){
		$this->db->where('id', $id);
		return $this->db->update('event', $eventData); 
	}
	
	//updating the old event
	public function disableEvent($id, $eventData){
		$this->db->where('id', $id);
		return $this->db->update('event', $eventData); 
	}
	
	 
	
	
	
	
}