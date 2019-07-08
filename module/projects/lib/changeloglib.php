<?php
/**
 *
 *
 * @project 	ecm
 * @author 	    developerck <os.developerck@gmail.com>
 * @copyright 	@devckworks
 * @version 	<1.1.1>
 * @since	    2014
 * @module      projects
 *
 */
namespace module\projects\lib;


class ChangelogLib{

	public function __construct(){


	}


	public function exportTxtChangelog($id){
	    global $CNF;
		$changelogobj = new \module\projects\service\Changelog();
		$records = $changelogobj->getFullDetailByChnagelog($id);
		$data = $records['data'];
		$pname = array();
		$rvname = array();
		$issueidarr = array();

		$str ='';
	    if(!empty($data)){
			foreach ($data as $record){
				$pname[$record['projectname']]=$record['projectname'];
				$rvname[$record['rvname']]=$record['rvname'];
				$issueidarr[$record['issueid']]=$record['issueid'];
				$str .= PHP_EOL.PHP_EOL.'    '.'/**********************  '.$record['issueid'].' START **********************/';

				$str .= PHP_EOL.PHP_EOL.'    '.'/* ---------------- File Log ---------------- */';
				$str .= PHP_EOL;
				$str .= PHP_EOL.'    '.'    '.$record['filelog'];
				$str .= PHP_EOL.PHP_EOL.'    '.'/* ---------------- Database Changes ---------------- */';
				$str .= PHP_EOL;
				$str .= PHP_EOL.'    '.'    '.$record['scriptlog'];
				$str .= PHP_EOL.PHP_EOL.'    '.'/* ---------------- Settings ----------------  */';
				$str .= PHP_EOL;
				$str .= PHP_EOL.'    '.'    '.$record['settings'];
				$str .= PHP_EOL.PHP_EOL.'    '.'/* ---------------- Comment ---------------- */';
				$str .= PHP_EOL;
				$str .= PHP_EOL.'    '.'    '.$record['comment'];
				$str .= PHP_EOL.'';
				$str .= PHP_EOL.PHP_EOL.'    '.'/********************** '.$record['issueid'].' END **********************/';
			}

			$header ='';
			$header .= '==================== HEADER =======================';
			$header .= PHP_EOL.'    '.'Project Name : '.implode(" ", $pname);
			$header .= PHP_EOL.'    '.'Release Version : '.implode(" ", $rvname);
			$header .= PHP_EOL.'    '.'Issues : '.implode(" ", $issueidarr);
			$header .= PHP_EOL.'==================== HEADER =======================';
			$header .= PHP_EOL;

			$footer ='';
			$footer .= PHP_EOL;
			$footer .= PHP_EOL.''.'==================== FOOTER =======================';
			$footer .= PHP_EOL.'    '.'Created at : '.pDate(time());
			$footer .= PHP_EOL.'    '.'                                       		  ';
			$footer .= PHP_EOL.'    '.'By : '.\devlib\General::AppSignature();
			$footer .= PHP_EOL.''.'==================== FOOTER =======================';


			return $header.$str.$footer;

	    }else{
	        return false;
	    }

	}

	public function exportTxtChangelogByLogType($id){
	    global $CNF;
	    $changelogobj = new \module\projects\service\Changelog();
	    $records = $changelogobj->getFullDetailByChnagelog($id);
	    $data = $records['data'];
	    $pname = array();
	    $rvname = array();
	    $issueidarr = array();

	    $filelog ='';
	    $scriptlog ='';
	    $settings ='';
	    $comment ='';
	    if(!empty($data)){
	        foreach ($data as $record){
	            $pname[$record['projectname']]=$record['projectname'];
	            $rvname[$record['rvname']]=$record['rvname'];
	            $issueidarr[$record['issueid']]=$record['issueid'];
	            $filelog .= PHP_EOL.PHP_EOL.'    '.' /********************** '.$record['issueid'].' START **********************/';
	           // $filelog .= PHP_EOL.PHP_EOL.'    '.'/* ---------------- File Log ---------------- */';
	            $filelog .= PHP_EOL;
	            $filelog .= PHP_EOL.'    '.'    '.$record['filelog'];
	            $filelog .= PHP_EOL.PHP_EOL.'    '.' /********************** '.$record['issueid'].' END **********************/';

	            $scriptlog .= PHP_EOL.PHP_EOL.'    '.' /**********************  '.$record['issueid'].' START **********************/';
	           // $scriptlog .= PHP_EOL.PHP_EOL.'    '.'/* ---------------- Database Changes ---------------- */';
	            $scriptlog .= PHP_EOL;
	            $scriptlog .= PHP_EOL.'    '.'    '.$record['scriptlog'];
	            $scriptlog .= PHP_EOL.'';
	            $scriptlog .= PHP_EOL.PHP_EOL.'    '.' /**********************  '.$record['issueid'].' END **********************/';

	            $settings .= PHP_EOL.PHP_EOL.'    '.' /**********************  '.$record['issueid'].' START **********************/';
	           // $settings .= PHP_EOL.PHP_EOL.'    '.'/* ---------------- Settings ---------------- */';
	            $settings .= PHP_EOL;
	            $settings .= PHP_EOL.'    '.'    '.$record['settings'];
	            $settings .= PHP_EOL.'';
	            $settings .= PHP_EOL.PHP_EOL.'    '.' /**********************  '.$record['issueid'].' END **********************/';

	            $comment .= PHP_EOL.PHP_EOL.'    '.' +++++++++++++++++++ '.$record['issueid'].' START **********************/';
	           // $comment .= PHP_EOL.PHP_EOL.'    '.'/* ---------------- Comment ---------------- */';
	            $comment .= PHP_EOL;
	            $comment .= PHP_EOL.'    '.'    '.$record['comment'];
	            $comment .= PHP_EOL.'';
	            $comment .= PHP_EOL.PHP_EOL.'    '.'/**********************  '.$record['issueid'].' END **********************/';
	        }

	        $header ='';
	        $header .= '==================== HEADER =======================';
	        $header .= PHP_EOL.'    '.'Project Name : '.implode(" ", $pname);
	        $header .= PHP_EOL.'    '.'Release Version : '.implode(" ", $rvname);
	        $header .= PHP_EOL.'    '.'Issues : '.implode(" ", $issueidarr);
	        $header .= PHP_EOL.'==================== HEADER =======================';
	        $header .= PHP_EOL;

	        $footer ='';
	        $footer .= PHP_EOL;
	        $footer .= PHP_EOL.''.'==================== FOOTER =======================';
	        $footer .= PHP_EOL.'    '.'Created at : '.pDate(time());
	        $footer .= PHP_EOL.'    '.'                                       		  ';
	        $footer .= PHP_EOL.'    '.'By : '.\devlib\General::AppSignature();
	        $footer .= PHP_EOL.''.'==================== FOOTER =======================';


	        	$retarr = array(
	        		"filelog"=>$header.$filelog.$footer
	        			,"scriptlog"=>$header.$scriptlog.$footer
	        			,"settings"=>$header.$settings.$footer
	        			,"comment"=>$header.$comment.$footer
	        	);
	        return $retarr;

	    }else{
	        return false;
	    }

	}
}