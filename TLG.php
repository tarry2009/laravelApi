<?php

/**
 * TLG (Tools Libraries Gadgets)
 * PaperG - added ability ...
 * 
 */
 
 class TLG
{
    private $dom = null;  
    public $nodes = array();
    public $parent = null;
    public $children = array();
    public $tag_start = 0;


    function __construct($dom)
    {
        
    }

    function __destruct()
    {
        $this->clear();
    }
 
    // clean up memory due to php5 circular references memory leak...
    function clear()
    {
        $this->dom = null;
        $this->nodes = null;
        $this->parent = null;
        $this->children = null;
        $this->tag_start = null;
    }
    
	 public static function strip($str)
		{
			$str = str_replace(chr(194) . chr(160), " ", $str);
			$str = preg_replace("/\s+|\n/", " ", $str);
			return trim($str);
		}

    public static function generateId($text)
    {
        $str = crc32($text);
        $id = sprintf("%u",$str);
        return $id;
    }

    public static function normalize($string)
    {
        $table = array(
            'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
            'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
            'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
            'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
            'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
            'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
            'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
        );

        return strtr($string, $table);
    }
    public static function toUTF8($text)
    {
        return iconv("ISO-8859-1", "UTF-8//IGNORE", $text);
    }


    public static function encode_funct($x)
    {
        if ($x=='&amp;') {return $x;}
        if ($x=='&euro;') {return '&#x20AC;';}
        return '&#'.ord(html_entity_decode($x,ENT_NOQUOTES,'UTF-8')).';';
    }

