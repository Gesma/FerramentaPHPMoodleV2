<?php

	class ConnectionFactory{
		
		private $host = "localhost";
		private $port = 5432;
		private $dbName = "moodle";
		private $user = "posrtgres";
		private $password = "root";
		
		public function getConnection(){
			return new PDO("pgsql:host=$this->host;dbname=$this->dbName;username=$this->user;password=$this->password");
		}
	} 