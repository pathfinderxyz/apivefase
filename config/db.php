<?php

class DB{
    private $user = 'qshdpwyoayjidf';
	private $pass = '5b2c4dd891985ed6b3f8fed13656ad510b4b9ebb3f6f516a0da747cb9ea9101e';
	private $db = 'd5jnscr9b6lic1';
	private $port = 5432;
	private $host = 'ec2-52-44-209-165.compute-1.amazonaws.com';

    public function connect(){
        $conn_str="pgsql:host=$this->host;dbname=$this->db;port=$this->port";
        $conn = new PDO($conn_str,$this->user,$this->pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        return $conn;
    }
}