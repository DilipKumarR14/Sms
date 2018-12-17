<?php
class ChechSmsSent
{
    public function sendSms($Smsid,$otp)
    {
        $objConst = new Constant();
        $exotel_sid = $objConst->exoId; // Your Exotel SID - Get it from here: http://my.exotel.in/Exotel/settings/site#api-settings
        $exotel_token = $objConst->exoToken; // Your exotel token - Get it from here: http://my.exotel.in/Exotel/settings/site#api-settings
        $url = "https://" . $exotel_sid . ":" . $exotel_token . "@api.exotel.com/v1/Accounts/" . $exotel_sid . "/SMS/Messages/" . $Smsid . ".json";

        $ch = curl_init();// initialize the curl session
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $http_result = curl_exec($ch);
        $decode = json_decode($http_result, true);
        $resultStatus = $decode['SMSMessage']['Status'];
        $resultBody = $decode['SMSMessage']['Body'];
        $result = preg_match("/$otp/",$resultBody);

        // $getResult = explode(" ",$resultBody);
        if ($resultStatus == 'sent' && preg_match("/$otp/",$resultBody)) {
            echo "Sent\n";

        } else if ($resultStatus == 'queued') {
            $obj = new ChechSmsSent();
            $obj->sendSms();
            sleep(30);
        } else {
            echo "Failed\n";        
        }
        // print_r(localtime(time(), true));

    }
}