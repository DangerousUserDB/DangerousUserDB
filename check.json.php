<?php
/*======================================================================
Copyright 2020, Riverside Rocks and the DUDB Authors
Licensed under the the Apache License v2.0 (the "License")
You may get a copy at
https://apache.org/licenses/LICENSE-2.0.txt
Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
========================================================================*/
header("Content-type: application/json");
header("Access-Control-Allow-Origin: *");


include "includes/apis.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$servername = $_ENV['MYSQL_SERVER'];
$username = $_ENV["MYSQL_USERNAME"];
$password = $_ENV["MYSQL_PASSWORD"];
$dbname = $_ENV["MYSQL_DATABASE"];

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if($_GET["id"] == ""){
        header("HTTP/1.1 400 Bad Request");
    $errors = array(
       "Message" => "Missing ID Parameter",
       "Code" => "401"
       );
   echo json_encode($errors, true);
   die();
}

$id = $_GET["id"];
$discord_token = $_ENV['BOT_TOKEN'];

$whitelist = json_decode(file_get_contents("/var/www/discord/whitelist.json"), true);

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => "https://discord.com/api/v8/users/${id}",
    CURLOPT_USERAGENT => 'Dangerous User DB'
]);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    "Authorization: Bot ${discord_token}",
));
$resp = curl_exec($curl);
curl_close($curl);

$api = json_decode($resp, true);

/*
if(!isset($r_discord_username)){
    header("Location: /?notfound=true");
}
*/

$r_discord_username = htmlspecialchars($api["username"]);
$sql_discord = $conn -> real_escape_string($_GET["id"]);
$sql = "SELECT * FROM reports WHERE discord_id='${sql_discord}'";
$result = $conn->query($sql);

$timez = array();
$total = 0;

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $total = $total + 1;
        if(in_array($row["reporter_discord_id"], $timez)){
            // Do nothing
        }else{
            array_push($timez, $row["reporter_discord_id"]);
        }
    }
}

$times = count($timez);

$sql = "SELECT * FROM reports WHERE discord_id='${sql_discord}' ORDER BY epoch ASC";
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $last = $row["epoch"];
        break;
    }
}

$sql = "SELECT * FROM reports WHERE discord_id='${sql_discord}' ORDER BY epoch DESC";
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $latest = $row["epoch"];
        break;
    }
}

$score = score($times, $last, $latest);

if(in_array($id, $whitelist))
        {
            $score = "0%";
        }

if($times == "0"){
    if($api["username"] == ""){
        header("HTTP/1.1 400 Bad Request");
        $errors = array(
       "Message" => "Discord API: User Does Not Exsist",
       "Code" => "400"
       );
        echo json_encode($errors, true);
        die();
    }
     $return = array(
         "username" => $api["username"],
         "reports" => $times,
         "total_reports" => $total,
         "score" => $score
     );
    echo json_encode($return, true);
        die();
}else{
    
    $return = array(
        "username" => $api["username"],
         "reports" => $times,
         "total_reports" => $total,
         "score" => $score
     );
    echo json_encode($return, true);
        die();
}
