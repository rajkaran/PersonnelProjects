<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Display_article_model extends CI_Model {
	
	public function __construct()
	{	
		//manually connecting to database
		$this->load->database();
	}
	
	/*retrieving all the articles associated with a given category 
	id or subcategory id*/
	public function getArticleList($id, $level){
		$idArray = array();
		$this->db->select('id');
		$this->db->from('articleinfo');
		$this->db->where($level, $id); 
		$this->db->where('status', 1); 
		$this->db->order_by('id');
		$query = $this->db->get();
		$mediate = $query->result_array();
		
		for($i=0; $i<count($mediate); $i++){
			$idArray[] = $mediate[$i]['id'];
		}
		return $idArray;
	}
	
	/*retrieving category info for a given category id
	public function getCategoryForId($id){
		$this->db->select('c.id, c.name as name, t.name as title, c.creationDate, CAST(c.status AS UNSIGNED) AS status');
		$this->db->from('category as c');
		$this->db->join('title as t', 'c.titleId = t.id');
		$this->db->where('c.id',$id);
		$query = $this->db->get();
		$mediate = $query->result_array();
		return $mediate[0];
	}*/
	
	/*retrieving subcategory info for a given subcategory id
	public function getSubCategoryForId($id){
		$this->db->select('sc.id, sc.name as name, t.name as title, sc.creationDate, CAST(sc.status AS UNSIGNED) AS status, c.name as category');
		$this->db->from('subcategory as sc');
		$this->db->join('title as t', 'sc.titleId = t.id');
		$this->db->join('category as c', 'sc.categoryId = c.id');
		$this->db->where('sc.id',$id);
		$query = $this->db->get();
		$mediate = $query->result_array();
		return $mediate[0];
	}*/
	
	/*get list of all controls for particle article id from 
	control database table
	public function getControlList($articleId){
		$query = $this->db->query('SELECT * FROM control WHERE `articleId` = '.$articleId.' AND CAST(status AS UNSIGNED) = 1 order by convert(TRIM(TRAILING "px" FROM `yAxis`),UNSIGNED) ASC');
		return $query->result_array();
	}*/
	
	/*retrieving element info from label and division tables
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
	
	//retrieveing element info
	public function getOptionInfo($id){
		$this->db->select('*');
		$this->db->from('ddoption');
		$this->db->where('controlId',$id);
		$query = $this->db->get();
		return $query->result_array();
	}*/
	
	/*get maximum value of Y Axis of control for an article 
	not using active records because of query complexity
	public function maxTop($id){
		$query = $this->db->query('SELECT 
convert(TRIM(TRAILING "px" FROM `height`),UNSIGNED)+convert(TRIM(TRAILING "px" FROM `yAxis`),UNSIGNED) as `maxTop` FROM control WHERE `articleId` = '.$id.' AND `status`=1 order by maxTop DESC LIMIT 0,1');
		$mediate =  $query->result_array();
		return $mediate[0]['maxTop'];
	}*/
	
	
	/*retrieveing article info for particular article id
	public function getArticleInfo($id){
		$this->db->select('id,titleId, CAST(connectedTo AS UNSIGNED) AS connectedTo, categoryId, subCategoryId, articleName, articleKeyword, articleTitle, CAST(havePdfVersion AS UNSIGNED) AS havePdfVersion, CAST(setToEmail AS UNSIGNED) AS setToEmail, CAST(isItForm AS UNSIGNED) AS isItForm, CAST(status AS UNSIGNED) AS status, creationDate');
		$this->db->from('articleinfo');
		$this->db->where('id',$id);
		$query = $this->db->get();
		$mediate =  $query->result_array();
		return $mediate[0];
	}*/
	
	
	
}