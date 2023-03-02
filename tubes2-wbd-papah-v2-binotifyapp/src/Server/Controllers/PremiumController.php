<?php 

namespace Server\Controllers;

use Exception;

class PremiumController extends Controller {
    public function __construct() {
        parent::__construct();
    }

    public function premiumsingerview(array $params = []) {
        if (isset($_SESSION["isadmin"])) {
            $response = $this->callREstAPI("GET", "http://bnmo-rest-web:4000/user-list", []);
            $statuses = $this->getStatus($_SESSION['user_id']);
            if ($response != null) {
                $users = $response["users"];
            } else {
                $users = [];
            }
            include 'Client/pages/PremiumSingerList/PremiumSingerList.php';
        } else {
            include 'Client/pages/Errors/Unauthorized.php';
        }
    }
    
    public function premiumSongListView(array $params = []) 
    {
        if (isset($_SESSION["isadmin"])) {
            $subs_id = $params['subs_id'];
            $singer_id = (int)$params['singer_id'];
            $response = $this->callREstAPI('GET', "http://bnmo-rest-web:4000/singer-song-list/" .$singer_id . "/" .$subs_id, []);
            if ($response != null) {
                $songs = $response['songs'];
            } else {
                $songs = [];
            }
            include 'Client/pages/PremiumSongList/PremiumSongList.php';
        } else {
            include 'Client/pages/Errors/Unauthorized.php';
        }
    }

    // Function to get strings between 2 string
    public function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    /* Function to call rest API
       $method -> HTTP-Method used (POST, GET, etc)
       $url -> destination URL
       $data -> [] if GET, json data if POST */
    public function callREstAPI($method, $url, $data) {
        $curl = curl_init($url);
        switch($method) {
            case "POST" :
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "GET":
                curl_setopt($curl, CURLOPT_HTTPGET, true);
                break;
            default:
                break;
        }

        curl_setopt($curl, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);

        if ($response === false) {
            throw new Exception(curl_error($curl), curl_errno($curl));
        }
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return json_decode($response, true);
    }

    /* Function to call soap WS
       $url -> destination URL
       $API_Key -> API-Key for authentication
       $body -> xml data body */
    public function callSoapAPi($url, $API_Key, $body) {
        $curl = curl_init($url);

        $headers = array(
            "Connection: Keep-Alive",
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "API-Key:" . $API_Key,
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: ''", 
            "Content-length: ".strlen($body)
        );

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);
        curl_close($curl);

        $return_value = $this->get_string_between($response, "<return>", "</return>");

        return json_decode($return_value);
    }

    // Callback endpoint from SOAP to PHP
    public function updateSubscription(array $params = []){
        try {
            if ($_SERVER['HTTP_API_KEY'] == $_ENV["BAPP_API_KEY"]) {
                // $creator_id = $params['creator_id'];
                // $subscriber_id = $params['subscriber_id'];
                // $status = $params['status'];
                $creator_id = $_POST['creator_id'];
                $subscriber_id = $_POST['subscriber_id'];
                $status = $_POST['status'];
                $stmt = "UPDATE subscription SET status = ? WHERE creator_id = ? AND subscriber_id = ?";
                $query = $this->database->prepare($stmt);
                $query->execute(array($status, $creator_id, $subscriber_id));
    
                http_response_code(200);
                print_r(json_encode(array(
                    "status" => 200,
                    "message" => "Successfully Updated",
                )));
            } else {
                http_response_code(200);
                print_r(json_encode(array(
                    "status" => 501,
                    "message" => "Update failure: Invalid API-Key",
                    "headerapikey" => $_SERVER['HTTP_API_KEY'],
                    "bappapikey" => $_ENV["BAPP_API_KEY"]
                )));
            }
        } catch (PDOException $e) {
            http_response_code(500);
            print_r(json_encode(array(
                "status" => 500,
                "message" => "Failed"
            )));
        }
    }

    // Function to send subscription request to SOAP
    public function subscribe() {
        $sql = "INSERT INTO subscription(creator_id, subscriber_id) VALUES(?, ?)";
        $query = $this->database->prepare($sql);
        $query->execute(array((int)$_POST['creator_id'], $_SESSION['user_id']));

        $xml = 
        '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
            <Body>
                <postSubscriptionRequests xmlns="http://services.soap.binotify/">
                    <creator_id xmlns="">'. (int)$_POST['creator_id'] .'</creator_id>
                    <subscriber_id xmlns="">'. $_SESSION['user_id'] . '</subscriber_id>
                </postSubscriptionRequests>
            </Body>
        </Envelope>';

        $response = $this->callSoapAPi('http://bnmo-soap-web:7000/subscription', $_ENV['BAPP_API_KEY'], $xml);
        var_dump($response);

        if ($response == true) {
            http_response_code(200);
        } else {
            http_response_code(400);
        }
    }

    // Function to update subscriber data in PHP database
    public function updateSubscriber(array $datas) {
        foreach($datas as $key=>$data) {
            $sql = "UPDATE subscription SET status = ? WHERE creator_id = ? AND subscriber_id = ?";
            $query = $this->database->prepare($sql);
            $query->execute(array($data[2], $data[0], $data[1]));
        }
        
    }

    // Function to get all statuses in PHP database
    public function getStatus($subscriber_id) {
        $sql = "SELECT * FROM subscription WHERE subscriber_id = ? ORDER BY creator_id ASC";
        $query = $this->database->prepare($sql);
        $query->execute(array($subscriber_id));

        $result = $query->fetchAll();
        return $result;
    }

    // Function to poll data to SOAP database and update PHP database
    public function poll_data() {
        $xml = 
            '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
                <Body>
                    <getSubscriptionRequestsByID xmlns="http://services.soap.binotify/">
                        <subscriber_id xmlns="">'. $_SESSION['user_id'] . '</subscriber_id>
                    </getSubscriptionRequestsByID>
                </Body>
            </Envelope>';

        $response = $this->callSoapAPi('http://bnmo-soap-web:7000/subscription', $_ENV['BAPP_API_KEY'], $xml);

        $this->updateSubscriber($response->records);
        
        if ($response != null) {
            http_response_code(200);
        } else {
            http_response_code(400);
        }
    }
}

?>