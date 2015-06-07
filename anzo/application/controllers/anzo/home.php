<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	 
	 public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('populate_navigation');
		$this->load->library('session');
		$this->load->library('extract_pdf_data');
		$this->load->library('search_pdf');
		$this->load->model('site/home_model');
		$this->load->library('form_validation');
		$this->load->model('admin/create_and_edit_model');
	}
	
	/*This function loads the specific view*/
	public function loadingView($cssString, $page, $relatedInfo){
		$pageData = array();
		$headerData["scriptAndStyle"] = 
			"<link href='".base_url()."/css/site/header_footer.css' rel='stylesheet' type='text/css' />\n";
		$headerData["scriptAndStyle"] .= 
			"<!--[if IE 8]><link href='".base_url()."/css/site/header_footer8.css' rel='stylesheet' type='text/css' /><![endif]-->";
		$headerData["scriptAndStyle"] .= 
			"<script type='text/javascript' src='".base_url()."js/site/search_and_indicator.js' ></script>\n";
		$headerData["scriptAndStyle"] .= $cssString;
		$headerData['title'] = $relatedInfo['title'];
		if(isset($relatedInfo['titleArray']))$headerData['titleArray'] = $relatedInfo['titleArray'];
		if(isset($relatedInfo['categoryArray']))$headerData['categoryArray'] = $relatedInfo['categoryArray'];
		if(isset($relatedInfo['subCategoryArray']))$headerData['subCategoryArray'] = $relatedInfo['subCategoryArray'];
		if(isset($relatedInfo['indicatorArray']))$headerData['indicatorArray'] = $relatedInfo['indicatorArray'];
		
		if(isset($relatedInfo['events']))$pageData['events'] = $relatedInfo['events'];
		if(isset($relatedInfo['screenMsg']))$pageData['screenMsg'] = $relatedInfo['screenMsg'];
		if(isset($relatedInfo['searchResult']))$pageData['searchResult'] = $relatedInfo['searchResult'];
		if(isset($relatedInfo['startTime']))$pageData['startTime'] = $relatedInfo['startTime'];
		
		$this->load->view('site/templates/header', $headerData);
		$this->load->view('site/pages/'.$page, $pageData);
		$this->load->view('site/templates/footer');
	}
	
	//loading search result view for the information provided 
	public function loadSearchResultView($searchType, $searchString){
		//recording the time when request made
		$mtime = microtime();
		$mtime = explode(" ",$mtime);
		$mtime = $mtime[1] + $mtime[0];
		$pageInfo['startTime'] = $mtime;
		
		$displaySearchType = ($searchType == "pdf")? "Pdf" : "Web page";
		$searchString = urldecode($searchString);
		
		$stylesheet = "<link href='".base_url()."/css/site/search_result.css' rel='stylesheet' type='text/css' />";
		$pageInfo['title'] = $searchString." - Artery Search";
		
		$pageInfo['titleArray'] = $this->create_and_edit_model->dumpTitleTable();
		$pageInfo['categoryArray'] = retrieveCategories($pageInfo['titleArray']);
		$pageInfo['subCategoryArray'] = retrieveSubCategories($pageInfo['categoryArray']);
		$pageInfo['indicatorArray'] = $this->create_and_edit_model->dumpIndicator();
		
		if($searchString != ""){
			$returnInfo = $this->searchPdfOrArticle($searchString, $searchType);
			if($returnInfo[0] == "success"){
				$pageInfo['screenMsg'] = "Showing ".$displaySearchType." results for <span>".$searchString."</span>";
				$pageInfo['searchResult'] = ($searchType == "pdf")?
										$this->home_model->getPdfInfoForId($returnInfo[1]):
										$this->home_model->getArticleInfoForId($returnInfo[1]);
						
			}else{
				$pageInfo['screenMsg'] = $returnInfo[1];
				$pageInfo['searchResult'] = array();
			}
			$this -> loadingView($stylesheet, "search_result_view", $pageInfo);
		}
		else{
			$pageInfo['screenMsg'] = "Search string is missing.";
			$this -> loadingView($stylesheet, "search_result_view", $pageInfo);
		}
	}
	
	/*This function searches the pdf or web page for given search string*/
	public function searchPdfOrArticle($inputSearchString, $searchType){
		$searchStringkeyword = $this->search_pdf -> parseSearchString($inputSearchString);
		
		if(count($searchStringkeyword) == 0){
			//when input string don,t have any keyword and parseSearchString has returned empty array
			return array("fail", "We didn't find any match, Please try other keywords.");
		}
		else{
			$idArray = array();
			$tableData = ($searchType == "pdf")?$this->home_model->dumpPdfInfoTable():$this->home_model->dumpArticleInfoTable();
			$idArray = $this->search_pdf -> infoBasedRanking($tableData, $searchStringkeyword);	
			
			$lastIndex = count($idArray)-1;
			//search array matched 100% with fileName, Title or Keyword.
			if($idArray[$lastIndex] === true){
				return array("success", $idArray);
			}
			
			//search array didn't matched any information So now completely rely on content matching
			else if($idArray[$lastIndex] === false && count($idArray) == 1 ){
				$contentHits = ($searchType == "pdf")?
							$this->home_model->matchContent(array_keys($searchStringkeyword)):
							$this->home_model->matchArticleContent(array_keys($searchStringkeyword));
						
				$idsArray = $this->search_pdf -> parseContentArray($contentHits, array_keys($searchStringkeyword));
				return array("success", $idsArray);
			}
			
			//search array matched but not 100% with any information So now jump into the pdf contents
			else if($idArray[$lastIndex] === false){
				$contentHits = ($searchType == "pdf")?
							$this->home_model->matchContentForId($idArray, array_keys($searchStringkeyword)):
							$this->home_model->matchArticleContentForId($idArray, array_keys($searchStringkeyword));
						
				$idsArray = $this->search_pdf -> contentBasedRanking($contentHits, array_keys($searchStringkeyword));
				return array("success", $idsArray);
			}
		}//end of else
	}
	
	/*loading the Home page view and passing requierd data to the page.
	passing three 2D arrays TitleArray, categoryArray and subCategoryArray*/
	public function index(){
		
		//echo ini_get('upload_max_filesize');
		//echo phpinfo();
		
		$stylesheet = "<link href='".base_url()."/css/site/home.css' rel='stylesheet' type='text/css' />\n";
		$stylesheet .= "<script type='text/javascript' src='".base_url()."js/site/event.js' ></script>\n";
		$pageInfo['title'] = 'Home';
		$pageInfo['titleArray'] = $this->create_and_edit_model->dumpTitleTable();
		$pageInfo['categoryArray'] = retrieveCategories($pageInfo['titleArray']);
		$pageInfo['indicatorArray'] = $this->create_and_edit_model->dumpIndicator();
		$pageInfo['subCategoryArray'] = retrieveSubCategories($pageInfo['categoryArray']);
		$pageInfo['events'] = $this -> home_model -> getEventList();
		
		$this -> loadingView($stylesheet, "home_view", $pageInfo);
	} 
  
	/*this function returns the event description for event id*/
	public function getEventDescription(){
		echo json_encode(array("msg" => "success", 
			"desc" => $this -> home_model -> getEventDescription($_POST['eventId']) ));
	}
	
	
	
	
	
}


