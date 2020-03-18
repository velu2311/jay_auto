<?php

$doc_root = $_SERVER ['DOCUMENT_ROOT'];
require_once $doc_root . '/model/DB.class.php';
$db_class_obj = new DB (); // DB Connection
$GLOBALS ['mysqli_link'] = $db_class_obj->mysql_pconnect;

function query($query, $page_title = '', $script_location = '') {
    $result = q ( trim($query) );
    if ($result === FALSE) {
        reportSqlError ( $query, $page_title, $scriptLocation );
    }
    return $result;
}

function q($query, $assoc = 1, $attempt = 1) {
    if ($attempt != 1) {
        $db_class_obj = new DB (); // mysqli DB connection and database selection
        $GLOBALS ['mysqli_link'] = $db_class_obj->mysql_pconnect;
    }
    $start_time = microtime ( TRUE ); // Get start time
    $r = @mysqli_query ( $GLOBALS ['mysqli_link'], $query );
    $end_time = microtime ( TRUE ); // Get End time
    $difftime = $end_time - $start_time; // round(($end_time - $start_time),2); //Difference time
    $diff_time = round ( $difftime, 2 );
    if ($diff_time > 1) {
        error_log ( "\n" . date ( "Y-m-d H:i:s" ) . " : Sec(s) - " . $difftime . " :: Query - " . $query, 3, $_SERVER ['DOCUMENT_ROOT'] . '/tmp/slow_query.log' );
    }
    if (mysqli_connect_errno ()) {
        if (mysqli_connect_errno () == 1062) // Duplicate elements
        {
            return false;
        } elseif (mysqli_connect_errno () == 2006 && $attempt < 3) {
            
            // Retry Query Getting Error mysqli Server gone away.
            $timeStamp = strtotime ( "now" );
            $body = "";
            $body .= "Time Stamp: " . date ( "m-d-Y g:i:s A" ) . "\r";
            $body .= "SQL ERROR: " . mysqli_error () . "\r";
            $body .= "SQL ERROR NO : " . mysqli_connect_errno () . "\r";
            $body .= "Query: " . $query . "\r\r";
            $body .= "Attempt: 1 \r\r";
            $subject = DOMAIN_URL . " - ERROR ENCOUNTERED";
            $attempt = $attempt + 1;
            sleep ( 1 );
            // mail(ADMIN_EMAIL,$subject,$body);
            return q ( $query, $assoc, $attempt );
        }
        $error = 'mysqli ERROR #' . mysqli_connect_errno () . ' : <small>' . mysqli_connect_errno () . '</small><br><VAR>$query</VAR>';
        echo ($error);
        return FALSE;
    }
    $insert_respons = array ();
    if (strtolower ( substr ( $query, 0, 6 ) ) != 'select'){
        $insert_respons = array (
            mysqli_affected_rows ( $GLOBALS ['mysqli_link'] ),
            mysqli_insert_id ( $GLOBALS ['mysqli_link'] )
        );
        
        return $insert_respons;
    }
    $count = @mysqli_num_rows ( $r );
    if (! $count)
        return 0;
        if ($count == 1) {
            if ($assoc)
                $f = mysqli_fetch_assoc ( $r );
                else
                    $f = mysqli_fetch_row ( $r );
                    mysqli_free_result ( $r );
                    if (count ( $f ) == 1) {
                        list ( $key ) = array_keys ( $f );
                        return $f [$key];
                    } else {
                        $all = array ();
                        $all [] = $f;
                        return $all;
                    }
        } else {
            $all = array ();
            for($i = 0; $i < $count; $i ++) {
                if ($assoc)
                    $f = mysqli_fetch_assoc ( $r );
                    else
                        $f = mysqli_fetch_row ( $r );
                        $all [] = $f;
            }
            mysqli_free_result ( $r );
            return $all;
        }
}


function reportSqlError( $q, $scriptName, $scriptLocation ) {
    $timeStamp = strtotime ( "now" );
    $body = "";
    $body .= "Error #: " . $timeStamp . "\r";
    $body .= "Script: " . $scriptName . "\r";
    $body .= "Script Location: " . $scriptLocation . "\r";
    $body .= "Time Stamp: " . date ( "m-d-Y g:i:s A" ) . "\r";
    $body .= "SQL ERROR: " . mysqli_error () . "\r";
    $body .= "Query: " . $q . "\r\r";
    $subject = DOMAIN_URL . " - ERROR ENCOUNTERED";
    error_log ( "\n" . date ( "Y-m-d H:i:s" ) . " : /*================ ", 3, $_SERVER ['DOCUMENT_ROOT'] . '/tmp/mysqli-error.log' );
    error_log ( "\n" . date ( "Y-m-d H:i:s" ) . " : Subject : " . $subject, 3, $_SERVER ['DOCUMENT_ROOT'] . '/tmp/mysqli-error.log' );
    error_log ( "\n" . date ( "Y-m-d H:i:s" ) . " : Error : " . mysqli_error (), 3, $_SERVER ['DOCUMENT_ROOT'] . '/tmp/mysqli-error.log' );
    error_log ( "\n" . date ( "Y-m-d H:i:s" ) . " : Error No : " . mysqli_errno (), 3, $_SERVER ['DOCUMENT_ROOT'] . '/tmp/mysqli-error.log' );
    error_log ( "\n" . date ( "Y-m-d H:i:s" ) . " : Query : " . $q, 3, $_SERVER ['DOCUMENT_ROOT'] . '/tmp/mysqli-error.log' );
    error_log ( "\n" . date ( "Y-m-d H:i:s" ) . " : ================*/ ", 3, $_SERVER ['DOCUMENT_ROOT'] . '/tmp/mysqli-error.log' );
    return false;
}