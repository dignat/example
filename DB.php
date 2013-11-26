<?php




	class DB{
		public $host;
		public $user;
		public $pass;
		public $db;
		public $con;
		public $name;
		private $array = array();
		private $rows = '';

		public function __construct($host = 'localhost', $user, $pass, $db = 'e')
		{
			$this->host = $host;
			$this->user = $user;
			$this->pass = $pass;
			$this->db = $db;
			
		}


		public function connect()
		{
			$this->con = mysql_connect($this->host, $this->user, $this->pass);
			if(mysql_error()){
				echo "Error connecting";
			}
			return $this->con;
		}

		private function query($sql)
		{
			return mysql_query($sql, $this->con);
		}

		private function data($data)
		{
			if(is_array($data)){
				foreach($data as $key=>$value){
					if(!is_array($data[$key])){
						$data[$key] = mysql_real_escape_string($data[$key], $this->con);
					}
				
				else{
					$data = mysql_real_escape_string($data, $this->con);
				}
			}
		}
			return $data;
		}

		private function setRows($rows)
		{
			$this->rows = $rows;
			if(is_array($rows) && !empty($rows)){

				$string = "id INT(11),";
				foreach($rows as $key=>$value){
					$string .= "{$key} VARCHAR(255),";
				
				}	
				$string = substr($string, 0, -1);
					return $string;

			}
			else{
				return $string;
			}

		}

		function selectDatabase($db) // selecting the database.

    {
    	$db = $this->db;
        mysql_select_db($this->db, $this->connect());  //use php inbuild functions for select database

        if(mysql_error()) // if error occured display the error message
        {

            echo "Cannot find the database ".$this->db;

        }
         echo "Database selected.." .$this->db;       
    }

    	public function getColumns($table)
    	{
    		$sql = "SHOW COLUMNS FROM {$table}";
    		$rs = $this->query($sql);
    		if(mysql_num_rows($rs)>0){
    			while($row = mysql_fetch_assoc($rs)){
    				//print_r($row);
    				echo $row['Field']. ',';

    			}
    		}



    	}

		public function table($name, $rows)
		{
			$rows = $this->setRows($rows);
			$create = "CREATE TABLE IF NOT EXISTS {$name} ({$rows})"; 
			
				 $query = $this->query($create);

				if(!$query){
					echo "table has not been created";
				}
				else{
					echo " we have table {$name}";
				}
				return print $create;

		}


		public function insert($array, $name)
		{
			$array = $this->data($array);
			$table = $this->name;

				
               // array_push($exclude, 'MAX_FILE_SIZE'); // Automatically exclude this one
                
                // Prepare Variables
                $array = $this->data($array);
                
                
                foreach($array as $key=>$value){
                       
                        $insert = "INSERT INTO {$name} VALUES (' ', ";
                      
                        $insert .= "'{$value}'";
                
               
                //$insert = substr($insert, 0, -2);
				
				$insert .= ")";
				$query = $this->query($insert);
				/*if(!$query){
					echo 'No insert has beed performed';
				}
				else{
					echo "I bet you need this news";
				}

				//print $insert;*/
}


	
			
		}
	}
?>