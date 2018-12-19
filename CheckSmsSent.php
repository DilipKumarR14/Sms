<?php
/**
 * GET Request for the smsid of call
 */
class ChechSmsSent
{
    public function sendSms($Smsid,$otp)
    {
        $objConst = new Constant();
        $exotel_sid = $objConst->exoId;
        $exotel_token = $objConst->exoToken; 
        $url = "https://" . $exotel_sid . ":" . $exotel_token . "@api.exotel.com/v1/Accounts/" . $exotel_sid . "/SMS/Messages/" . $Smsid . ".json";

        $ch = curl_init();// initialize the curl session
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $http_result = curl_exec($ch);
        $decode = json_decode($http_result, true);
        $resultStatus = $decode['SMSMessage']['Status'];
        $resultBody = $decode['SMSMessage']['Body'];

        // $getResult = explode(" ",$resultBody);
        if ($resultStatus == 'sent' && preg_match("/$otp/",$resultBody)) {
            echo "\nSent\n";

        } else if ($resultStatus == 'queued') {
            sleep(1);
            $obj = new ChechSmsSent();
            $obj->sendSms($Smsid,$otp);
        } else {

            echo "Failed\n";
            echo "Calling..\n";
            require "/var/www/html/call/Call.php";
            $objCall = new Call();
            $objCall->testCall();        
        }
        // print_r(localtime(time(), true));

    }
}