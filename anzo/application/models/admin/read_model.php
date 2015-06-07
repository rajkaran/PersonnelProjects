<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Read_model extends CI_Model {
	
	public function __construct()
	{	
		//manually connecting to database
		$this->load->database();
	}
	
	//retrieveing element info
	public function getOptionInfo($id){
		$this->db->select('*');
		$this->db->from('ddoption');
		$this->db->where('controlId',$id);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	//retrieveing article info for particular article id
	public function getArticleInfoForId($id){
		$this->db->select('id, CAST(connectedTo AS UNSIGNED) AS connectedTo, categoryId, subCategoryId, articleName, articleKeyword, articleTitle, CAST(havePdfVersion AS UNSIGNED) AS havePdfVersion, CAST(setToEmail AS UNSIGNED) AS setToEmail, CAST(isItForm AS UNSIGNED) AS isItForm, CAST(status AS UNSIGNED) AS status, creationDate');
		$this->db->from('articleinfo');
		$this->db->where('id',$id);
		$query = $this->db->get();
		$mediate =  $query->result_array();
		return $mediate[0];
	}
	
	//get category or sub category name for given category or sub category id
	public function getName($id, $table){
		$this->db->select('name');
		$this->db->from($table);
		$this->db->where('id',$id);
		$query = $this->db->get();
		$mediate =  $query->result_array();
		return $mediate[0]['name'];
	}
	
	//get the list of categories for title id
	public function getList($idArray, $column, $table){
		$resultArray = array();
		for($i=0; $i<count($idArray); $i++){
			$this->db->select('id,name');
			$this->db->where($column,$idArray[$i]);
			$this->db->from($table);
			$query = $this->db->get();
			$mediate = $query->result_array();
			
			for($j=0; $j<count($mediate); $j++){
				$resultArray[] = $mediate[$j];
			}
		}
		return $resultArray;
	}
	
	//retrieves the email ids set up for the article
	public function getEmailReceipient($id){
		$this->db->select('*');
		$this->db->from("recipient");
		$this->db->where('articleId',$id);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	/*get list of all controls for particle article id from 
	control database table*/
	public function getControlList($articleId){
		$query = $this->db->query('SELECT * FROM control WHERE `articleId` = '.$articleId.' AND `status`=1 order by convert(TRIM(TRAILING "px" FROM `yAxis`),UNSIGNED) ASC');
		return $query->result_array();
	}
	
	/*get list of all interactive controls for an article id from control database table*/
	public function getInteractiveControlList($articleId){
		$query = $this->db->query("SELECT * FROM control WHERE `articleId` = ".$articleId." AND `status`=1 AND `tagName` != 'label' AND `tagName` != 'div' order by convert(TRIM(TRAILING 'px' FROM `yAxis`),UNSIGNED) ASC");
		return $query->result_array();
	}
	
	//retrieving element info from label and division tables
	public function getControlInfo($controlId, $table){
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where('controlId',$controlId);
		$query = $this->db->get();
		$mediate = $query->result_array();
		return $mediate[0];
	}
	
	//retrieving element info from different element tables
	public function getInputInfo($controlId, $table){
		$this->db->select('controlId, name,  
					CAST(required AS UNSIGNED) required, 
					CAST(checked AS UNSIGNED) checked,
					type, fontSize, value');
		$this->db->from($table);
		$this->db->where('controlId',$controlId);
		$query = $this->db->get();
		
		$mediate = $query->result_array();
		return $mediate[0];
	}
	
	//retrieving element info from different element tables
	public function getSelectInfo($controlId, $table){
		$this->db->select('controlId, name, size, 
					CAST(required AS UNSIGNED) required, 
					CAST(multiple AS UNSIGNED) multiple');
		$this->db->from($table);
		$this->db->where('controlId',$controlId);
		$query = $this->db->get();
		$mediate = $query->result_array();
		return $mediate[0];
	}
	
	//retrieving element info from different element tables
	public function getTextAreaInfo($controlId, $table){
		$this->db->select('controlId, name,
					CAST(required AS UNSIGNED) required,
					rows, columns, fontSize');
		$this->db->from($table);
		$this->db->where('controlId',$controlId);
		$query = $this->db->get();
		
		$mediate = $query->result_array();
		return $mediate[0];
	}
	
	//this function toggles the pdf exist flag in database
	public function updatePdfExist($id, $data){
		$this->db->where('id',$id);
		return  $this->db->update("articleinfo",$data);
	}
	
	//this function deletes the records with copyTo values for given Article Id 
	public function unsetCopyTo($id){
		$this->db->where('articleId', $id);
		$this->db->where('sendTo', null);
		return $this->db->delete('recipient'); 
	}
	
	//this function deletes the records with sendTo values for given Article Id 
	public function unsetSendTo($id){
		$this->db->where('articleId', $id);
		$this->db->where('copyTo', null);
		return $this->db->delete('recipient');
		echo $this->db->last_query();
	}
	
	//this function deletes all the email records for given Article Id 
	public function unsetEmail($id){
		$this->db->where('articleId', $id);
		return $this->db->delete('recipient');
	}
	
	//this function inserts the records with sendTo values for Article Id
	public function setSendTo($id, $sendTo){
		$rowInserted = 0;
		$this->unsetSendTo($id);
		for($i=0; $i<count($sendTo); $i++){
			$data = array('articleId' => $id, 'sendTo' => $sendTo[$i]);
			$rowInserted += $this->db->insert('recipient', $data); 
		}
		return $rowInserted;
	}
	
	//this function inserts the records with copyTo values for Article Id
	public function setCopyTo($id, $copyTo){
		$rowInserted = 0;
		$this->unsetCopyTo($id);
		for($i=0; $i<count($copyTo); $i++){
			$data = array('articleId' => $id, 'copyTo ' => $copyTo[$i]);
			$rowInserted += $this->db->insert('recipient', $data); 
		}
		return $rowInserted;
	}
	
	//get the title id for category id
	public function TitleId($id, $copyTo){
		$rowInserted = 0;
		$this->unsetCopyTo($id);
		for($i=0; $i<count($copyTo); $i++){
			$data = array('articleId' => $id, 'copyTo ' => $copyTo[$i]);
			$rowInserted += $this->db->insert('recipient', $data); 
		}
		return $rowInserted;
	}
	
	//get categoryId and TitleId for Category Name
	public function getParentForCategoryName($name){
		$this->db->select('id categoryId, titleId');
		$this->db->from("category");
		$this->db->where('name',$name);
		$query = $this->db->get();
		$mediate =  $query->result_array();
		return $mediate[0];
	}
	
	//get subcategoryId and TitleId for Category Name
	public function getParentForSubCategoryName($name){
		$this->db->select('sc.id subCategoryId, c.titleId');
		$this->db->from("subcategory as sc");
		$this->db->join('category as c', 'sc.categoryId = c.id');
		$this->db->where('sc.name',$name);
		$query = $this->db->get();
		$mediate =  $query->result_array();
		return $mediate[0];
	}
	
	//get subcategoryId and TitleId for Category Name
	public function updateSetting($id, $settingArray){
		$this->db->where('id', $id);
		return $this->db->update('articleinfo', $settingArray); 
	}
	
	

}