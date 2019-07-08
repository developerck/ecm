<?php

/**
 * setup installer
 *
 * @project 	ecm
 * @author 	    developerck <os.developerck@gmail.com>
 * @copyright 	@devckworks
 * @version 	<1.1.1>
 * @since	    2014
 */
?>
<?php
// TODO: make this file better for future version
function checkRequirement() {
	global $SETUP_VAR;
	if (phpversion_compare () && isExtLoaded ( 'mysql' )&& isExtLoaded ( 'zlib' ) && isExtLoaded ( 'curl' ) && isWritable ( $SETUP_VAR ['uploaddata_dir'] ) && isWritable ( $SETUP_VAR ['config_dir'] )) {
		return true;
	} else {
		return false;
	}
}
function checkDB($param) {
	global $SETUP_VAR;
	$wwwroot = trim ( $param ['wwwroot'] );
	$db_hostname = trim ( $param ['db_hostname'] );
	$db_username = trim ( $param ['db_username'] );
	$db_password = trim ( $param ['db_password'] );
    $db_port = trim ( $param ['db_port'] );
	$db_name = trim ( $param ['db_name'] );
	if ($wwwroot == '') {
		return 'please provide website URL.';
	}
	if ($db_hostname == '') {
		return 'please provide database hostname.';
	}
    if (!is_numeric($db_port)) {
		return 'port no. should be numeric.';
	}
	if ($db_username == '') {
		return 'please provide database username.';
	}
	if ($db_name == '') {
		return 'please provide database name .';
	}
	$wwwroot = trim ( $wwwroot, "/" ) . "/";
	$link = new \mysqli ( $db_hostname, $db_username, $db_password,$db_name,$db_port );

	if($link->connect_errno > 0){
		return 'could not conenct to database with provided credential and db name';
	} else {

			// write a file config.ing in config.php

			$str = <<<EOT
;------------------------------------
;	ECM [Ease Changelog Manager]	|
;------------------------------------
[globals]
;wwwroot
wwwroot = "$wwwroot"

;database connection properties

conn[host] = "$db_hostname"
conn[port] = "$db_port"
conn[dbname] = "$db_name"
conn[dsn] = "mysql:host=$db_hostname;port=$db_port;dbname=$db_name"
conn[username] = "$db_username"
conn[password] = "$db_password"
EOT;
			// now write a file in config folder
			$configfilename = $SETUP_VAR ['config_config.ini_file'];
			if (! file_exists ( $configfilename )) {
				$file = fopen ( $configfilename, "w" );
				if (fwrite ( $file, $str )) {
					fclose ( $file );
					return '';
				}

				return array("flag"=>"config","msg"=>'file could not write. please create a file in config folder name as "config.ini"<br/> and put below script into that.<p ><pre class="scripts">' . $str . "</pre></p>");

		}
	}
}
function currentSitePath() {
	$current = ! empty ( $_SERVER ['HTTPS'] ) ? "https://" . $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'] : "http://" . $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'];
	// by assuming we are under plugins/setup so removing last two item
	return strstr ( $current, '/plugins/install', true );
}
function loadMysqlScript() {
	global $SETUP_VAR;
	$configfilename = $SETUP_VAR ['config_config.ini_file'];

	if (file_exists ( $configfilename )) {
		$f3 = require_once ($SETUP_VAR ['lib_base_dir']);
		$f3->config ( $configfilename );
		$host = $f3->get ( 'conn.host' );
        $port = $f3->get ( 'conn.port' );
		$username = $f3->get ( 'conn.username' );
		$password = $f3->get ( 'conn.password' );
		$dbname = $f3->get ( 'conn.dbname' );

		// Connect to MySQL server
		$link = new \mysqli  ( $host, $username, $password,$dbname,$port );
		if ($link->connect_errno > 0) {

			return 'Error connecting to MySQL server: ' . $link->connect_error ();
		}
			// Select database

				// Temporary variable, used to store current query
				$templine = '';
				// Read in entire file
				$scriptname = $SETUP_VAR ['plugins_setup_basescript_dir'] . 'setup.sql';
				$lines = file ( $scriptname );
				// Loop through each line
				foreach ( $lines as $line ) {
					// Skip it if it's a comment
					if (substr ( $line, 0, 2 ) == '--' || $line == '')
						continue;

						// Add this line to the current segment
					$templine .= $line;
					// If it has a semicolon at the end, it's the end of the query
					if (substr ( trim ( $line ), - 1, 1 ) == ';') {
						// Perform the query
						if (! $link->query ( $templine )) {
							return 'Error performing query \'<strong> <p> You can manually do this step by got to path : ' . $scriptname . ' and execute the sql yourself. </p><br /><br />';
						}
						// Reset temp variable to empty
						$templine = '';
					}
				}
				return '';


	} else {
		return 'Couldn\t get the config.setup.ini file. did you follow previous step? please check if file exist under config/ folder';
	}
}
function createAdmin($param) {
	global $SETUP_VAR;
	$configfilename = $SETUP_VAR ['config_config.ini_file'];
	$emailid = trim ( $param ['emailid'] );
	$password = trim ( $param ['password'] );
	if ($emailid == '') {
		return 'please provide emailid.';
	}
	if ($password == '') {
		return 'please provide admin password.';
	}
	$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
	if (preg_match ( $regex, $emailid )) {
		if (strlen ( $password ) >= 6 && strlen ( $password ) < 20) {
			$emailid = $param ['emailid'];
			$passwordsalt = rand ( 1, 99999 );
			$encyptpassword = crypt ( $param ['password'], $passwordsalt );
			$f3 = require_once ($SETUP_VAR ['lib_base_dir']);
			$f3->config ( $configfilename );
			$host = $f3->get ( 'conn.host' );
            $port = $f3->get ( 'conn.port' );
			$username = $f3->get ( 'conn.username' );
			$password = $f3->get ( 'conn.password' );
			$dbname = $f3->get ( 'conn.dbname' );
			$link = new \mysqli  ( $host, $username, $password,$dbname,$port );
			if ($link->connect_errno > 0) {

			    return 'Error connecting to MySQL server: ' . $link->connect_error ();
			}
					$query = "Insert Into ecm_users (emailid,password,passwordsalt,firstname,displayname,creationtime)values('$emailid','$encyptpassword','$passwordsalt','Admin','Admin', '" . time () . "')";
					$link->query ( $query );
					$uid = mysqli_insert_id ($link);
					$query = "Insert Into ecm_user_role (user_id,role_id)values('$uid','1')";
					if ($link->query( $query )) {
						//TODO: function hangs the script if does not work so commenting this now
						//setupmail($emailid,$password, $f3->get ( 'wwwroot' ));

						$f3->reroute ( $f3->get ( 'wwwroot' ) );
					} else {
						return 'user could not create!';
					}


		} else {
			return 'password should be b/w 6 to 20 character';
		}
	} else {
		return 'Email Id is not valid';
	}
}

