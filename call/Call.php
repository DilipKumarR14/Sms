<?php
include "CallBack.php";
class Call
{
    public function testCall()
    {
        $post_data = array(
            'From' => '855379036',
            'CallerId' => "0803394882",
            'Url' => "http://my.exotel.com/exoteltest4/exoml/start_voice/20451"
        );
        $exotel_sid = "exoteltest4";
        $exotel_token = "0f84842f1b8a20a194d11813c43dc010ed72a89";

        $url = "https://" . $exotel_sid . ":" . $exotel_token . "@api.exotel.com/v1/Accounts/" . $exotel_sid . "/Calls/connect.json";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));

        $http_result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code != 400) {
            $jsonResult = json_decode($http_result, true);
            $sid = $jsonResult["Call"]["Sid"];
            $objCallBack = new CallBack();
            $objCallBack->callbackresult($sid);
        } else {
            echo "\nFailed Calling the Number\n";
        }
        curl_close($ch);
    }
}
?>