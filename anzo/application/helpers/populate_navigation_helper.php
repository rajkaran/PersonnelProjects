<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	/*This function cut off the string to a particular length and append few dots at 
	the end*/
	function sliceString($string, $length){
		$result = $string;
		if(strlen($string) > $length){
			$result = substr(trim($string), 0, $length-3)."...";
		}
		return ucwords($result);
	}
	
	/*This function retireves categories for a list of titles and return a 2D array 
	in this format categoryArray[titleId] = array(categoryId => categoryName)*/
	function retrieveCategories($titleArray){
		$categoryArray = array();
		$ci =& get_instance();
		$ci->load->model('site/home_model');
		
		for($i=0; $i<count($titleArray); $i++){
			$categories = $ci->home_model->getCategoryForTitle($titleArray[$i]['id']);
			$categoryArray[$titleArray[$i]['title']] = array();
			for($j=0; $j<count($categories); $j++){
				
				$categoryArray[$titleArray[$i]['title']][$categories[$j]['id']] = 
						array("name" => sliceString($categories[$j]['name'], 30), 
							  "condition" => $categories[$j]['condition'] );
			}
		}
		return $categoryArray;
	}
	
	/*This function retireves sub categories for a list of categories and return a 2D array 
	in this format subCategoryArray[categoryId] = array(subCategoryId => subCategoryName)*/
	function retrieveSubCategories($categoryArray){
		$subCategoryArray = array();
		$ci =& get_instance();
		$ci->load->model('site/home_model');
			
		foreach($categoryArray as $key=>$categories){
			foreach($categories as $key=>$category){
				$subCategories = $ci->home_model->getSubCategoryForCategory($key);
				$subCategoryArray[$category['name']] = array();
				for($j=0; $j<count($subCategories); $j++){
					$subCategoryArray[$category['name']][$subCategories[$j]['id']] = 
							array("name" => sliceString($subCategories[$j]['name'], 25),
								  "condition" => $subCategories[$j]['condition'] );
				}
			}
		}
		return $subCategoryArray;
	}
	
	/*check whether isRanged flag for current selected title is set*/
	function checkTitleIsRanged($titleName = null){
		$ci =& get_instance();
		$ci->load->model('admin/create_and_edit_model');
		
		if($titleName == null) $currentTitle = $_POST['title'];
		else $currentTitle = $titleName;
		
		$isRangedFlag = $ci->create_and_edit_model->isRangedForTitle($currentTitle);
		 
		if($titleName == null) {
			if($isRangedFlag == false) $isRangedFlag == "System failed to fetch isRanged Flag.";
			$indicatorArray = $ci->create_and_edit_model->dumpIndicator();
			
			$indicatorDropDown = "<select id='isRanged' >";
			for($i=0; $i<count($indicatorArray); $i++){
				$indicatorDropDown .= "<option value='".$indicatorArray[$i]['id']."'";
				if($i==0) $indicatorDropDown .= "selected='selected'";
				$indicatorDropDown .= ">".$indicatorArray[$i]['colour']."</option>";
			}
			$indicatorDropDown .= "</select>";
			
			return array("isRangedFlag" => $isRangedFlag, "dropDownString" => $indicatorDropDown);
		}
		else return $isRangedFlag;
	}
	
	
