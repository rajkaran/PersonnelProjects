<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Form_submission_model extends CI_Model {
	
	public function __construct()
	{	
		//manually connecting to database
		$this->load->database();
	}
	
	//returns the list of control ids for givent article id.
	public function getControlIdList($articleId){
		$names = array('div', 'label');

		$this->db->select('id, tagName, tagId');
		$this->db->from('control');
		$this->db->where('status',1);
		$this->db->where('articleId',$articleId);
		$this->db->where_not_in('tagName', $names);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	/*getting a control name from a particular table for specific control id*/
	public function getControlName($id,$table ){
		$this->db->select('name, CAST(required AS UNSIGNED) required');
		
		if($table == "input")
			$this->db->select('type');
			
		$this->db->from($table);
		$this->db->where('controlId',$id);
		$query = $this->db->get();
		$mediate = $query->result_array();
		return $mediate[0];
	}
	
	/*getting a control name from a particular table for specific control id*/
	public function getLabelForControl($tagId, $controlIds){
		$this->db->select('innerText');
		$this->db->from("label");
		$this->db->where('forValue',$tagId);
		$this->db->where_in('controlId',$controlIds);
		$query = $this->db->get();
		$mediate = $query->result_array();
		return $mediate[0]['innerText'];
	}
	
	/*create new record in submittedformdata for this submisssion*/
	public function saveFormData($data, $table){
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}
	
	/*saving control data submitted by the user*/
	public function saveControlData($data){
		return $this->db->insert("controldata", $data);
	}
	
	/*getting the send to and copy to recipients for */
	public function getRecipients($id, $nullValue, $returnValue){
		$resultArray = array();
		$this->db->select($returnValue);
		$this->db->from("recipient");
		$this->db->where('articleId',$id);
		$this->db->where($nullValue,null);
		$query = $this->db->get();
		$mediate = $query->result_array();
		
		foreach($mediate as $elementArray){
			$resultArray[]= $elementArray[$returnValue];
		}
		
		return $resultArray;
			
	}
	
	/*getting submission date and time when this form has been submitted*/
	public function getSubmissionTimeStamp($id){
		$this->db->select('submissionDate');
		$this->db->from("submittedformdata");
		$this->db->where('id',$id);
		$query = $this->db->get();
		$mediate = $query->result_array();
		return $mediate[0]['submissionDate'];
	}
	
	/*get the inner text for div associated with submitted form. Using direct query cause issue with Active records.*/
	public function getDivText($articleId){
		$query = $this->db->query('SELECT `controlId` , `innerText` FROM `division` WHERE `controlId` IN ( SELECT `id` FROM `control` WHERE `tagName` = "div" && `articleId` = '.$articleId.' && `status` =1 )');
		return $query->result_array();
	}
	
	/*This returns the sum of yAxis and Height of given control id.*/
	public function getcontrolBottom($controlId){
		$query = $this->db->query('SELECT convert(TRIM(TRAILING "px" FROM `height`),UNSIGNED)+convert(TRIM(TRAILING "px" FROM `yAxis`),UNSIGNED) as `controlBottom` FROM control WHERE `id` = '.$controlId.'');
		$mediate =  $query->result_array();
		return $mediate[0]['controlBottom'];
	}
	
	
	
	
}