<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Create_and_edit_model extends CI_Model {
	
	public function __construct()
	{	
		//manually connecting to database
		$this->load->database();
	}
	
	/*saving category and sub category into the database*/
	public function saveCategoryOrSubCategory($data, $table){
		$mediate = $this->db->insert($table, $data);
		if($mediate)
			return $this->db->insert_id();
		else return false;
	}
	
	/*getting data related to a particular sub category*/
	public function getSubCategoryForId($id){
		$this->db->select('sc.id, sc.name as name, t.name as title, sc.condition, sc.creationDate, CAST(sc.status AS UNSIGNED) AS status, c.name as category');
		$this->db->from('subcategory as sc');
		$this->db->join('category as c', 'sc.categoryId = c.id');
		$this->db->join('title as t', 'c.titleId = t.id');
		$this->db->where('sc.id',$id);
		$query = $this->db->get();
		$mediate = $query->result_array();
		return $mediate[0];
	}
	
	/*getting data related to a particular category*/
	public function getCategoryForId($id){
		$this->db->select('c.id, c.name as name, t.name as title, c.condition, c.creationDate, CAST(c.status AS UNSIGNED) AS status');
		$this->db->from('category as c');
		$this->db->join('title as t', 'c.titleId = t.id');
		$this->db->where('c.id',$id);
		$query = $this->db->get();
		$mediate = $query->result_array();
		return $mediate[0];
	}
	
	//returns an array of title names for given array of title ids
	public function getTitleList($titleIdList){
		$resultArray = array();
		for($i=0; $i<count($titleIdList); $i++){
			$this->db->select('name');
			$this->db->where('id',$titleIdList[$i]);
			$query = $this->db->get('title');
			$mediate = $query->result_array();
			$resultArray[] = $mediate[0]['name'];
		}
		return $resultArray;
	}
	
	//returns title id for given title name
	public function getTitleId($name){
		$this->db->select('id');
		$this->db->where('name',$name);
		$query = $this->db->get('title');
		$mediate = $query->result_array();
		return $mediate[0]['id'];
	}
	
	//returns category id for given category name
	public function getCategoryId($name){
		$this->db->select('id');
		$this->db->where('name',$name);
		$query = $this->db->get('category');
		$mediate = $query->result_array();
		return $mediate[0]['id'];
	}
	
	
	//returns list of category names for given title id
	public function getCategoryList($titleId){
		$resultArray = array();
		$this->db->select('name');
		$this->db->where('titleId',$titleId);
		$query = $this->db->get('category');
		$mediate = $query->result_array();
		
		for($i=0; $i<count($mediate); $i++){
			$resultArray[] = $mediate[$i]['name'];
		}
		return $resultArray;
	}
	
	//returns list of category names for given category id
	public function getSubCategoryList($categoryId){
		$resultArray = array();
		$this->db->select('name');
		$this->db->where('categoryId',$categoryId);
		$query = $this->db->get('subcategory');
		$mediate = $query->result_array();
		
		for($i=0; $i<count($mediate); $i++){
			$resultArray[] = $mediate[$i]['name'];
		}
		return $resultArray;
	}
	
	//returns the id of category or title for given category name or title name
	public function getId($name, $table){
		$this->db->select('id');
		$this->db->where('name',$name);
		$query = $this->db->get($table);
		$mediate = $query->result_array();
		return $mediate[0]['id'];
	}
	
	//updates the control info
	public function updateCategoryOrSubCategory($data, $id, $table){
		$this->db->where('id',$id);
		$mediate = $this->db->update($table,$data);		
		if($mediate)			
			return $this->db->affected_rows();
		else return false;
	}
	
	/*get maximum value of Y Axis of control for an article 
	not using active records because of query complexity*/
	public function maxTop($id){
		$query = $this->db->query('SELECT 
convert(TRIM(TRAILING "px" FROM `height`),UNSIGNED)+convert(TRIM(TRAILING "px" FROM `yAxis`),UNSIGNED) as `maxTop` FROM control WHERE `articleId` = '.$id.' AND `status`=1 order by maxTop DESC LIMIT 0,1');
		$mediate =  $query->result_array();
		return $mediate[0]['maxTop'];
	}
	
	//get list of titles for userid
	public function getTitle($userId){
		$this->db->select('a.titleId as id, t.name as title');
		$this->db->from('accessLayer as a');
		$this->db->join('title as t', 'a.titleId = t.id');
		$this->db->where('a.userId',$userId);
		$this->db->order_by('a.titleId', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	//get list of all titles identical to get title list 
	public function dumpTitleTable(){
		$this->db->select('id, name as title, CAST(isRanged as UNSIGNED) isRanged');
		$this->db->from('title');
		$this->db->order_by('id', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	//store article Info into database
	public function saveArticleInfo($infoArray){
		$this->db->insert('articleinfo', $infoArray);
		return $this->db->insert_id();
	}
	
	//store Info related to controls into database
	public function saveControlInfo($infoArray){
		$this->db->insert('control', $infoArray);
		return $this->db->insert_id();
	}
	
	//store Info of a specific control into database
	public function saveTagInfo($infoArray, $table){
		$this->db->insert($table, $infoArray);
		$this->db->insert_id();
		return $this->db->affected_rows();
	}
	
	//store Info of a specific control into database
	public function saveDdOptionInfo($infoArray){
		$this->db->insert("ddoption", $infoArray);
		$this->db->insert_id();
		return $this->db->affected_rows();
	}

	
	//retrieves the tag ids and ids of existing controls for an article
	public function existingTagId($id){
		$this->db->select('id, tagId');
		$this->db->from("control");
		$this->db->where('articleId',$id);
		$this->db->where('status',1);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	//updates the control info
	public function updateControlInfo($data, $controlId){
		$this->db->where('id',$controlId);
		return $this->db->update("control",$data);
	}
	
	//updates the control info
	public function updateDdOptionInfo($id){
		$this->db->where('controlId',$id);
		$this->db->delete("ddoption");
		return $this->db->affected_rows();
	}
	
	//updates the tag/element info
	public function updateTagInfo($data,$table){
		$id = $data["controlId"];
		unset($data["controlId"]);
		$this->db->where('controlId',$id);
		return  $this->db->update($table,$data);
	}
	
	//remove the control from the article
	public function updateControlStatus($id){
		$data = array("status" => false);
		$this->db->where('id',$id);
		return  $this->db->update("control",$data);
	}
	
	//Compare the new control info to the existing info, and returns no. of matched rows. 
	public function matchControlInput($compareArray, $table){
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where($compareArray);
		return $this->db->count_all_results();
	}
	
	/*This function accepts the name string, column and table to check 
	whether article, category or sub category exist with the same name.*/ 
	public function nameExist($name, $column, $table){
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where($column,$name);
		return $this->db->count_all_results();
	}
	
	/*This function creates a new modification log for an article id.*/ 
	public function createModLog($data){
		$this->db->insert('modlog', $data);
		return $this->db->insert_id();
	}
	
	/*This function creates a new modification log for an article id.*/ 
	public function createModControlRecord($data){
		return $this->db->insert('modcontrol', $data);
	}
	
	/*This function accepts the name string, column and table to check 
	whether article, category or sub category exist with the same name.*/ 
	public function duplicateNameExist($name, $column, $table){
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where($column,$name);
		$query = $this->db->get();
		
		if($this->db->count_all_results() == 1){
			return $query->result_array();
		}else return false;
	}
	
	//Compare the new control info to the existing info, and returns no. of matched rows. 
	public function getControlInfoForId($table, $column, $controlId){
		($table == "control")?$this->db->select('yAxis, xAxis, height, width'):$this->db->select('innerText');
		$this->db->from($table);
		$this->db->where($column,$controlId );
		$query = $this->db->get();
		$mediate = $query->result_array();
		return $mediate[0];
	}
	
	//return isRanged value for selected title 
	public function isRangedForTitle($title){
		$this->db->select('CAST(`isRanged` as UNSIGNED) isRanged');
		$this->db->from('title');
		$this->db->where('name',$title );
		$query = $this->db->get();
		$mediate = $query->result_array();
		if($mediate)
			return $mediate[0]['isRanged'];
		else return false;
	}
	
	//retrieve all the indicators 
	public function dumpIndicator(){
		$this->db->select('*');
		$this->db->from('indicator');
		$query = $this->db->get();
		return $query->result_array();
	}

	
	
	
}