<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload_and_index extends CI_Controller {
	
	private $messageToView = ""; 
	
	public function __construct(){
		parent::__construct();
		$this->load->model('admin/upload_and_index_model');
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('create_article_string');
		$this->load->library('extract_pdf_data');
		$this->load->library("pagination");
		$this->load->helper('add_pages');
		$this->load->helper('create_delete_pdf');
		$this->load->library('ckeditor');
        $this->load->library('ckfinder');
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
		
		if(isset($relatedInfo['uploadMsg']))$pageData['uploadMsg'] = $relatedInfo['uploadMsg'];
		if(isset($relatedInfo['listOfFiles']))$pageData['listOfFiles'] = $relatedInfo['listOfFiles'];
		if(isset($relatedInfo['links']))$pageData['links'] = $relatedInfo['links'];
		if(isset($relatedInfo['backLink']))$pageData['backLink'] = $relatedInfo['backLink'];
		if(isset($relatedInfo['returnedMsg']))$pageData['returnedMsg'] = $relatedInfo['returnedMsg'];
		
		$this->load->view('admin/templates/header', $headerData);
		$this->load->view('admin/pages/'.$page, $pageData);
		$this->load->view('admin/templates/footer');
	}
	
	/*gather the information required to load the view*/
	public function loadUploadAndIndexView($uploadMsg =""){
		$userName = $this->session->userdata('userName');
		
		//check whether session is not expired yet
		if($userName != ""){
			$headerInfo['viewDescription'] = "Indexing PDFs and Articles";
			$stylesheet = "<link href='".base_url()."css/admin/upload_and_index.css' rel='stylesheet' type='text/css' />\n";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/jquery.form.js' ></script>\n";
			$stylesheet .= "<link href='".base_url()."css/admin/action.css' rel='stylesheet' type='text/css' />\n";
			$stylesheet .= "<script type='text/javascript' src='".base_url()."js/admin/indexing.js' ></script>\n";
			$headerInfo['isLoggedIn'] = true;
			$headerInfo['userName'] = ucwords($userName);
			$headerInfo['uploadMsg'] = $uploadMsg;
			$headerInfo['backLink'] = "administrator/login/profileLogIn";
			
			$this->loadingView($stylesheet, "upload_and_index_view", $headerInfo);
		}
		else{redirect('administrator/login/index');}
	}
	
	/*Indexing pdf files by using extractPdfData library*/
	public function indexPdfFiles(){
		//recording time when request received
		$mtime = microtime();
		$mtime = explode(" ",$mtime);
		$mtime = $mtime[1] + $mtime[0];
		$startTime = $mtime;
		
		$extLessFileNameArray = array();
		$pdfDataArray = array();
		//retrieve all files already indexed
		$indexedFilesArray = $this->upload_and_index_model->getAllFileNames();
		$rootDir = $this->extract_pdf_data->getPathToRoot();
		$extLessFileNameArray = $this->extract_pdf_data -> getArrayOfFilenames ($rootDir, $indexedFilesArray);
		//extracting info and content of pdf files
		for($i=0; $i<count($extLessFileNameArray); $i++){
			$pdfInfo = $this->extract_pdf_data->getPdfInfo($rootDir, $extLessFileNameArray[$i]);
			$pdfDataArray[$i]= array();
			$pdfDataArray[$i]["name"]= $extLessFileNameArray[$i];
			if(isset($pdfInfo["title"]) == true )$pdfDataArray[$i]["title"]=$pdfInfo["title"];
			if(isset($pdfInfo["keywords"]) == true )$pdfDataArray[$i]["keywords"]=$pdfInfo["keywords"];
			if(isset($pdfInfo["author"]) == true )$pdfDataArray[$i]["author"]=$pdfInfo["author"];
			if(isset($pdfInfo["creationdate"]) == true )$pdfDataArray[$i]["creationdate"]=$this->extract_pdf_data->changeFormat($pdfInfo["creationdate"]);
			$pdfDataArray[$i]["content"]= $this->extract_pdf_data->getPdfContent($rootDir, $extLessFileNameArray[$i]);
		}
		set_time_limit(1200);
		//inserting info and content of pdf into database and receiving number of rows inserted.
		$insertedRows = $this->upload_and_index_model->forwardingToDatabase($pdfDataArray);		
		
		$mtime = microtime();
		$mtime = explode(" ",$mtime);
		$mtime = $mtime[1] + $mtime[0];
		$endtime = $mtime;
		$timeTaken = ($endtime - $startTime);
		
		if(count($extLessFileNameArray) > 0 && $insertedRows != false){
			echo json_encode(array("response" => "success", 
					"msg" => "Indexing is successful, ".$insertedRows." files are indexed in "
							.round($timeTaken,2)." seconds." ));
		}
		elseif(count($extLessFileNameArray) == 0){
			echo json_encode(array("response" => "success", 
					"msg" => "<span>There is not any new pdf file to index.</span>" ));
		}
		else{
			echo json_encode(array("response" => "fail", 
					"msg" => "<span>Some database error occured.</span>" ));
		}
		
	}
	
	/*This function returns the array of keywords with their count in the article.
	Function accepts article id for which gathers the data then first parse it 
	and feed to the method of external library extract pdf info content*/
	public function extractArticleKeywords($id){
		$articleDataString = getRawArticle($id);
		
		//parse the artcle data into text string
		$inputString = strtolower(iconv(mb_detect_encoding($articleDataString, mb_detect_order(), true), "UTF-8", $articleDataString));
		$strippedString = strip_tags($inputString);		
		$parsedString = preg_replace('/\s+/', ' ',$strippedString);
		$parsedString = preg_replace('/[^a-zA-Z0-9\s]/', '', $parsedString);
		
		return $this->extract_pdf_data->retrieveKeywords($parsedString);
	}
	
	//Index articles
	public function indexArticles(){
		//recording time when request received
		$mtime = microtime();
		$mtime = explode(" ",$mtime);
		$mtime = $mtime[1] + $mtime[0];
		$startTime = $mtime;
		
		$response = "noNew";
		$errorIds = "";
		
		/*getting list of articles already indexed and the match them with 
		all articles to find new unindexed articles*/
		$indexedIds = $this->upload_and_index_model->getIndexedArticles();
		$newArticles = $this->upload_and_index_model->getNewArticles($indexedIds);
		
		//extract the keywords of only new articles
		for($i=0; $i<count($newArticles); $i++){
			$keywordArray = $this->extractArticleKeywords($newArticles[$i]);
			foreach($keywordArray as $key=>$value){
				$data = array("articleId" => $newArticles[$i], "keyword" => $key, "count" => $value );
				if($this->upload_and_index_model->saveArticleKeywords($data) == 1)
					$response = "success";
				else{ 
					$response = "fail";
					$errorIds = $newArticles[$i].", ";
				}
			}
		}
		
		$mtime = microtime();
		$mtime = explode(" ",$mtime);
		$mtime = $mtime[1] + $mtime[0];
		$endtime = $mtime;
		$timeTaken = ($endtime - $startTime);
		
		if( $response == "success"){
			echo json_encode(array("response" => "success", 
					"msg" => "Indexing is successful, ".count($newArticles)." Articles indexed in "
							.round($timeTaken,2)." seconds." ));
		}
		elseif( $response == "noNew"){
			echo json_encode(array("response" => "noNew", 
					"msg" => "<span>There is not any new article to index.</span>" ));
		}
		else{
			echo json_encode(array("response" => "fail", 
					"msg" => "<span>Some database error occured.</span>" ));
		}
		
	}
	
	
	
	
	
	
}

?>
