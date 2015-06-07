<?php 	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Form_submission extends CI_Controller {
	 
	 public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('validate_form_data');
		$this->load->helper('email');
		$this->load->helper('create_delete_pdf');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->library('extract_pdf_data');
		$this->load->model('site/form_submission_model');
		$this->load->model('admin/track_submission_model');
		$this->load->model('site/display_article_model');
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
		
		if(isset($relatedInfo['articleList']))$pageData['articleList'] = $relatedInfo['articleList'];
		if(isset($relatedInfo['breadCrumb']))$pageData['breadCrumb'] = $relatedInfo['breadCrumb'];
		
		$this->load->view('site/templates/header', $headerData);
		$this->load->view('site/pages/'.$page, $pageData);
		$this->load->view('site/templates/footer');
	}
	/* This method populates the body of email with the data submitted.	*/	public function collectInputData($id, $idAndDataArray){				$tagString = "";				$radioCovered = [];				$controlsArray = $this->read_model->getInteractiveControlList($id);								for($i=0; $i<count($controlsArray); $i++){							if($controlsArray[$i]['tagName'] == "input"){							$inputArray = $this->read_model->getInputInfo($controlsArray[$i]['id'], "input");				if($inputArray['type'] == "radio" ){					if( !in_array($inputArray['name'], $radioCovered) ){						array_push($radioCovered,$inputArray['name']);						$tagString .= "<div>"										."<div style='display:table-cell'><strong>".$inputArray['name']."</strong>&nbsp;-&nbsp;</div>"										."<div style='display:table-cell'>".$idAndDataArray[$controlsArray[$i]['id']]."</div>"									."</div>";					}									}				else{										$tagString .= "<div>"									."<div style='display:table-cell'><strong>".$inputArray['name']."</strong>&nbsp;-&nbsp;</div>"									."<div style='display:table-cell'>".$idAndDataArray[$controlsArray[$i]['id']]."</div>"								."</div>";				}							}						elseif($controlsArray[$i]['tagName'] == "textarea"){				$inputArray = $this->read_model->getTextAreaInfo($controlsArray[$i]['id'], "textarea");				$tagString .= "<div>"								."<div style='display:table-cell'><strong>".$inputArray['name']."</strong>&nbsp;-&nbsp;</div>"								."<div style='display:table-cell;'>".$idAndDataArray[$controlsArray[$i]['id']]."</div>"							."</div>";			}						elseif($controlsArray[$i]['tagName'] == "select"){				$selectArray = $this->read_model->getSelectInfo($controlsArray[$i]['id'], "selecttag");				$tagString .= "<div>"								."<div style='display:table-cell'><strong>".str_replace( "[]", "", trim($selectArray['name']) )."</strong>&nbsp;-&nbsp;</div>"								."<div style='display:table-cell;'>".$idAndDataArray[$controlsArray[$i]['id']]."</div>"							."</div>";			}		}		 		return $tagString;	}
	
	/*This function retrieves the specific article's data for given article id
	from the database and place values of the elements submitted by user*/
	public function getArticleData($id, $idAndDataArray){
		$tagString = "";
		
		$controlsArray = $this->read_model->getControlList($id);
		
		for($i=0; $i<count($controlsArray); $i++){
			
			if($controlsArray[$i]['tagName'] == "label"){
				$labelData = $this->read_model->getControlInfo($controlsArray[$i]['id'], "label");
				
				$tagString .= "<label id='".$controlsArray[$i]['tagId']."' class = 'item' for='"
				.$labelData['forValue']."' style='top:".$controlsArray[$i]['yAxis']
				.";left:".$controlsArray[$i]['xAxis'].";font-size:".$labelData['fontSize']
				.";color:".$labelData['color'].";position:absolute;"
				."font-family:Tahoma;width:auto; height:auto;text-align:left;'>"
				.$labelData['innerText']."</label> ";
			}
			elseif($controlsArray[$i]['tagName'] == "input"){
				$inputArray = $this->read_model->getInputInfo($controlsArray[$i]['id'], "input");
				
				if($inputArray['type'] == "radio" || $inputArray['type'] == "checkbox"){
					$tagString .= "<input type='".$inputArray['type']."' name='"
					.$inputArray['name']."' id='".$controlsArray[$i]['tagId']
					."' class='item' value='".$inputArray['value']
					."' style='position:absolute;top:".$controlsArray[$i]['yAxis']
					.";left:".$controlsArray[$i]['xAxis'].";' ";
						
					if($inputArray['value'] == $idAndDataArray[$controlsArray[$i]['id']])
						$tagString .= "checked = 'checked' ";
					
					$tagString .= "/>";
					
				}
				
				else{
					$tagString .= "<input type='".$inputArray['type']."' name='"
					.$inputArray['name']."' id='".$controlsArray[$i]['tagId']
					."' class='item' style='position:absolute;top:".$controlsArray[$i]['yAxis']
					.";left:".$controlsArray[$i]['xAxis'].";height:"
					.$controlsArray[$i]['height'].";width:".$controlsArray[$i]['width']
					.";font-size:".$inputArray['fontSize'].";border:1px solid black;' "
					."value='".$idAndDataArray[$controlsArray[$i]['id']]."'";
					
					if($inputArray['required'] == 1)
						$tagString .= "required = 'required' ";
						
					$tagString .= "/>";
				}
				
			}
			elseif($controlsArray[$i]['tagName'] == "select"){
				$selectArray = $this->read_model->getSelectInfo($controlsArray[$i]['id'], "selecttag");
				
				$tagString .= "<select id='".$controlsArray[$i]['tagId']."' name='"
				.$selectArray['name']."' class='item' size='".$selectArray['size']
				."' style='position:absolute; top:".$controlsArray[$i]['yAxis']."; left:"
				.$controlsArray[$i]['xAxis'].";height:".$controlsArray[$i]['height'].";width:".$controlsArray[$i]['width'].";' ";
				
				if($selectArray['required'] == 1)
						$tagString .= "required = 'required' ";
				
				if($selectArray['multiple'] == 1)
						$tagString .= "multiple = 'multiple' ";
				
				$tagString .= ">";
				
				$optionList = $this->read_model->getOptionInfo($selectArray['controlId']);
				$optionDataArray = explode(",", $idAndDataArray[$controlsArray[$i]['id']]);
				for($j=0; $j<count($optionList); $j++){
					$tagString .= "<option value='".$optionList[$j]['value']."' ";
					
					if(in_array($optionList[$j]['value'], $optionDataArray) == true)
						$tagString .= "selected ='selected' ";
					$tagString .= ">".$optionList[$j]['displayText']."</option> ";
				}
				
				$tagString .= "</select>";
			}
			elseif($controlsArray[$i]['tagName'] == "div"){
				$divArray = $this->read_model->getControlInfo($controlsArray[$i]['id'], "division");
				
				$tagString .= "<div id='".$controlsArray[$i]['tagId']."' class='item' style='position:absolute;top:"
				.$controlsArray[$i]['yAxis']."; left:".$controlsArray[$i]['xAxis']."; width:"
				.$controlsArray[$i]['width']."; height:".$controlsArray[$i]['height'].";'>"
				.$divArray['innerText']."</div>";
			}
			elseif($controlsArray[$i]['tagName'] == "textarea"){
				$textArray = $this->read_model->getTextAreaInfo($controlsArray[$i]['id'], "textarea");
				
				$tagString .= "<textarea id='".$controlsArray[$i]['tagId']."' name='"
				.$textArray['name']."' class='item' rows='".$textArray['rows']
				."' columns='".$textArray['columns']."' style='position:absolute; top:"
				.$controlsArray[$i]['yAxis']."; left:".$controlsArray[$i]['xAxis']."; width:"

				.$controlsArray[$i]['width']."; height:".$controlsArray[$i]['height']."; font-size:"
				.$textArray['fontSize']."; border:1px solid black;' ";
				
				if($textArray['required'] == 1)
						$tagString .= "required = 'required' ";
						
				$tagString .= ">".$idAndDataArray[$controlsArray[$i]['id']]."</textarea>";
			}
		}
		
		return $tagString;
	}

	
	
	/*This function emails the submitted copy of form to the recipients set for the article.*/
	public function sendEmail($articleId, $submissionId, $body){
		$sendToArray = $this->form_submission_model->getRecipients($articleId, "copyTo", "sendTo");
		$copyToArray = $this->form_submission_model->getRecipients($articleId, "sendTo", "copyTo");
		$articleInfo = $this->read_model->getArticleInfoForId($articleId);
		$submissionDateTime = $this->form_submission_model -> getSubmissionTimeStamp($submissionId);				$config = Array(			'protocol' => 'mail',			'mailtype' => 'html', 			'charset' => 'utf-8',			'wordwrap' => TRUE,		);				$this->load->library('email');				$this->email->initialize($config);				$this->email->set_newline("\r\n");		$this->email->from('no-reply@anzo.ca', 'ANZO');				$this->email->to(implode(",", $sendToArray));				$this->email->cc(implode(",", $copyToArray)); 		$this->email->subject('Submission of '.$articleInfo['articleName'].' Submitted on '.$submissionDateTime);				$this->email->message($body);	
		// returning responses to the user
		if($this->email->send()){
			$returnMsg = "Email has been sent to ".implode(", ",$sendToArray);
			if(count($copyToArray)>0) 
				$returnMsg .= " and copied to ".implode(", ",$copyToArray);
			return array("success", $returnMsg);
		}
		else{					return array("fail", $this->email->print_debugger());  
		}
	}
	
	//email submitted data 
	public function emailSubmission($id, $controlIdAndData, $submissionId){
		$returnMsg = "";
				$articleString = $this->collectInputData($id, $controlIdAndData);		
		$emailed = $this->sendEmail($id, $submissionId, $articleString);		
		if($emailed[0] == "success")
			$returnMsg = $emailed[1];
		else $returnMsg = "System failed to send email. But the form has been "
					."submitted to database, So note the Date and Time to "
					."track down the submission.";
		return $returnMsg;
	}
	
	/*This function finds the label for the control if set any. otherwise 
	returns tagId. Function accepts the array containing all the controls
	and the id of control for which label has to find. */
	private function getLabelForControl($controlId, $controlArray, $labelledControlId){
		$tagId = "";
		for($i=0; $i<count($controlArray); $i++){
			if($controlArray[$i]['id'] == $controlId)
				$tagId = $controlArray[$i]['tagId'];
		}
		$label =  $this -> form_submission_model -> getLabelForControl($tagId, $labelledControlId);
		if($label == "" || $label == null || $label == false)
			$label = "unlabelled with tagId ".$tagId;
		return $label;
	}
	
	/*this function validates the form data, if data is valid than save 
	it to the database or otherwise throw an error.*/
	public function validateFormData(){
		$controlIdAndName = array();
		$controlIdAndData = array();
		$ddIdArray = array();
		$isError = false;
		$errorString = "<p>Form Submission failed due to following reasons.</p><ul>";
		$articleId =  $_POST['articleId'];
		//list of label's control id for this article
		$labelledControlId = $this -> track_submission_model -> getLabelControlId($articleId);
		$controls = $this -> form_submission_model -> getControlIdList($articleId);
		
		for($i=0; $i<count($controls); $i++){
			if($controls[$i]['tagName'] == "input"){
				$controlIdAndName[$controls[$i]['id']] = 
						$this -> form_submission_model -> getControlName($controls[$i]['id'],"input" );				
			}
			
			elseif($controls[$i]['tagName'] == "select"){
				$ddIdArray[] = $controls[$i]['id'];
				$controlIdAndName[$controls[$i]['id']] = 
						$this -> form_submission_model -> getControlName($controls[$i]['id'],"selecttag" );
			}
			
			elseif($controls[$i]['tagName'] == "textarea"){
				$controlIdAndName[$controls[$i]['id']] = 
						$this -> form_submission_model -> getControlName($controls[$i]['id'],"textarea" );
			}
			
		}
		
		foreach($controlIdAndName as $key=> $controlInfo){						$controlInfo['name'] = str_replace( " ", "_", trim($controlInfo['name']) );			$controlInfo['name'] = str_replace( "[]", "", trim($controlInfo['name']) );			
			if(isset($_POST[$controlInfo['name']]) == true){				
				if(in_array($key, $ddIdArray) == true){					$csv = "";					foreach($_POST[$controlInfo['name']] as $value){						$csv .= $value[0].",";											}					$controlIdAndData[$key] = $csv;
				}
				else $controlIdAndData[$key] = $_POST[$controlInfo['name']];
			}
			else{ 
				$controlIdAndData[$key] = null;
			}
			
			if(isset($controlInfo['type']) == true){
				if($controlInfo['type'] == "date" && 
						isDate($controlIdAndData[$key]) == false){
					$isError = true;
					$errorString .= "<li>".$this->getLabelForControl($key, $controls, $labelledControlId)
							." is not in a correct date format. for example 2011-04-29</li>";
				}
				
				if($controlInfo['type'] == "email" && 
						isEmail($controlIdAndData[$key]) == false){
					$isError = true;
					$errorString .= "<li>".$this->getLabelForControl($key, $controls, $labelledControlId)
								." is not valid email id.</li>";
				}
				
				if($controlInfo['type'] == "number" && 
						isNumber($controlIdAndData[$key]) == false){
					$isError = true;
					$errorString .= "<li>".$this->getLabelForControl($key, $controls, $labelledControlId)
								." is not a number</li>";
				}
			}
			
			if($controlInfo['required'] == 1 && 
					isEmpty($controlIdAndData[$key]) == true){
				$isError = true;
				$errorString .= "<li>".$this->getLabelForControl($key, $controls, $labelledControlId)
							." is required field</li>";
			}
			
		}
		
		if($isError == true){
			$errorString .= "</ul>";
			$articleInfo = $this->read_model->getArticleInfoForId($articleId);
			$level = ($articleInfo['connectedTo'] == 1)?"category":"sub-category";
			$id = ($articleInfo['connectedTo'] == 1)?$articleInfo['categoryId']:$articleInfo['subCategoryId'];
			$this->session->set_flashdata('displayMessage', $errorString);
			redirect('anzo/display_article/loadArticles/'.$id.'/'.$level.'/'.$articleId, 'refresh');
		}
		else{
			$numRow = 0;
			$dataId = $this -> form_submission_model -> saveFormData(array('articleId'=>$articleId), "submittedformdata");
			foreach($controlIdAndData as $key=>$element){
				$numRow += $this -> form_submission_model -> saveControlData(
							array('submissionId'=>$dataId, 'controlId'=>$key, 'data'=>$element));
			}
			
			// adding div text and control id to maintain the consistency while tracking submission
			$divElements = $this -> form_submission_model -> getDivText($articleId);
			foreach($divElements as $divText){
				$this -> form_submission_model -> saveControlData(
							array('submissionId'=>$dataId, 'controlId'=>$divText['controlId'], 'data'=>$divText['innerText']));
			}
			
			if(count($controlIdAndData) == $numRow){
				$articleInfo = $this->read_model->getArticleInfoForId($articleId);
				$level = ($articleInfo['connectedTo'] == 1)?"category":"sub-category";
				$id = ($articleInfo['connectedTo'] == 1)?$articleInfo['categoryId']:$articleInfo['subCategoryId'];
				$successMsg = "<p style='color:green;'>Form has been submitted successfully. ";
				
				if($articleInfo['setToEmail'] == 1){
					$successMsg .= $this->emailSubmission($articleId, $controlIdAndData, $dataId);
				}
				
				$successMsg .= "</p>";
				$this->session->set_flashdata('displayMessage', $successMsg);
				redirect('anzo/display_article/loadArticles/'.$id.'/'.$level.'/'.$articleId, 'refresh');
			}
				
		}
		
	}
	
	
	
	
	
	
	
}


