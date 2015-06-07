<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*This library is a part of search engine that returns pdf matches to 
the user provided keywords.
This library extends the extractPdfData.php to retrieve the keywords 
from user provided string.
*/ 

class Search_pdf extends Extract_pdf_data {
	
	private $isFileNameMatched = false;
	private $isTitleMatched = false;
	private $isKeywordMatched = false;
	private $isContentMatched = false;
	private $matchedIdsArray = array();
	private $matchScore = array();
	
	//Number of ids returned on successful match in any case
	private $idReturnedOnSucess = 50;
	
	//Number of ids returned in lack of unsucessful match
	private $idReturnedOnFailure = 100;
	
	/*qualifying percentage for resultant array, only those values of 
	$is----Matched array which matched more than 40% will be included in resultant.*/
	private $sucessPercentage = 40;
	
	public function __construct(){
		
	}
	
	/*This function breaks input string into the stemmed keywords*/
	public function parseSearchString($searchString){
		$text = iconv(mb_detect_encoding($searchString, mb_detect_order(), true), "UTF-8", $searchString);
		return $this -> retrieveKeywords(trim(strtolower($text)));
	}
	
	public function calculatePercentage($missed, $outOf){
		$result = 0;
		$score = $outOf - $missed;
		if($score != 0)
			$result = round(($score/$outOf)*100, 0, PHP_ROUND_HALF_UP);
		return $result;
	}
	
	/*This function checks whether any file name matches the given search array
	On successful match sets an array with ids of matched file names and turn 
	on the flag 'isFilenameMatched'. This function sets an array with the 
	percentage of match in both conditions.*/ 
	public function matchFileName($dataArray, $searchArray){
		$j = 0;$k = 0;
		for($i=0; $i<count($dataArray); $i++){
			$fileNameKeyword = $this->parseSearchString(str_replace("_", " ", $dataArray[$i]["fileName"]));
			$unmatchedElement = array_diff(array_keys($searchArray), array_keys($fileNameKeyword));
			if(count($unmatchedElement) == 0 && $this -> isFileNameMatched == false){
				$this -> isFileNameMatched = true;
				$this -> matchedIdsArray[$j] = $dataArray[$i]["id"];
				$j++;
			}
			else if(count($unmatchedElement) == 0 && $this -> isFileNameMatched == true){
				$this -> matchedIdsArray[$j] = $dataArray[$i]["id"];
				$j++;
			}
			else if(count($unmatchedElement) != 0){
				$this -> matchScore[$dataArray[$i]["id"]] = array();
				$this -> matchScore[$dataArray[$i]["id"]]["fileName"] = 
							$this -> calculatePercentage(count($unmatchedElement), count($searchArray));
			}
		}
	}
	
	/*This function returns the no. of elements of an array exists in a string*/
	public function isStringContainArray($array, $string){
		$missed = 0;
		foreach($array as $element){
			//echo $element. ", ". $string ."<br />";
			if(strpos($string, $element) === false)
				$missed++;
		}
		return $missed;
	}
	
	/*This function checks whether any Title matches the given search array
	On successful match sets an array with ids of matched file names and turn 
	on the flag 'isTitleMatched'. This function sets an array with the 
	percentage of match in both conditions.*/ 
	public function matchTitle($dataArray, $searchArray){
		$j = 0;$k = 0;
		for($i=0; $i<count($dataArray); $i++){
			if($dataArray[$i]["title"] != ""){
				$unmatchedCount = $this -> isStringContainArray(array_keys($searchArray), strtolower($dataArray[$i]["title"]));
				if($unmatchedCount == 0 && $this -> isTitleMatched == false){
					$this -> isTitleMatched = true;
					$this -> matchedIdsArray[$j] = $dataArray[$i]["id"];
					$j++;
				}
				else if($unmatchedCount == 0 && $this -> isTitleMatched == true){
					$this -> matchedIdsArray[$j] = $dataArray[$i]["id"];
					$j++;
				}
				else if($unmatchedCount != 0){
					$this -> matchScore[$dataArray[$i]["id"]]["title"] = 
								$this -> calculatePercentage($unmatchedCount, count($searchArray));
				}
			}
			
		}
	}
	
