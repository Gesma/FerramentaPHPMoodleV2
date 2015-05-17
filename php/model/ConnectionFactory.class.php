<?php

	class ConnectionFactory{
		
		private $host = "localhost";
		private $port = 5432;
		private $dbName = "moodle";
		private $user = "posrtgres";
		private $password = "root";
		
		public function getConnection(){
			return new PDO("mysql:host=". $this->host . ";dbname=" . $this->dbName,
					 $this->user, $this->password);
		}
	} 