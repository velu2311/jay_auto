<?php
require $_SERVER['DOCUMENT_ROOT']."/includes/conf/conf.php";
require_once DOC_ROOT.'/controller/oauth.php';

if (! empty ( $_REQUEST )) {
    $code = $_REQUEST['code'];
    $service = new OAuthServices();
    $token = $service->getAccessToken($code);
    echo "<pre>"; print_r($token);exit;
}