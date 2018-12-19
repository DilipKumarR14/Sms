<?php
class CallBack
{
    public function callbackresult($sid)
    {

        $exotel_sid = "exoteltest4";
        $exotel_token = "0f84842f1b8a20a194d11813c43dc010ed712a89";

        $url = "https://" . $exotel_sid . ":" . $exotel_token . "@api.exotel.com/v1/Accounts/" . $exotel_sid . "/Calls/$sid.json";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $http_result = curl_exec($ch);
        $jsonresult = json_decode($http_result, true);

        $status = $jsonresult["Call"]["Status"];
        if ($status == "in-progress" || $status ==  "queued") {
            sleep(1);
            $this->callbackresult($sid);
           
        } else if ($status == "completed") {
            echo "\nSuccess\n";
            $res = explode(",", $http_result);
            print_r($res);
        }else if($status == "ringing"){
            sleep(1);
            $this->callbackresult($sid);
        }else if($status == "no-answer"){
            echo "\nCall Not Answered\n";
        }
        else{
            echo "Failed\n";
        }
        curl_close($ch);
    }
}
?>