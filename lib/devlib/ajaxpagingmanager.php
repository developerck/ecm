<?php
/*
 * @version 1.0
 * @author developerck
 * @date 22-Feb-2013
 * @desc this class can be used to show a dynamic list has paging.
 * 		 e.g: You have a list of 100 record and you fetch that through
 * 				ajax but now you want to show these records by paging
 *
 *
 *
 */
?>


<?php
 //---------------------------------------
class AjaxPagingManager {

	private $base_url		= ''; // The page we are linking to
	private $pgparam		= 'page';
	private $getSrr			= '';
	private $total_records  	= ''; // Total number of items (database results)
	private $per_page	 	= 20; // Max number of items you want shown per page
	private $cur_page	 	=  0; // The current page being viewed
	private $pagingStr	 	=  ''; //
	private $param			='';
	private $pagename		='';
	private $extraparam		='';
	private $div             	='';
	private $pageno			='';

	// for 123_PAGING
	private $seriesRange	=  5; // Number of "digit" links to show before/after the currently viewed page


	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 * @param  pagingSize (Reocords per page)
	 * @param pagename (php file name fetching ajax content)
	 * @param div (html div id where to show result)
	 * @param pageno
	 * @param extraparam ( pass parameter to ajax php file)
	 */

	public function __construct($param)
		{
			$this->param = $param;
			$param = $param['param'];
			if(isset($param)){
				$this->pagename = array_key_exists('pagename',$param['paging'])?$param['paging']['pagename']:'';
				$this->div = array_key_exists('div',$param['paging'])?$param['paging']['div']:'';
				$this->extraparam = array_key_exists('extraparam',$param['paging'])?$param['paging']['extraparam']:'';
				$this->cur_page = array_key_exists('pageno',$param['paging'])?$param['paging']['pageno']:1;
				$this->pgparam = array_key_exists('pgparam',$param['paging'])?$param['paging']['pgparam']:$this->pgparam;
				$pagingSize = array_key_exists('pagingsize',$param['paging'])?$param['paging']['pagingsize']:$this->per_page;
				$this->initialize($pagingSize);
			}
		}

	// --------------------------------------------------------------------

	/**
	 * Initialize Preferences
	 *
	 * @access	private
	 * @param	array	initialization parameters
	 * @return	void
	 */
	private function initialize($pagingSize)
		{

		if ((isset($_GET)) && (is_array($_GET)))
		{
			$getStr = '';
			foreach ($_GET as $key => $value)
			{
				if (strstr($key, $this->pgparam))
				{
					continue;
				}
				$getStr .= "$key=$value&";
			}


			$this->getStr	= ($getStr == '') ? "?"  : '?' . $getStr;

			// make sure that pagenumber is integer
			if (! is_numeric($this->cur_page) OR ($this->cur_page == 0))
			{
				$this->cur_page = 1;
			}
		}
		else
		{
			$this->cur_page = 1;
		}


		if ($pagingSize != '')
		{
			$this->per_page = $pagingSize;
		}
		// make sure that pagingsize is integer
		if (! is_numeric($this->per_page))
		{
			$this->per_page = 10;
		}

		}

	public function getStart()
		{
		if($this->cur_page > 0)
		{
			//first item to display on this page
			$this->start = ($this->cur_page - 1) * $this->per_page;
		}
		else
		{
			//if no page var is given, set start to 0
			$this->start = 0;
		}
		return $this->start;
		}

	public function getOffset()
		{
		return $this->per_page;
		}

	public function doPaging($totalRecords)
		{
		if ($this->pagingStr != '')
		{
			return $this->pagingStr;
		}
		$this->total_records = $totalRecords;

		// If our item count or per-page total is zero there is no need to continue.
		if ($this->total_records == 0 OR $this->per_page == 0)
		{
			return '';
		}


		$this->pagingStr = $this->printPageNumbers('series', 'numbers');

		return $this->pagingStr;
		}


	// --------------------------------------------------------------------

