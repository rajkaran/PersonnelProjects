<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_model extends CI_Model {
	
	public function __construct()
	{	
		//manually connecting to database
		$this->load->database();
	}
	
	//dumping title ids
	public function dumpTitleTable(){
		$result = array();
		
		$this->db->select('id');
		$this->db->from('title');
		
		$query = $this->db->get();
		$mediate = $query->result_array();
		
		foreach($mediate as $key => $element )
			$result[] = $element['id'];
		
		return $result;
	}
	
	/*This function retrieves list of categories that matches for the given titleIds*/
	public function getCategoryForTitle($titleIds){
		$resultArray = array();
		foreach($titleIds as $element){
			$this->db->select('c.id, c.name as name, t.name as title, c.creationDate, CAST( c.status AS UNSIGNED ) AS status');
			$this->db->from('title as t');
			$this->db->join('category as c', 'c.titleId = t.id');
			$this->db->where('t.id',$element);
			$this->db->order_by('id', 'ASC');
			$query = $this->db->get();
			$mediate = $query->result_array();
			
			for($i=0; $i<count($mediate); $i++){
				$resultArray[] = $mediate[$i];
			}
		}
		return $resultArray;
	}
	
	/*This function retrieves list of articles that matches for the given titleIds*/
	public function getArticleForTitle($ParentIds, $forParent){
		$resultArray = array();
		$index = 0;
		for($i=0; $i< count($ParentIds); $i++){
			$this->db->select('a.id,a.articleName as name, 
								case a.`connectedTo`
									when 1 then "Category"
									when 0 then "Subcategory"
									end as `connectedTo`, 
								 CAST( a.havePdfVersion AS UNSIGNED ) havePdfVersion, 
								 CAST( a.isItForm AS UNSIGNED ) isItForm, 
								 CAST(a.status AS UNSIGNED) AS status, 
								 a.creationDate ', FALSE);
			$this->db->from('articleinfo as a');
			
			if($forParent == "category")
				$this->db->where('a.categoryId',$ParentIds[$i]['id']);
			else $this->db->where('a.subCategoryId',$ParentIds[$i]['id']);
			
			$this->db->order_by('a.id', 'ASC');
			$query = $this->db->get();
			$mediate = $query->result_array();
			
			for($j=0; $j<count($mediate); $j++){
				$resultArray[$index] = $mediate[$j];
				$resultArray[$index]['parent'] = $ParentIds[$i]['parent'];
				$resultArray[$index]['title'] = $ParentIds[$i]['title'];
				$index++;
			}
		}
		return array_values(array_filter($resultArray));
	}
	
	/*This function retrieves list of categories that matches for the given titleIds*/
	public function getSubCategoryForTitle($titleIds){
		$resultArray = array();
		foreach($titleIds as $element){
			$this->db->select('sc.id, sc.name as name, t.name as title, sc.creationDate, CAST( sc.status AS UNSIGNED ) AS status, c.name as category');
			$this->db->from('title as t');
			$this->db->join('category as c', 't.id = c.titleId');
			$this->db->join('subcategory as sc', 'c.id = sc.categoryId');
			$this->db->where('t.id',$element);
			$this->db->order_by('id', 'ASC');
			$query = $this->db->get();
			$mediate = $query->result_array();
			for($i=0; $i<count($mediate); $i++){
				$resultArray[] = $mediate[$i];
			}
		}
		return $resultArray;
	}
	
	//get the category info for categori id.
	public function categoryForId($id){
		$this->db->select('c.id, c.name, c.titleId, c.creationDate, c.condition, CAST(c.status AS UNSIGNED) AS status, t.name as title');
		$this->db->from('category as c');
		$this->db->join('title as t', 'c.titleId = t.id');
		$this->db->where('c.id',$id);
		$query = $this->db->get();
		$mediate = $query->result_array();
		return $mediate[0];
	}

	//get the category info for categori id.
	public function getSubCategoryCount($id){
		$this->db->select('*');
		$this->db->from('subcategory');
		$this->db->where('categoryId',$id);
		return $this->db->count_all_results();
	}

	//get the category info for categori id.
	public function getArticleCount($id, $column){
		$this->db->select('*');
		$this->db->from('articleinfo');
		$this->db->where($column,$id);
		return $this->db->count_all_results();
	}
	
	//get the category info for categori id.
	public function subcategoryForId($id){
		$this->db->select('sc.id, sc.name, sc.creationDate, sc.condition, sc.categoryId, CAST(sc.status AS UNSIGNED) AS status, t.name as title, c.name as category');
		$this->db->from('subcategory as sc');
		$this->db->join('category as c', 'sc.categoryId = c.id');
		$this->db->join('title as t', 'c.titleId = t.id');
		$this->db->where('sc.id',$id);
		$query = $this->db->get();
		$mediate = $query->result_array();
		return $mediate[0];
	}
	
	//gives list of sub categories for given category id
	public function listSubcategory($id){
		$this->db->select('sc.id,sc.name,CAST(sc.status AS UNSIGNED) AS status,sc.creationDate');
		$this->db->from('subcategory as sc');
		$this->db->where('categoryId',$id);
		$query = $this->db->get();
		return  $query->result_array();
	}
	
	//gives list of article for given category id
	public function listarticle($id, $parentId){
		$this->db->select('a.id,a.articleName as name, CAST(a.status AS UNSIGNED) AS status, a.creationDate');
		$this->db->from('articleinfo as a');
		$this->db->where($parentId,$id);
		$query = $this->db->get();
		return  $query->result_array();
	}
	
	//update the status of particular row in the table
	public function updateStatus($id, $table, $data){
		$this->db->where('id',$id);
		return $this->db->update($table,$data);
	}
	
	//get the indicator colour for indicator id
	public function getIndicatorColour($id){
		$this->db->select('colour');
		$this->db->from('indicator');
		$this->db->where('id',$id);
		$query = $this->db->get();
		$mediate = $query->result_array();
		return $mediate[0]['colour'];
	}
	
	
	
	
	
}