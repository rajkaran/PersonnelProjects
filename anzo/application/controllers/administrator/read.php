<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*This brings up the list of category, sub category and articles and also opens
category and subcategory in read mode.


*/
class Read extends CI_Controller {
	
	private $articlePdfPathRelative = "../../../../../../../pdfDirectory/articlePdf";
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin/read_model');
		$this->load->model('admin/list_model');
		$this->load->model('admin/create_and_edit_model');
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library("pagination");
		$this->load->library('extract_pdf_data');
		$this->load->helper('email');
		$this->load->helper('populate_navigation');
		$this->load->helper('create_article_string');
		$this->load->helper('create_delete_pdf');
		$this->load->helper('validate_form_data');
	}
	
	/*This function loads the specific view*/
	public function loadingView($cssString, $page, $relatedInfo){
		$pageData = array();
		$headerData["scriptAndStyle"] = 
			"<link href='".base_url()."css/admin/header_footer.css' rel='stylesheet' type='text/css' />";
		$headerData["scriptAndStyle"] .= $cssString;
		$headerData['viewDescription'] = $relatedInfo['viewDescription'];
		$headerData['isLoggedIn'] = $relatedInfo['isLoggedIn'];
		if(isset($relatedInfo['userName']))$headerData['userName'] = ucwords($relatedInfo['userName']);
		
		if(isset($relatedInfo['articleId']))$pageData['articleId'] = $relatedInfo['articleId'];
		if(isset($relatedInfo['previousLevel']))$pageData['previousLevel'] = $relatedInfo['previousLevel'];
		if(isset($relatedInfo['backLink']))$pageData['backLink'] = $relatedInfo['backLink'];
		if(isset($relatedInfo['thisLevel']))$pageData['thisLevel'] = $relatedInfo['thisLevel'];
		if(isset($relatedInfo['articleData']))$pageData['articleData'] = $relatedInfo['articleData'];
		if(isset($relatedInfo['pdfLink']))$pageData['pdfLink'] = $relatedInfo['pdfLink'];
		
		$this->load->view('admin/templates/header', $headerData);
		$this->load->view('admin/pages/'.$page, $pageData);
		$this->load->view('admin/templates/footer');
		
	}
	
	/*This function loads the article in the read mode*/
	public function articleReadMode($level,$articleId){
		$userName = $this->session->userdata('userName');
		
		//check whether session is not expired yet
		if($userName != ""){
			
			$headerInfo['articleData'] = $this->createRawArticle($articleId);
			$articleInfo = $this->read_model->getArticleInfoForId($articleId);
			$headerInfo['viewDescription'] = sliceString($articleInfo['articleName'], 30);
			
			$headerInfo['pdfLink'] = "";
			if($articleInfo['havePdfVersion'] == 1){
				$fileName = $this->extract_pdf_data-> parseFileName($articleInfo['articleName']);
				$headerInfo['pdfLink'] = $this->articlePdfPathRelative."/".$fileName.".pdf";
			}
			
			//add required scripts and style sheets
			$stylesheet = "<link href='".base_url()."css/admin/action.css' rel='stylesheet' type='text/css' />\n";
			$stylesheet .= "<link href='".base_url()."css/admin/read.css' rel='stylesheet' type='text/css' />\n";
			$stylesheet .= "<link href='".base_url()."js/formBuilder/css/jquery-ui.css' rel='stylesheet' type='text/css' />\n";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/formBuilder/jquery-ui.js' ></script>\n";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/admin/read.js' ></script>\n";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/validator.js' ></script>\n";
			
			//send required data to page
			$headerInfo['isLoggedIn'] = true;
			$headerInfo['userName'] = ucwords($userName);
			$headerInfo['thisLevel'] = "article";
			$headerInfo['previousLevel'] = $level;
			$headerInfo['articleId'] = $articleId;
			
			if($level == "category")
				$headerInfo['backLink'] = "administrator/listing/loadLevelList/".$level."/".$articleInfo['subCategoryId'];
				
			if($level == "sub-categoryList")
				$headerInfo['backLink'] = "administrator/listing/loadLevelList/".$level."/".$articleInfo['subCategoryId'];
			
			if($level == "categoryList")
				$headerInfo['backLink'] = "administrator/listing/loadLevelList/".$level."/".$articleInfo['categoryId'];
			
			if($level == "articleList")
				$headerInfo['backLink'] = "administrator/listing/loadLevelList/".$level;
				
			$this->loadingView($stylesheet, "read_view", $headerInfo);
			
		} else{redirect('administrator/login/index');}

	}
	
	/*This function retrieves the specific article's data for given article id
	from the database*/
	public function createRawArticle($id){
		if(isset($_POST['data']) ==true)
			echo json_encode(array("dataString" => getRawArticle($id)));
		else return getRawArticle($id);
	}
	
	//This function gives all the information related to specific article for given id
	public function getArticleSetting(){
		$id = $_POST['articleId'];
		$sendToString = "";
		$copyToString = "";
		$articleInfo = $this->read_model->getArticleInfoForId($id);
		if($articleInfo["connectedTo"] == 1)
			$articleInfo["category"] = $this->read_model->getName($articleInfo["categoryId"],"category");
		else $articleInfo["subCategory"] = $this->read_model->getName($articleInfo["subCategoryId"], "subcategory");
		
		if($articleInfo["setToEmail"] == 1){
			$recipient = $this->read_model->getEmailReceipient($id);
			for($i=0; $i<count($recipient); $i++){
				if($recipient[$i]["sendTo"] == "")
					$copyToString .= ($copyToString == "")?$recipient[$i]["copyTo"]:", ".$recipient[$i]["copyTo"];
				else 
					$sendToString .= ($sendToString === "")?$recipient[$i]["sendTo"]:', '.$recipient[$i]["sendTo"];
			}
		}
		
		$articleInfo["sendTo"] = $sendToString;
		$articleInfo["copyTo"] = $copyToString;
		
		echo json_encode($articleInfo);
	}
	
	/*This function returns the list of categories and subcategories falls 
	under the titles accessible to current user.*/
	public function getParentsList(){
		$parentList = array();
		$connectedTo = $_POST['data'];
			
		//get the list of titles user have access to.
		$titleIdList = $this->session->userdata('accessInfo');
		
		//if role admin than user gave access to all the titles.
		if($this->session->userdata('role') == "admin")
			$titleIdList = $this->list_model->dumpTitleTable();
			
		$parentList = $this->read_model->getList($titleIdList ,'titleId', 'category');
		
		//if parent is subcategory then get the subcategories for accessible titles.	
		if($connectedTo != "category"){
			$categoryIdList = array();
			for($i=0; $i<count($parentList); $i++){
				$categoryIdList[] = $parentList[$i]['id'];
			}
			
			$parentList = $this->read_model->getList($categoryIdList ,'categoryId', 'subcategory');
		}
		
		echo json_encode($parentList);
	}
	
	/*This function accepts the article id and returns path to root and parsed file name*/
	private function getRootArticleName($articleId){
		$articleInfo = $this->read_model->getArticleInfoForId($articleId);
		return array("fileName" => $this->extract_pdf_data->parseFileName($articleInfo['articleName']),
					"root" => $this->extract_pdf_data->getPathToRoot(),
					"havePdfVersion" => $articleInfo['havePdfVersion']);
	}
	
	/*This function creates pdf of current article and saves it in 
	pdfDirectory/arteryPdf directory. At the same time updates the flag in database*/
	public function makePdf(){
		$content = $_POST['data'];
		$id = $_POST['articleId'];
		
		$rootAndFile = $this->getRootArticleName($id);
		$fileName = $rootAndFile['fileName'];
		$root = $rootAndFile['root'];
		$path = $root."/pdfDirectory/articlePdf";
		
		$iscreated = createPdf($path, $fileName, $content);
		if($iscreated === true ){
			if($this->read_model->updatePdfExist($id, $data = array("havePdfVersion" => true)) == 1)
				echo json_encode(array("msg" => "success", 
					"path" => $this->articlePdfPathRelative."/".$fileName.".pdf"));
			else json_encode(array("msg" => "fail", "error" => "Not updated in database"));
		}
		else echo json_encode(array("msg" => "fail", "error" => $iscreated));
		
	}
	
	/*This function deletes the pdf from the pdfDirectory/arteryPdf 
	directory and updates the flag in database */
	public function disposePdf(){
		$id = $_POST['articleId'];
			
		$rootAndFile = $this->getRootArticleName($id);
		$fileName = $rootAndFile['fileName'];
		$root = $rootAndFile['root'];
		$path = $root."/pdfDirectory/articlePdf";
		
		$isRemoved = removePdf($path, $fileName);  
		
		if ($isRemoved == true) {
			if($this->read_model->updatePdfExist($id, $data = array("havePdfVersion" => false)) == 1)
				echo json_encode(array("msg" => "success"));
			else json_encode(array("msg" => "fail", "error" => "Not updated in database."));
		} 
		else echo json_encode(array("msg" => "fail", "error" => "The target file does not exist in targeted directory."));
		
	}
	
	/*This function emails current article on the given email Id. If article has Pdf 
	version then that existing version sends an an attchment, otherwise creates new 
	pdf and removes it ones sent.*/
	public function sendEmail(){
		$htmlOfArticle = $_POST['data'];
		$id = $_POST['articleId'];
		$to = strtolower(trim($_POST['email']));
		$response = "fail";
		$msg = "Email id is not valid";
		
		if(isEmail($to) == true ){
			$rootAndFile = $this->getRootArticleName($id);
			$fileName = $rootAndFile['fileName'];
			$root = $rootAndFile['root'];
			$path = $root."/pdfDirectory/temp";
			
			//if article has a pdf version.
			if($rootAndFile['havePdfVersion'] == 0) createPdf($path, $fileName, $htmlOfArticle);
			else $path = $root."/pdfDirectory/articlePdf";
			
			$config['protocol'] = 'smtp';
			$config['smtp_host'] = '172.29.10.93';
			$config['smtp_port'] = '25';
			$config['smtp_timeout'] = '7';
			$config['charset']    = 'utf-8';
			$config['newline']    = "\r\n";
			$config['mailtype'] = 'text'; // or html
			
			$this->load->library( 'email', $config );
			
			$this->email->from( 'rchauhan@wgh.on.ca', 'Raj karan ' );
			$this->email->to( $to );
			
			$this->email->subject( 'The article You have requested' );
			$this->email->message( "The attached pdf is the Article that you requested."
							." Please ignore if you are not the intented receipient." );
			$this->email->attach($path."/".$fileName.".pdf");
			
			if($this->email->send()) {
				$response = "success";
				$msg = "<span>Email sent to ".$to. " id.</span>";
			}
			else $msg = "System failed to send email to ".$to. " id.";
			
			//if article has a pdf version.
			if($rootAndFile['havePdfVersion'] == 0) removePdf($path, $fileName);
		}
		
		echo json_encode(array("response" => $response, "msg" => $msg));
	}
	
	/*This function takes the comma separated email Ids and returns the array of email Ids,
	only if all csv values were valid email ids other wise returns false.*/
	private function parseRecipients($csvEmails){
		$resultArray = array();
		$csvEmails = trim($csvEmails);
		$emailArray = explode(",",$csvEmails);
		
		$emailArray = array_values(array_filter($emailArray));
		
		for($i=0; $i<count($emailArray); $i++){
			//if any csv email Id is invalid halts the execution.
			if (isEmail($emailArray[$i]) == false) return false;
			$resultArray[] = trim($emailArray[$i]);
		}
		
		return $resultArray;
	}
	
	//Update article settings/Info
	public function updateSetting(){
		$articleId = $_POST['articleId'];
		$settingArray = $_POST['setting'];
		$isError = false;
		$errorMsg = "";
		
		if(isEmpty($settingArray['parentName']) == true || isEmpty($settingArray['articleName']) == true){
			$isError = true;
			$errorMsg = "Parent Name or Article name is missing.";
		}
		
		$settingArray['articleName'] = removeSpecialChar(trim($settingArray['articleName']));
		
		if($settingArray['connectedTo'] == "category"){
			$settingArray['connectedTo'] = true;
			$result = $this->read_model->getParentForCategoryName(trim($settingArray['parentName']) );
			$settingArray['categoryId'] = $result['categoryId'];
			$settingArray['subCategoryId'] = null;
		}
		else{
			$settingArray['connectedTo'] = false;
			$result = $this->read_model->getParentForSubCategoryName(trim($settingArray['parentName']) );
			$settingArray['subCategoryId'] = $result['subCategoryId'];
			$settingArray['categoryId'] = null;
		}
		
		$rootAndFileName = $this->getRootArticleName($articleId);
		if($rootAndFileName['havePdfVersion'] == 1){
			$oldFileName = $rootAndFileName['fileName'];
			$newFileName = $this->extract_pdf_data-> parseFileName(trim($settingArray['articleName']));
			$root = $rootAndFileName['root'];
			$path = $root."/pdfDirectory/articlePdf/";
			rename($path.$oldFileName.".pdf",$path.$newFileName.".pdf");
		}
		
		//converting to boolean datatype
		if($settingArray['isItForm'] === 'true')
			$settingArray['isItForm'] = true;
		else $settingArray['isItForm'] = false;
		
		if($settingArray['setToEmail'] === 'true')
			$settingArray['setToEmail'] = true;
		else $settingArray['setToEmail'] = false;
		
		//check if article with same name already exist
		$articleInfo = $this->create_and_edit_model->duplicateNameExist($settingArray['articleName'], "articleName", "articleinfo");
		if($articleInfo == false || $articleInfo[0]['id'] == $articleId){
			if($settingArray['setToEmail'] === true){
				//check if user had provided valid email ids for send To field
				if(isset($settingArray['sendTo']) == true && $settingArray['sendTo'] != ""){
					$sendToArray = $this -> parseRecipients($settingArray['sendTo']);
					if($sendToArray != false && $isError === false)
						$this->read_model->setSendTo($articleId, $sendToArray);
					else{
						$isError = true;
						$errorMsg .= "All the sendTo email ids are not valid.<br />";
					}
				}
				else{
					$isError = true;
					$errorMsg .= "-You have indicated that form need to be emailed, but you hadn't mentioned the receipients.<br />";
				}
				
				//check if user had provided valid email ids for copy To field
				if(isset($settingArray['copyTo']) == true && $settingArray['copyTo'] != ""){
					$copyToArray = $this -> parseRecipients($settingArray['copyTo']);
					if($copyToArray != false && $isError === false)
						$this->read_model->setCopyTo($articleId, $copyToArray);
					else{
						$isError = true;
						$errorMsg .= "All the copyTo email ids are not valid.<br />";
					}
				}
				elseif($isError === false)
					$this->read_model->unsetCopyTo($articleId);
				
				unset($settingArray['sendTo']);
				unset($settingArray['copyTo']);
			}
			elseif($settingArray['setToEmail'] == false){
				$this->read_model->unsetEmail($articleId);
			}
		
		}else{
			$isError = true;
			$errorMsg .= "Article with the same name already exist.";
		}
		unset($settingArray['parentName']);
		if($isError === false){
			if($this->read_model->updateSetting($articleId, $settingArray) == 1)
				echo json_encode(array("response" => "success", 
								"msg" => "<span>Settings have been updated, wait untill system reload your settings.</span>"));
		}
		
		else echo json_encode(array("response" => "fail", 
							"msg" => $errorMsg));
	}
	
	
	
	
	
}