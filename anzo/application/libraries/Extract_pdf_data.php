<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*This library extracts content of pdf and the meta information about that pdf
this make use of pdftotext software, work of David R. Nadaeu and porter stemmer library
to move this class to some other place you need to adjust the path to pdfdirectory and software
*/

class Extract_pdf_data{
	
	public function __construct(){
		require_once(APPPATH.'libraries/porterStemmer.php');
	}
	
	//this function replaces spaces in the filename with underscore and adds the extenion.
	public function parseFileName($inputString){
		$spaceReplaced = str_replace(" ", "_", $inputString);
		$specialCharacterLess = preg_replace('/[^a-zA-Z0-9-_]/', '', $spaceReplaced);
		$result = strtolower(trim($specialCharacterLess));
		return $result;
	}
	
	//This function generates array of stop words
	public function generateStopWords(){
		//stemmed version of stop words - so need to stem it again
		$stopWords = "a abl about abov abst accord accordingli across act actual ad adj affect after afterward again against ah all almost alon along alreadi also although alwai am among amongst an and announc anoth ani anybodi anyhow anymor anyon anyth anywai anywher keep kept kg km know known l larg last late later latter latterli least less lest let like line littl ll look ltd m made mainli make mani mai mayb me mean meantim meanwhil mere mg might t take taken tell tend th than thank thanx that that'll that'v the their them themselv then thenc there thereaft therebi therefor therein there'll thereof therer thereto thereupon there'v these thei theyd they'll theyr they'v think thi those thou appar approxim ar aren arent aris around as asid ask at auth avail awai awfulli b back be becam becaus becom been befor beforehand begin behind believ below besid between beyond biol both brief briefli but by million miss ml more moreov most mostli mr much mug must my myself n na name nai nd near nearli necessarili necessari need neither never nevertheless new next nine nineti no nobodi non none nonetheless noon nor normal not note noth though thoughh thousand throug through throughout thru thu til tip to togeth too took toward tri truli try ts twice two u un under unfortun unless unlik until unto up upon us usefulli usual c ca came can cannot can't caus certain certainli co com come contain could couldnt d date did didn't differ do doe doesn't done don't down downward due dure e each ed edu effect eg eight eighti either els elsewher now nowher o obtain obvious of off often oh ok okai old omit on onc onli onto or ord other otherwis ought our ourselv out outsid over overal ow own p page part particular particularli past per perhap place v valu variou ve veri via viz vol vs w want wa wasn't wai we wed welcom we'll went were weren't we've what whatev what'll when whenc whenev where whereaft wherea wherebi wherein whereupon wherev whether which while whim whither who end enough especi et et-al etc even ever everi everybodi everyon everyth everywher ex except f far few ff fifth first five fix follow for former formerli forth found four from further furthermor g gave get give given pleas plu poorli possibl potenti pp predominantli present previous primarili probabl promptli proud provid put q que quickli quit qv r ran rather rd re readili realli recent ref regard regardless relat rel research respect result right run s whod whoever whole who'll whom whomev whose why wide will wish with within without won't word world would wouldn't www x y ye yet you youd you'll your yourself yourselv you've z zero go goe gone got gotten h had happen hardli ha hasn't have haven't he hed henc her here hereaft herebi herein hereupon herself hi hid him himself hither home how howbeit howev hundr i id ie if i'll im immedi said same saw sai sec section see seem seen self selv sent seven sever shall she shed she'll should shouldn't show shown signific significantli similar similarli sinc six slightli so some somebodi somehow someon somethan import in inc inde index inform instead into invent inward is isn't it itd it'll itself i've j just k someth sometim somewhat somewher soon sorri specif specifi still stop strongli sub substanti successfulli such suffici suggest sup sure a' allow appropri c'mon consid correspond definit describ exactli exampl greet he' ain't apart aren't c' cant chang couldn't despit hadn't hello here' appear associ best clearli concern cours entir help appreci better consequ current i'm indic seriou it'd novel serious hopefulli i'd ignor inasmuch inner insofar it' let' presum reason second secondli sensibl t' they'd third who' that' there' they'r thorough three we'd well what' you're thoroughli where' wonder we're you'd";
		
		mb_regex_encoding( "utf-8" );
		return mb_split( ' +', $stopWords );
	}
	