	private function printPageNumbers($range = 'series', $type = 'numbers')
		{
		// find the total number of pages.
		$totalPages = ceil($this->total_records/$this->per_page);

		$paging = '';

		switch ($range)
		{


			case 'series':

				if ($this->cur_page < $this->seriesRange)
				{
					$seriesrange1 = ($this->seriesRange - $this->cur_page) + $this->seriesRange;
				}
				else
				{
					$seriesrange1 = $this->seriesRange;
				}

				if (($totalPages - $this->cur_page) < $this->seriesRange)
				{
					$seriesrange2 = ($this->seriesRange - 1 - ($totalPages - $this->cur_page)) + $this->seriesRange;
				}
				else
				{
					$seriesrange2 = $this->seriesRange;
				}

				$from = ($this->cur_page - $seriesrange2 < 1)? 1 : $this->cur_page - $seriesrange2 ;
				$to = ($this->cur_page + $seriesrange1 > $totalPages)? $totalPages : $this->cur_page+$seriesrange1 ;

				if (($to - $from) >= ($this->seriesRange * 2))
				{
					// make sure that the total page number printed at any time is seriesrnage*2
					if ($seriesrange2 > $seriesrange1)
					{
						$from--;
					}
					else
					{
						$to--;
					}
				}

				$prev = ($this->cur_page > 1)? $this->cur_page - 1 : 1 ;
				$next = ($this->cur_page < $totalPages)? $this->cur_page + 1 : $totalPages ;
				if ($this->cur_page == 1)
				{
					$paging .= "<li class=\"disabled\"><a href=\"#\">&lt;&lt;</a></li>";
					$paging .= "<li class=\"disabled\"><a href=\"#\">Previous</a></li> ";

				}
				else
				{
					// Print last and previous page number links
								$curarr  = $this->param;
								if(array_key_exists('pageno',$curarr['param']['paging'])){
									$curarr['param']['paging']['pageno']=1;
								}
								$curarr = json_encode($curarr);
					$paging .= "<li><a href='javascript:ajaxPaging($curarr)''javascript:ajaxPaging($curarr)' title=\"First Page\">&lt;&lt;</a> </li>";
								$curarr  = $this->param;
								if(array_key_exists('pageno',$curarr['param']['paging'])){
									$curarr['param']['paging']['pageno']=$prev;
								}
								$curarr = json_encode($curarr);
					$paging .= "<li><a href='javascript:ajaxPaging($curarr)' title=\"Previous Page\">Previous</a> </li>";
				}

				for ($i = $from; $i <= $to; $i++)
				{
					switch ($type)
					{
						case 'numbers':
							if ($i != $this->cur_page)
							{
								$curarr  = $this->param;
								if(array_key_exists('pageno',$curarr['param']['paging'])){
									$curarr['param']['paging']['pageno']=$i;
								}
								$curarr = json_encode($curarr);
								$paging .= "<li><a href='javascript:ajaxPaging($curarr)'>$i</a> </li>";
							}
							else
							{
								$paging .= "<li class=\"active\"><a href=\"#\">$i<span class=\"sr-only\">(current)</span></a></li>";
							}
							break;


					}
				}

				if ($this->cur_page == $totalPages)
				{
					$paging .= "<li class=\"disabled\"><a href=\"#\">Next</a> </li>";
					$paging .= "<li class=\"disabled\"><a href=\"#\">&gt;&gt;</a></li> ";
				}
				else
				{
								$curarr  = $this->param;
								if(array_key_exists('pageno',$curarr['param']['paging'])){
									$curarr['param']['paging']['pageno']=$next;
								}
								$curarr = json_encode($curarr);
					$paging .= "<li><a href='javascript:ajaxPaging($curarr)' title=\"Next Page\">Next</a></li>";
								$curarr  = $this->param;
								if(array_key_exists('pageno',$curarr['param']['paging'])){
									$curarr['param']['paging']['pageno']=$totalPages;
								}
								$curarr = json_encode($curarr);
					$paging .= "<li><a href='javascript:ajaxPaging($curarr)' title=\"Last Page\">&gt;&gt;</a></li> ";
				}
				break;
		} // End of main switch statement.

		return "<div class=\"pager\"><ul class=\"pagination pagination-sm\">" . $paging . "</ul></div>";
		}



	public function getPagingString($str = '')
		{
		if ($str == '')
		{
			$str = "Showing %d to %d of %d records";
		}

		$pageFrom = $this->per_page * ($this->cur_page - 1) + 1;
		$pageTo	  = $pageFrom + $this->per_page - 1;
		if ($pageTo > $this->total_records)
		{
			$pageTo = $this->total_records;
		}

		return sprintf($str, $pageFrom, $pageTo, $this->total_records);
		}

	public function set_base_url($url)
		{
		$this->base_url=$url;
		}

}
// END Pagination Class
?>