<?php

class OAuthServices {

    public function getAccessToken($code) {
        $auth_token_url = 'https://api.vonage.com/token';
        $params ['client_id'] = OAUTH2_CLIENT_ID;
        $params ['client_secret'] = OAUTH2_CLIENT_SECRET;
        $params ['code'] = $code;
        $params ['redirect_uri'] = 'http://velmani-dev.sirahu.net/controller/redirect.php';
        $params ['grant_type'] = 'authorization_code';
        $token_response = $this->APICall ( $auth_token_url, $params, 'POST' );
        return $token_response;
    }

    public function APICall($url, $params, $method_type = 'POST') {
        $credentials = base64_encode(OAUTH2_CLIENT_ID . ":" . OAUTH2_CLIENT_SECRET);
        echo $credentials; 
        $curl = curl_init ();
        curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $curl, CURLOPT_URL, $url );
        curl_setopt ( $curl, CURLOPT_HTTPHEADER,"Authorization: Basic".$credentials);
        curl_setopt ( $curl, CURLOPT_HTTPHEADER, "Content-Type: application/x-www-form-urlencoded");
        curl_setopt ( $curl, CURLOPT_POST, true);
        curl_setopt ( $curl, CURLOPT_POSTFIELDS, http_build_query($params) );
        $result = curl_exec ( $curl );
        curl_close ( $curl );
        $result = json_decode ( $result, true );
        return $result;
    }
    
}