	//This function generates array of extra words that need to be removed
	public function generateUnwantedWords(){
		// add more words to the space separated string to exclude them from keywords list.
		$unwantedWords = "all from of to and as write next dd mmm yyyy pin   d.o.b call age sex type";		
		mb_regex_encoding( "utf-8" );
		$unwantedWordsArray = mb_split( ' +', $unwantedWords );
		foreach ( $unwantedWordsArray as $key => $word )
			$unwantedWordsArray[$key] = PorterStemmer::Stem( $word, true );
		return $unwantedWordsArray;
	}
	
	/*this function converts pdf to text by using PdfToText software
	returns the associative array of keywords in the text.
	*/
	public function getPdfContent($rootDir, $fileName){
		$pathToSoftware = $rootDir."/thirdPartySoftware/xpdf_lin/bin64/pdftotext ";
		$pathToFile = $rootDir."/pdfDirectory/".$fileName.".pdf";
		$content  = shell_exec($pathToSoftware.$pathToFile.' -');
		$text = mb_convert_encoding($content, 'UTF-8');
		return $this -> retrieveKeywords($text);
	}
	
	/*this function retrieves keywords from the string passed to it
	in the from of associative array of keywords and their counts
	*/
	public function retrieveKeywords($rawText){
		//removing puntuation, special symbols, numbers and mathematical symbols removed
		$punctuationRemoved =  $this->strip_punctuation( $rawText );
		$specialSymbolsRemoved = $this -> strip_symbols( $rawText );
		$numbersRemoved = $this -> strip_numbers( $specialSymbolsRemoved );
		
		//bringing whole converted text to lower case
		$lowerCasedText = mb_strtolower( $numbersRemoved, "utf-8" );
		mb_regex_encoding( "utf-8" );
		
		//create an array of text string
		$wordsArray = mb_split( ' +', $lowerCasedText );
		
		//stemming all the words in the created array
		foreach ( $wordsArray as $key => $word ){
		$wordsArray[$key] = PorterStemmer::Stem( $word, true );
		}
		
		//removing stop words
		$stopWordsRemoved = array_diff( $wordsArray, $this->generateStopWords() );
		
		//removing unwanted words
		$unwantedWordsRemoved = array_diff( $stopWordsRemoved, $this->generateUnwantedWords() );
		
		//counted the occurence of a particular keyword 
		$keywordCounts = array_count_values( $unwantedWordsRemoved );
		arsort( $keywordCounts, SORT_NUMERIC );
		return $keywordCounts;
	}
	
	/*This function extracts information about pdf and returns an 
	associative array of information*/
	public function getPdfInfo($rootDir, $fileName){
		$result = array();
		$pathToSoftware = $rootDir."/thirdPartySoftware/xpdf_lin/bin64/pdfinfo ";
		$pathToFile = $rootDir."/pdfDirectory/".$fileName.".pdf";
		$infoToExtract = array("Title","Subject","Keywords","Author","Creator",
							"Producer","CreationDate","ModDate","Tagged","Form",
							"Pages","Encrypted","Page size","File size",
							"Optimized","PDF version");				$pdfInfoString = shell_exec($pathToSoftware.$pathToFile);				$pdfInfoArray = explode("\n",trim($pdfInfoString));
		for($i=0; $i<count($infoToExtract); $i++){
			for($j=0; $j<count($pdfInfoArray); $j++){
				if(false != ($value = strstr($pdfInfoArray[$j],$infoToExtract[$i]))){
					$arrayKey = strtolower($infoToExtract[$i]);
					$replacedValue = $infoToExtract[$i].":";
					$result[strtolower($infoToExtract[$i])] =  trim(str_replace($infoToExtract[$i].":", " ", $value));
					break;
				}
				elseif($j == count($pdfInfoArray)-1)
					$result[strtolower($infoToExtract[$i])] =  "";
			}
		}

		return $result;
	}
	
	//this function gets path to root directory
	public function getPathToRoot(){
		$pathToCurrent = realpath(dirname(__FILE__));
		$frameworkFolders = array("controllers", "site", "application", "Artery", "libraries","//", "arteri");
		$pathToRoot = str_replace($frameworkFolders, "", $pathToCurrent);
		$result = str_replace('\\', '/', $pathToRoot);
		return $result;
	}
	
