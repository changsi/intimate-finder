<?php
require_once getLibFilePath("util.Stemmer");

class StringHelper {
	
	//stemming the word
	public static function stem($str){
		$stemmer = new Stemmer();
		return $stemmer->stem($str);
	}

	//normalize the string
	public static function normalizeString($str){
		$str = self::removeAccent($str);
		$str = self::removeNonUTF8($str);
		
		$str = preg_replace('/\t/',' ', $str);
		$str = preg_replace('/( )+/',' ', $str);
		$str = trim($str);
		$str = strtolower($str);
		$str = preg_replace('(-| )','_', $str);
		$str = preg_replace('/(_)+/','_', $str);
		$str = strtolower($str);
		$str = trim($str);
		
		//echo $str;
		
		return $str;
	}

	//remove accent
	public static function removeAccent($str){
		setlocale(LC_ALL, 'en_US.utf8');
		return iconv('utf8', 'ascii//TRANSLIT', $str);
	}

	//remove non UTF-8 chacrater
	public static function removeNonUTF8($str){
		return preg_replace('/[^(\x20-\x7F)]*/','', $str);
	}

	public static function getHashCode($str){
		$hash = 0;
		
		for ($i = 0; $i < strlen($str); $i++){
			$hash = self::getBigInt(bcadd(bcmul(31, $hash), ord($str[$i])));	
			//echo $str[$i] . " - " . ord($str[$i]) . " - " . $hash . "<br><br>";
		}
		
		return $hash;
	}

	public static function getHashCodePositive($str){
		$hash = self::getHashCode($str);
		
		if($hash < 0) {
			$abs = bcsub(0, $hash);
			$hash = bcadd(MAX_OVERFLOW_VALUE, bcsub($abs, 1));
		}
		
		return $hash;
	}
	
	//get big integer's value, consistent with core-platform
	public static function getBigInt($num) {
		$result = $num;
		$max = MAX_OVERFLOW_VALUE;
		$min = MIN_OVERFLOW_VALUE;
		$length = bcsub($max, $min);
		$flag = bccomp($num, $min);
		
		//process smaller negative value
		if($flag == -1) {
			$result = bcsub(0, $result);
		}

		if(bccomp($result, $max) == 1) {
			$f = bcdiv($result, $length);
			$r = bcmod($result, $length);
			
			if($f == 0) {
				$r = bcsub($result, $max);
			}

			$result = bcadd($min, bcsub($r,1));

			if($flag == -1) {
				$result = bcsub(0, $result);
			}

			//echo $f . " - " . $r . " - " . $result . "--<br>";
		}

		return $result;
	}
	
	public static $Eng_str = "the or not and your where is was hello help all application applications for like up this to it in of and you are if like this the his her a an as out will by other accelerometer us must are at then why when where what which its Its than includes have while avoid ready on each behind only our their every cannot get from them yet some new provides now comes App with including many I rather it very she but even more makes been Youve also such few just Ever so someone Want can our into such the Youll You do that be those between app Im that were can couldnt couldn't it sure my youll Get on has through has Go Or they we We into Have it also were much use Do maybe has so They Become Did try especially brought there it you about same These Ive youre onto how to em during more after most these Yours cant would is it who how these could always However however How put what any doesnt make one best me men gave women a able about above abroad according accordingly across actually adj after afterwards again against ago ahead ain't all allow allows almost alone along alongside already also although always am amid amidst among amongst an and another any anybody anyhow anyone anything anyway anyways anywhere apart appear appreciate appropriate are aren't around as a's aside ask asking associated at available away awfully b back backward backwards be became because become becomes becoming been before beforehand begin behind being believe below beside besides best better between beyond both brief but by c came can cannot cant can't caption cause causes certain certainly changes clearly c'mon co co. com come comes concerning consequently consider considering contain containing contains corresponding could couldn't course c's currently d dare daren't definitely described despite did didn't different directly do does doesn't doing done don't down downwards during e each edu eg eight eighty either else elsewhere end ending enough entirely especially et etc even ever evermore every everybody everyone everything everywhere ex exactly example except f fairly far farther few fewer fifth first five followed following follows for forever former formerly forth forward found four from further furthermore g get gets getting given gives go goes going gone got gotten greetings h had hadn't half happens hardly has hasn't have haven't having he he'd he'll hello help hence her here hereafter hereby herein here's hereupon hers herself he's hi him himself his hither hopefully how howbeit however hundred i i'd ie if ignored i'll i'm immediate in inasmuch inc inc. indeed indicate indicated indicates inner inside insofar instead into inward is isn't it it'd it'll its it's itself i've j just k keep keeps kept know known knows l last lately later latter latterly least less lest let let's like liked likely likewise little look looking looks low lower ltd m made mainly make makes many may maybe mayn't me mean meantime meanwhile merely might mightn't mine minus miss more moreover most mostly mr mrs much must mustn't my myself n name namely nd near nearly necessary need needn't needs neither never neverf neverless nevertheless new next nine ninety no nobody non none nonetheless noone no-one nor normally not nothing notwithstanding novel now nowhere o obviously of off often oh ok okay old on once one ones one's only onto opposite or other others otherwise ought oughtn't our ours ourselves out outside over overall own p particular particularly past per perhaps placed please plus possible presumably probably provided provides q que quite qv r rather rd re really reasonably recent recently regarding regardless regards relatively respectively right round s said same saw say saying says second secondly see seeing seem seemed seeming seems seen self selves sensible sent serious seriously seven several shall shan't she she'd she'll she's should shouldn't since six so some somebody someday somehow someone something sometime sometimes somewhat somewhere soon sorry specified specify specifying still sub such sup sure t take taken taking tell tends th than thank thanks thanx that that'll thats that's that've the their theirs them themselves then thence there thereafter thereby there'd therefore therein there'll there're theres there's thereupon there've these they they'd they'll they're they've thing things think third thirty this thorough thoroughly those though three through throughout thru thus till to together too took toward towards tried tries truly try trying t's twice two u un under underneath undoing unfortunately unless unlike unlikely until unto up upon upwards us use used useful uses using usually v value various versus very via viz vs w want wants was wasn't way we we'd welcome well we'll went were we're weren't we've what whatever what'll what's what've when whence whenever where whereafter whereas whereby wherein where's whereupon wherever whether which whichever while whilst whither who who'd whoever whole who'll whom whomever who's whose why will willing wish with within without wonder won't would wouldn't x y yes yet you you'd you'll your you're yours yourself yourselves you've z zero";
	