    public static function encode($text)
    {

        if (mb_detect_encoding($text)!='UTF-8')
        {
            $text = mb_convert_encoding($text,'UTF-8');
        }

        $text  = html_entity_decode($text,ENT_QUOTES,"UTF-8");

        $text2 = htmlentities($text,ENT_QUOTES,"UTF-8");


        //sometimes mb_detect_encoding not detect utf8, so htmlentities returns empty string
        if (empty($text2))
        {
            $text  = mb_convert_encoding($text,'UTF-8');
            $text2 = htmlentities($text,ENT_QUOTES,"UTF-8");
        }

        $codes = array(
            'À'=>'&Agrave;',
            'à'=>'&agrave;',
            'Á'=>'&Aacute;',
            'á'=>'&aacute;',
            'Â'=>'&Acirc;',
            'â'=>'&acirc;',
            'Ã'=>'&Atilde;',
            'ã'=>'&atilde;',
            'Ä'=>'&Auml;',
            'ä'=>'&auml;',
            'Å'=>'&Aring;',
            'å'=>'&aring;',
            'Æ'=>'&AElig;',
            'æ'=>'&aelig;',
            'Ç'=>'&Ccedil;',
            'ç'=>'&ccedil;',
            'Ð'=>'&ETH;',
            'ð'=>'&eth;',
            'È'=>'&Egrave;',
            'è'=>'&egrave;',
            'É'=>'&Eacute;',
            'é'=>'&eacute;',
            'Ê'=>'&Ecirc;',
            'ê'=>'&ecirc;',
            'Ë'=>'&Euml;',
            'ë'=>'&euml;',
            'Ì'=>'&Igrave;',
            'ì'=>'&igrave;',
            'Í'=>'&Iacute;',
            'í'=>'&iacute;',
            'Î'=>'&Icirc;',
            'î'=>'&icirc;',
            'Ï'=>'&Iuml;',
            'ï'=>'&iuml;',
            'Ñ'=>'&Ntilde;',
            'ñ'=>'&ntilde;',
            'Ò'=>'&Ograve;',
            'ò'=>'&ograve;',
            'Ó'=>'&Oacute;',
            'ó'=>'&oacute;',
            'Ô'=>'&Ocirc;',
            'ô'=>'&ocirc;',
            'Õ'=>'&Otilde;',
            'õ'=>'&otilde;',
            'Ö'=>'&Ouml;',
            'ö'=>'&ouml;',
            'Ø'=>'&Oslash;',
            'ø'=>'&oslash;',
            'Œ'=>'&OElig;',
            'œ'=>'&oelig;',
            'ß'=>'&szlig;',
            'Þ'=>'&THORN;',
            'þ'=>'&thorn;',
            'Ù'=>'&Ugrave;',
            'ù'=>'&ugrave;',
            'Ú'=>'&Uacute;',
            'ú'=>'&uacute;',
            'Û'=>'&Ucirc;',
            'û'=>'&ucirc;',
            'Ü'=>'&Uuml;',
            'ü'=>'&uuml;',
            'Ý'=>'&Yacute;',
            'ý'=>'&yacute;',
            'Ÿ'=>'&Yuml;',
            'ÿ'=>'&yuml;'
        );
        $codes['&#130;'] = '&sbquo;';    // Single Low-9 Quotation Mark
        $codes['&#131;'] = '&fnof;';    // Latin Small Letter F With Hook
        $codes['&#132;'] = '&bdquo;';    // Double Low-9 Quotation Mark
        $codes['&#133;'] = '&hellip;';    // Horizontal Ellipsis
        $codes['&#134;'] = '&dagger;';    // Dagger
        $codes['&#135;'] = '&Dagger;';    // Double Dagger
        $codes['&#136;'] = '&circ;';    // Modifier Letter Circumflex Accent
        $codes['&#137;'] = '&permil;';    // Per Mille Sign
        $codes['&#138;'] = '&Scaron;';    // Latin Capital Letter S With Caron
        $codes['&#139;'] = '&lsaquo;';    // Single Left-Pointing Angle Quotation Mark
        $codes['&#140;'] = '&OElig;';    // Latin Capital Ligature OE
        $codes['&#145;'] = '&lsquo;';    // Left Single Quotation Mark
        $codes['&#146;'] = '&rsquo;';    // Right Single Quotation Mark
        $codes['&#149;'] = '&bull;';    // Bullet
        $codes['&#150;'] = '&ndash;';    // En Dash
        $codes['&#151;'] = '&mdash;';    // Em Dash
        $codes['&#152;'] = '&tilde;';    // Small Tilde
        $codes['&#153;'] = '&trade;';    // Trade Mark Sign
        $codes['&#154;'] = '&scaron;';    // Latin Small Letter S With Caron
        $codes['&#155;'] = '&rsaquo;';    // Single Right-Pointing Angle Quotation Mark
        $codes['&#156;'] = '&oelig;';    // Latin Small Ligature OE
        $codes['&#159;'] = '&Yuml;';    // Latin Capital Letter Y With Diaeresis
        $codes['euro']   = '&euro;';    // euro currency symbol
        $codes['&#178;'] = '&sup2;'; 
        $codes['&#173;'] = '&Agrave;';  //soft hyphen
        $codes['&#173;'] = '&Aacute;';  //soft hyphen
        $codes['&#173;'] = '&Acirc;';  //soft hyphen
        $codes['&#173;'] = '&Atilde;';  //soft hyphen
        $codes['&#173;'] = '&Auml;';  //soft hyphen
         
            
        $text2 = preg_replace('!&[rl]{1}dquo;!i','"',$text2);

        $text2 = self::fixUTF8($text2);

        foreach ($codes as $char => $code)
        {
            $text2 = str_replace($code,$char,$text2);
        }

        preg_match_all('!(&[^#][^; ]+;)!',$text2,$res);
        $res[1] = array_unique($res[1]);
        foreach ($res[1] as $element)
        {
            if ($element=='&quot;') {continue;}
            if ($element=='&nbsp;')
            {
                $text2 = str_replace($element,'&#160;',$text2);
                continue;
            }
            $text2 = str_replace($element,self::encode_funct($element),$text2);
        }

        return str_replace('&#194;','',$text2);
    }

