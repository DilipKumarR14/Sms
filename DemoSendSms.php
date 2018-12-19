<?php
include "Constant.php";
include "otp.txt";
class DemoSendSms
{
    public function sendSms()
    {
        $objConst = new Constant();
        $r = rand(1000, 9999);
        $file = fopen("otp.txt", "w");
        $a = fwrite($file, "Your OTP Number is $r");
        fclose($file);
        $post_data = array(
            'From' => $objConst->from,
            'To' => $objConst->to,
            'Body' => "This is a test message being sent using Exotel with a (OTP) and ($r). If this is being abused, report to 08088919888"
        );
        $exotel_sid = $objConst->exoId;
        $exotel_token = $objConst->exoToken;

        $url = "https://" . $exotel_sid . ":" . $exotel_token . "@twilix.exotel.in/v1/Accounts/" . $exotel_sid . "/Sms/send.json";

        $ch = curl_init();// initialize the curl session
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));

        $http_result = curl_exec($ch);
        $jsondecode = json_decode($http_result, true);
        if ($jsondecode["RestException"]["Status"] != 400) {
            $error = curl_error($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $decode = json_decode($http_result, true);

            $sid = $decode['SMSMessage']['Sid'];
            $status = $decode['SMSMessage']['Body'];

            if ($http_code == 200) {
                echo "SMS Sent Successfully\n";
                echo $http_result;
                sleep(1);
            // $res = localtime(time());
            // $res = $res[0];
            // echo $res;
                include "CheckSmsSent.php";
                $obj = new ChechSmsSent();
                $obj->sendSms($sid, $r);

            } else {
                echo "\n SMS Sent Failed\n";
                echo $http_result;
            }
        } else {
            require "/var/www/html/call/Call.php";
            echo "\nSMS SENT FAILED\n";
            $objCall = new Call();
            $objCall->testCall();
        }

        curl_close($ch);
    }
}
$objdemo = new DemoSendSms();
$objdemo->sendSms();
?>