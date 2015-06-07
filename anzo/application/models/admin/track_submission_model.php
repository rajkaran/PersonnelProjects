<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Track_submission_model extends CI_Model {
	
	public function __construct()
	{	
		//manually connecting to database
		$this->load->database();
	}
	
	/*This function retrieves list of categories that matches for the given titleIds*/
	public function enabledCategoryForTitle($titleIds)
	{
		$resultArray = array();
		foreach($titleIds as $element){
			$this->db->select('c.id');
			$this->db->from('category as c');
			$this->db->join('title as t', 'c.titleId = t.id');
			$this->db->where('c.titleId',$element);
			$this->db->where('c.status',1);
			$this->db->order_by('id', 'ASC');
			$query = $this->db->get();
			$mediate = $query->result_array();
			
			for($i=0; $i<count($mediate); $i++){
				$resultArray[] = $mediate[$i];
			}
		}
		return $resultArray;
	}
	
	/*This function retrieves list of categories that matches for the given titleIds*/
	public function enabledSubCategoryForTitle($titleIds) {
		$resultArray = array();
		foreach($titleIds as $element){
			$this->db->select('sc.id');
			$this->db->from('category as c ');
			$this->db->join('subcategory as sc', 'sc.categoryId = c.id');
			$this->db->where('c.titleId',$element);
			$this->db->where('sc.status',1);
			$this->db->where('c.status',1);
			$this->db->order_by('sc.id', 'ASC');
			$query = $this->db->get();
			$mediate = $query->result_array();
			for($i=0; $i<count($mediate); $i++){
				$resultArray[] = $mediate[$i];
			}
		}
		return $resultArray;
	}
	
	/*This function retrieves list of categories that matches for the given titleIds*/
	public function getArticleForParent($idArray, $column){
		$resultArray = array();
		foreach($idArray as $element){
			$this->db->select('id, categoryId, subCategoryId, articleName, 
								case `connectedTo`
									when 1 then "Category"
									when 0 then "Subcategory"
									end as `connectedTo`, articleKeyword, articleTitle, 
								 creationDate', FALSE);
			$this->db->from('articleinfo');
			$this->db->where($column,$element);
			$this->db->where('status',1);
			$this->db->where('isItForm',1);
			$query = $this->db->get();
			$mediate = $query->result_array();
			for($i=0; $i<count($mediate); $i++){
				$resultArray[] = $mediate[$i];
			}
		}
		return $resultArray;
	}
	
	/*This function retrieves list of categories that matches for the given titleIds*/
	public function getNameForId($id, $table){
		$this->db->select('name');
		$this->db->from($table);
		$this->db->where('id',$id);
		$query = $this->db->get();
		$mediate = $query->result_array();
		return $mediate[0]['name'];
	}
	
	/*This function caculates the no. of times a particular article has been submitted*/
	public function getTotalSubmission($id){
		$this->db->select('*');
		$this->db->from('submittedformdata');
		$this->db->where('articleId',$id);
		return $this->db->count_all_results();
	}
	
	/*This function retrieves list of the submission of the article*/
	public function getSubmissionForId($id){
		$this->db->select('*');
		$this->db->from('submittedformdata');
		$this->db->where('articleId',$id);
		$this->db->order_by('submissionDate','DESC');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	/*This function retrieves name of article for given id*/
	public function getArticleNameForId($id){
		$this->db->select('articleName');
		$this->db->from('articleinfo');
		$this->db->where('id',$id);
		$query = $this->db->get();
		$mediate = $query->result_array();
		return $mediate[0]['articleName'];
	}
	
	/*This function gets the controldata for the submission id*/
	public function getControlDataForId($id){
		$this->db->select('controlId, data');
		$this->db->from('controldata');
		$this->db->where('submissionId',$id);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	/*This function gets the controldata for the submission id*/
	public function getControlForId($id){
		$this->db->select('id, tagId, articleId, tagName, yAxis, xAxis, height, width, CAST(status AS UNSIGNED) status');
		$this->db->from('control');
		$this->db->where('id',$id);
		$query = $this->db->get();
		$mediate = $query->result_array();
		return $mediate[0];
	}
	
	/*This function gets the label secondary info for the tag Id of control*/
	public function getLabelForTagId($tagId, $controlIds){
		$this->db->select('*');
		$this->db->from('label');
		$this->db->where('forValue',$tagId);
		$this->db->where_in('controlId',$controlIds);
		$query = $this->db->get();
		$mediate = $query->result_array();
		if(count($mediate) > 0)
			return $mediate[0];
		else return $mediate;
	}
	
	/*This function gets the label secondary info for the tag Id of control*/
	public function getFormIdForSubmissionId($submissionId){
		$this->db->select('articleId');
		$this->db->from('submittedformdata');
		$this->db->where('id',$submissionId);
		$query = $this->db->get();
		$mediate = $query->result_array();
		return $mediate[0]['articleId'];
	}
	
	/*This function gets the nearest modification time stamp to the submission time stamp if any.*/
	public function getNearestModification($id){
		$this->db->select('m.id');
		$this->db->from('modlog as m');
		$this->db->join('submittedformdata as s', 'm.articleId = s.articleId');
		$this->db->where('s.id',$id);
		$this->db->where('m.modificationDate >', 's.submissionDate', false);
		$this->db->order_by('m.modificationDate', 'ASC'); 
		$this->db->limit(1,0);
		$query = $this->db->get();
		$mediate = $query->result_array();
		
		if(count($mediate) >0 )
			return $mediate[0]['id'];
		else return $mediate;
	}
	
	/*this function gets the list of contols modified in given mod id..*/
	public function getModControlList($id){
		$resultArray = array();
		
		$this->db->select('controlId');
		$this->db->from('modcontrol');
		$this->db->where('modId',$id);
		$query = $this->db->get();
		$mediate = $query->result_array();
		
		for($i=0; $i<count($mediate); $i++){
			$resultArray[] = $mediate[$i]['controlId'];
		}
		return $resultArray;
	}
	
	/*this function gets the list of contols modified in given mod id..*/
	public function getModControl($controlId, $modId){
		$resultArray = array();
		
		$this->db->select('*');
		$this->db->from('modcontrol');
		$this->db->where('modId',$modId);
		$this->db->where('controlId',$controlId);
		$query = $this->db->get();
		$mediate = $query->result_array();
		
		return $mediate[0];
	}
	
	/*This returns the sum of yAxis and Height of given control id.*/
	public function getcontrolBottom($controlId){
		$query = $this->db->query('SELECT convert(TRIM(TRAILING "px" FROM `height`),UNSIGNED)+convert(TRIM(TRAILING "px" FROM `yAxis`),UNSIGNED) as `controlBottom` FROM control WHERE `id` = '.$controlId.'');
		$mediate =  $query->result_array();
		return $mediate[0]['controlBottom'];
	}
	
	/*get the list of label's control ids for a given article*/
	public function getLabelControlId($articleId){
		$resultArray = array();
		$this->db->select('id');
		$this->db->from("control");
		$this->db->where('articleId',$articleId);
		$this->db->where('tagName','label');
		$query = $this->db->get();
		$mediate = $query->result_array();
		
		for($i=0; $i<count($mediate); $i++){
			$resultArray[] = $mediate[$i]['id'];
		}
		return $resultArray;
	}
	
	/*get article id for mod id*/
	public function getArticleIdForModId($modId){
		$this->db->select('articleId');
		$this->db->from("modlog");
		$this->db->where('id',$modId);
		$query = $this->db->get();
		$mediate = $query->result_array();
		return $mediate[0]['articleId'];
	}
	
	
}