	/*This function checks whether any Keyword matches the given search array
	On successful match sets an array with ids of matched file names and turn 
	on the flag 'isKeywordMatched'. This function sets an array with the 
	percentage of match in both conditions.*/ 
	public function matchKeyword($dataArray, $searchArray){
		$j = 0;$k = 0;
		for($i=0; $i<count($dataArray); $i++){
			if($dataArray[$i]["keyword"] != ""){
				$unmatchedCount = $this -> isStringContainArray(array_keys($searchArray), strtolower($dataArray[$i]["keyword"]));
				if($unmatchedCount == 0 && $this -> isKeywordMatched == false){
					$this -> isKeywordMatched = true;
					$this -> matchedIdsArray[$j] = $dataArray[$i]["id"];
					$j++;
				}
				else if($unmatchedCount == 0 && $this -> isKeywordMatched == true){
					$this -> matchedIdsArray[$j] = $dataArray[$i]["id"];
					$j++;
				}
				else if($unmatchedCount != 0){
					$this -> matchScore[$dataArray[$i]["id"]]["keyword"] = 
								$this -> calculatePercentage($unmatchedCount, count($searchArray));
				}
			}
		}
	}
	
	/*this function returns the resultant 2D array of Pdf info on successful file name match*/
	public function MatchSuccess($level){
		$matchedArray = array();
		$finalMatchedArray = array();
		$resultsLimit = $this -> idReturnedOnFailure;
		$i=0;
		
		if($level != "failure"){
			sort($this -> matchedIdsArray, SORT_NUMERIC);
			$resultsLimit = $this -> idReturnedOnSucess;
		}
		
		if($level == "fileName"){
			foreach($this -> matchScore as $key => $value)
				$matchedArray[$key] = $value["fileName"];
		}
		
		else if($level == "title"){
			foreach($this -> matchScore as $key => $value){
				if(isset($value["title"])){
					if($value["title"] === 0)
						$matchedArray[$key] = $value["fileName"];
					else
						$matchedArray[$key] = intval(($value["fileName"]+$value["title"])/2);
				}
			}
		}
		
		else if($level == "keyword" || $level == "failure"){
			foreach($this -> matchScore as $key => $value){
				if(isset($value["keyword"])){
					if($value["keyword"] === 0)
						$matchedArray[$key] = intval(($value["fileName"]+$value["title"])/2);
					else
						$matchedArray[$key] = intval(($value["fileName"]+$value["title"]+$value["keyword"])/3);
				}
			}
		}
		arsort($matchedArray, SORT_NUMERIC);
		
		if($level != "failure"){
			foreach($this -> matchedIdsArray as $element){
				if($i < $resultsLimit){
					$finalMatchedArray[$i] = intval($element);
					$i++;
				}
				else break;
			}
		}
		
		$j = count($finalMatchedArray);
		foreach($matchedArray as $key => $element){
			if($element > $this -> sucessPercentage && $j < $resultsLimit){
				$finalMatchedArray[$j] = $key;
				$j++;
			}
			else break;
		}
			
		return $finalMatchedArray;
	}
	
