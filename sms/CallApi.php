<?php
include "Status.php";
class CallApi
{
    public function callApp()
    {
        echo "\nCalling the Number\n";
        $objConst = new Constant();
        $post_data = array(
            'From' =>$objConst->to,
            'CallerId' =>$objConst->from,
            'Url' => "http://my.exotel.com/exoteltest4/exoml/start_voice/204581"
        );
        $exotel_sid = $objConst->exoId;
        $exotel_token = $objConst->exoToken;

        $url = "https://" . $exotel_sid . ":" . $exotel_token . "@api.exotel.com/v1/Accounts/" . $exotel_sid . "/Calls/connect.json";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));

        $http_result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $decode = json_decode($http_result, true);
        $sid = $decode["Call"]["Sid"];

        // get request
        sleep(20);
        $objstat = new Status();
        $result = $objstat->stat($exotel_sid,$exotel_token,$sid);
        

        if ($result == "completed") {
            echo "\nSuccess Call\n";
        }
        else {
            echo "\nFailed Calling the Number\n";
        }

        curl_close($ch);
    }
}