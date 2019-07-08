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

class TextManager{

    public $textpath;
    
public function __construct(){
    global $CNF;
    $this->textpath = $CNF->textdir;
}
    
    public static function getText($key='ECM', $module='general'){
        global $CNF;
        require $CNF->textdir.$module.'.php';
        
        if(array_key_exists($key, $text)){
            return $text[$key];
        }else{
            return false;
        }
    }
    
    
    
    
    
}