    public static function fixUTF8($text) {
        if(mb_strlen($text) > 2000) {
            return self::fixUTF8(mb_substr($text, 0, 2000)) . self::fixUTF8(mb_substr($text, 2000));
        } else {
            $regex = <<<'END'
/
  (
	(?: [\x00-\x7F]               # single-byte sequences   0xxxxxxx
	|   [\xC0-\xDF][\x80-\xBF]    # double-byte sequences   110xxxxx 10xxxxxx
	|   [\xE0-\xEF][\x80-\xBF]{2} # triple-byte sequences   1110xxxx 10xxxxxx * 2
	|   [\xF0-\xF7][\x80-\xBF]{3} # quadruple-byte sequence 11110xxx 10xxxxxx * 3
	)+                            # ...one or more times
  )
| ( [\x80-\xBF] )                 # invalid byte in range 10000000 - 10111111
| ( [\xC0-\xFF] )                 # invalid byte in range 11000000 - 11111111
/x
END;
            //'/[[^\r\n\t\x20-\x7E\xA0-\xFF]\xC3]/'
            $text = preg_replace('/[\x10-\x1F]/', '', $text); //remove the C0 control characters (more info: http://en.wikipedia.org/wiki/C0_and_C1_control_codes)
            $text = preg_replace('/[\x00-\x0F]/', '', $text); //remove the C0 control characters (more info: http://en.wikipedia.org/wiki/C0_and_C1_control_codes)
            $text = preg_replace('/[\x7F]/', '', $text); //remove the C0 control characters (more info: http://en.wikipedia.org/wiki/C0_and_C1_control_codes)

            $text = preg_replace('/[\x80-\x8F]/', '', $text); //remove the C1 control characters (more info: http://en.wikipedia.org/wiki/C0_and_C1_control_codes)
            $text = preg_replace('/[\x90-\x9F]/', '', $text); //remove the C1 control characters (more info: http://en.wikipedia.org/wiki/C0_and_C1_control_codes)

            $text = preg_replace('/[[^\r\n\t\x20-\x7E\xA0-\xFF]\xC3]/', '', $text);
            return preg_replace($regex, '$1', $text);
        }
    }

    public static function toUnixTimestamp($text)
    {
        return strtotime($text);
    }


 /**
     * parse ground surface from string
     * supports input format: "1 ha" or "1ha" or "1 are 45 ca"
     *
     * @param string $text 1 ha or 1ha or 1 are 45 ca..
     * @param integer
     */
    public static function toMeter($text)
    {
        $result = 0;

        if(preg_match("/(\d+[,.\d+]*)\s*ha\s*(\d+[,.\d+]*)\s*(a|are|ares)\s*(\d+[,.\d+]*)\s*ca/i", $text, $match))
        {
            $ha = str_replace(",", ".", $match[1]);
            $a =  str_replace(",", ".", $match[2]);
            $ca =  str_replace(",", ".", $match[4]);
            $result = ($ha * 10000) + ($a * 100) + $ca;
        }
        else if(preg_match("/(\d+[,.\d+]*)\s*ha\s*(\d+[,.\d+]*)\s*a/i", $text, $match))
        {
            $ha = str_replace(",", ".", $match[1]);
            $a =  str_replace(",", ".", $match[2]);
            $result = ($ha * 10000) + ($a * 100);
        }
        else if(preg_match("/(\d+[,.\d+]*)\s*ha\s*(\d+[,.\d+]*)\s*ca/i", $text, $match))
        {
            $ha = str_replace(",", ".", $match[1]);
            $ca =  str_replace(",", ".", $match[2]);
            $result = ($ha * 10000) + $ca;
        }
        else if(preg_match("/(\d+[,.\d+]*)\s*(a|are|ares)\s*(\d+[,.\d+]*)\s*[ca]*/i", $text, $match))
        {
            $a =  str_replace(",", ".", $match[1]);
            $ca =  str_replace(",", ".", $match[3]);
            $result = ($a * 100) + $ca;
        }
        else if(preg_match("/(\d+[,.\d+]*)\s*(ha|hectare)/i", $text, $match))
        {
            $ha = str_replace(",", ".", $match[1]);
            $result = ($ha * 10000) ;
        }
        else if(preg_match("/(\d+[,.\d+]*)\s*(\ba\b|\bare\b|\bares\b)/i", $text, $match))
        {
            $a =  str_replace(",", ".", $match[1]);
            $result = ($a * 100);
        }
        else if(preg_match("/(\d+[,.\d+]*)\s*ca/i", $text, $match))
        {
            $ca =  str_replace(",", ".", $match[1]);
            $result = $ca;
        }

        return $this->toNumber($result);
    }

