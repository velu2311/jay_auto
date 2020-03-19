<?php

/*
 * Author - 
 * For Users table
 */


 class Users{
     
     public $user_id;
     public $account_id;
     public $identifier;
     public $first_name;
     public $last_name;
     public $say_name;
     public $email;
     public $is_enabled;
     public $password;
     public $u_time_zone;
     public $user_type_id;
     public $bs_userid;
     public $bs_access_code;
     public $bs_phone_number;
     public $bs_id;
     public $id;
     public $user_auth;
     public $extension_password;
     public $oauth_type;
     public $additional_features;
     public $date_entered;
     public $date_modified;
     public $last_login;
     public $u_language;
     public $account_connector_type;
     public $tb_conference_id;
     public $tb_pin;
     public $tb_token;
     public $gacal_token;
     public $gacal_email;
     public $terms_checked;
     public $bw_password;
     public $bw_username;
     public $sp_telecom_type;
     public $fields;
    
     public function getUserDetails($confirmationCode){
         $query = "select * from users where identifier = '".$confirmationCode."'";
         $user_details = query ( $query, $pageTitle, $scriptLocation );
         return $user_details;
     }
    
}

?>