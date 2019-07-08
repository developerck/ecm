<?php

/**
 * update installer
 *
 * @project 	ecm
 * @author 	    developerck <os.developerck@gmail.com>
 * @copyright 	@devckworks
 * @version 	<1.1.1>
 * @since	    2014
 */
?>
<?php
//TODO: In basic we are giving manual update,set it auto update

// upgrade script
function getMaxUploadSize(){
    return  (int)(ini_get('upload_max_filesize'));
}

//TODO : cehck for login
if(isset($_POST['submit'])){
    $f3 = require_once ($SETUP_VAR['lib_base_dir']);
	$f3->config ( $SETUP_VAR['config_config.ini_file']);
	$f3->reroute ( $f3->get ( 'wwwroot' ) );
	$target_path =   $dirroot."/uploaddata/updates/versions/";;

	$target_path = $target_path . basename( $_FILES['importfile']['name']);
	// checking file size
	$filesize = $_FILES['importfile']['size'];
	// TODO : check upload file size
	// TODO : check permission denied


		if(move_uploaded_file($_FILES['importfile']['tmp_name'], $target_path)) {
			echo "succesfull uploaded";
		} else{
			echo 'File couldn\'t move to destination';
		}
	}

	if(isset($_POST['process'])){
		$canporcess =false;
		if (ob_get_level() == 0) ob_start();
	// 	upgrade steps:
	// assuming we know the zip name that we are going to update
	// check if zip exist there
	$filename = $dirroot.'/uploaddata/updates/versions/'.'update_1.1.2.zip';
	// here are the upgrade stpes
		//1:- check pre-requisite
			//a) check folder is writable or not
				if(is_writable($dirroot)){
					echo 'a)Writable : OK';
				}else{
					echo "a) For Update Automatically, ECM Directory should be writable or please follow Manual steps.";
				}

				ob_flush();
				flush();

				//b) check database connection
				if(true){
					echo 'b)DB:  OK';
				}else{
					echo "b) Can not conenct to DB!";
				}

				ob_flush();
				flush();

				// c) unzip the file
				if('zip' == pathinfo($filename, PATHINFO_EXTENSION)){
					echo 'c)zip:  OK';

				}else{
					echo "c) NOT a ZIP File ";
				}
				ob_flush();
				flush();
				//d) unzip

				echo "d) ";
			$zip = new ZipArchive;
			$res = $zip->open($filename);
		if ($res === TRUE) {
		  $zip->extractTo($dirroot.'/uploaddata/updates/versions/');
		  $zip->close();
		  echo 'DONE!';
		} else {
		  echo 'NOT DONE!';
		}
				ob_flush();
				flush();
		// parsing the version file
				echo "e)";
				$path_parts = pathinfo($filename);

		$folderpath = $dirroot.'/uploaddata/updates/versions/'.$path_parts['filename']."/";
		if(file_exists($folderpath."update.xml")){
				// if exists then parse it
				$parsexml =  simplexml_load_file($folderpath."update.xml");
				if($parsexml){
						// check if all required tags are available after that check checksum

							if(md5($parsexml->product['current_version'])== $parsexml->product['current_version_checksum']
								&& md5($parsexml->product['required_version'])== $parsexml->product['required_version_checksum']
								&& md5(json_encode($parsexml->changelog)) == $parsexml->product['changelog_checksum'] ){
							// if checksum valid
							// check if this update is not done in system
							//TODO: get the last version in system
								$cv = $parsexml->product['current_version'];
								$rv = $parsexml->product['required_version'];
								$dbv = '010101';
							if($dbv == $rv){
									// conditons are met and we can update not
									// prerequisite opeartion end here
										$canporcess =true;
							}else{
								// echo required version is not in you system
								// die here
							}
						}
				}

		}else{
			echo 'Update file not exists!';
		}


		if($canporcess){
			//TODO: show message and confirm for accept updates
			//	 Keep in mind that no user should be access this site when porcess begins, else their work may be corrupted

		}

echo "Done.";

//ob_end_flush();



	//TODO: let's assume is user acccept for porcess updates
	$accept = true;
if($accept){
// Doing update process

	//TODO : Set Site In maintain mode

	// TODO: Create a folder in /uploaddata/updates/backup/ for this version
		if(!is_dir($dirroot.'/uploaddata/updates/backup/'.$path_parts['filename'].'/')){
			mkdir($dirroot.'/uploaddata/updates/backup/'.$path_parts['filename'], '0777');
			// after making directory
			//TODO :  make db dump
			//TODO :  File Backup
			//TODO : Copy File From version Directory to it's location from change log
			//TODO : Execcute Script
			//TODO :  Update table version
			// if it fails then do a roll back
			//TODO : rollback files from backup to destination
			//TODO :  execute rollback script


		}
}
	}
?>

