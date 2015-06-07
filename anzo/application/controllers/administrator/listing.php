<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listing extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->model('admin/list_model');
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library("pagination");
		$this->load->helper('populate_navigation');
		$this->load->helper('validate_form_data');
		$this->load->helper('add_pages');
	}
	
	/*A common function to load views*/
	public function loadingView($cssString, $page, $relatedInfo){
		$pageData = array();
		$headerData["scriptAndStyle"] = 
			"<link href='".base_url()."css/admin/header_footer.css' rel='stylesheet' type='text/css' />";
		$headerData["scriptAndStyle"] .= $cssString;
		$headerData['viewDescription'] = $relatedInfo['viewDescription'];
		$headerData['isLoggedIn'] = $relatedInfo['isLoggedIn'];
		if(isset($relatedInfo['userName']))$headerData['userName'] = ucwords($relatedInfo['userName']);
		
		
		if(isset($relatedInfo['list']))$pageData['list'] = $relatedInfo['list'];
		if(isset($relatedInfo['links']))$pageData['links'] = $relatedInfo['links'];
		if(isset($relatedInfo['previousLevel']))$pageData['previousLevel'] = $relatedInfo['previousLevel'];
		if(isset($relatedInfo['backLink']))$pageData['backLink'] = $relatedInfo['backLink'];
		if(isset($relatedInfo['levelInfo']))$pageData['levelInfo'] = $relatedInfo['levelInfo'];
		if(isset($relatedInfo['thisLevel']))$pageData['thisLevel'] = $relatedInfo['thisLevel'];
		
		$this->load->view('admin/templates/header', $headerData);
		$this->load->view('admin/pages/'.$page, $pageData);
		$this->load->view('admin/templates/footer');
		
	}
	
	/*This function returns the merged list of categories and articles if the previousLevel 
	is categoryList otherwise only returns the article list*/
	private function createDescendantList($previousLevel, $id){
		$resultantList = array();
		
		if($previousLevel == "categoryList"){
			$subcategoryList = $this->list_model->listSubcategory($id);
			$articleList = $this->list_model->listarticle($id, 'categoryId');
			
			//add type to the every element of resultant 2D array/list. 
			for($i=0; $i<count($subcategoryList); $i++){
				$subcategoryList[$i]['type'] = "Sub Category";
			}
			
		}else $articleList = $this->list_model->listarticle($id, 'subCategoryId');
		
		//add type to the every element of resultant 2D array/list.
		for($i=0; $i<count($articleList); $i++){
			$articleList[$i]['type'] = "Article";
		}
		
		if($previousLevel == "categoryList"){
			$resultantList = array_merge($subcategoryList, $articleList);
		}else $resultantList = $articleList;
		
		return $resultantList;
	}
	
	/*returns the array of category Ids or sub categori Ids associated with given 
	list of title Ids Array*/
	public function getParentIdList($titleIdList, $forParent){
		$resultArray = array();
		$parentList = array();
		
		if($forParent == "category")
			$parentList = $this->list_model->getCategoryForTitle($titleIdList);
		else $parentList = $this->list_model->getSubCategoryForTitle($titleIdList);
		for($i=0; $i<count($parentList); $i++){
			$resultArray[$i]['id'] = $parentList[$i]['id'];
			$resultArray[$i]['parent'] = $parentList[$i]['name'];
			$resultArray[$i]['title'] = $parentList[$i]['title'];
		}
		
		return $resultArray;
	}
	
	/*loads list for different levels such as category, subcategory and article*/
	public function loadLevelList($previousLevel, $id=0){
		$userName = $this->session->userdata('userName');
		
		//check whether session is not expired yet
		if($userName != ""){
			$retrievedList = array();
			$levelInfo = array();
			$headerInfo = array();
			
			//get the list of titles user have access to.
			$titleIdList = $this->session->userdata('accessInfo');
			
			//if role admin than user gave access to all the titles.
			if($this->session->userdata('role') == "admin")
				$titleIdList = $this->list_model->dumpTitleTable();
			
			/*If id is not set means need to populate the 1 step of listing, which 
			comes just after clicking on icons at dashboard.*/
			if($id == 0){
				
				//set the variable for category listing	
				if($previousLevel == "categoryList"){
					$retrievedList = $this->list_model->getCategoryForTitle($titleIdList);
					
					for($i=0; $i<count($retrievedList); $i++){
						$retrievedList[$i]['subCategoryCount'] = $this->list_model->getSubCategoryCount($retrievedList[$i]['id']);
						$retrievedList[$i]['articleCount'] = $this->list_model->getArticleCount($retrievedList[$i]['id'], "categoryId");
					}
					
					$headerInfo['viewDescription'] = "Categories List";
				}
				
				//set the variable for sub category listing
				elseif($previousLevel == "sub-categoryList"){
					$retrievedList = $this->list_model->getSubCategoryForTitle($titleIdList);
					
					for($i=0; $i<count($retrievedList); $i++){
						$retrievedList[$i]['articleCount'] = $this->list_model->
										getArticleCount($retrievedList[$i]['id'], "subCategoryId");
					}
					
					$headerInfo['viewDescription'] = "Sub - Categories List";
				}
				//set the variable for article listing
				elseif($previousLevel == "articleList"){
					$categoryIdList = $this->getParentIdList($titleIdList, "category");
					$subCategoryIdList = $this->getParentIdList($titleIdList, "subCategory");
					
					$articleListForCategoryParent = $this->list_model->getArticleForTitle($categoryIdList, "category");
					$articleListForSubCategoryParent = $this->list_model->getArticleForTitle($subCategoryIdList, "subCategory");
					$retrievedList = array_merge($articleListForCategoryParent, $articleListForSubCategoryParent);
					//print_r($articleListForCategoryParent);
					$headerInfo['viewDescription'] = "Articles List";
				}
					
				$headerInfo['thisLevel'] = $previousLevel;
				$headerInfo['previousLevel'] = "";
				$headerInfo['backLink'] = "administrator/login/profileLogIn";
			}
			
			/*If id has been set then populate 2 or 3 step of listing which comes 
			after clicking particular category or subcategory*/
			else{
				
				/*If previous level was categoryList then retrieves the category info for 
				the given id and creates the combine list of sub categories and articles*/
				if($previousLevel == "categoryList"){
					$levelInfo = $this->list_model->categoryForId($id);
					
					if(checkTitleIsRanged($levelInfo['title']) == 1)
						$levelInfo['condition'] = $this->list_model->getIndicatorColour($levelInfo['condition']);
					else $levelInfo['condition'] = "N/A";
					
					$levelInfo['subCategoryCount'] = $this->list_model->getSubCategoryCount($id);
					$levelInfo['articleCount'] = $this->list_model->getArticleCount($id, "categoryId");
					
					//get the combine list of sub categories and articles
					$retrievedList = $this -> createDescendantList($previousLevel, $id);
					
					$headerInfo['thisLevel'] = "category";
					$headerInfo['previousLevel'] = $previousLevel;
					$headerInfo['backLink'] = "administrator/listing/loadLevelList/categoryList";
					$headerInfo['viewDescription'] = sliceString($levelInfo['name'], 30);
					$headerInfo['levelInfo'] = $levelInfo;
				}
				
				/*If the previous level was category or subcategoryList the retrieves the sub
				category info and creates the list of articles connected to this subcategory.*/
				else{
					$levelInfo = $this->list_model->subcategoryForId($id);
					
					if(checkTitleIsRanged($levelInfo['title']) == 1)
						$levelInfo['condition'] = $this->list_model->getIndicatorColour($levelInfo['condition']);
					else $levelInfo['condition'] = "N/A";
						
					$levelInfo['articleCount'] = $this->list_model->getArticleCount($id, "subCategoryId");
					
					//get the list of articles
					$retrievedList = $this -> createDescendantList($previousLevel, $id);
					
					if($previousLevel == "category")
						$headerInfo['backLink'] = 'administrator/listing/loadLevelList/categoryList/'.$levelInfo['categoryId'];
					else
						$headerInfo['backLink'] = 'administrator/listing/loadLevelList/'.$previousLevel;
					
					//sliceString is a populate_navigation helper function
					$headerInfo['viewDescription'] = sliceString($levelInfo['name'], 30);
					$headerInfo['thisLevel'] = "sub-category";
					$headerInfo['previousLevel'] = $previousLevel;
					$headerInfo['levelInfo'] = $levelInfo;
				}
			}
			
			$stylesheet = "<link href='".base_url()."css/admin/action.css' rel='stylesheet' type='text/css' />\n";
			$stylesheet .= "<link href='".base_url()."css/admin/list.css' rel='stylesheet' type='text/css' />\n";
			$stylesheet .= "<link href='".base_url()."css/admin/table.css' rel='stylesheet' type='text/css' />";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/admin/listing.js' ></script>\n";
			$headerInfo['title'] = $headerInfo['viewDescription'];
			$headerInfo['isLoggedIn'] = true;
			$headerInfo['userName'] = ucwords($userName);
			$headerInfo['webPageType'] = "dataList";
			
			//add pagination to the view from add pages helper 
			$return = addPages( base_url().'administrator/listing/loadLevelList/'.$previousLevel.'/'.$id, $retrievedList, 10, 6);
			$headerInfo['list'] = $return[0];
			$headerInfo['links'] = $return[1];
			
			$this->loadingView($stylesheet, "list_view", $headerInfo);
			
		}
		else{redirect('administrator/login/index');}
		
	}
	
	/*function to enable and disable item from the lists*/
	public function enableAndDisable(){
		$message = "fail";
		$idArray = array();
		if(isset($_POST['firstIdArray']) == true) $idArray = $_POST['firstIdArray'];
		$table = "";
		$affectedrows = 0;
		$secondIdArrayLength = 0;
		$data = array('status' => 0);
		
		if( $_POST['action'] == "enable") $data['status'] = 1;
		
		//enable or disable category
		if($_POST['level'] == "categoryList")
			$table = "category";
		
		//enable or disable article
		if($_POST['level'] == "sub-category" || $_POST['level'] == "category" || $_POST['level'] == "articleList")
			$table = "articleinfo";
		
		//enable or disable sub category
		if($_POST['level'] == "sub-categoryList")
			$table = "subcategory";
		
		//enable or disable one of category, sub category or article
		for($i=0; $i<count($idArray); $i++)
			$affectedrows += $this->list_model->updateStatus($idArray[$i], $table, $data);
		
		////enable or disable sub category from the particular category view
		if($_POST['level'] == "category"){
			if(isset($_POST['secondIdArray']) == true){
				$secondIdArrayLength = count($_POST['secondIdArray']);
				
				for($i=0; $i<count($_POST['secondIdArray']); $i++)
					$affectedrows += $this->list_model->updateStatus($_POST['secondIdArray'][$i], "subcategory", $data);
			}
		}
		
		if($affectedrows == count($idArray) + $secondIdArrayLength )
			$message = "success";
			
		echo json_encode(array("msg" => $message));
	}
	
	
}