<?php
class Status{
    public function stat($exotel_sid,$exotel_token,$sid){
        echo "\nChecking Status\n";
        sleep(40);
        $url = "https://" . $exotel_sid . ":" . $exotel_token . "@api.exotel.com/v1/Accounts/" . $exotel_sid . "/Calls/$sid.json";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $http_result = curl_exec($ch);

        $res = explode(",", $http_result);
        $decode = json_decode($http_result, true);

        $http_result = $decode["Call"]["Status"];
        return $http_result;
    }
}