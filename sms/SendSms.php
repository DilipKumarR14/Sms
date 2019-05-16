<?php
include "Constant.php";
include "otp.txt";
include "CallApi.php";
class SendSms
{
    public function sendingSMS()
    {
        $objConst = new Constant();
        file_put_contents("otp.txt", "");
        $r = rand(1000, 9999);
        $file = fopen("otp.txt", "w");
        fwrite($file, "Your OTP Number is $r");
        fclose($file);
        $post_data = array(
            'From' => "08660558202",
            'To' => "8553790364",
            'Body' => "This is a test message being sent using Exotel with a (OTP) and ($r) yeah!! report to 08088919888"
        );
        $exotel_sid = $objConst->exoId;
        $exotel_token = $objConst->exoToken;
        
        // post request
$url = "http://" . $exotel_sid . ":" . $exotel_token . "@twilix.exotel.in/v1/Accounts/".$exotel_sid . "/Sms/send.json";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));

        $http_result = curl_exec($ch);

        $decode = json_decode($http_result,true);
        
        // get request

        echo "\nWaiting for Response..\n";
        sleep(10);
        
        $url = "http://" . $exotel_sid . ":" . $exotel_token . "@twilix.exotel.com/v1/Accounts/" . $exotel_sid . "/SMS/Messages/" . $decode["SMSMessage"]["Sid"] . ".json";

        $ch = curl_init();// initialize the curl session
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $http_result = curl_exec($ch);
        $decode = json_decode($http_result, true);

        $resultStatus = $decode['SMSMessage']['Status'];
        
        if ($resultStatus == "submitted") {
            echo "\nSMS Sent Successfully\n";
            print_r($http_result);
            echo "\n";
        } else {
            // call
            echo "\n SMS Sent Failed\n";
            print_r($http_result);
            echo "\n";
            // $obj = new CallApi();
            // $obj->callApp();  
    
        }
        curl_close($ch);
    }
}
$objSms = new SendSms();
$objSms->sendingSMS();