	/*This function ranks the matched result only on the basis of pdf info.
	function returns top 50 results if 100% matched found withing any 
	category (Name, Title, Keyword).
	function returns top 100 results if sorted by the percentage match.
	*/
	public function infoBasedRanking($dataArray, $searchArray){
		
		//check if file name has 100% match
		$this -> matchFileName($dataArray, $searchArray);
		
		//if file name failed than check title
		if($this -> isFileNameMatched == false){
			$this -> matchTitle($dataArray, $searchArray);
		}
		else{//file name match and generate resultant array
			$resultantArray = $this -> MatchSuccess("fileName");
			$resultantArray[count($resultantArray)] = true;
			return $resultantArray;
		}
		
		//if title failed than check keyword
		if($this -> isTitleMatched == false){
			$this -> matchKeyword($dataArray, $searchArray);
		}
		else{//title match and generate resultant array
			$resultantArray = $this -> MatchSuccess("title");
			$resultantArray[count($resultantArray)] = true;
			return $resultantArray;
		}
		
		//if keyword failed than check content
		if($this -> isKeywordMatched == false){
			$resultantArray = $this -> MatchSuccess("failure");
			$resultantArray[count($resultantArray)] = false;
			return $resultantArray;
		}
		else{//keyword match and generate resultant array
			$resultantArray = $this -> MatchSuccess("keyword");
			$resultantArray[count($resultantArray)] = true;
			return $resultantArray;
		}
	}
	
	/*This funtion ranks the array keys on the basis of values of 
	both arrays and returns the array of ids.*/
	public function ranking($percent){
		$percentage = array(); 
		$hits = array();
		 
		foreach($percent as $elementArray){
			$percentage[] = $elementArray["percent"];
			$hits[] = $elementArray["averageHit"]; 
		}
		
		array_multisort($percentage, SORT_DESC, $hits, SORT_DESC, $percent);
		return $percent;	
	}
	
	/*This function calculates the ranking on the basis of hits of search string in 
	the content of pdf and returns ranked array of ids. */
	public function contentBasedRanking($contentHitArray, $searchArray){
		$idPercentArray = array();
		$resultantArray = array();
		$i=0;$j=0;
		foreach($contentHitArray as $id => $keywordHitArray){
			$repetitionSum = 0;
			$matchedSearchElementCount = 0;
			foreach($keywordHitArray as $searchElement => $hit){
				if($hit !== 0){
					$matchedSearchElementCount++;
					$repetitionSum += $hit;
				}
			}
			if($matchedSearchElementCount != 0){
				$unmatchedElement = count($searchArray) - $matchedSearchElementCount;
				$idPercentArray[$i] = array();
				$idPercentArray[$i]["id"] = $id;
				$idPercentArray[$i]["percent"] = intval($this -> calculatePercentage($unmatchedElement, count($searchArray)));
				$idPercentArray[$i]["averageHit"] = $repetitionSum/$matchedSearchElementCount;
			}
			$i++;
		}
		
		$rankedArray = $this -> ranking($idPercentArray);
		foreach($rankedArray as $elementArray){
			if($j < $this -> idReturnedOnFailure){
				$resultantArray[] = $elementArray["id"];
				$j++;
			}
			else break;
			
		}
		$resultantArray[count($resultantArray)] = true;
		return $resultantArray;
	}
	
	/*This function changes the layout of 2D array passed to it.*/
	public function parseContentArray($hitsArray, $searchArray){
		$newLayout = array();
		$innerArrayLenghts = array();
		$longestArray = "";
		
		foreach($hitsArray as $key => $array){
			$innerArrayLenghts[$key] = count($array);
		}
		
		arsort($innerArrayLenghts, SORT_NUMERIC);
		reset($innerArrayLenghts);
		$longestArray = key($innerArrayLenghts);
		
		foreach($hitsArray[$longestArray] as $id => $count){
			$newLayout[$id] = array();
			$newLayout[$id][$longestArray] = $count;
		}
		
		foreach($hitsArray as $keyword => $idCountArray){
			if($keyword !== $longestArray){
				foreach($newLayout as $id => $keywordHitArray){
					if(isset($hitsArray[$keyword][$id])){
						$newLayout[$id][$keyword] = $hitsArray[$keyword][$id];
					}
					else{
						$newLayout[$id][$keyword] = 0;
					}
					
				}
			}
		}
		
		return $this -> contentBasedRanking($newLayout, $searchArray);
	}
	
	
	
	
	
}

?>