	//remove words which are not useful in the parsing
	public static function removeMeaninglessWords($text_array){
		global $Eng_str;
	
		$filterarray = explode(" ", $Eng_str);
		
		$new_text_array = array();
		
		foreach($text_array as $word){
			$flag = true;
			
			foreach($filterarray as $filter){
				if(strtolower($word) == $filter){
					$flag = false;
					break;
				}
			}
			
			if($flag){
				if($word != ''){	//check if $word is empty
					$new_text_array[] = $word;
				}
			}
		}
		
		return $new_text_array;
	}
	
	//remove url link in the text
	public static function removeUrl($text){
		$pat = "/(?#Protocol)(?:(?:ht|f)tp(?:s?)\:\/\/|~\/|\/)?(?#Username:Password)(?:\w+:\w+@)?(?#Subdomains)(?:(?:[-\w]+\.)+(?#TopLevel Domains)(?:com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum|travel|[a-z]{2}))(?#Port)(?::[\d]{1,5})?(?#Directories)(?:(?:(?:\/(?:[-\w~!$+|.,=]|%[a-f\d]{2})+)+|\/)+|\?|#)?(?#Query)(?:(?:\?(?:[-\w~!$+|.,*:]|%[a-f\d{2}])+=?(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)(?:&(?:[-\w~!$+|.,*:]|%[a-f\d{2}])+=?(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)*)*(?#Anchor)(?:#(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)?/";
		
		return preg_replace($pat,"",$text);
	}
	
	//remove Punctuation Character
	public static function removePunctuation($text){
		$pat = "/[^a-zA-Z0-9']+/";
		
		return preg_replace($pat," ",$text);
	}
	
	//remove duplicate space
	public static function removeDuplicateSpace($text){
		$pat = "/[ ]{2,}?/";
		
		return preg_replace($pat," ",$text);
	}
	
	//remove words less than 3 characters
	public static function removeWordslessthan3($text_array) {
		$array_text = array();
		
		foreach($text_array as $text) {
			if(strlen($text) > 2) {
				array_push($array_text,$text);
			}
		}
		
		return($array_text);
	}
	
	//remove numeric fields
	public static function removeNumericFields($text_array) {
		$array_text = array();
		
		foreach($text_array as $text) {
			if (!preg_match("/^[0-9]+$/",$text)) {
				array_push($array_text,$text);
			}
		}
		
		return($array_text);
	}
	
	//get keywords array from text
	public static function getWordsFromText($text){
		$keywords = array();
	
		$text = strtolower($text);
		$text = self::removeUrl($text);
		$text = self::removePunctuation($text);
		$text = self::removeDuplicateSpace($text);
		
		$keywords = explode(" ", $text);
		$keywords = self::removeMeaninglessWords($keywords);
		
		$keywords = self::removeWordslessthan3($keywords);
		$keywords = self::removeNumericFields($keywords);
		
		//ksort($keywords);
		
		return $keywords;
	}
}
?>
