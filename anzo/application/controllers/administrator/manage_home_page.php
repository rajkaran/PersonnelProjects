<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage_home_page extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->model('admin/home_page_model');
		$this->load->model('admin/list_model');
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('validate_form_data');
		$this->load->helper('populate_navigation');
		$this->load->library("pagination");
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
		if(isset($relatedInfo['backLink']))$pageData['backLink'] = $relatedInfo['backLink'];
		if(isset($relatedInfo['eventData']))$pageData['eventData'] = $relatedInfo['eventData'];
		if(isset($relatedInfo['links']))$pageData['links'] = $relatedInfo['links'];
		
		$this->load->view('admin/templates/header', $headerData);
		$this->load->view('admin/pages/'.$page, $pageData);
		$this->load->view('admin/templates/footer');
		
	}
	
	/*Load the home page view the default is creat new mode.*/
	public function loadHomePage($id=0,$action = "new"){
		$userName = $this->session->userdata('userName');
		
		//check whether session is not expired yet
		if($userName != ""){
			
			$userId = $this->session->userdata('userId');
			$eventList = $this->home_page_model->listEvent($userId);
				
			//admin can access all events but other users cant't.
			if($this->session->userdata('role') == "admin")
				$eventList = $this->home_page_model->dumpEventTable();
			
			//if showing an event in read mode (actually opens in edit mode are combined).
			if($action == "open"){
				$eventData = $this->home_page_model->getEvent($id);
				$headerInfo['eventData'] = $eventData;
			}
			
			$headerInfo['viewDescription'] = "Manage Home Page";
			$headerInfo['backLink'] = "administrator/login/profileLogIn";
			
			$stylesheet = "<link href='".base_url()."/css/admin/action.css' rel='stylesheet' type='text/css' />";
			$stylesheet .= "<link href='".base_url()."css/admin/home_page.css' rel='stylesheet' type='text/css' />\n";
			$stylesheet .= "<link href='".base_url()."css/admin/table.css' rel='stylesheet' type='text/css' />\n";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/formBuilder/ckeditor/ckeditor.js' ></script>";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/formBuilder/ckfinder/ckfinder.js' ></script>";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/admin/home_page.js' ></script>\n";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/validator.js' ></script>\n";
			
			$headerInfo['title'] = $headerInfo['viewDescription'];
			$headerInfo['isLoggedIn'] = true;
			$headerInfo['userName'] = ucwords($userName);
			
			//add pagination to the view 
			$return = addPages( base_url().'administrator/manage_home_page/loadHomePage/'.$id.'/'.$action, $eventList, 15, 6);
			
			$headerInfo['list'] = $return[0];
			$headerInfo['links'] = $return[1];
			
			$this->loadingView($stylesheet, "home_page_view", $headerInfo);
		}

		else{redirect('administrator/login/index');}
	}
	
	/*Create new event or update existing one*/
	public function saveEvent(){
		$response = "fail";
		$action = "";
		
		//If event id is set it means need to update existing event otherwise create new.
		if($_POST['eventId'] == ""){
			$data = array('userId ' => $this->session->userdata('userId'),
					'name' => $_POST['name'],
					'description' => $_POST['description'],
					'status' => true);
			if($this->home_page_model->createEvent($data) == 1){
				$response = "success";
				$action = "created";
			}
		}
		else{
			$data = array('name' => $_POST['name'],
					'description' => $_POST['description']);
			if($this->home_page_model->updateEvent($_POST['eventId'], $data) == 1){
				$response = "success";
				$action = "updated";
			}
		}
		
		echo json_encode(array("response" => $response, "action" => $action));
	}
	
	//Enable or disable events 
	public function enableOrDisable(){
		$rowsAffected = 0;
		
		for($i=0; $i<count($_POST['idArray']); $i++){
			if($_POST['action'] == "enable")
				$rowsAffected += $this->home_page_model->enableEvent($_POST['idArray'][$i], array('status' => true));
			else
				$rowsAffected += $this->home_page_model->disableEvent($_POST['idArray'][$i], array('status' => false));
		}
		
		if(count($_POST['idArray']) == $rowsAffected){
			echo json_encode(array("response" => "success"));
		}else echo json_encode(array("response" => "fail"));
		
	}
	

	
}