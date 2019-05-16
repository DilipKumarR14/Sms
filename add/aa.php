<?php
include_once "./predis/autoload.php";
class UpdateDndNumberToJfk
{

    private $conn = null;
    private $hostName = "";
    private $dbName = "";
    private $dbUserName = "";
    private $dbPassword = "";
    function __construct()
    {
        $this->getConnection($this->hostName, $this->dbName, $this->dbUserName, $this->dbPassword);
    }

    /**
     * twilix database connection
     * @param string hostname name of the host
     * @param string dbname database name
     * @param string database user name
     * @param string database password 
     */
    private function getConnection($hostName, $dbName, $dbUserName, $dbPassword)
    {
        $this->conn = new mysqli($hostName, $dbUserName, $dbPassword, $dbName);
        if ($this->conn->connect_error) {
            echo "DB conection falied\n";
            exit(2);
        }
    }

    /**
     * function get the list of `to failed-dnd number` from SMSMessage table
     * @return array of `to` numbers
     */
    function getFailedDndNumbers()
    {
        $start_date = date('Y-m-d', strtotime('first day of last month')) . ' 00:00:00';
        $end_date = date('Y-m-d', strtotime('last day of last month')) . ' 00:00:00';
        $less_than_ten_may = date('Y-m-d'). ' 00:00:00';
        // $sql_query =
        // "SELECT `To` 
        //  FROM SMSMessage 
        //  WHERE Status='failed-dnd' 
        //  AND   DateCreated >= $start_date
        //  AND   DateCreated <= $end_date ";
        $sql_query = "select `To` from  where ='failed-dnd' and DateCreated <='2019-05-10 00:00:00' limit 1";
        $resultToNumber = $this->executeSelectQuery($sql_query);
        if(empty($resultToNumber)){
            return;
        }
        return $resultToNumber;
    }

    /**
     * function executes the sql query and return the to failed dnd numbers
     * @param string sql query for db
     * @return array of to numbers
   */
    private function executeSelectQuery($sql)
    {
        $toNum = array();
        $rows = $this->conn->query($sql);
        while ($row = $rows->fetch_object()) {
            $toNum[] = $row;
        }
        if(empty($toNum)){
            return;
        }
        $rows->close();
        return $toNum;
    }

    /**
     * function create the object of redis
     * @return object redis object
     */
    function connectToRedis()
    {

        Predis\Autoloader::register();
        try {
            $redis = new Predis\Client(array(
                "host" => "",
                "port" => "6379"
            ));
        } catch (Exception $e) {
            echo ("Could not connected to Redis " . $e->getMessage());
        }
        return $redis;
    }

    /**
     * function adds the failed-dnd number to jfk
     * @param object $redis object
     * @param int $number
     * @param int $preference
     */
    function addNumberToJfk($redis, $number, $preference)
    {
        $preference = "A|" . $preference;
        $number = substr($number,-10);
        $key = $this->getHashKey($number);
        $redis->hset($key, $number, $preference);
    }

    /**
     * function adds the preference for the numbers
     * @param int number
     * @return string key of the number
     */
    function getHashKey($number)
    {
        $KeysPerBucket = 1000;
        $bucket = (int)$number / $KeysPerBucket;
        $preference = "prop:". strval((int)$bucket);
return $preference;
    }
    /**
     * function starts the cron , get the numbers from mysql
     * checks whether number is present in the jfk
     * if not present add it.
     */
    public function startCron()
    {
        // mysql call
        $resultToNum = $this->getFailedDndNumbers();
        if(empty($resultToNum)){
            echo "empty to number from db\n";
            return;
        }
        echo "to number from mysql\n".print_r($resultToNum,true);
        $file = fopen("to_numbers.csv","a");
        $resultToNum = json_decode(json_encode($resultToNum), true);
        $temp = array();
        foreach ($resultToNum as $key => $value) {
            foreach ($value as $key => $to) {
                fwrite($file,substr($to,-10)."\n");
                $temp[] = $to;
                break;
            }
            break;
        }
        fclose($file);
        $resultToNum = $temp;
        // redis call
        $redisNum = $this->connectToRedis();
        foreach ($resultToNum as $index => $number) {
            $this->addNumberToJfk($redisNum,$number,0);
        }
    }
}
$obj = new UpdateDndNumberToJfk();
$results = $obj->startCron();
?>

