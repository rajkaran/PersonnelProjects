<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage_user extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin/create_and_edit_model');
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->model('admin/user_model');
		$this->load->helper('email');
		$this->load->helper('form');
		$this->load->helper('string');
		$this->load->library('form_validation');
		$this->load->helper('validate_form_data');
		$this->load->helper('add_pages');
		$this->load->library('pagination');
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
		
		
		if(isset($relatedInfo['userList']))$pageData['userList'] = $relatedInfo['userList'];
		if(isset($relatedInfo['backLink']))$pageData['backLink'] = $relatedInfo['backLink'];
		if(isset($relatedInfo['titleArray']))$pageData['titleArray'] = $relatedInfo['titleArray'];
		if(isset($relatedInfo['userData']))$pageData['userData'] = $relatedInfo['userData'];
		if(isset($relatedInfo['action']))$pageData['action'] = $relatedInfo['action'];
		if(isset($relatedInfo['displayMsg']))$pageData['displayMsg'] = $relatedInfo['displayMsg'];
		if(isset($relatedInfo['links']))$pageData['links'] = $relatedInfo['links'];
		
		$this->load->view('admin/templates/header', $headerData);
		$this->load->view('admin/pages/'.$page, $pageData);
		$this->load->view('admin/templates/footer');
		
	}
	
	/*loads list for different levels such as category, subcategory and article*/
	public function loadUserList($id=0,$action = "list"){
		$userName = $this->session->userdata('userName');
		
		//check whether session is not expired yet
		if($userName != ""){
			
			//retrieve particular user info if id is set and action is not list.
			if($id != 0){
				
				$headerInfo['backLink'] = "administrator/manage_user/loadUserList";
				$headerInfo['titleArray'] = $this->create_and_edit_model->dumpTitleTable();
				$headerInfo['userData'] = $this->user_model->getUserData($id);
				$headerInfo['viewDescription'] = ucwords($headerInfo['userData']['userName']);
				
				$titleAllowed = array();
				foreach($this->user_model->getTitlesForUser($id) as $elementArray){
					$titleAllowed[] = $elementArray['title'];
				}
				
				$headerInfo['userData']['title'] = implode(",",$titleAllowed);
			}
			
			//if id is not set and action is list then retrieve the list of users.
			elseif($action == "list"){
				$headerInfo['backLink'] = "administrator/login/profileLogIn";
				$headerInfo['viewDescription'] = "List of Users";
				$userList = $this->user_model->listUser();
				
				for($i=0; $i<count($userList); $i++){
					$titleAllowed = array();
					foreach($this->user_model->getTitlesForUser($userList[$i]['id']) as $elementArray){
						$titleAllowed[] = $elementArray['title'];
					}
					$userList[$i]['title'] = implode(",",$titleAllowed);
				}
				
				//add pagination to the view 
				$return = addPages( base_url().'administrator/manage_user/loadUserList/'.$id.'/'.$action, $userList, 7, 6);
				$headerInfo['userList'] = $return[0];
				$headerInfo['links'] = $return[1];
			}
			
			//if id is not set and action is not list this means creating new user.
			elseif($action == "new"){
				$headerInfo['viewDescription'] = "Add New";
				$headerInfo['backLink'] = "administrator/manage_user/loadUserList";
				$headerInfo['titleArray'] = $this->create_and_edit_model->dumpTitleTable();
			}
			
			$stylesheet = "<link href='".base_url()."css/admin/action.css' rel='stylesheet' type='text/css' />";
			$stylesheet .= "<link href='".base_url()."css/admin/user.css' rel='stylesheet' type='text/css' />\n";
			$stylesheet .= "<link href='".base_url()."css/admin/table.css' rel='stylesheet' type='text/css' />\n";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/admin/application_user.js' ></script>\n";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/validator.js' ></script>\n";
			$headerInfo['isLoggedIn'] = true;
			$headerInfo['userName'] = ucwords($userName);
			
			$this->loadingView($stylesheet, "user_view", $headerInfo);
		}

		else{redirect('administrator/login/index');}
	}
	
	
	
	//Validate the user input data. Returns true if valid otherwise returns error string.
	private function isDataValid($inputArray){
		$isError = false;
		$errorString = "<ul>";
		
		if( isEmpty($inputArray['userName']) == true ){
			$isError = true;
			$errorString .= "<li>User Name is required field.</li>";
		}
		
		if( isEmpty($inputArray['password']) == true ){
			$isError = true;
			$errorString .= "<li>Password is required field.</li>";
		}
		
		if( isEqual($inputArray['password'], $inputArray['confirmPassword']) == false ){
			$isError = true;
			$errorString .= "<li>Password and Confirm Password fields has to be same.</li>";
		}
		
		if( isEmpty($inputArray['empId']) == true ){
			$isError = true;
			$errorString .= "<li>Employee Id is required field.</li>";
		}
		
		if( isNumber($inputArray['empId']) == false ){
			$isError = true;
			$errorString .= "<li>Employee Id is not a number.</li>";
		}
		
		if( count($inputArray['titles']) < 1){
			$isError = true;
			$errorString .= "<li>Please select at least one title.</li>";
		}
		
		if(isEmail($inputArray['emailId']) == false ){
			$isError = true;
			$errorString .= "<li>Email Id is not valid.</li>";
		}
		
		$errorString .= "</ul>";
		
		if($isError == true){
			return $errorString;
		} else return true;

	}
	
	/*Save the data of new event into the database*/
	public function saveUser(){
		$response = "fail";
		$returnMsg = "Some database error occured";
		$userInfo = $_POST['userInfo'];
		$isValid = $this->isDataValid($_POST['userInfo']);
		
		if($isValid === true){
			//no need to save confirm password field data into the database
			unset($userInfo['confirmPassword']);
			
			//if the value of hidden field userId is empty this means we are creating new user.
			if(isEmpty($_POST['userId']) === true ){
				$userInfo['password'] = base64_encode($userInfo['password']); 
				$userInfo['role'] = false; 
				$userInfo['status'] = true;
				
				//remove title from the user input because it will go into the access layer table.
				$titles =  $userInfo['titles'];
				unset($userInfo['titles']);
				
				$userId = $this->user_model->addNewUser($userInfo);
				if($this->user_model->mapTitlesToUser($userId, $titles) == true){
					$this->session->set_flashdata('displayMessage', 
								"<span> A new user ".$userInfo['userName']." has been created.</span>");
					$response = "success";
				}
			}
			else{
				$userInfo['password'] = base64_encode($userInfo['password']); 
				$userInfo['role'] = false; 
				$userInfo['status'] = true;
				$titles =  $userInfo['titles']; 
				unset($userInfo['titles']);
				if($this->user_model->updateUser($_POST['userId'], $userInfo) == 1 &&
					$this->user_model->mapTitlesToUser($_POST['userId'], $titles) == true ){
						
					$this->session->set_flashdata('displayMessage', "<span>User has been updated.</span>");
					$response = "success";
				}
			}
		}
		
		/*if input user data is not valid then returns the error string otherwise returns
		success as respnose and true as return message.*/	
		echo json_encode(array("response" => $response, "returnMsg" => $isValid));
	}
	
	/*Enabling or disabling users*/
	public function enableOrDisable(){
		$rowsAffected = 0;
		
		for($i=0; $i<count($_POST['idArray']); $i++){
			if($_POST['action'] == "enable")
				$rowsAffected += $this->user_model->enableUser($_POST['idArray'][$i], array('status' => true));
			else
				$rowsAffected += $this->user_model->disableUser($_POST['idArray'][$i], array('status' => false));
		}
		
		if(count($_POST['idArray']) == $rowsAffected){
			echo json_encode(array("response" => "success"));
		}else echo json_encode(array("response" => "fail"));
		
	}
	
	/*loading change password view*/
	public function loadChangePassword($displayMessage=""){
		$userName = $this->session->userdata('userName');
		
		//check whether session is not expired yet
		if($userName != ""){
				
			$stylesheet = "<link href='".base_url()."css/admin/password.css' rel='stylesheet' type='text/css' />\n";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/admin/password.js' ></script>\n";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/validator.js' ></script>\n";
			$headerInfo['isLoggedIn'] = true;
			$headerInfo['userName'] = ucwords($userName);
			$headerInfo['backLink'] = "administrator/login/profileLogIn";
			$headerInfo['viewDescription'] = "Change Password";
			$headerInfo['action'] = "change";
			if($displayMessage != "") $headerInfo['displayMsg'] = $displayMessage;
			
			$this->loadingView($stylesheet, "password_view", $headerInfo);
		}

		else{redirect('administrator/login/index');}
	}
	
	/*updating password in database*/
	public function changePassword(){
		$userName = $this->session->userdata('userName');
		
		//check whether session is not expired yet
		if($userName != ""){
			$userId = $this->session->userdata('userId');
			$match = $this->user_model->matchPassword($userId, base64_encode($_POST['old']) );
			
			//match value of old password field with the password in database for that user Id
			if(empty($match)== false ){
				
				if( $this->user_model->updatePassword($userId, 
							array("password" => base64_encode($_POST['new'])) ) == 1)
					$displayMessage = '<span>Password has been updated</span>';
			}
			else 
				$displayMessage = 'Old Password is incorrect';
			
			//reloading the change password view
			$this->loadChangePassword($displayMessage);	
		}
		else{redirect('administrator/login/index');}
	}
	
	/*load forget password view*/
	public function loadForgetPassword($displayMessage = ""){
		$stylesheet = "<link href='".base_url()."css/admin/password.css' rel='stylesheet' type='text/css' />\n";
		$stylesheet .= "<script type='text/javascript' src='".base_url()."js/admin/password.js' ></script>\n";
		$stylesheet .= "<script type='text/javascript' src='".base_url()."js/validator.js' ></script>\n";
		$headerInfo['backLink'] = "administrator/login/index";
		$headerInfo['viewDescription'] = "Forget Password";
		$headerInfo['action'] = "forget";
		$headerInfo['isLoggedIn'] = false;
		if($displayMessage != "") $headerInfo['displayMsg'] = $displayMessage;
			
		$this->loadingView($stylesheet, "password_view", $headerInfo);
		
	}
	
	public function sendPasswordThroughEmail($password, $email, $user){
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = '172.29.10.93';
		$config['smtp_port'] = '25';
		$config['smtp_timeout'] = '7';
		$config['charset']    = 'utf-8';
		$config['newline']    = "\r\n";
		$config['mailtype'] = 'html';
		
		$this->load->library( 'email', $config );
		
		$this->email->set_crlf( "\r\n" );
		$this->email->from( 'rchauhan@wgh.on.ca', 'Raj karan ' );
		$this->email->to( $email );
		
		$this->email->subject( 'Requested New Password' );
		$this->email->message( "<p>Here is the new Log in credentials for your The Artery "
					."account.We suggest you to change your password after Loging in.</p>"
					."<ul><li>User Name: ".$user."</li><li>Password: ".$password."</li></ul>" );
		
		// returning responses to the user
		if($this->email->send()) 
			return true;
		else{
			return "Email server error occured.";
			//return $this->email->print_debugger();
		}
	}
	
	/*updating password in database and sending it through email*/
	public function forgetPassword(){
		$match = $this->user_model->matchEmail(trim($_POST['email']) );
			
			//match value of old password field with the password in database for that user Id
			if(empty($match)== false ){
				$newPassword = random_string('alnum', 8);
				$isEmailSent = $this->sendPasswordThroughEmail($newPassword, trim($_POST['email']),
								 $match[0]['userName']);
				if($isEmailSent == true){
					if( $this->user_model->updatePassword($match[0]['id'], 
							array("password" => base64_encode($newPassword)) ) == 1)
						$displayMessage = '<span style="color:green;">Password has been sent to '
								.trim($_POST['email']).'</span>';
					else $displayMessage = "Some database error occured.";
				}else $displayMessage = $isEmailSent;
			}
			else 
				$displayMessage = 'There is no account registered for this email id '
							.'or may have deactivated, please contact Administrator.';
			
			//reloading the forget password view
			$this->loadForgetPassword($displayMessage);		
		
	}
	
	

	
}

