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

class ListGenerator
{
	private $_data;
	private $_header;
	private $_column;
	private $arr;
    public $pgparam;
    public $cururi ;

	public function __construct($data, $header, $column = array(),$uri='',$pgparam='page')
		{
		$this->_data = $data;
		$this->_header = $header;
		$this->_column = $column;
        $this->pgparam = $pgparam;
        $this->cururi = (! empty ( $_SERVER ['HTTPS'] )) ? "https://" . $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'] : "http://" . $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'];


		}

	public function setTableArray($serial=false ,$offset='',$sortByColumn='',$sort=SORT_ASC)
		{


		$head_array = $this->_header;
		$header_array=array();
		foreach($head_array as $value){
		  if($value !='')
			$header_array[]=ucwords($value);


		}
		if($sortByColumn <> ''){
			$this->array_sort_by_column($this->_data, $sortByColumn, $sort);

		}

		if ($this->_data && is_array($this->_data))
		{
			$list_array = array();
			if($offset <> ''){
				$val=$offset+1;
			}
			else{
				$val=1;
			}
			foreach ($this->_data as $value)
			{
				$tmp_arr = array();
				if($serial){

					$tmp_arr['serial']=$val;
				}
				foreach ($this->_column as $key=>$colname)
				{
					$tmp_arr[$colname] = $value[$colname];

				}

				if($sortByColumn <> '' ){
					if(array_key_exists($sortByColumn,$tmp_arr)){
						$sortingOn[$sortByColumn]=$tmp_arr[$sortByColumn];
					}

				}

				$list_array[] = $tmp_arr;
				$val++;
			}

		}
		else
		{
			$page=$this->getPageNo();

			if($page>=2){
			 //this is for, let table has 11 record and we are on second page then by deleting 11th record it should return on previous page
			 $link=$this->getReloadLink();

			 // redirecting for previous page
				header("Location:$link");
				exit;
			}
			else{
			$list_array = array(array("NORECORD"=>"No Record Found"));
			}
		}



		return array("header"=>$header_array,"data"=> $list_array);
		}

	private function  array_sort_by_column(&$arr, $col, $dir) {
		$sort_col = array();
		foreach ($arr as $key=> $row) {
			$sort_col[$key] = $row[$col];
		}

		array_multisort($sort_col, $dir, $arr);
	}


	private function getPageNo(){

		if($value = AppController::getKeyValue($this->pgparam)){
            return $value;
        }else{
            return false;
        }



	}

	private function getReloadLink(){
		          // if there is page link then decreasing it by one

        if($value = AppController::getKeyValue($this->pgparam)){
            // making for page no greater than one
            if($value > 1){
                $value = $value-1;
            }
          return  AppController::generateGetLink(array($this->pgparam=>$value), $this->cururi);
        }else{
            return false;
        }


	}


}