	/*this function loop through all the files of a particular directory
	checks whether the file is already indexed if not rename it with parsed
	new name.
	returns array of pdf file's names without extensions.*/
	public function getArrayOfFilenames ($dir, $indexedFiles){
		$result = array();
		$j = 0;
		foreach (glob($dir."/pdfDirectory/*.pdf") as $fileName) {
			$extLessFileName = trim(basename($fileName, ".pdf"));
			if(in_array($extLessFileName, $indexedFiles) === false){
				$newName =  $this -> parseFileName($extLessFileName);
				rename($fileName,$dir."/pdfDirectory/".$newName.".pdf");
				$result[$j] = $newName;
				$j++;
			}
		}
		return $result;
	}
	
	/*open source library function written by David R. Nadeau to strip 
	punctuation from given text*/
	public function strip_punctuation( $text ){
		$urlbrackets    = '\[\]\(\)';
		$urlspacebefore = ':;\'_\*%@&?!' . $urlbrackets;
		$urlspaceafter  = '\.,:;\'\-_\*@&\/\\\\\?!#' . $urlbrackets;
		$urlall         = '\.,:;\'\-_\*%@&\/\\\\\?!#' . $urlbrackets;
	
		$specialquotes = '\'"\*<>';
	
		$fullstop      = '\x{002E}\x{FE52}\x{FF0E}';
		$comma         = '\x{002C}\x{FE50}\x{FF0C}';
		$arabsep       = '\x{066B}\x{066C}';
		$numseparators = $fullstop . $comma . $arabsep;
	
		$numbersign    = '\x{0023}\x{FE5F}\x{FF03}';
		$percent       = '\x{066A}\x{0025}\x{066A}\x{FE6A}\x{FF05}\x{2030}\x{2031}';
		$prime         = '\x{2032}\x{2033}\x{2034}\x{2057}';
		$nummodifiers  = $numbersign . $percent . $prime;
	
		return preg_replace(
			array(
			// Remove separator, control, formatting, surrogate,
			// open/close quotes.
				'/[\p{Z}\p{Cc}\p{Cf}\p{Cs}\p{Pi}\p{Pf}]/u',
			// Remove other punctuation except special cases
				'/\p{Po}(?<![' . $specialquotes .
					$numseparators . $urlall . $nummodifiers . '])/u',
			// Remove non-URL open/close brackets, except URL brackets.
				'/[\p{Ps}\p{Pe}](?<![' . $urlbrackets . '])/u',
			// Remove special quotes, dashes, connectors, number
			// separators, and URL characters followed by a space
				'/[' . $specialquotes . $numseparators . $urlspaceafter .
					'\p{Pd}\p{Pc}]+((?= )|$)/u',
			// Remove special quotes, connectors, and URL characters
			// preceded by a space
				'/((?<= )|^)[' . $specialquotes . $urlspacebefore . '\p{Pc}]+/u',
			// Remove dashes preceded by a space, but not followed by a number
				'/((?<= )|^)\p{Pd}+(?![\p{N}\p{Sc}])/u',
			// Remove consecutive spaces
				'/ +/',
			),
			' ',
			$text );
	}
	
	/*open source library function written by David R. Nadeau to strip 
	useless symbols from given text*/
	public function strip_symbols( $text ){
		$plus   = '\+\x{FE62}\x{FF0B}\x{208A}\x{207A}';
		$minus  = '\x{2012}\x{208B}\x{207B}';
	
		$units  = '\\x{00B0}\x{2103}\x{2109}\\x{23CD}';
		$units .= '\\x{32CC}-\\x{32CE}';
		$units .= '\\x{3300}-\\x{3357}';
		$units .= '\\x{3371}-\\x{33DF}';
		$units .= '\\x{33FF}';
	
		$ideo   = '\\x{2E80}-\\x{2EF3}';
		$ideo  .= '\\x{2F00}-\\x{2FD5}';
		$ideo  .= '\\x{2FF0}-\\x{2FFB}';
		$ideo  .= '\\x{3037}-\\x{303F}';
		$ideo  .= '\\x{3190}-\\x{319F}';
		$ideo  .= '\\x{31C0}-\\x{31CF}';
		$ideo  .= '\\x{32C0}-\\x{32CB}';
		$ideo  .= '\\x{3358}-\\x{3370}';
		$ideo  .= '\\x{33E0}-\\x{33FE}';
		$ideo  .= '\\x{A490}-\\x{A4C6}';
	
		return preg_replace(
			array(
			// Remove modifier and private use symbols.
				'/[\p{Sk}\p{Co}]/u',
			// Remove math symbols except + - = ~ and fraction slash
				'/\p{Sm}(?<![' . $plus . $minus . '=~\x{2044}])/u',
			// Remove + - if space before, no number or currency after
				'/((?<= )|^)[' . $plus . $minus . ']+((?![\p{N}\p{Sc}])|$)/u',
			// Remove = if space before
				'/((?<= )|^)=+/u',
			// Remove + - = ~ if space after
				'/[' . $plus . $minus . '=~]+((?= )|$)/u',
			// Remove other symbols except units and ideograph parts
				'/\p{So}(?<![' . $units . $ideo . '])/u',
			// Remove consecutive white space
				'/ +/',
			),
			' ',
			$text );
	}

