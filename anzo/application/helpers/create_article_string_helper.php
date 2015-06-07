<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	/*This function retrieves the specific article's data for given article id
	from the database*/
	function getRawArticle($articleId, $purpose = "generate"){
		$tagString = "";
		$ci =& get_instance();
		$ci->load->model('admin/read_model');
		
		$controlsArray = $ci->read_model->getControlList($articleId);
		
		for($i=0; $i<count($controlsArray); $i++){
			
			if($controlsArray[$i]['tagName'] == "label"){
				$labelData = $ci->read_model->getControlInfo($controlsArray[$i]['id'], "label");
				
				$tagString .= "<label id='".$controlsArray[$i]['tagId']."' class = 'item' for='"
				.$labelData['forValue']."' style='top:".$controlsArray[$i]['yAxis']
				.";left:".$controlsArray[$i]['xAxis'].";font-size:".$labelData['fontSize']
				.";color:".$labelData['color'].";position:absolute;"
				."font-family:Tahoma;width:auto; height:auto;text-align:left;'>"
				.$labelData['innerText']."</label> ";
			}
			elseif($controlsArray[$i]['tagName'] == "input"){
				$inputArray = $ci->read_model->getInputInfo($controlsArray[$i]['id'], "input");
				
				if($inputArray['type'] == "radio" || $inputArray['type'] == "checkbox"){
					$tagString .= "<input type='".$inputArray['type']."' name='"
					.$inputArray['name']."' id='".$controlsArray[$i]['tagId']
					."' class='item' value='".$inputArray['value']
					."' style='position:absolute;top:".$controlsArray[$i]['yAxis']
					.";left:".$controlsArray[$i]['xAxis'].";' ";
					
					if($inputArray['required'] == 1)
						$tagString .= "required = 'required' ";
						
					if($inputArray['checked'] == 1)
						$tagString .= "checked = 'checked' ";
					
					$tagString .= "/>";
				}
				
				else{
					
					$tagString .= "<input type='".$inputArray['type']."' name='"
					.$inputArray['name']."' id='".$controlsArray[$i]['tagId']
					."' class='item' style='position:absolute;top:".$controlsArray[$i]['yAxis']
					.";left:".$controlsArray[$i]['xAxis'].";height:"
					.$controlsArray[$i]['height'].";width:".$controlsArray[$i]['width']
					.";font-size:".$inputArray['fontSize'].";border:1px solid black;' ";
					
					if($inputArray['required'] == 1)
						$tagString .= "required = 'required' ";
						
					$tagString .= "/>";
				}
				
			}
			elseif($controlsArray[$i]['tagName'] == "select"){
				$selectArray = $ci->read_model->getSelectInfo($controlsArray[$i]['id'], "selecttag");
				
				$tagString .= "<select id='".$controlsArray[$i]['tagId']."' ";
				
				if($purpose == "submission") $tagString .= "name='".$selectArray['name']."[]' ";
				else $tagString .= "name='".$selectArray['name']."' ";
				 
				 $tagString .= "class='item' size='".$selectArray['size']
				."' style='position:absolute; top:".$controlsArray[$i]['yAxis']."; left:"
				.$controlsArray[$i]['xAxis'].";height:".$controlsArray[$i]['height'].";width:".$controlsArray[$i]['width']."' ";
				
				if($selectArray['required'] == 1)
						$tagString .= "required = 'required' ";
						
				if($selectArray['multiple'] == 1)
						$tagString .= "multiple = 'multiple' ";
				
				$tagString .= ">";
				
				$optionList = $ci->read_model->getOptionInfo($selectArray['controlId']);
				for($j=0; $j<count($optionList); $j++){
					$tagString .= "<option value='".$optionList[$j]['value']."' ";
					if($optionList[$j]['value'] == 1)
						$tagString .= "selected ";
					$tagString .= ">".$optionList[$j]['displayText']."</option> ";
				}
				
				$tagString .= "</select>"; 
			}
			elseif($controlsArray[$i]['tagName'] == "div"){
				$divArray = $ci->read_model->getControlInfo($controlsArray[$i]['id'], "division");
				
				$tagString .= "<div id='".$controlsArray[$i]['tagId']."' class='item' style='position:absolute;top:"
				.$controlsArray[$i]['yAxis']."; left:".$controlsArray[$i]['xAxis']."; width:"
				.$controlsArray[$i]['width']."; height:".$controlsArray[$i]['height'].";' data-inline = 'true'>"
				.$divArray['innerText']."</div>";
			}
			elseif($controlsArray[$i]['tagName'] == "textarea"){
				$textArray = $ci->read_model->getTextAreaInfo($controlsArray[$i]['id'], "textarea");
				
				$tagString .= "<textarea id='".$controlsArray[$i]['tagId']."' name='"
				.$textArray['name']."' class='item' rows='".$textArray['rows']
				."' cols='".$textArray['columns']."' style='position:absolute; top:"
				.$controlsArray[$i]['yAxis']."; left:".$controlsArray[$i]['xAxis']."; width:"
				.$controlsArray[$i]['width']."; height:".$controlsArray[$i]['height']."; font-size:"
				.$textArray['fontSize']."; border:1px solid black;' ";
				
				if($textArray['required'] == 1)
						$tagString .= "required = 'required' ";
						
				$tagString .= "></textarea>";
			}
		}
		return $tagString;
	}
	
	/*This function removes the special characters from the given string.*/
	function removeSpecialChar($string){
		$spaceReplaced = preg_replace('/\s\s+/', ' ', $string);
		$specialCharacterLess = preg_replace('/[^a-zA-Z0-9-_\s]/', '', $spaceReplaced);
		return $specialCharacterLess;
	}