function setupmail($emailid ,$password, $url){
	set_time_limit(5);
// mail that setup has done
	ini_set('display_errors', 0);
	$headers_to_rep='';
	$eol = PHP_EOL;
	$separator = md5(time());
	$message = <<<EOT
<html>
<head>
<title>ECM SETUP </title>
</head>
<body>
<p>
Hi ,
</p>
<p style="margin-left:20px;padding:10px;">
<b>Congrats! ECM has setup successfully!</b>
<br/>
Now You can access the System By below url:
<br/>
$url
<br/>
<br/>
You Account Information is:-
<br/>
<span style="padding:50px">User Name : $emailid</span>
<br/>
<span style="padding:50px">Password : $password</span>
<br/>
<br/>
Please provide you feedback about ECM.
</p>
<p>
With Regards
<br/>
	ECM TEAM
</p>
</body>
</html>
EOT;


	// Basic Header Input

	$to=$emailid;

	// configurable area
	$cc=$emailid;
	$bcc=$emailid;


	$subject='ECM Setup Mail';

	$headers  = 'From: '.$emailid . "\r\n";
	$headers .= "Cc: $cc" . "\r\n";
	$headers .= "Bcc: $bcc" . "\r\n";
	////////end
	// main header

	$headers .= "MIME-Version: 1.0".$eol;
	$headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"";
	$headers_to_rep .= "MIME-Version: 1.0".$eol;
	$headers_to_rep .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"";

	// no more headers after this, we start the body! //

	$body = "--".$separator.$eol;
	$body .= "Content-Transfer-Encoding: 7bit".$eol.$eol;


	// message
	$body .= "--".$separator.$eol;
	$body .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
	$body .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
	$body .= $message.$eol;



	if(!mail($to, $subject, $body,$headers)) {
	    return false;
	} else {

	    return true;
	}


}
// --core function below
function phpversion_compare($version = '5.3.28') {
	if (version_compare ( phpversion (), $version, '<' )) {
		return false;
	} else {
		return true;
	}
}
function isExtLoaded($ext) {
	return extension_loaded ( $ext );
}
function isWritable($file) {
	return is_writable ( $file );
}
function checkHtaccess() {
	$html1 = "test.html";
	$html2 = "test2.html";
	$htaccess = ".htaccess";
	$string1 = "<html><head><title>Hello</title></head><body>Hello World</body></html>";
	$string2 = "<html><head><title>Hello</title></head><body>You have been redirected</body></html>";
	$string3 = "redirect 301 /test.html /test2.html";
	$handle1 = fopen ( $html1, "w" );
	$handle2 = fopen ( $html2, "w" );
	$handle3 = fopen ( $htaccess, "w" );

	fwrite ( $handle1, $string1 );
	fwrite ( $handle2, $string2 );
	fwrite ( $handle3, $string3 );

	$http = curl_init ( $_SERVER ['SERVER_NAME'] . "/test.html" );
	$result = curl_exec ( $http );
	$code = curl_getinfo ( $http, CURLINFO_HTTP_CODE );

	if ($code == 301) {
		return true;
	} else {
		return false;
	}
}

?>