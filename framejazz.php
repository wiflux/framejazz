<?php
/**
                                                                                      ..,;: sAB#@@@@@S      
                                                                         r22s    S@@@@@@@@@ @@@@@@@@@i      
                                                     .,:rSs      @@@s    @@@@    2@@@@@@@@# #@@@@@@@@s      
                                    :::i.  :@@@@  @@@@@@@@@      @@@r   :@@@@.   s@@@@@@@@H S@AA9A@@@,      
                  ..       i@@@.    @@@@G  A@@@@  @@@@@@@@@      M@@;   2@@@@2        .@@@       9@@A       
   ,;rSXH@@: @@@@@@@@@     @@@@H    @@@@@  @@@@@  @@@            A@@;   @@9H@@        @@@S      :@@@        
   @@@@@@@@, @@@2,:@@@A   r@@#@@    @@2@@  @@S@@  @@@            A@@;   @@.r@@       h@@@       @@@s        
   @@@:      @@@   3@@i   @@2 @@5   @@;2@. @5;@@  @@@@@@@@r      A@@;  ;@@  @@;     :@@@.      H@@@         
   H@@@@@@A  @@@@@@@M;   ;@@  #@@   @@:,@i:@,:@@  @@@@@@@@S      A@@;  H@@  @@A     @@@X      :@@@.         
   A@@r  .   @@@;;@@A    @@@, 9@@;  @@r @@M@ ;@@  @@@;.::s.      A@@;  @@#  @@@    H@@@       @@@&          
   #@@:      @@@  ;@@@  :@@@@@@@@@  @@i @@@@ s@@  @@@       X@@  &@@: ,@@@H9@@@   ;@@@.      X@@@           
   .s3.      #@@,  M@@@ @@@r:;;@@@  @@2 3@@A S@@  @@@,      @@@  @@@. 2@@@@@@@@s  @@@i      .@@@;           
                    ;i&.9H9    ;@@# @@A :@@r 3@@  @@@@@@@@@ X@@@#@@@  @@@@@@@@@# B@@@       @@@A            
                                    .r,  XH  s@@  @@@@@@@@@  @@@@@@9  @@M    @@@ @@@@@@@@@#.@@@#AMBBMr      
                                                      ,,;2i   M@@@X  5@@3    @@@.#@@@@@@@@@ @@@@@@@@@#      
                                                                       ,     ;#@;3@@@@@@@@@ @@@@@@@@@&      
                                                                                         ;. 5S5&BM@@@A      
 *
 * Framejazz PHP Class
 * PHP versions 4 and 5
 * This is Beta Version of PHP Class
 * Developer : Dharmender Singh Negi
 * Date : 10-Dec-2011
 */
 Class Framejazz{
	#Database configuration
	var $default = array(
						'host' 		=>	'localhost',
						'username'	=>	'root',
						'password'	=>	'',
						'database'	=>	'api',
					);
	
	/********************************************************************************************************************************************
	* For Setting public Varibles
	*********************************************************************************************************************************************/
		#0: No error messages, errors, or warnings shown.
		#1: Errors and warnings shown.
		#2: As in 1, but also with full debug messages.
		var $debug = 2;
		
		#For setting Time zone
		#List of time Zons : http://php.net/manual/en/timezones.php
		#example : var $timezone = "Asia/Kolkata";
		var $timezone = false;
		
		#For setting execution time in seconds;
		#example : var $maxExecutionTime = 30; # 30seconds
		var $maxExecutionTime = false;
		
		#For Setting  Maximum upload file size in MB
		#example  var $maxUploadSize	= 2M; # 2MB
		var $maxUploadSize	= false;
		
		#for filtring POST, GET and REQUEST method array data
		#using trim and mysql_escape_string
		var $filter = array(
							'POST'	=> false,
							'GET'	=> false,
							'REQUEST' => false,
							); 
		#For enabling ajax
		#You must include : http://code.jquery.com/jquery.min.js
		var $jquery	= array(
							'ajax' 			=> true,
							'validation' 	=> true,
							'datepicker' 	=> true,
							'masking'		=> true,
							);
							
		var $connection;
		var $database;
		
		var $page;
	}
	/******************************************************************************************************************************************/
 
 
	/*******************************************************************************************************************************************
	* For Hendling Databse : Methods 
	*******************************************************************************************************************************************/
	 class Model extends Framejazz{
		
		protected function connect(){
			$con = mysql_connect($this->default['host'],$this->default['username'],$this->default['password']);
			if(!$con){ die("Connection Fail : ".mysql_error()); }
			$this->connection = $con;
			$db = mysql_select_db($this->default['database'],$con);
			if(!$con){ die("Database Not Selected : ".mysql_error());}
			$this->database = $db;
		}
		
		public function query($sql){
			$result = mysql_query($sql,$this->connection);
			if(!$result){ die("Error on Query : ".mysql_error()); }
			return $result;
		}

	}
	/*******************************************************************************************************************************************/
 
 
 
	/*******************************************************************************************************************************************
	* For Hendling Databse : Methods 
	*******************************************************************************************************************************************/
	class Paginator extends Model{
		var $limit = 10;
		var $pageno = 1;
		
		public function paginate($sql){
			$result = mysql_query($sql,$this->connection);
			if(!$result){ die("Error on Query : ".mysql_error()); }
			$totalResords = mysql_num_rows($result);
			 
			$this->page['var'] = $this->calculate_pages($totalResords, $this->limit, $this->pageno);
			$this->page['var']['info']['total'] = $totalResords;
			
			$this->GeratePaginate();
			
			$result = mysql_query($sql." ".$this->page['var']['limit'],$this->connection);
			if(!$result){ die("Error on Query : ".mysql_error()); }
			return $result;
		}
		
		private function GeratePaginate(){
			
			$arr = $this->page['var'];
			
			$first = ($arr['current'] == 1) ? '<span> first</span>' : '<a href="?page=1" title="Go to page first"> first </a>';
			$previous = ((in_array($arr['previous'],$arr['pages']) === true) && ($arr['current'] != $arr['previous'])) ? '<a href="?page='.$arr['previous'].'" title="Go to previous page"> previous </a>' : '<span> previous </span>';
			$midpage = null;
			foreach($arr['pages'] as $page){
					if($arr['current'] == $page){
						$midpage .= '<span class="current">'.$page.'</span>';
					}else{
						$midpage .= '<a href="?page='.$page.'" title="Go to page '.$page.'">'.$page.'</a>';
					}
			}
			$next = ((in_array($arr['next'],$arr['pages']) === true) && ($arr['current'] != $arr['next'])) ? '<a href="?page='.$arr['next'].'" title="Go to next page"> next </a>' : '<span> next </span>';
			$last = ($arr['current'] == $arr['last']) ? '<span> last </span>' : '<a href="?page='.$arr['last'].'" title="Go to last page"> last </a>';
			
			$this->page['link']  =	'<div class="pagination">'.$first.$previous.$midpage.$next.$last.'</div>';
			
		}
		
		
		private function calculate_pages($total_rows, $rows_per_page, $page_num){
			$arr = array();
			// calculate last page
			$last_page = ceil($total_rows / $rows_per_page);
			// make sure we are within limits
			$page_num = (int) $page_num;
			
			if ($page_num < 1){
			   $page_num = 1;
			}elseif($page_num > $last_page){
			   $page_num = $last_page;
			}
			
			$upto = ($page_num - 1) * $rows_per_page;
			$arr['limit'] = 'LIMIT '.$upto.',' .$rows_per_page;
			$arr['current'] = $page_num;
			
			if ($page_num == 1){
				$arr['previous'] = $page_num;
			}else{
				$arr['previous'] = $page_num - 1;
			}
			if ($page_num == $last_page){
				$arr['next'] = $last_page;
			}else{
				$arr['next'] = $page_num + 1;
			}
			
			$arr['last'] = $last_page;
			$arr['info']['start']	=	$upto;
			$arr['info']['limit']	=	$rows_per_page;
			$arr['info']['current'] = 	$page_num;
			$arr['info']['last']	= 	$last_page;
			$arr['pages'] = $this->get_surrounding_pages($page_num, $last_page, $arr['next']);
			return $arr;
			
		}
		
		private function get_surrounding_pages($page_num, $last_page, $next){
			$arr = array();
			$show = 5; // how many boxes
			// at first
			if ($page_num == 1){
				// case of 1 page only
				if($next == $page_num) return array(1);
				for ($i = 0; $i < $show; $i++){
					if ($i == $last_page) break;
					array_push($arr, $i + 1);
				}
				return $arr;
			}
			// at last
			if ($page_num == $last_page){
				$start = $last_page - $show;
				if ($start < 1) $start = 0;
				for ($i = $start; $i < $last_page; $i++){
					array_push($arr, $i + 1);
				}
				return $arr;
			}
			// at middle
			$start = $page_num - $show;
			if ($start < 1) $start = 0;
			for ($i = $start; $i < $page_num; $i++){
				array_push($arr, $i + 1);
			}
			for ($i = ($page_num + 1); $i < ($page_num + $show); $i++){
				if ($i == ($last_page + 1)) break;
				array_push($arr, $i);
			}
			return $arr;
		}
	}
	/*******************************************************************************************************************************************/
	
	
	
	
	/*******************************************************************************************************************************************
	* For Hendling Request : Methods 
	*******************************************************************************************************************************************/
	class RequestFilter extends Paginator{
			
			
			function filter(){
				$this->GETFilter();
				$this->POSTFilter();
				$this->REQUESTFilter();
			}
			
			private function GETFilter(){
				if(!empty($_GET)){
					foreach($_GET as $key => $value){
						if($key = 'page'){
							if(is_numeric($value)){
								$this->pageno = $value;
							}
						}
						$_GET[$key] = $this->filterRule($value);
					}
				}
			}
			
			private function POSTFilter(){
				if(!empty($_POST)){
					
				}
			}
			
			private function REQUESTFilter(){
				if(!empty($_POST)){
					
				}
			}
			
			private function filterRule($value){
				return trim(mysql_escape_string($value));
			}
			
			

	}
 
	/******************************************************************************************************************************************/
	
	
	
	/*******************************************************************************************************************************************
	* For Hendling HTML : Methods 
	*******************************************************************************************************************************************/
	class HTML extends RequestFilter{
	
		public function css($arr,$url="css/"){
			if(is_array($arr)){
				$link = null;
				foreach($arr as $a){
					$link .= '<link rel="stylesheet" type="text/css" media="screen" href="'.$url.$a.'.css"/>';
				}
			}else{
				$link = '<link rel="stylesheet" type="text/css" media="screen" href="'.$url.$arr.'.css"/>';
			}
			return $link;
		}
		
		public function javascript($arr,$url="js/"){
			if(is_array($arr)){
				$link = null;
				foreach($arr as $a){
					$link .= '<script src="'.$url.$a.'.js" type="text/javascript"></script>';
				}
			}else{
				$link = '<script src="'.$url.$arr.'.js" type="text/javascript"></script>';
			}
			return $link;
		}
	
		function __construct(){
			$this->connect();
			$this->filter();
		}
	
	}
	
	
	/*******************************************************************************************************************************************/
	function pr($arr){
		echo "<pre>";
		print_r($arr);
	}
	
	$Obj = new HTML;
	