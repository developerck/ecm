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

abstract class AppController implements AppInterface{

     public $f3;
     public $view;
     public $baseurl='/';
     public $cururi ='';
     public $basemethod='index';
     public $textmanager;
     public $module;
     public $allowedAction;


     abstract protected function beforeController();

     abstract protected function afterController();

     public function __construct(){
			global $CNF;
		$this->allowedAction = array('logout','profile','mainDashboard');	
        $this->cururi = (! empty ( $_SERVER ['HTTPS'] )) ? "https://" . $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'] : "http://" . $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'];
        $this->f3 = \Base::instance();

        $this->baseurl = rtrim($CNF->wwwroot,"/")."/";
        $this->textmanager = new TextManager();
       }

        /**
         * parsing paramter
         * @param string $param
         * @param string $separator
         * @return array|bolean Key value pair of parameter
         *
         */
        public static function parseUrl($url ='',  $separator='&', $valseparator='='){
            if(!$url){
                $url =(! empty ( $_SERVER ['HTTPS'] )) ? "https://" . $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'] : "http://" . $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'] ;
            }
            if($parse_url = parse_url($url)){

                $retuararr = array();
                $query =isset($parse_url['query'])?$parse_url['query']:'';
                if(isset($parse_url['path'])){
                	$path = $parse_url['scheme']."://".$parse_url['host'].$parse_url['path'];

                }
                if($query){
                $params = explode($separator, $query);
                foreach($params as $value){
                    if($value == ''){
                        continue;
                    }
                    $tmparr = array();
                    $tmparr =explode($valseparator, $value);
                    $retuararr[$tmparr[0]] =$tmparr[1];
                }
                }
                return array("path"=>$path,'param'=>$retuararr);

            }else{
                return false;
            }

        }

            /**
             *  return key value from url
             *
             */
            public static function getKeyValue($key,$value='', $type='', $url=''){
                if(!$key){
                    return false;
                }
                $parseurl = self::parseUrl($url);
                $param = $parseurl['param'];
                if(array_key_exists($key, $param)){
                    if($type != ''){
                        //type checks
                        if(gettype($param[$key] != $type)){
                            throw new Exception('Optional parameter type does not match!');
                        }
                    }
                    if($value !=''){
                        if($param[$key]==''){
                            return $value;
                        }
                    }else{
                     return $param[$key];
                    }
                }else{
                    return false;
                }

            }

             /**
             *  return key value from url
             *
             */
            public static function getKeyValueRequired($key, $url='', $type=''){
                if(!$key){
                     throw new Exception('parameter nname not avialable!');
                }
                $parseurl = self::parseUrl($url);
                $param = $parseurl['param'];
                if(array_key_exists($key, $param)){
                     if($type != ''){
                        //type checks
                        if(gettype($param[$key] != $type)){
                            throw new Exception('Required parameter type does not match!');
                        }
                    }
                    return $param[$key];
                }else{
                    throw new Exception($key.' parameter value is required');
                }

            }


        /**
         *  create get link
         *
         * @return string $url
         *
         */
         public static function generateGetLink($param=array(),$url =''){
			if(!is_array($param)){
				$param = array();
			}
            if($urlparam =self::parseUrl($url)){

                $path = $urlparam['path'];
                $params = $urlparam['param'];

                if(is_array($params)){
                	$params = array_merge($params,$param);
                }else{
                	$params = $param;
                }
                    return  rtrim($path,"/")."/?".http_build_query($params);
            }else{
                return false;
            }


         }


          protected function logControllerException($ex,$type='CONTROLLER'){

            throw new \devlib\Exception($ex,$type);
    }

    /**
     *  set Session Message
     *
     */

    public function setSessionMessage($msg, $extraparam =array(),$type='success'){
        if($msg !=''){
            $msg = '<div class="alert alert-'.$type.' alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <strong>'.$msg.'</strong></div>';
            $this->f3->set('SESSION.ecm_msg',$msg);
        }
    }

	/*
	 * filterUserData
	 *
	 */
    	protected  function filterInputData(&$param){
			//TODO: write defination
    	}

/**
 * Implmenting getText Function to get peformated
 *
 */

        public function getHelpText($key, $module, $type='tooltip', $data_placement='right'){
            if($type !='tooltip' && $type !='popover'){
                $type= 'tooltip';
            }
            $text=TextManager::getText($key,$module);
            if($text){
            	if($type=='tooltip'){
            		return '<i class="fa fa-question-circle " data-html="true" data-container="body" style = "cursor:help" data-toggle="tooltip" data-placement="'.$data_placement.'" title="'.$text.'" ></i>';
            	}else if($type=='tooltip'){
            		return '<i class="fa fa-question-circle " data-html="true" data-container="body" style = "cursor:help" data-toggle="popover" data-placement="'.$data_placement.'" data-content="'.$text.'" ></i>';
            	}


            }else{
                return true;
            }

        }

}

interface AppInterface{

}