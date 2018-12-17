<?php
class Call
{
    public function testCall()
    {
        $post_data = array(
            'From' => "8553790364",
            'To' => "9886549615",
            'CallerId' => "08033948823",
            'Url' => "https://4c162254.ngrok.io/Call.php",
            'TimeLimit' => "<time-in-seconds>", //This is optional
            'TimeOut' => "<time-in-seconds>", //This is also optional
            'CallType' => "trans",
            'StatusCallback' => "https://4c162254.ngrok.io/Call.php" //This is also also optional
        );
         
        $exotel_sid = "exoteltest4"; // Your Exotel SID - Get it here: http://my.exotel.in/Exotel/settings/site#exotel-settings
        $exotel_token = "0f84842f1b8a20a194d11813c43dc010ed712a89"; // Your exotel token - Get it here: http://my.exotel.in/Exotel/settings/site#exotel-settings
         
        $url = "https://".$exotel_sid.":".$exotel_token."@twilix.exotel.in/v1/Accounts/".$exotel_sid."/Calls/connect";
         
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
         
        $http_result = curl_exec($ch);
        $error = curl_error($ch);
        $http_code = curl_getinfo($ch ,CURLINFO_HTTP_CODE);
         
        curl_close($ch);
         
        print "Response = ".print_r($http_result);

    }
}
$call = new Call();
$call->testCall();
?>