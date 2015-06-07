<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home_model extends CI_Model {
	
	public function __construct()
	{	
		//manually connecting to database
		$this->load->database();
	}
	
	/*getting all the titles
	public function getAllTitles(){
		$this->db->select('id, name');
		$this->db->from('title');
		$this->db->order_by('id');
		$query = $this->db->get();
		return $query->result_array();
	}*/
	
	//getting all categories associated with a title id
	public function getCategoryForTitle($titleId){
		$this->db->select('id, name, condition');
		$this->db->from('category');
		$this->db->where('titleId', $titleId); 
		$this->db->where('status', 1);
		$this->db->order_by('id');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	//getting all sub categories associated with a category id
	public function getSubCategoryForCategory($categoryId){
		$this->db->select('id, name, categoryId, condition');
		$this->db->from('subcategory');
		$this->db->where('categoryId', $categoryId); 
		$this->db->where('status', 1);
		$this->db->order_by('id');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	//getting list of all events
	public function getEventList(){
		$this->db->select('id, name');
		$this->db->from('event');
		$this->db->where('status', 1); 
		$this->db->order_by('creationDate','DESC');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	//retreiving description for an event id
	public function getEventDescription($id){
		$this->db->select('description');
		$this->db->from('event');
		$this->db->where('id', $id); 
		$query = $this->db->get();
		$mediate =  $query->result_array();
		return $mediate[0]['description'];
	}
	
	/*Return 2D array containg data of pdfInfo table*/
	public function dumpPdfInfoTable(){
		$query = $this->db->get('pdfinfo');
		return $query->result_array();
	}
	
	/*Return 2D array containg data from articleinfo table*/
	public function dumpArticleInfoTable(){
		$this->db->select('id, articleName fileName, articleTitle title, articleKeyword keyword');
		$this->db->from('articleinfo');
		$this->db->where('status',1);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	/*return 2D associative array of matched ids and their counts against elements of 
	search array */
	public function matchContent($searchArray){
		$resultArray = array();
		for($i=0; $i<count($searchArray); $i++){
			$this->db->select('pdfId, count');
			$this->db->from('pdfkeyword');
			$this->db->where('keyword',$searchArray[$i]);
			$this->db->order_by("count", "desc"); 
			
			$query = $this->db->get();
			$mediate = $query->result_array();
			$particularSearch = array();
			if(count($mediate) != 0){
				for($j=0; $j<count($mediate); $j++){
					$particularSearch[$mediate[$j]['pdfId']] = $mediate[$j]['count'];
				}
			}
			$resultArray[$searchArray[$i]] = $particularSearch;
		}
		return $resultArray;
	}
	
	/*return 2D associative array of matched ids and their counts against elements of 
	search array */
	public function matchArticleContent($searchArray){
		$resultArray = array();
		for($i=0; $i<count($searchArray); $i++){
			$this->db->select('articleId, count');
			$this->db->from('articlekeyword');
			$this->db->where('keyword',$searchArray[$i]);
			$this->db->order_by("count", "desc"); 
			
			$query = $this->db->get();
			$mediate = $query->result_array();
			$particularSearch = array();
			if(count($mediate) != 0){
				for($j=0; $j<count($mediate); $j++){
					$particularSearch[$mediate[$j]['articleId']] = $mediate[$j]['count'];
				}
			}
			$resultArray[$searchArray[$i]] = $particularSearch;
		}
		return $resultArray;
	}
	
	/*return 2D associative array of matched ids against elements of 
	search array this one is for specific Ids */
	public function matchContentForId($idsArray, $searchArray){
		$resultArray = array();
		for($j=0; $j<count($idsArray)-1; $j++){
			$mediateArray = array();
			
			for($i=0; $i<count($searchArray); $i++){
				$this->db->select('count');
				$this->db->from('pdfkeyword');
				$this->db->where('pdfId',$idsArray[$j]);
				$this->db->where('keyword',$searchArray[$i]);
				
				$query = $this->db->get();
				$mediate = $query->result_array();
				
				$mediateArray[$searchArray[$i]] = 0;
				if(count($mediate) != 0)
					$mediateArray[$searchArray[$i]] = intval($mediate[0]['count']);
			}
			
			$resultArray[$idsArray[$j]] = array();
			$resultArray[$idsArray[$j]] = $mediateArray;
		}
		return $resultArray;
	}
	
	/*return 2D associative array of matched ids against elements of 
	search array this one is for specific Ids */
	public function matchArticleContentForId($idsArray, $searchArray){
		$resultArray = array();
		for($j=0; $j<count($idsArray)-1; $j++){
			$mediateArray = array();
			
			for($i=0; $i<count($searchArray); $i++){
				$this->db->select('count');
				$this->db->from('articlekeyword');
				$this->db->where('articleId',$idsArray[$j]);
				$this->db->where('keyword',$searchArray[$i]);
				
				$query = $this->db->get();
				$mediate = $query->result_array();
				
				$mediateArray[$searchArray[$i]] = 0;
				if(count($mediate) != 0)
					$mediateArray[$searchArray[$i]] = intval($mediate[0]['count']);
			}
			
			$resultArray[$idsArray[$j]] = array();
			$resultArray[$idsArray[$j]] = $mediateArray;
		}
		return $resultArray;
	}
	
	/*return 2D array of data from pdfInfo for specific Ids*/
	public function getPdfInfoForId($idsArray){
		$resultArray = array();
		for($i=0; $i<count($idsArray)-1; $i++){
			$this->db->select('*');
			$this->db->from('pdfinfo');
			$this->db->where('id', $idsArray[$i]);
			$query = $this->db->get();
			$mediate = $query->result_array();
			$resultArray[$i] = $mediate[0];
		}
		return $resultArray;
	}
	
	/*return 2D array of data from padfInfo for specific Ids*/
	public function getArticleInfoForId($idsArray){
		$resultArray = array();
		for($i=0; $i<count($idsArray)-1; $i++){
			$this->db->select('id, articleName,CAST(connectedTo AS UNSIGNED) AS connectedTo, categoryId, subCategoryId, articleTitle title, articleKeyword keyword, creationDate');
			$this->db->from('articleinfo');
			$this->db->where('id', $idsArray[$i]);
			$query = $this->db->get();
			$mediate = $query->result_array();
			$resultArray[$i] = $mediate[0];
		}
		return $resultArray;
	}
	
	
	
}