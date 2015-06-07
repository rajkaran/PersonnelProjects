<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	function isEmpty($string){
		$result = false;
		if(trim($string) == "" || $string == null || $string == false)
			$result = true;
		return $result;
	}
	
	function isDate($string){
		$format = "Y-m-d";
		$result = true;
		
		$date = DateTime::createFromFormat($format, $string);
		if ($date == false ) 
			$result = false;
		return $result;
	}
	
	function isNumber($string){
		$result = true;
		if((int)trim($string) === 0)
			$result = false;
		return $result;
	}
	
	function isEmail($string){
		$result = true;
		if(valid_email(trim($string)) == false)
			$result = false;
		return $result;
	}
	
	function isEqual($string1, $string2){
		$result = false;
		if(trim($string1) === trim($string2))
			$result = true;
		return $result;
	}
	
	
	
