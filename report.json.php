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

function xss($text)
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

$servername = $_ENV['MYSQL_SERVER'];
$username = $_ENV["MYSQL_USERNAME"];
$password = $_ENV["MYSQL_PASSWORD"];
$dbname = $_ENV["MYSQL_DATABASE"];

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/*

Requirments for making a request to the report API:

  - POST request
  - API Key (key)
  - User ID (id)
  - Details (details)
  - 

*/


// The code from the report page, but its the backend only.

if($_SERVER['REQUEST_METHOD'] !== "POST"){
  $_POST = $_GET;
}

if(isset($_POST["id"])){
    if(! $_POST["key"]){
      header("HTTP/1.1 401 Unauthorized");
      $mes = array(
        "message" => "Error, this endpoint requires an API key."
      );
      $send = json_encode($mes, true);
      die($send);
    }
    /*======================
    |   Define Variables   |
    ======================*/
    $discord_id = $conn -> real_escape_string(xss($_POST["id"]));
    $post_key = $conn -> real_escape_string(xss($_POST["key"]));
    
    
    
    if (!ctype_digit($discord_id)) {
      header("HTTP/1.1 400 Bad Request");
      $mes = array(
        "message" => "Error, Discord IDs only contain digits."
      );
      $send = json_encode($mes, true);
      die($send);
    }

    $sql = "SELECT * FROM keysa WHERE keya='${post_key}'";
    $result = $conn->query($sql);

    if($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        break;
      }
    }

    if($row["discord_id"] == ""){
      header("HTTP/1.1 401 Unauthorized");
      $mes = array(
        "message" => "Error, invalid API key."
      );
      $send = json_encode($mes, true);
      die($send);
    }
    $cat = $conn -> real_escape_string(xss("api-report"));
    $epoch = time();
    $details = $conn -> real_escape_string(xss($_POST["details"]));
    $discord_reporter = $conn -> real_escape_string(xss($row["discord_id"]));
    $apis = json_decode(file_get_contents("https://discord.riverside.rocks/check.json.php?id=" . $discord_reporter), true);
    $discord_username = $conn -> real_escape_string(xss($apis["username"]));
    // Note, we don't halt the request here if there are not details. Details are
    // not required for a report.
    
    $sql = "SELECT * FROM reports WHERE discord_id='${discord_id}' AND reporter_discord_id='${discord_reporter}' ORDER BY epoch DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $last_time = $row["epoch"];
        }
    }
    
    $t = time();
    $split = $t - $last_time;
    
    if($split < 600)
    {
        header("HTTP/1.1 429 Too Many Requests");
        $mes = array(
            "message" => "You can only report a user every 10 minutes.",
            "success" => "false"
          );
          $send = json_encode($mes, true);
          die($send);
    }
    
    $sql = "INSERT INTO reports (discord_id, reporter_discord_id, reporter_discord_username, cat, details, epoch) VALUES ('${discord_id}', '${discord_reporter}', '${discord_username}', '${cat}', '${details}', '${epoch}')";
    $result = $conn->query($sql);

    $mes = array(
      "message" => "Success"
    );
    $send = json_encode($mes, true);
    die($send);
}else{
  header("HTTP/1.1 400 Bad Request");
  $mes = array(
    "message" => "Error, please include a user to report. Use the id parameter."
  );
  $send = json_encode($mes, true);
  die($send);
}

?>