	/*open source library function written by David R. Nadeau to strip 
	numbers and mathematical symbols from given text*/
	public function strip_numbers( $text ){
		$urlchars      = '\.,:;\'=+\-_\*%@&\/\\\\?!#~\[\]\(\)';
		$notdelim      = '\p{L}\p{M}\p{N}\p{Pc}\p{Pd}' . $urlchars;
		$predelim      = '((?<=[^' . $notdelim . '])|^)';
		$postdelim     = '((?=[^'  . $notdelim . '])|$)';
		 
		$fullstop      = '\x{002E}\x{FE52}\x{FF0E}';
		$comma         = '\x{002C}\x{FE50}\x{FF0C}';
		$arabsep       = '\x{066B}\x{066C}';
		$numseparators = $fullstop . $comma . $arabsep;
		$plus          = '\+\x{FE62}\x{FF0B}\x{208A}\x{207A}';
		$minus         = '\x{2212}\x{208B}\x{207B}\p{Pd}';
		$slash         = '[\/\x{2044}]';
		$colon         = ':\x{FE55}\x{FF1A}\x{2236}';
		$units         = '%\x{FF05}\x{FE64}\x{2030}\x{2031}';
		$units        .= '\x{00B0}\x{2103}\x{2109}\x{23CD}';
		$units        .= '\x{32CC}-\x{32CE}';
		$units        .= '\x{3300}-\x{3357}';
		$units        .= '\x{3371}-\x{33DF}';
		$units        .= '\x{33FF}';
		$percents      = '%\x{FE64}\x{FF05}\x{2030}\x{2031}';
		$ampm          = '([aApP][mM])';
		  
		$digits        = '[\p{N}' . $numseparators . ']+';
		$sign          = '[' . $plus . $minus . ']?';
		$exponent      = '([eE]' . $sign . $digits . ')?';
		$prenum        = $sign . '[\p{Sc}#]?' . $sign;
		$postnum       = '([\p{Sc}' . $units . $percents . ']|' . $ampm . ')?';
		$number        = $prenum . $digits . $exponent . $postnum;
		$fraction      = $number . '(' . $slash . $number . ')?';
		$numpair       = $fraction . '([' . $minus . $colon . $fullstop . ']' . $fraction . ')*';
	
		return preg_replace(
			array(
			// Match delimited numbers
				'/' . $predelim . $numpair . $postdelim . '/u',
			// Match consecutive white space
				'/ +/u',
			),
			' ',
			$text );
	}

	/*php convert Thu Jul 28 07:34:53 2011 into YYYY-MM-DD HH:MM:SS
	This one is for the linux	*/
	public function changeFormat($timestamp){
		date_default_timezone_set('America/New_York');
		return date("Y-m-d", strtotime($timestamp));;
	}

	/*php convert 09/06/12 09:13:11 into YYYY-MM-DD HH:MM:SS for windows*/
	/*public function changeFormat($timestamp){
		$breakComponents = explode(" ", $timestamp);
		$date = DateTime::createFromFormat("d/m/y", $breakComponents[0]);
		$formatedDate = $date->format("Y-m-d");
		$result = $formatedDate." ".$breakComponents[1];
		return $result;
	}*/

}





?>