<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload_and_index_model extends CI_Model {
	
	public function __construct()
	{	
		//manually connecting to database
		$this->load->database();
	}
	
	/*This finction retrieves the already indexed file names from the pdfInfo*/
	public function getAllFileNames(){
		$resultArray = array();
		$this->db->select('fileName');
		$this->db->from('pdfinfo');
		$query = $this->db->get();
		$mediate = $query->result_array();
		for($i=0; $i<count($mediate); $i++){
			$resultArray[$i] = $mediate[$i]["fileName"];
		}
		return $resultArray;
			
	}
	
	/*placing data retrived from pdf including ifo and content into the database and
	returns true if placed successfull otherwise false*/
	public function forwardingToDatabase($dataArray)
	{
		$numberOfRowsInserted = 0;
		for($i=0; $i<count($dataArray); $i++){
			$data = array();
			   if(isset($dataArray[$i]['name']))$data['fileName'] = $dataArray[$i]['name'];
			   if(isset($dataArray[$i]['title']))$data['title'] = $dataArray[$i]['title'];
			   if(isset($dataArray[$i]['keyword']))$data['keyword'] = $dataArray[$i]['keyword'];
			   if(isset($dataArray[$i]['author']))$data['author'] = $dataArray[$i]['author'];
			   if(isset($dataArray[$i]['creationdate']))$data['creationdate'] = $dataArray[$i]['creationdate'];
			$this->db->insert('pdfinfo', $data);
			$pdfId = $this->db->insert_id();
			if($this->db->affected_rows() == 1);
				$numberOfRowsInserted++;
			
			if(isset($dataArray[$i]['content'])){
				foreach($dataArray[$i]['content'] as $key => $count){
					$content = array();
					$content['pdfId'] = $pdfId; 
					$content['keyword'] = $key;
					$content['count'] = $count;
					$this->db->insert('pdfkeyword', $content);
				}
			}
		}
		return ($numberOfRowsInserted > 0) ? $numberOfRowsInserted : false;
	}
	
	/*this function gives the distinct ids from the articleKeyword table*/
	public function getIndexedArticles(){
		$resultArray = array();
		$this->db->select('articleId');
		$this->db->distinct();
		$query = $this->db->get('articlekeyword');
		$mediate = $query->result_array();
		for($i=0; $i<count($mediate); $i++){
			$resultArray[] = $mediate[$i]['articleId'];
		}
		return $resultArray;
	}
	
	/*this function gives list of articles which were not indexed before*/
	public function getNewArticles($indexedIds){
		$resultArray = array();
		$this->db->select('id');
		$this->db->from('articleinfo');
		if(count($indexedIds) > 0)
			$this->db->where_not_in('id', $indexedIds);
		$query = $this->db->get();
		$this->db->last_query();
		$mediate = $query->result_array();
		for($i=0; $i<count($mediate); $i++){
			$resultArray[] = $mediate[$i]['id'];
		}
		return $resultArray;
	}
	
	//This function save the retrieved article keywords
	public function saveArticleKeywords($data){
		return $this->db->insert('articlekeyword', $data);
	}
	
	/*this function check whether selected pdf is indexed*/
	public function getIdForPdfName($fileToDelete){
		$this->db->select('id');
		$this->db->where('fileName',$fileToDelete);
		$query = $this->db->get('pdfinfo');
		$mediate = $query->result_array();
		
		if(empty($mediate)) return false;
		else return $mediate[0]['id'];
	}
	
	/*this function delete indexing records*/
	public function deletePdfInfo($FileIdsArray){
		$this->db->where_in('id', $FileIdsArray);
		$this->db->delete('pdfinfo');
		return $this->db->affected_rows();
	}
	
	/*this function delete indexing records keywords*/
	public function deletePdfKeyword($FileIdsArray){
		$this->db->where_in('pdfId', $FileIdsArray);
		return $this->db->delete('pdfkeyword');
	}
	
	
	
}