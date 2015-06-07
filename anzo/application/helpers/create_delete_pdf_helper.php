<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	/*This function creates pdf of curtrent article and saves it in pdfDirectory.
	At the same time updates the flag in database*/
	function createPdf($pathToFile, $targetFile, $content){
		require_once(APPPATH.'libraries/PdfCrowd.php');
		
		try{   
			// create an API client instance
			$client = new Pdfcrowd("pdfartery", "d4ef1050e001f72be64233fd1acb1a8e");
			
			$client->setPageMargins(".1in", ".1in", ".3in", ".1in");
			$client->setFooterHtml("<div style='float:left;'>%p</div><div style='float:right;'>The Artery</div>");
			$client->setAuthor("The Artery");
		
			// convert a web page and store the generated PDF into a $pdf variable
			$pdf_from_html = $client->convertHtml($content, fopen($pathToFile."/".$targetFile.".pdf", 'wb'));
			
			return true;
		}
		catch(PdfcrowdException $why){
			return $why;
		}
	}
	
	/*This function deletes the pdf from the directory and updates the flag in database */
	function removePdf($pathToFile, $targetFile){
		if (file_exists($pathToFile."/".$targetFile.".pdf")) {
			unlink($pathToFile."/".$targetFile.".pdf");
			return true;
		}
		else return false; 
		
	}
