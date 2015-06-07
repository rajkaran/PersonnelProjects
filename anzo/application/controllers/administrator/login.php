<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->model('admin/authorisation_and_authentication');
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
	}
	
	//This function loads the specific view
	public function loadingView($cssString, $page, $relatedInfo){
		$pageData = array();
		$headerData["scriptAndStyle"] = 
			"<link href='".base_url()."css/admin/header_footer.css' rel='stylesheet' type='text/css' />";
		$headerData["scriptAndStyle"] .= $cssString;
		$headerData['title'] = $relatedInfo['title'];
		$headerData['viewDescription'] = $relatedInfo['viewDescription'];
		$headerData['isLoggedIn'] = $relatedInfo['isLoggedIn'];
		if(isset($relatedInfo['userName']))$headerData['userName'] = ucwords($relatedInfo['userName']);
		
		if(isset($relatedInfo['accessInfo']))$pageData['accessInfo'] = $relatedInfo['accessInfo'];
		
		$this->load->view('admin/templates/header', $headerData);
		$this->load->view('admin/pages/'.$page, $pageData);
		$this->load->view('admin/templates/footer');
	}
	
	//loading the login_view
	public function index(){
		$stylesheet = "<link href='".base_url()."css/admin/login.css' rel='stylesheet' type='text/css' />";
		$pageInfo['title'] = 'Log In';
		$pageInfo['viewDescription'] = 'Administrator Log In';
		$pageInfo['isLoggedIn'] = false;
		$this -> loadingView($stylesheet, "login_view", $pageInfo);
	}
	
	/*This function validates the user entered credentials. If empty field is submitted 
	then returns error. If incorrect credentials are submitted then returns error. If 
	credentials are correct then start a new sesssion, set username and redirect to the dashboard*/
	public function validate_credentials(){
		$this->form_validation->set_rules('userName', 'User Name', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		
		if ($this->form_validation->run() === FALSE){
			$this->session->set_flashdata('loginError', 
				'Log In Failed!!<br />User Name and Password did not received.');
			redirect('administrator/login/index');
		}
		else{
			$data['userInfo'] = $this->authorisation_and_authentication->userExist();
			
			if(empty($data['userInfo'])) {
				$this->session->set_flashdata('loginError', 
				'Log In Failed!!<br />If it happens again try "Forget Password" or contact I.T. Depart.');
			redirect('administrator/login/index');
			}
			else {
				$this->session->set_userdata('userName', $data['userInfo'][0]['userName']);
				$this->session->set_userdata('userId', $data['userInfo'][0]['id']);
				$this->session->set_userdata('role', ($data['userInfo'][0]['role'] == 0) ? "user" : "admin");
				
				redirect('administrator/login/profileLogIn');
			}
		}
		
	}
	
	/*This function loads the the Dashboard view and retrieves the access priveleges
	of the current user and set a session variable*/
	public function profileLogIn(){
		$userName = $this->session->userdata('userName');
		if($userName != ""){
			$accessInfo = array();
			if($this->session->userdata('role') != "admin")
				$accessInfo = $this->authorisation_and_authentication->userAuthorised($this->session->userdata('userId'));
			$this->session->set_userdata('accessInfo', $accessInfo);	
			
			$stylesheet = "<link href='".base_url()."css/admin/dashboard.css' rel='stylesheet' type='text/css' />";
			$headerInfo['title'] = 'DashBoard';
			$headerInfo['viewDescription'] = 'DashBoard';
			$headerInfo['isLoggedIn'] = true;
			$headerInfo['userName'] = ucwords($userName);
			$headerInfo['accessInfo'] = $accessInfo;
			$this -> loadingView($stylesheet, "dashboard", $headerInfo);
		}
		else{redirect('administrator/login/index');}
	}
	
	//This function destroys all the sessions information when user logged out.
	public function loggingOut(){
		$this->session->sess_destroy();
		redirect('administrator/login/index');
	}
	
}

