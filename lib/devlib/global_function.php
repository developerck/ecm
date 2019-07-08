<?php
/*
 * This file contain some general function that is used globally in application. @project 	ecm @author developerck <os.developerck@gmail.com> @copyright 	@devckworks @version 	<1.1.1> @since	 2014
 */

 /*
  * used this function to appending braces for jsonp request
  *
  */
function JSONPstart() {
	return (isset ( $_GET ['callback'] )) ? $_GET ['callback'] . '(' : '';
}
function JSONPend() {
	return (isset ( $_GET ['callback'] )) ? ');' : '';
}

/*
 * Cleaning array
 */
function clean($var) {
	if (is_array ( $var )) {
		return array_map ( "clean", $var );
	} else {
		return strip_tags ( trim ( $var ) );
	}
}

/**
 * prints whatever is passed to the function
 */
function p() {
	$arguments = func_get_args ();
	foreach ( $arguments as $argument ) {
		if (is_array ( $argument ) || is_object ( $argument )) {
			echo "<pre>";
			print_r ( $argument );
			echo "</pre>";
		} else {
			echo "<br>" . $argument . "<br>";
		}
	}
}

/**
 * send response back to browser
 */
function sendResponse($responseData) {
	echo JSONPstart () . json_encode ( $responseData ) . JSONPend ();
}
function generalEncrypt($string, $key = "ECM_DEVCK") {
	$res = '';
	for($i = 0; $i < strlen ( $string ); $i ++) {
		$c = ord ( substr ( $string, $i ) );
		$c += ord ( substr ( $key, (($i + 1) % strlen ( $key )) ) );
		$res .= chr ( $c & 0xFF );
	}

	$res = base64_encode ( $res );

	return $res;
}
function generalDecrypt($string, $key = "ECM_DEVCK") {
	$res = '';
	$string = base64_decode ( $string );
	for($i = 0; $i < strlen ( $string ); $i ++) {
		$c = ord ( substr ( $string, $i ) );

		$c -= ord ( substr ( $key, (($i + 1) % strlen ( $key )) ) );
		$res .= chr ( abs ( $c ) & 0xFF );
	}

	return $res;
}

// --- dateformat
function pDate($date, $format = 'd/m/Y') {
	if ($date == '') {
		return false;
	}

	return date ( $format, $date );
}

// conver Bytes
function convertBytes($value) {
	if (is_numeric ( $value )) {
		return $value;
	} else {
		$value_length = strlen ( $value );
		$qty = substr ( $value, 0, $value_length - 1 );
		$unit = strtolower ( substr ( $value, $value_length - 1 ) );
		switch ($unit) {
			case 'k' :
				$qty *= 1024;
				break;
			case 'm' :
				$qty *= 1048576;
				break;
			case 'g' :
				$qty *= 1073741824;
				break;
		}
		return $qty;
	}
}

/*
 * assocArrayLeftMerge merge two associative array , return first key with value found in second
 */
function assocArrayLeftMerge(&$leftarr, $rightarr) {
	if (! is_array ( $leftarr ) || ! is_array ( $rightarr )) {
		return false;
	}
	$retarr = array ();
	foreach ( $leftarr as $key => $value ) {
		$leftarr [$key] = array_key_exists ( $key, $rightarr ) ? $rightarr [$key] : $value;
	}

	return $leftarr;
}


/*
 * function to delete recursivly
 * // delete untill uploaddata/tmp directory
 *
 */
function rrmdir($dir, $tilldir='') {
	global $CNF;
	$tilldir = $CNF->tmpdir;
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
            }
        }
        reset($objects);
        if($dir != $tilldir){
            rmdir($dir);
        }

    }
}

/*
 * copy function
 *
 */
function rcopy($src, $dst) {
    //if (file_exists($dst)) rrmdir($dst);
    if (is_dir($src)) {
        mkdir($dst,0777, true);
        $files = scandir($src);
        foreach ($files as $file)
        {
        	if ($file != "." && $file != ".."){
        		rcopy("$src/$file", "$dst/$file");
        	}
        }
    }
    else if (file_exists($src)){
    	if(!copy($src, $dst)){
    		return false;
    	}else{
    		return true;
    	}
    }

}

function makeZip($source, $destination)
{
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new \ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true)
    {
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source), \RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file)
        {
            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                continue;

            $file = realpath($file);

            if (is_dir($file) === true)
            {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            }
            else if (is_file($file) === true)
            {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    }
    else if (is_file($source) === true)
    {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}
?>