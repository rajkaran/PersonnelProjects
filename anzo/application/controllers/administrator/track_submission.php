<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Track_submission extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('admin/list_model');
		$this->load->model('admin/read_model');
		$this->load->model('admin/track_submission_model');
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->library("pagination");
		$this->load->helper('add_pages');
	}
	
	public function loadingView($cssString, $page, $relatedInfo){
		$pageData = array();
		$headerData["scriptAndStyle"] = 
			"<link href='".base_url()."css/admin/header_footer.css' rel='stylesheet' type='text/css' />";
		$headerData["scriptAndStyle"] .= $cssString;
		$headerData['viewDescription'] = $relatedInfo['viewDescription'];
		$headerData['isLoggedIn'] = $relatedInfo['isLoggedIn'];
		if(isset($relatedInfo['userName']))$headerData['userName'] = ucwords($relatedInfo['userName']);
		
		if(isset($relatedInfo['backLink']))$headerData['backLink'] = $relatedInfo['backLink'];
		if(isset($relatedInfo['formList']))$headerData['formList'] = $relatedInfo['formList'];
		if(isset($relatedInfo['submissionList']))$headerData['submissionList'] = $relatedInfo['submissionList'];
		if(isset($relatedInfo['filledForm']))$headerData['filledForm'] = $relatedInfo['filledForm'];
		if(isset($relatedInfo['links']))$headerData['links'] = $relatedInfo['links'];
		if(isset($relatedInfo['formName']))$headerData['formName'] = $relatedInfo['formName'];
		if(isset($relatedInfo['formHeight']))$headerData['formHeight'] = $relatedInfo['formHeight'];
		
		$this->load->view('admin/templates/header', $headerData);
		$this->load->view('admin/pages/'.$page, $pageData);
		$this->load->view('admin/templates/footer');
		
	}
	
	//load forms list view to display list of forms
	public function loadFormList(){
		
		$userName = $this->session->userdata('userName');
		
		//check whether session is not expired yet
		if($userName != ""){
			
			$categoryIdList = array();
			$subCategoryIdList = array();
			
			//get the list of titles user have access to.
			$titleIdList = $this->session->userdata('accessInfo');
			
			//if role admin than user gave access to all the titles.
			if($this->session->userdata('role') == "admin")
				$titleIdList = $this->list_model->dumpTitleTable();
			
			//get the list of all categories and subcategories accessible to current user.	
			foreach($this->track_submission_model->
						enabledCategoryForTitle($titleIdList)as $key => $record){
				$categoryIdList[] = $record['id'];
			}
			
			foreach($this->track_submission_model->
						enabledSubCategoryForTitle($titleIdList)as $key => $record){
				$subCategoryIdList[] = $record['id'];
			}
			
			//get the list of article connected to all accessible categories and subcategories
			$formList1 = $this->track_submission_model->getArticleForParent($categoryIdList, "categoryId");
			$formList2 = $this->track_submission_model->getArticleForParent($subCategoryIdList, "subCategoryId");
			$formList = array_merge($formList1, $formList2);
			
			//arrange formlist before sending to view
			for($i=0; $i<count($formList); $i++){
				if($formList[$i]['connectedTo'] == "Category"){
					$formList[$i]['parent'] = $this->track_submission_model->
						getNameForId($formList[$i]['categoryId'], "category");
				}else $formList[$i]['parent'] = $this->track_submission_model->
						getNameForId($formList[$i]['subCategoryId'], "subcategory");
				
				$formList[$i]['totalSubmission'] = $this->track_submission_model->
						getTotalSubmission($formList[$i]['id']);
			}
			
			//$headerInfo['formList'] = $formList;
				
			$stylesheet = "<link href='".base_url()."css/admin/track_submission.css' rel='stylesheet' type='text/css' />\n";
			$stylesheet .= "<link href='".base_url()."css/admin/table.css' rel='stylesheet' type='text/css' />\n";
			
			$headerInfo['isLoggedIn'] = true;
			$headerInfo['viewDescription'] = "List of Forms";
			$headerInfo['userName'] = ucwords($userName);
			$headerInfo['backLink'] = "administrator/login/profileLogIn";
			
			//add pagination to the view from add pages helper 
			$return = addPages( base_url().'administrator/track_submission/loadFormList', $formList, 14, 4);
			$headerInfo['formList'] = $return[0];
			$headerInfo['links'] = $return[1];
				
			$this->loadingView($stylesheet, "track_submission_view", $headerInfo);
			
		} else{redirect('administrator/login/index');}
		
	}
	
	//load forms list view to display list of forms
	public function loadSubmission($formId){
		$userName = $this->session->userdata('userName');
		
		//check whether session is not expired yet
		if($userName != ""){
			$stylesheet = "<link href='".base_url()."css/admin/track_submission.css' rel='stylesheet' type='text/css' />\n";
			$stylesheet .= "<link href='".base_url()."css/admin/table.css' rel='stylesheet' type='text/css' />\n";
			
			$retrievedList = $this->track_submission_model-> getSubmissionForId($formId);
			$headerInfo['isLoggedIn'] = true;
			$headerInfo['viewDescription'] = "List of Form Submissions";
			$headerInfo['userName'] = ucwords($userName);
			$headerInfo['backLink'] = "administrator/track_submission/loadFormList";
			$headerInfo['formName'] = $this->track_submission_model-> getArticleNameForId($formId);
			
			//add pagination to the view from add pages helper 
			$return = addPages( base_url().'administrator/track_submission/loadSubmission/'.$formId, $retrievedList, 12, 5);
			$headerInfo['submissionList'] = $return[0];
			$headerInfo['links'] = $return[1];
			
			$this->loadingView($stylesheet, "track_submission_view", $headerInfo);
			
		} else{redirect('administrator/login/index');}
		
	}
	
	/*This function returns the label innertext for a given tagId. Function first 
	searches the label database table for the given tagId in forValue column, if 
	finds then returns the label string otherwise returns empty string.*/
	public function labelForControl($tagId, $modControl, $modId, $labelledControlId){
		 
		$labelArray = $this->track_submission_model-> getLabelForTagId($tagId, $labelledControlId);
		if(!empty($labelArray)){
			$controlPrimaryInfo = $this->getControlPrimaryInfo($labelArray['controlId'], $modControl, $modId);
			$labelText = (isset($controlPrimaryInfo['innerText'] ) == true)?$controlPrimaryInfo['innerText']:$labelArray['innerText'];
			
			$tagString = "<label style='top:".$controlPrimaryInfo['yAxis']
				.";left:".$controlPrimaryInfo['xAxis'].";font-size:".$labelArray['fontSize']
				.";color:".$labelArray['color'].";position:absolute;"
				."font-family:Tahoma; width:auto; height:auto; text-align:left;'>"
				.$labelText."</label> ";
			
			return array($this->track_submission_model-> getcontrolBottom($labelArray['controlId']), $tagString);	
		}
		else return array(0, "");
	}
	
	/*This function resets the coordinates, height, width and innertext (if any) 
	of a given control, if modified for a mod Id */
	private function getControlPrimaryInfo($controlId, $modControl, $modId){
		$primaryInfo = $this->track_submission_model-> getControlForId($controlId);
		
		if(count($modControl) > 0 && in_array($controlId, $modControl) ){
			$oldControlInfo = $this->track_submission_model-> getModControl($controlId, $modId);
				
			$primaryInfo['xAxis'] = $oldControlInfo['xAxis'];
			$primaryInfo['yAxis'] = $oldControlInfo['yAxis'];
			$primaryInfo['height'] = $oldControlInfo['height'];
			$primaryInfo['width'] = $oldControlInfo['width'];
			if($oldControlInfo['innerText'] != "")$primaryInfo['innerText'] = $oldControlInfo['innerText'];
		}
		
		return $primaryInfo;
	}
	
	/*This function creates the string of html elements with submmited data for 
	that element for the given article id.*/
	public function getArticleData($id, $controlDataArray, $formId){
		$tagString = "";
		$formLength = 0;
		$modControl = array();
		$returnedArray = array();
		
		//list of label's control id for this article
		$labelledControlId = $this -> track_submission_model -> getLabelControlId($formId);
		
		$modId = $this->track_submission_model-> getNearestModification($id);
		if($modId) $modControl = $this->track_submission_model-> getModControlList($modId);
		
		for($i=0; $i<count($controlDataArray); $i++){
			$controlPrimaryInfo = $this->getControlPrimaryInfo($controlDataArray[$i]['controlId'], $modControl, $modId);
			
			if($controlPrimaryInfo['tagName'] == "input"){
				$inputArray = $this->read_model->getInputInfo($controlPrimaryInfo['id'], "input");
				
				if($inputArray['type'] == "radio" || $inputArray['type'] == "checkbox"){
					$tagString .= "<input type='".$inputArray['type']."' disabled='disabled'"  
						."' value='".$inputArray['value']."' style='position:absolute; top:"
						.$controlPrimaryInfo['yAxis']."; left:".$controlPrimaryInfo['xAxis'].";' ";
						
					if($controlDataArray[$i]['data'] == $inputArray['value'])
						$tagString .= "checked = 'checked' ";
					
					$tagString .= "/>";
				}
				else{
					$tagString .= "<input type='".$inputArray['type']."' disabled='disabled'"
						." value='".$controlDataArray[$i]['data']."' style='position:absolute;top:"
						.$controlPrimaryInfo['yAxis'].";left:".$controlPrimaryInfo['xAxis'].";height:"
						.$controlPrimaryInfo['height'].";width:".$controlPrimaryInfo['width']
						.";font-size:".$inputArray['fontSize'].";border:1px solid black;' ";
						
					$tagString .= "/>";
				}
				
				$controlBottom = $this->track_submission_model-> getcontrolBottom($controlDataArray[$i]['controlId']);
				if($controlBottom > $formLength) $formLength = $controlBottom;
				
				$returnedArray = $this->labelForControl($controlPrimaryInfo['tagId'], $modControl, $modId, $labelledControlId);
				if($returnedArray[0] > $formLength) $formLength = $controlBottom;
				$tagString .= $returnedArray[1];
			}
				
			if($controlPrimaryInfo['tagName'] == "textarea"){
				$textArray = $this->read_model->getTextAreaInfo($controlPrimaryInfo['id'], "textarea");
			
				$tagString .= "<textarea rows='".$textArray['rows']."' columns='"
					.$textArray['columns']."' style='position:absolute; top:"
					.$controlPrimaryInfo['yAxis']."; left:".$controlPrimaryInfo['xAxis']."; width:"
					.$controlPrimaryInfo['width']."; height:".$controlPrimaryInfo['height']."; font-size:"
					.$textArray['fontSize']."; border:1px solid black;' ";
				
				$tagString .= " disabled='disabled' >".$controlDataArray[$i]['data']."</textarea>";
				
				$controlBottom = $this->track_submission_model-> getcontrolBottom($controlDataArray[$i]['controlId']);
				if($controlBottom > $formLength) $formLength = $controlBottom;
				
				$returnedArray = $this->labelForControl($controlPrimaryInfo['tagId'], $modControl, $modId, $labelledControlId);
				if($returnedArray[0] > $formLength) $formLength = $controlBottom;
				$tagString .= $returnedArray[1];
				
			}
				
			if($controlPrimaryInfo['tagName'] == "select"){
				$selectArray = $this->read_model->getSelectInfo($controlPrimaryInfo['id'], "selecttag");
				
				$tagString .= "<select size='".$selectArray['size']."'style='position:absolute; top:"
					.$controlPrimaryInfo['yAxis']."; left:".$controlPrimaryInfo['xAxis']
					.";height:".$controlPrimaryInfo['height']."; width:".$controlPrimaryInfo['width'].";' disabled='disabled' ";
						
				if($selectArray['multiple'] == 1)
					$tagString .= "multiple = 'multiple' ";
				
				$tagString .= ">";
				
				$optionList = $this->read_model->getOptionInfo($selectArray['controlId']);
				$optionDataArray = explode(",", $controlDataArray[$i]['data']);
				
				for($j=0; $j<count($optionList); $j++){
					$tagString .= "<option value='".$optionList[$j]['value']."' ";
					if(in_array($optionList[$j]['value'], $optionDataArray) == true)
						$tagString .= "selected ";
					$tagString .= ">".$optionList[$j]['displayText']."</option> ";
				}
				
				$tagString .= "</select>";
				
				$controlBottom = $this->track_submission_model-> getcontrolBottom($controlDataArray[$i]['controlId']);
				if($controlBottom > $formLength) $formLength = $controlBottom;
				
				$returnedArray = $this->labelForControl($controlPrimaryInfo['tagId'], $modControl, $modId, $labelledControlId);
				if($returnedArray[0] > $formLength) $formLength = $controlBottom;
				$tagString .= $returnedArray[1];
				
			}
				
			if($controlPrimaryInfo['tagName'] == "div"){
				$divArray = $this->read_model->getControlInfo($controlPrimaryInfo['id'], "division");
			
				$tagString .= "<div style='position:absolute;top:".$controlPrimaryInfo['yAxis']
					."; left:".$controlPrimaryInfo['xAxis']."; width:" .$controlPrimaryInfo['width']
					."; height:".$controlPrimaryInfo['height'].";'>".$controlDataArray[$i]['data']."</div>";
				
				$controlBottom = $this->track_submission_model-> getcontrolBottom($controlDataArray[$i]['controlId']);
				if($controlBottom > $formLength) $formLength = $controlBottom;			
			}
			
		}
		
		return array($formLength, $tagString);
	}
	
	//load the submitted data for given submission id
	public function filledForm($submissionId){
		$userName = $this->session->userdata('userName');
		
		//check whether session is not expired yet
		if($userName != ""){
			
			$stylesheet = "<link href='".base_url()."css/admin/track_submission.css' rel='stylesheet' type='text/css' />\n";
			$formId = $this->track_submission_model->getFormIdForSubmissionId($submissionId);//articleId
			$controlData = $this->track_submission_model-> getControlDataForId($submissionId);
			
			
			$return = $this->getArticleData($submissionId, $controlData, $formId);
			$headerInfo['filledForm'] = $return[1];
			$headerInfo['formHeight'] = $return[0];
			
			$headerInfo['isLoggedIn'] = true;
			$headerInfo['viewDescription'] = "List of Forms";
			$headerInfo['userName'] = ucwords($userName);
			$headerInfo['backLink'] = "administrator/track_submission/loadSubmission/".$formId;
			
				
			$this->loadingView($stylesheet, "track_submission_view", $headerInfo);
			
		} else{redirect('administrator/login/index');}
		
	}
	
	
	
	
	
	
	
	
}