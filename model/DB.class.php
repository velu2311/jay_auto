<?php
class DB {
    public $hostname;
    public $database;
    public $username;
    public $password;
    public $mysql_pconnect;
    
    public function __construct( ) {
    $this->database = "jayauto";
    $this->username = "root";
    $this->password = "";
    $this->hostname = "localhost";
    $this->mysql_pconnect = mysqli_connect ( $this->hostname, $this->username, $this->password, $this->database );
    if (! $this->mysql_pconnect) {
        die ( 'Connection is currently unavailable.' );
    }
    return $this->mysql_pconnect;
    }
  
 }
