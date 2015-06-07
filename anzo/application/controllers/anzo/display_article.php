<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 
class Display_article extends CI_Controller {
	 
	 public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('populate_navigation');
		$this->load->helper('create_article_string');
		$this->load->library('session');
		$this->load->model('site/display_article_model');
		$this->load->model('admin/read_model');
		$this->load->model('admin/create_and_edit_model');
		$this->load->library('form_validation');
		$this->load->model('site/home_model');
		$this->load->model('admin/read_model');
	}
	
	
	/*This function loads the specific view*/
	public function loadingView($cssString, $page, $relatedInfo){
		$pageData = array();
		$headerData["scriptAndStyle"] = 
			"<link href='".base_url()."/css/site/header_footer.css' rel='stylesheet' type='text/css' />";
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
		
		if(isset($relatedInfo['articleList']))$pageData['articleList'] = $relatedInfo['articleList'];
		if(isset($relatedInfo['breadCrumb']))$pageData['breadCrumb'] = $relatedInfo['breadCrumb'];
		if(isset($relatedInfo['submitError']))$pageData['submitError'] = $relatedInfo['submitError'];
		
		$this->load->view('site/templates/header', $headerData);
		$this->load->view('site/pages/'.$page, $pageData);
		$this->load->view('site/templates/footer');
	}
	
	/*This funtion loads all the articles associated with a given 
	category id or subcategory id.*/
	public function loadArticles($id, $level="category", $articleId = 0){
		$stylesheet = "<link href='".base_url()."/css/site/display_article.css' rel='stylesheet' type='text/css' />\n";
		$stylesheet .= "<script type='text/javascript' src='".base_url()."js/site/slide_and_movement.js' ></script>\n";
		$pageInfo = array();
		
		if($level == "category"){
			$pageInfo['articleList'] = $this -> display_article_model -> getArticleList($id, "categoryId");
			$info = $this -> create_and_edit_model -> getCategoryForId($id);
			$pageInfo['title'] = $info['name'];
			$pageInfo['breadCrumb'] = $info['title']." > ".$info['name']." > <span></span>";
		}
		else{
			$pageInfo['articleList'] = $this -> display_article_model -> getArticleList($id, "subCategoryId");
			$info = $this -> create_and_edit_model -> getSubCategoryForId($id);
			$pageInfo['title'] = $info['name'];
			$pageInfo['breadCrumb'] = $info['title']." > ".$info['category']." > ".$info['name']." > <span></span>";
		}
		
		if($articleId != 0){
			unset($pageInfo['articleList']);
			$pageInfo['articleList'][0] = $articleId;
		}
		
		$pageInfo['titleArray'] = $this->create_and_edit_model->dumpTitleTable();
		$pageInfo['categoryArray'] = retrieveCategories($pageInfo['titleArray']);
		$pageInfo['subCategoryArray'] = retrieveSubCategories($pageInfo['categoryArray']);
		$pageInfo['indicatorArray'] = $this->create_and_edit_model->dumpIndicator();
		
		$this -> loadingView($stylesheet, "display_article_view", $pageInfo);
	}
	
	/*Create string of controls for articles. at the same time calculates it's 
	height and passes to the view.*/
	public function createRawArticle($articleId){
		//$id = $_POST['articleId'];
		$articleInfo = $this->read_model->getArticleInfoForId($articleId);
		echo json_encode(array("dataString" => getRawArticle($articleId, "submission"),
					"articleHeight" => $this->create_and_edit_model->maxTop($articleId),
					"isItForm" => $articleInfo['isItForm'],
					"name" => $articleInfo['articleName'] ));
	}
	
	
	
	
}