	public static function toNumber($str)
    {
        $value = 0;
        $str = preg_replace("/(,\d{2})$|(\.\d{2})$|\s|\+\/-/", "", $str);
        $str = preg_replace("/,(\d{3})|\.(\d{3})/",  "$1$2", $str);
        if(preg_match("/(-?\d+)/", $str, $match)) $value = intval($match[1]);

        return $value;
    }

    public static function toXHTML($html)
    {
        $tidy_config = array(
            "clean" => true,
            "output-xhtml" => true,
            "wrap" => 0,
        );

        $tidy = tidy_parse_string($html, $tidy_config);
        $tidy->cleanRepair();
        return $tidy;
    }


	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//function for print array
	function debug($obj, $e = 1)
	{
		echo "<pre>";
		print_r($obj);
		echo "</pre>";
		if($e)
		  exit;
		
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//function for echo array
	public static function debugx($obj, $e = false)
	{
		echo "<br />************************<br/>";
		echo $obj;
		echo "<br/>************************<br/>";
		if($e)
		  exit;
		
	}

	public static function handleMultiSlashes(){
		$url =  strchr($_SERVER['REQUEST_URI'],"//"); 
		if(!empty($url)){
			$url = str_replace('///','/',$_SERVER['REQUEST_URI']);
			$url = str_replace('//','/',$_SERVER['REQUEST_URI']);
			echo '<script> window.location = "'.$url.'"; </script>' ;
		}
	}
	
	 
 public static function stripslashes_recursively(&$array) {
	trigger_error('stripslashes_recursively is deprecated in 3.2', E_USER_DEPRECATED);
	foreach($array as $k => $v) {
		if(is_array($v)) stripslashes_recursively($array[$k]);
		else $array[$k] = stripslashes($v);
	}
}
	 
	public static function sendEmailHTML($data  ){
			if(empty($data))
			return false;
			
			if(isset($data['email_to']))
				$to = strip_tags($data['email_to']);
			else
				$to = 'abc@example.com';

			if(isset($data['email_to']))
				$subject = strip_tags($data['subject']);
			else
				$subject = 'test subject';

			if(isset($data['email_to']))
				$headers = "From: ".strip_tags($data['from'])." \r\n";
            else
				$headers = "From: info@examplet.com \r\n";
            
            //$headers .= "Reply-To: ". strip_tags($_POST['req-email']) . "\r\n";
            //$headers .= "CC: susan@example.com\r\n";
            
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            $message = '<html><body>';
            $message .= ' <p>Hi ,</p>';
            $message .= '<p>Here is your <a href="#"> test reset link</a>for  </p><div class="yj6qo"> </div>';
            $message .= ' <p>Bye ,</p>';
            $message .= '</body></html>';
            mail($to, $subject, $message, $headers);
       }  
       
       public static function GenLog($file=''){
			$log = '';
			$log = date("Y-m-d h:i:s A");
			$log .= "	";
			$log .= $_SERVER['REMOTE_ADDR'];
			$log .= "	";
			$log .= $_SERVER['REQUEST_URI'];
			$log .= "	";
			$log .= $_SERVER['HTTP_USER_AGENT'];
			 
			$action = "	---------> start \n";  
			
			if(empty($file ))
			$file = dirname(__FILE__) . '/Log/logfile.log';  
				
			file_put_contents($file, $log . $action, FILE_APPEND | LOCK_EX); 
			
	   }     
	 
			
/*
 * $arr 
 * $v = index for search
 * $bool return value
 */
function checkSet($arr='',$v='',$bool=''){

    if($bool=='@')
        return isset($arr[$v]) ? true : false ;
    else 
       return isset($arr[$v]) ? $arr[$v] : $bool ;
} 

            
}// Ending Class
