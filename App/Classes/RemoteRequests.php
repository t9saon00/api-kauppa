<?php

namespace Slimapp\Classes;

class RemoteRequests {

    private function send($url, $params, $headers = array()){
        $session = curl_init();
        $url = $url . '?' . http_build_query($params);

        curl_setopt_array($session, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $headers
          ));

        return curl_exec($session);
    }

    public static function remote_get($url, $params, $headers = array()){
        $self = (new self);
        
        return $self->send($url, $params, $headers);     
    }


}