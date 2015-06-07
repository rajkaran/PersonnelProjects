<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Create_and_edit extends CI_Controller {
	//global variable for this class
	private $modLogId = 0;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('create_article_string');
		$this->load->helper('populate_navigation');
		$this->load->library('form_validation');
		$this->load->model('admin/create_and_edit_model');
		$this->load->model('admin/list_model');
		$this->load->model('admin/read_model');
		$this->load->library('extract_pdf_data');
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
		
		
		if(isset($relatedInfo['previousLevel']))$pageData['previousLevel'] = $relatedInfo['previousLevel'];
		if(isset($relatedInfo['backLink']))$pageData['backLink'] = $relatedInfo['backLink'];
		if(isset($relatedInfo['info']))$pageData['info'] = $relatedInfo['info'];
		if(isset($relatedInfo['thisLevel']))$pageData['thisLevel'] = $relatedInfo['thisLevel'];
		if(isset($relatedInfo['articleId']))$pageData['articleId'] = $relatedInfo['articleId'];
		
		$this->load->view('admin/templates/header', $headerData);
		$this->load->view('admin/pages/'.$page, $pageData);
		$this->load->view('admin/templates/footer');
		
	}
	
	/*load a view to create new category or sub category*/
	public function createCategoryAndSubCategory($level="", $id=""){
		$userName = $this->session->userdata('userName');
		
		//check whether session is not expired yet
		if($userName != ""){
			
			if($level == "category" ){
				$headerInfo['viewDescription'] = "Create New Sub Category";
				$headerInfo['backLink'] = 'administrator/listing/loadLevelList/categoryList/'.$id;
				$headerInfo['thisLevel'] = "sub-category";
				$headerInfo['info'] = $this->create_and_edit_model->getCategoryForId($id);
				
				/*check whether parent title has isRanged flag on, if yes then set 
				condition drop down array element.*/
				if(checkTitleIsRanged($headerInfo['info']['title']) == 1)
					$headerInfo['info']['conditionDropDown'] = $this->createConditionDropDown($headerInfo['info']['condition']);
			}
			elseif($level == "sub-categoryList"){
				$headerInfo['viewDescription'] = "Create New Sub Category";
				$headerInfo['backLink'] = 'administrator/listing/loadLevelList/'.$level.'/'.$id;
				$headerInfo['thisLevel'] = "sub-category";
			}
			elseif($level == "categoryList"){
				$headerInfo['viewDescription'] = "Create New Category";
				$headerInfo['backLink'] = 'administrator/listing/loadLevelList/'.$level;
				$headerInfo['thisLevel'] = "category";
			}
			
			//add required scripts and style sheets
			$stylesheet = "<link href='".base_url()."css/admin/action.css' rel='stylesheet' type='text/css' />\n";
			$stylesheet .= "<link href='".base_url()."css/admin/create_and_edit.css' rel='stylesheet' type='text/css' />\n";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/admin/create_and_edit.js' ></script>\n";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/validator.js' ></script>\n";
			
			$headerInfo['title'] = $headerInfo['viewDescription'];
			$headerInfo['isLoggedIn'] = true;
			$headerInfo['userName'] = ucwords($userName);
			$headerInfo['previousLevel'] = $level;
			
			$this->loadingView($stylesheet, "create_view", $headerInfo); 
			
		}
		else{redirect('administrator/login/index');}
	}
	
	/*Adding / saving new category or sub-category*/
	public function createCategoryOrSubCategory(){
		$message = "fail";
		$table = "category";
		$data = array();
		$returned = false;
		$level = "categoryList";
		
		if($_POST['data']['category'] != "") $table = "subcategory";
			
		//check if category or sub category with same name already exist
		if($this->create_and_edit_model->nameExist($_POST['data']['name'], "name", $table) == 0){
			$titleId = $this->create_and_edit_model->getId($_POST['data']['title'], "title");
			if($_POST['data']['category'] == "") $data['titleId'] = $titleId; 
			
			$isRangedFlag = checkTitleIsRanged($_POST['data']['title']);
			if($isRangedFlag == 1) $data['condition'] = $_POST['data']['isRanged'];
			else if($isRangedFlag == false) $message = "fail";
			
			//check whether creating new category or sub category condition matches create sub catgeory
			if($_POST['data']['category'] != ""){
				$categoryId = $this->create_and_edit_model->getId($_POST['data']['category'], "category");
				$data['categoryId'] = $categoryId;
				$level = "sub-categoryList";
			}
				
			$data['name'] = removeSpecialChar($_POST['data']['name']);
			$data['status'] = ($_POST['data']['isEnabled'] == "1")?true:false;
			
			date_default_timezone_set('America/New_York');
			$data['creationDate'] = date("Y-m-d");
			
			$returned = $this->create_and_edit_model->saveCategoryOrSubCategory($data, $table);
			if($returned != false) $message = "success";
			
		}else $message = "exist";
		
		echo json_encode(array("msg" => $message, "id" => $returned, 
				"level" => $level, "actingOn" => ucwords($table) ));
	}
	
	/*create the indicator drop down with selected condition. */
	public function createConditionDropDownForTitle(){
		echo json_encode(checkTitleIsRanged() );
	}
	
	/*create the indicator drop down with selected condition. */
	private function createConditionDropDown($indicatorId){
		$indicatorArray = $this->create_and_edit_model->dumpIndicator();
			
		$indicatorDropDown = "<select id='isRanged' >";
		for($i=0; $i<count($indicatorArray); $i++){
			$indicatorDropDown .= "<option value='".$indicatorArray[$i]['id']."'";
			if($indicatorArray[$i]['id'] == $indicatorId) $indicatorDropDown .= "selected='selected'";
			$indicatorDropDown .= ">".$indicatorArray[$i]['colour']."</option>";
		}
		$indicatorDropDown .= "</select>";
		
		return $indicatorDropDown;
	}
	
	/*loading the view for category and subcategory in edit mode*/
	public function editCategoryAndSubCategory($level, $id){
		$userName = $this->session->userdata('userName');
		//check whether session is not expired yet
		if($userName != ""){
			//editing sub category
			if($level == "category" || $level == "sub-categoryList"){
				$headerInfo['info'] = $this->create_and_edit_model->getSubCategoryForId($id);
				$headerInfo['viewDescription'] = "Edit";
				$headerInfo['backLink'] = 'administrator/listing/loadLevelList/'.$level.'/'.$id;
				$headerInfo['thisLevel'] = "sub-category";
			}
			
			//editing category
			elseif($level == "categoryList"){
				$headerInfo['info'] = $this->create_and_edit_model->getCategoryForId($id);
				$headerInfo['viewDescription'] = "Edit";
				$headerInfo['backLink'] = 'administrator/listing/loadLevelList/'.$level.'/'.$id;
				$headerInfo['thisLevel'] = "category";
			}
			
			/*check whether parent title has isRanged flag on, if yes then set 
			condition drop down array element.*/
			if(checkTitleIsRanged($headerInfo['info']['title']) == 1)
				$headerInfo['info']['conditionDropDown'] = $this->createConditionDropDown($headerInfo['info']['condition']);
			
			//add required style sheets
			$stylesheet = "<link href='".base_url()."css/admin/action.css' rel='stylesheet' type='text/css' />\n";
			$stylesheet .= "<link href='".base_url()."css/admin/create_and_edit.css' rel='stylesheet' type='text/css' />\n";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/admin/create_and_edit.js' ></script>\n";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/validator.js' ></script>\n";
			
			$headerInfo['title'] = $headerInfo['viewDescription'];
			$headerInfo['isLoggedIn'] = true;
			$headerInfo['userName'] = ucwords($userName);
			$headerInfo['previousLevel'] = $level;
			
			$this->loadingView($stylesheet, "edit_view", $headerInfo);
			
		}
		else{redirect('administrator/login/index');}
	}
	
	/*updates the category and sub category info in database*/
	public function editCategoryOrSubCategory(){
		$message = "fail";
		$affectedRows = 0;
		$data = array();
		$table = "category";
		//echo $_POST['id'];
		if($_POST['data']['category'] != "") $table = "subcategory";
		
		//check if category or sub category with same name already exist
		$info = $this->create_and_edit_model->duplicateNameExist($_POST['data']['name'], "name", $table);
		if($info == false || $info[0]['id'] == $_POST['id']){
			
			$titleId = $this->create_and_edit_model->getId($_POST['data']['title'], "title");
			if($_POST['data']['category'] == "") $data['titleId'] = $titleId; 
			
			$isRangedFlag = checkTitleIsRanged($_POST['data']['title']);
			if($isRangedFlag == 1) $data['condition'] = $_POST['data']['isRanged'];
			if($isRangedFlag == 0) $data['condition'] = null;
			else if($isRangedFlag == false) $message = "fail";
			
			//check whether editing category or sub category condition matches edit sub catgeory
			if($_POST['data']['category'] != ""){
				$categoryId = $this->create_and_edit_model->getId($_POST['data']['category'], "category");
				$data['categoryId'] = $categoryId;
				$table = "subcategory";
			}
				
			$data['name'] = removeSpecialChar($_POST['data']['name']);
			$data['status'] = $_POST['data']['isEnabled'] == "1"?true:false;
			
			$affectedRows = $this->create_and_edit_model->updateCategoryOrSubCategory($data, $_POST['id'], $table);						
			if($affectedRows != false) $message = "success";
			
		}else $message = "exist";
				
		echo json_encode(array("msg" => $message, "rows" => $affectedRows, "actingOn" => ucwords($table) ));
	}
	
	/*create a drop down for the list of titles accessible to current user*/
	public function createTitleDropDown(){
		$currentTitle = $_POST['currentValue'];
		
		//get the list of titles user have access to.
		$titleIdList = $this->session->userdata('accessInfo');
		
		//if role admin than user gave access to all the titles.
		if($this->session->userdata('role') == "admin")
			$titleIdList = $this->list_model->dumpTitleTable();
		
		$titleList = $this->create_and_edit_model->getTitleList($titleIdList);
		
		//$dropdownElement = "<select id='title' required='required' onChange='refreshCategory()'>";
		$dropdownElement = "<select id='title' required='required'>";
		
		for($i=0; $i<count($titleList); $i++){
			$dropdownElement .= "<option value='".$titleList[$i]."' "; 
			if($currentTitle == $titleList[$i])
				$dropdownElement .= " selected='selected' ";
			$dropdownElement .= ">".$titleList[$i]."</option>";
		}
		
		$dropdownElement .= "</select>";
		
		$dataArray = array("dataString" => $dropdownElement);
		echo json_encode($dataArray);
	}
	
	/*create a drop down for the list of titles accessible to current user*/
	public function createCategoryDropDown(){
		$currentCategory = $_POST['currentValue'];
		$title = $_POST['title'];
			
		//getting title id of title name
		$titleId = $this->create_and_edit_model->getTitleId($title);
		
		$categoryList = $this->create_and_edit_model->getCategoryList($titleId);
		
		$dropdownElement = "<select id='category' required='required'>";
		
		for($i=0; $i<count($categoryList); $i++){
			$dropdownElement .= "<option value='".$categoryList[$i]."' "; 
			if($currentCategory == $categoryList[$i])
				$dropdownElement .= " selected='selected' ";
			$dropdownElement .= ">".$categoryList[$i]."</option>";
		}
		
		$dropdownElement .= "</select>";
		
		$dataArray = array("dataString" => $dropdownElement);
		echo json_encode($dataArray);
	}
	
	/*create a drop down for the list of titles accessible to current user*/
	public function createSubCategoryDropDown(){
		$currentSubCategory = $_POST['currentValue'];
		$category = $_POST['category'];
			
		//getting category id of category name
		$categoryId = $this->create_and_edit_model->getCategoryId($category);
		
		$SubcategoryList = $this->create_and_edit_model->getSubCategoryList($categoryId);
		
		$dropdownElement = "<select id='sub-category' required='required'><option value='none'> No Sub Category </option>";
		
		for($i=0; $i<count($SubcategoryList); $i++){
			$dropdownElement .= "<option value='".$SubcategoryList[$i]."' "; 
			
			if($currentSubCategory == $SubcategoryList[$i])
				$dropdownElement .= " selected='selected' ";
			
			//if there is no subcategory to replace
			if($currentSubCategory == "" && $i==1)
				$dropdownElement .= " selected='selected' ";
				
			$dropdownElement .= ">".$SubcategoryList[$i]."</option>";
		}
		
		$dropdownElement .= "</select>";
		
		$dataArray = array("dataString" => $dropdownElement);
		echo json_encode($dataArray);
	}
	
	/*Loading the form builder to create new category, sub category and article*/
	public function createArticle($level="",$id=0){
		$userName = $this->session->userdata('userName');
		
		//check whether session is not expired yet
		if($userName != ""){
			
			//add required scripts and style sheets
			$stylesheet = "<link href='".base_url()."css/admin/action.css' rel='stylesheet' type='text/css' />\n";
			$stylesheet .= "<link href='".base_url()."css/admin/create_and_edit.css' rel='stylesheet' type='text/css' />\n";
			$stylesheet .= "<link href='".base_url()."js/formBuilder/css/dnd.css' rel='stylesheet' type='text/css' />\n";
			$stylesheet .= "<link href='".base_url()."js/formBuilder/css/jquery-ui.css' rel='stylesheet' type='text/css' />\n";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/formBuilder/jquery-ui.js' ></script>\n";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/formBuilder/ckeditor/ckeditor.js' ></script>\n";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/formBuilder/ckeditor/config.js' ></script>\n";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/formBuilder/builder.js' ></script>\n";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/admin/create_and_edit.js' ></script>\n";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/validator.js' ></script>\n";
			
			$headerInfo['viewDescription'] = "Create New Article";
			$headerInfo['title'] = $headerInfo['viewDescription'];;
			$headerInfo['isLoggedIn'] = true;
			$headerInfo['userName'] = ucwords($userName);
			$headerInfo['thisLevel'] = "article";
			$headerInfo['previousLevel'] = $level;

			//if creating under sub category
			if($level == "sub-categoryList" || $level == "category"){
				$headerInfo['backLink'] = "administrator/listing/loadLevelList/".$level."/".$id;
				$headerInfo['info'] = $this->create_and_edit_model->getSubCategoryForId($id);
				
			}
			
			//if creating under category
			if($level == "categoryList"){
				$headerInfo['backLink'] = "administrator/listing/loadLevelList/".$level."/".$id;
				$headerInfo['info'] = $this->create_and_edit_model->getCategoryForId($id);
			}

			if($level == null || $level == false)
				$headerInfo['backLink'] = "administrator/listing/loadLevelList/articleList";
			
			$this->loadingView($stylesheet, "create_view", $headerInfo);
			
		}
		else{redirect('administrator/login/index');}
	}
	
	/*Creating new article and send it's info and control info to the database*/
	public function saveArticle(){
		$categoryId;
		$numControlsInserted = 0;
        $articleData = array();
		$message = "fail";
		$articleId=0;
		
		//creating under category
		if(trim($_POST['articleInfo']['sub-category']) == "none" || trim($_POST['articleInfo']['sub-category']) == ""){
			$articleData['connectedTo'] = 1;
			$articleData['categoryId'] = $this->create_and_edit_model->getId($_POST['articleInfo']['category'], "category");
		}
		//creating under sub category
		else{
			$articleData['connectedTo'] = 0;
			$articleData['subCategoryId'] = $this->create_and_edit_model->getId($_POST['articleInfo']['sub-category'], "subcategory");
		}
		
		$articleData['articleName'] = removeSpecialChar(trim($_POST['articleInfo']['articleName']));
		$articleData['articleKeyword'] = trim($_POST['articleInfo']['articleKeyword']);
		$articleData['articleTitle'] = trim($_POST['articleInfo']['articleTitle']);
		
		date_default_timezone_set('America/New_York');
		$articleData['creationDate'] = date("Y-m-d");
		$articleData['havePdfVersion'] = false;
		$articleData['setToEmail'] = false;
		$articleData['isItForm'] = false;
		$articleData['status'] = true;
		
		//check if article with same name already exist
		if($this->create_and_edit_model->nameExist($articleData['articleName'], "articleName", "articleinfo") == 0){
		
			$articleId = $this->create_and_edit_model->saveArticleInfo($articleData);
			
			for($i=0; $i<count($_POST['data']); $i++){
				$controlId = $this -> savingControlInfo($_POST['data'][$i], $articleId);
				
				$tagAndTableData = $this -> constructTagArray($_POST['data'][$i][0], $controlId, $_POST['data'][$i] );
				$table = $tagAndTableData['table'];
				$tagData = array_slice($tagAndTableData, 0, count($tagAndTableData)-1, true);
				
				if($table != "" && $this->create_and_edit_model->saveTagInfo($tagData, $table) == 1)
					$numControlsInserted++;
			}
		} else $message = "exist";
		
		
		if($numControlsInserted == count($_POST['data']))
			$message = "success";
			
		echo json_encode(array("msg"=>$message,"articleId"=>$articleId));
	}

	/*This function loads the view for article in editable mode */
	public function editArticle($level,$articleId){
		
		$userName = $this->session->userdata('userName');
		//check whether session is not expired yet
		if($userName != ""){
			
			//add required scripts and style sheets
			$stylesheet = "<link href='".base_url()."css/admin/action.css' rel='stylesheet' type='text/css' />\n";
			$stylesheet .= "<link href='".base_url()."css/admin/create_and_edit.css' rel='stylesheet' type='text/css' />\n";
			$stylesheet .= "<link href='".base_url()."js/formBuilder/css/dnd.css' rel='stylesheet' type='text/css' />\n";
			$stylesheet .= "<link href='".base_url()."js/formBuilder/css/jquery-ui.css' rel='stylesheet' type='text/css' />\n";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/formBuilder/jquery-ui.js' ></script>\n";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/formBuilder/ckeditor/ckeditor.js' ></script>\n";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/formBuilder/ckeditor/config.js' ></script>\n";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/formBuilder/builder.js' ></script>\n";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/admin/create_and_edit.js' ></script>\n";
			
			$headerInfo['viewDescription'] = "Edit";
			$headerInfo['title'] = $headerInfo['viewDescription'];
			$headerInfo['isLoggedIn'] = true;
			$headerInfo['userName'] = ucwords($userName);
			$headerInfo['thisLevel'] = "article";
			$headerInfo['articleId'] = $articleId;
			$headerInfo['previousLevel'] = $level;
			$headerInfo['backLink'] = 'administrator/read/articleReadMode/'.$level.'/'.$articleId;
			
			$this->loadingView($stylesheet, "edit_view", $headerInfo);
			
		}
		else{redirect('administrator/login/index');}

	}
	
	/*calculating the length of an article*/
	public function getArticleHeight(){
		$topAndId = $this->create_and_edit_model->maxTop($_POST['articleId']) ;
		echo json_encode(array("articleLength" => $topAndId));
	}
	
	/*This function stores the display text and value of particular select 
	control from the article. It accepts the display text and value as formatted string. */
	private function storeOptions($dataString,$id,$action = "insert"){
		$optionData = array();
		$optionData['controlId'] = $id;
		$insertedOptions = 0;
		$deletedOptions = 0;
		$isError = false;
		
		/*breaks the string in pairs of display text and value, given 
		format is text,value#text,value#text,value*/
		$options = explode("#",$dataString);
		$len = count($options)-1;
		
		//if we are editing options then first delete the previously set options from database.
		if($action != "insert"){
			$deletedOptions += $this->create_and_edit_model->updateDdOptionInfo($id);
		}
		
		for($i=0; $i<$len; $i++){
			//breaking every pair into display text and value
			$specOption = explode(",",$options[$i]);
			$optionData['displayText'] = $specOption[0];
			
			if(isset($specOption[1]) == true)
				$optionData['value'] = $specOption[1];
			else $isError = true;
			
			//if an options is selected default then it has been indicated with * mark in string.
			$pos = strpos($specOption[1],"*");
			if($pos !== false){
				$optionData['isSelected'] = true;
				$optionData['value'] = substr($optionData['value'], $pos+1);
			}
			
			if($this->create_and_edit_model->saveDdOptionInfo($optionData) == 1){
				$optionData['isSelected'] = false;
				$insertedOptions++;
			}
			
		}
		
		if($action != "insert" && $insertedOptions != $deletedOptions)
			$isError = true;
		
		if($insertedOptions == $len && $isError == false)
			return true;
		else return false;
	}

	/*construct the array of secondary info related to article controls, that needs to be 
	saved in secondary tables such as division, selecttag, input and textarea. While creating 
	control info array appends table as a last element of array*/
	private function constructTagArray($tagName, $id, $dataArray, $action = "insert" ){
		$resultArray = array();
		$resultArray['controlId'] = $id;
		
		if($tagName == "div"){
			$resultArray['innerText'] = $dataArray[7];
			$resultArray['table'] = "division";
		}
		
		elseif($tagName == "label"){
			$resultArray['innerText'] = $dataArray[7];
			$resultArray['fontSize'] = $dataArray[15];
			$resultArray['color'] = $dataArray[17];
			$resultArray['forValue'] = $dataArray[16];
			$resultArray['table'] = "label";
		}
		
		elseif($tagName == "select"){
			$resultArray['name'] = $dataArray[2];
			$resultArray['size'] = $dataArray[13];
			
			if(trim($dataArray[10]) !== "")
				$resultArray['required'] = true;
			else $resultArray['required'] = false;
				
				
			if(trim($dataArray[12]) !== "")
				$resultArray['multiple'] = true;
			else $resultArray['multiple'] = false;
			
			
			if($action == "insert"){
				if($this -> storeOptions($dataArray[7], $id) == true)
					$resultArray['table'] = "selecttag";
			}else{
				if($this -> storeOptions($dataArray[7], $id, "update") == true)
					$resultArray['table'] = "selecttag";
			}
		}
		
		elseif($tagName == "textarea"){
			$resultArray['name'] = $dataArray[2];
			
			if(trim($dataArray[10]) != "")
				$resultArray['required'] = true;
			else $resultArray['required'] = false;
				
			$resultArray['rows'] = $dataArray[3];
			$resultArray['columns'] = $dataArray[4];
			$resultArray['fontSize'] = $dataArray[15];
			$resultArray['table'] = "textarea";
		}
		
		elseif($tagName == "input"){
			
			$resultArray['name'] = $dataArray[2];
			
			if(trim($dataArray[10]) != "")
				$resultArray['required'] = true;
			else $resultArray['required'] = false;
				
			$resultArray['type'] = $dataArray[9];
			if($resultArray['type'] != "radio" && $resultArray['type'] != "checkbox")
				$resultArray['fontSize'] = $dataArray[15];
			
			if($resultArray['type'] == "radio" || $resultArray['type'] == "checkbox"){
				$resultArray['value'] = $dataArray[8];
				if(trim($dataArray[11]) != "")
					$resultArray['checked'] = true;
				else $resultArray['checked'] = false;
			}
			
			$resultArray['table'] = "input";
		}
		return $resultArray;
	}
	
	/*Create modification log for the article which is a form. Log will be created
	if one or more controls have been edited.*/
	private function createModificationLog($newdata, $controlId, $articleId) {
		$isModified = false;
		$compareArray = array('id' => $controlId, 'xAxis' => $newdata[19], 'yAxis' => $newdata[18],
						'width' => $newdata[6], 'height' => $newdata[5]);
		$existingData = array();
		
		// don't create mod log if article is not a form				
		$articleInfo = $this->read_model->getArticleInfoForId($articleId);
		if($articleInfo['isItForm'] == 0) return true; 
		
		/*check whether coordinates, height or width has been changed. if yes then 
		proceed otherwise check given control is label or div and the innertext is changed.*/
		if( $this->create_and_edit_model->matchControlInput($compareArray, "control") == 0 ){
			$isModified = true;
			$existingData = $this->create_and_edit_model->getControlInfoForId("control", "id", $controlId);
			
			if($newdata[0]=="label" || $newdata[0]=="div"){
				$table1 = ($newdata[0]=="label")?"label":"division";
				$existingData = array_merge($existingData, 
								$this->create_and_edit_model->getControlInfoForId($table1, "controlId", $controlId) );
				$compareArray['innerText']=$newdata[7];
			}
		}
		elseif($newdata[0]=="label" || $newdata[0]=="div"){
			$table1 = ($newdata[0]=="label")?"label":"division";
			if($this->create_and_edit_model->matchControlInput(array('controlId' => $controlId, 
											'innerText' => $newdata[7]), $table1) == 0){
				$isModified = true;
				$existingData = array_merge($this->create_and_edit_model->getControlInfoForId("control", "id", $controlId),
									$this->create_and_edit_model->getControlInfoForId($table1, "controlId", $controlId) );
			}
		}
		
		//create log only if change occured to atleast one control.
		if($isModified == true){
			if($this->modLogId == 0)
					$this->modLogId = $this->create_and_edit_model->createModLog(array("articleId" => $articleId) );
			$existingData['modId'] = $this->modLogId;
			$existingData['controlId'] = $controlId;
			$this->create_and_edit_model->createModControlRecord($existingData);
		}
		
		return true;
	}
	
	/*Add or Update primary control info for the given control id in the database*/
	private function savingControlInfo($data, $articleId, $controlId = 0){
		$rowAffected = 0;
		$controlData = array();
		$controlData['tagId'] = $data[1];
		$controlData['articleId'] = $articleId;
		$controlData['tagName'] = $data[0];
		$controlData['yAxis'] = $data[18];
		$controlData['xAxis'] = $data[19];
		$controlData['height'] = $data[5];
		$controlData['width'] = $data[6];
		$controlData['status'] = true;
		
		//if control id is 0 means we are adding new article otherwise editing previous one.
		if($controlId == 0)
			return $this->create_and_edit_model->saveControlInfo($controlData);
		else{
			if($this->createModificationLog($data, $controlId, $articleId) == true)
				$rowAffected = $this->create_and_edit_model->updateControlInfo($controlData, $controlId);
			
			return ($rowAffected == 1) ? $controlId : false;
		}
	}

	/*Update article control primary and secondary info into the database*/
	public function updateArticle(){
		$updatingControls = array();
		$newControls = array();
		$numControlsInserted = 0;
		$numControlsUpdated = 0;
		$numControlsInArticle = 0;
		$numControlsRemoved = 0;
		$articleId = $_POST['articleId'];
		
		//get the list of existing controls in the given article.
		$existingControl = $this->create_and_edit_model->existingTagId($articleId);
		$updatingControls = $existingControl;
		
		//get the list of controls from this article edit request.
		for($i=0; $i<count($_POST['data']); $i++){
			$newControls[] = $_POST['data'][$i][1];
		}
		$numControlsInArticle = count($newControls);
		
		/*Remove the controls from the updatingControls which does not exist in the 
		newControls array , so we left with only those controls which have to be updated.
		Remove the controls newControls array which does not exist in th list of 
		existingControls, So we left with only new controls added in this article edit request.*/
		for($j=0; $j<count($updatingControls);$j++){
			for($k=0; $k<count($newControls);$k++){
				if($updatingControls[$j]['tagId'] == $newControls[$k]){
					$newControls[$k] = null;
					break;
				}
				else if($updatingControls[$j]['tagId'] != $newControls[$k] && $k == count($newControls)-1)
					$updatingControls[$j] = null;
			}
		}
		
		//resetting the index of arrays
		$removedControls = array_keys($updatingControls, null);
		$updatingControls = array_values(array_filter($updatingControls));
		$newControls = array_values(array_filter($newControls));
		
		//Add new controls under the given article Id
		for($i=0; $i<count($newControls); $i++){
			for($j=0; $j<count($_POST['data']); $j++){
				if($newControls[$i] == $_POST['data'][$j][1]){
					$controlId = $this -> savingControlInfo($_POST['data'][$j], $articleId);
					
					$tagAndTableData = $this -> constructTagArray($_POST['data'][$j][0], $controlId, $_POST['data'][$j] );
					$table = $tagAndTableData['table'];
					$tagData = array_slice($tagAndTableData, 0, count($tagAndTableData)-1, true);
					
					if($table != "" && $this->create_and_edit_model->saveTagInfo($tagData, $table) == 1)
						$numControlsInserted++;
				}
			}
		}
		
		//Update the existing controls
		for($i=0; $i<count($updatingControls); $i++){
			for($j=0; $j<count($_POST['data']); $j++){
				if($updatingControls[$i]['tagId'] == $_POST['data'][$j][1]){
					$controlId = $this -> savingControlInfo($_POST['data'][$j], $articleId, $updatingControls[$i]['id']);
					
					$tagAndTableData = $this -> constructTagArray($_POST['data'][$j][0], $controlId, $_POST['data'][$j], "update" );
					$table = $tagAndTableData['table'];
					$tagData = array_slice($tagAndTableData, 0, count($tagAndTableData)-1, true);
					
					if($table != "" && $this->create_and_edit_model->updateTagInfo($tagData, $table) == 1)
						$numControlsUpdated++;
				}
			}
		}
		
		//Disable the controls which are removed in this edit article request.
		for($i=0; $i<count($removedControls); $i++){
			$numControlsRemoved += $this->create_and_edit_model
				->updateControlStatus($existingControl[$removedControls[$i]]['id']);
		}
		
		$message = "fail";
		if($numControlsInArticle == $numControlsUpdated+$numControlsInserted 
				&& count($existingControl) == $numControlsRemoved+$numControlsUpdated)
			$message = "success";
			
		echo json_encode(array("msg"=>$message,"articleId"=>$articleId));
		
	}
	

	
}

