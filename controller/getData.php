<?php
/*
 * Author - 
 * For Getting Details for jayAuto
 */
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/includes/conf/conf.php';
require_once DOC_ROOT.'/includes/functions.php';
require_once DOC_ROOT.'/includes/conf/conf.php';
require_once DOC_ROOT.'/model/Users.class.php';

set_time_limit(0);
ini_set('display_errors', 0); 

foreach ($_POST as $k => $v) {
    $v = filter_var($v, FILTER_SANITIZE_STRING);
    $_POST[$k] = $v;
}
foreach ($_GET as $k => $v) {
    $v = filter_var($v, FILTER_SANITIZE_STRING);
    $_GET[$k] = $v;
}
$payload['status'] = "failed";

if (strlen ( $_REQUEST ['type'] )) {
    
    switch ($_REQUEST ['type']) {
        
        case "getConfirmationCode" : // Get confirmation code
            $confirmation_code = trim ( $_POST ['confirmation_code'] );
            getConfirmationCode ( $confirmation_code );
            break;
            
        default :
            header ( "Content-type: application/json" );
            $payload ['message'] = 'invalid method';
            print (json_encode ( $payload )) ;
            break;
    }
} else {
    header ( "Content-type: application/json" );
    $payload ['message'] = 'invalid method';
    print (json_encode ( $payload )) ;
    exit ();
}

function getConfirmationCode($confirmation_code){
    $data['status'] = 'failed';
    $users = new Users();
   
    $isExistUser = $users->getUserDetails($confirmation_code);
    if(empty($isExistUser)){
        $data['status'] = 'success';
        
    }
    print (json_encode ( $data )) ;
    echo $data;
    exit ();
}

?>