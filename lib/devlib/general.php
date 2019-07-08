<?php
/**
 *
 *
 * @project 	ecm
 * @author 	    developerck <os.developerck@gmail.com>
 * @copyright 	@devckworks
 * @version 	<1.1.1>
 * @since	    2014
 */
namespace devlib;

class General{

/**
 * string to ascii array
 * @access public
 * @param string $string
 * @return array $ascii
 */

    public static function sToAS($string)
    {
    $ascii = array();
    $strlen = strlen($string);

    for ($i = 0; $i < $strlen; $i++)
    {
    	$ascii[] =ord($string[$i]);
    }

    return($ascii);
    }

 /**
 * convert ascii array to string
 * @access public
 * @param array $ascii
 * @return string $string
 */

    public static function asToS($ascii)
    {
    $string = '';
    $strlen = count($ascii);

    for ($i = 0; $i < $strlen; $i++)
    {
    	$string .=chr($ascii[$i]);
    }

    return $string;
    }


    public static function classAutoload(){

    	//loading    module classes dynamically
    	spl_autoload_register(function ($class) {
    		// module either namespace or in url
    		global $CNF;
    		$parts = explode('\\', $class);
    		$classname = array_pop($parts);
			$filename = implode($CNF->DS,$parts);
			// lower case file name and name space is also lowercase to match with foldername
			$filename =$filename.$CNF->DS.strtolower($classname).'.php';
			if(file_exists($CNF->basedir.$CNF->DS .$filename)){
    			include_once $filename ;
			}else{
				throw new Exception($filename." Not Found while loading thorugh class Autload!");
			}
    	});
    }


	public static function AppSignature(){
		global $CNF;
		$str ='';
		if(property_exists($CNF,'ecm')){
			$arr = $CNF->ecm;
				if(is_array($arr)){
					if(array_key_exists("appsign",$arr)){
						$str = $arr['appsign'];
					}
				}
		}else{
			$str = 'e'.'c'.'m'.'@'.'2'.'0'.'1'.'4';
		}
		return $str;



	}

}