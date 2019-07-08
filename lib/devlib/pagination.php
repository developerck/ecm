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

class Pagination {
	private $base_url = ''; // The page we are linking to
	private $pgparam = '';
	private $total_records = ''; // Total number of items (database results)
	private $per_page = 20; // Max number of items you want shown per page
	private $cur_page = 1; // The current page being viewed
	private $pagingStr = ''; //
	private $getStr = '';
	private $seriesRange	=  5;
    private $start ;

	/**
	 * Constructor
	 *
	 * @access public
	 * @param
	 *        	pageno
	 * @param
	 *        	pagingSize (Reocords per page)
	 * @param
	 *        	pgparam
	 *
	 *
	 */
	public function __construct($pageno = 1, $pagingsize =20, $pgparam = 'page', $baseurl = '') {

      $this->base_url = $baseurl != '' ? $baseurl : (! empty ( $_SERVER ['HTTPS'] )) ? "https://" . $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'] : "http://" . $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'];
       if(!$pageno){
	    $parseurl = AppController::parseUrl($this->base_url);
        $urlparam = $parseurl['param'];
        $pageno = isset($urlparam[$pgparam])?$urlparam[$pgparam]:1;
	   }
		$this->cur_page = $pageno;
		$this->pgparam = $pgparam;
		$this->per_page = $pagingsize==''? $this->per_page:( int )$pagingsize;

	
	}

	// --------------------------------------------------------------------

	
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

	public function getPerPage() {
		return $this->per_page;
	}

	public function getOffset(){
		return $this->per_page*($this->cur_page -1);
	}

	public function doPaging($totalRecords) {
		if ($this->pagingStr != '') {
			return $this->pagingStr;
		}
		$this->total_records = $totalRecords;

		// If our item count or per-page total is zero there is no need to continue.
		if ($this->total_records == 0 or $this->per_page == 0) {
			return '';
		}

		$this->pagingStr = $this->printPageNumbers ( );

		return $this->pagingStr;
	}


	private function printPageNumbers() {
		// find the total number of pages.
		$totalPages = ceil ( $this->total_records / $this->per_page );
		$paging = '';


				if ($this->cur_page < $this->seriesRange) {
					$seriesrange1 = ($this->seriesRange - $this->cur_page) + $this->seriesRange;
				} else {
					$seriesrange1 = $this->seriesRange;
				}

				if (($totalPages - $this->cur_page) < $this->seriesRange) {
					$seriesrange2 = ($this->seriesRange - 1 - ($totalPages - $this->cur_page)) + $this->seriesRange;
				} else {
					$seriesrange2 = $this->seriesRange;
				}

				$from = ($this->cur_page - $seriesrange2 < 1) ? 1 : $this->cur_page - $seriesrange2;
				$to = ($this->cur_page + $seriesrange1 > $totalPages) ? $totalPages : $this->cur_page + $seriesrange1;

				if (($to - $from) >= ($this->seriesRange * 2)) {
					// make sure that the total page number printed at any time is seriesrnage*2
					if ($seriesrange2 > $seriesrange1) {
						$from --;
					} else {
						$to --;
					}
				}

				$prev = ($this->cur_page > 1) ? $this->cur_page - 1 : 1;
				$next = ($this->cur_page < $totalPages) ? $this->cur_page + 1 : $totalPages;
				if ($this->cur_page == 1) {
					$paging .= "<li class=\"disabled\"><a href=\"javascript:void();\">&lt;&lt;</a></li>";
					$paging .= "<li class=\"disabled\"><a href=\"javascript:void();\">Previous</a></li> ";
				} else {
					// Print last and previous page number links
                    $start = 1;
					$paging .= "<li><a href='" .$this->generateLink($start  ) . "' title=\"First Page\">&lt;&lt;</a> </li>";

					$paging .= "<li><a href='" .$this->generateLink( $prev )."' title=\"Previous Page\">Previous</a> </li>";
				}

				for($i = $from; $i <= $to; $i ++) {

							if ($i != $this->cur_page) {
								$paging .= "<li><a href='" . $this->generateLink( $i). "'>$i</a> </li>";
							} else {
								$paging .= "<li class=\"active\"><a href=\"javascript:void();\">$i<span class=\"sr-only\">(current)</span></a></li>";
							}

				}

				if ($this->cur_page == $totalPages) {
					$paging .= "<li class=\"disabled\"><a href=\"javascript:void();\">Next</a> </li>";
					$paging .= "<li class=\"disabled\"><a href=\"javascript:void();\">&gt;&gt;</a></li> ";
				} else {

					$paging .= "<li><a href='" .$this->generateLink( $next )."' title=\"Next Page\">Next</a></li>";

					$paging .= "<li><a href='" .$this->generateLink( $totalPages ). "' title=\"Last Page\">&gt;&gt;</a></li> ";
				}

		return "<div class=\"urlpaging\"><ul class=\"pagination \">" . $paging . "</ul></div>";
	}

	protected function generateLink($value){
		return AppController::generateGetLink(array($this->pgparam=>$value),$this->base_url);
	}

	public function getPagingString($str = '') {
		if ($str == '') {
			$str = "Showing %d to %d of %d records";
		}

		$pageFrom = $this->per_page * ($this->cur_page - 1) + 1;
		$pageTo = $pageFrom + $this->per_page - 1;
		if ($pageTo > $this->total_records) {
			$pageTo = $this->total_records;
		}

		return sprintf ( $str, $pageFrom, $pageTo, $this->total_records );
	}


}
// END Pagination Class
