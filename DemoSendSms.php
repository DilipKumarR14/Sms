<?php
include "CheckSmsSent.php";
include "Constant.php";
class DemoSendSms
{
    public function sendSms()
    {
        $objConst = new Constant();
        $r = rand(1000,9999);
        $post_data = array(
            'From' => $objConst->from,
            'To' => $objConst->to,
            'Body' => "This is a test message being sent using Exotel with a (OTP) and ($r). If this is being abused, report to 08088919888"
        );
        $exotel_sid = $objConst->exoId; // Your Exotel SID - Get it from here: http://my.exotel.in/Exotel/settings/site#api-settings
        $exotel_token = $objConst->exoToken; // Your exotel token - Get it from here: http://my.exotel.in/Exotel/settings/site#api-settings

        $url = "https://" . $exotel_sid . ":" . $exotel_token . "@twilix.exotel.in/v1/Accounts/" . $exotel_sid . "/Sms/send.json";

        $ch = curl_init();// initialize the curl session
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));

        $http_result = curl_exec($ch);
        $error = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $decode = json_decode($http_result, true);

        $sid = $decode['SMSMessage']['Sid'];
        $status = $decode['SMSMessage']['Body'];

        if ($http_code == 200) {
            echo "SMS Sent Successfully<br>";
            echo $http_result;
            // print "Response = " .print_r($http_result);
            sleep(5);
            // $res = localtime(time());
            // $res = $res[0];
            // echo $res;
            $obj = new ChechSmsSent();
            $obj->sendSms($sid,$r);
 
        } else {
            echo " SMS Sent Failed<br>";
            echo $http_result;
            // print "Response = " .print_r($http_result);
        }
        curl_close($ch);
    }
}
$obj = new DemoSendSms();
$obj->sendSms();
?>