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

if($_GET["key"])
{
    $token = $_GET["key"];
}else{
    $token = $_POST["key"];
}

if(!$token){
      header("HTTP/1.1 401 Unauthorized");
      $mes = array(
        "message" => "Error, this endpoint requires an API key. Please either POST or GET your key."
      );
      $send = json_encode($mes, true);
      die($send);
}
$sql_token = $conn -> real_escape_string($token);

    $sql = "SELECT * FROM keysa WHERE keya='${sql_token}'";
    $result = $conn->query($sql);

    if($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        break;
      }
    }

$sql_discord = $conn -> real_escape_string($row["discord_id"]);
$sql = "DELETE FROM reports WHERE reporter_discord_id='${sql_discord}'";
$result = $conn->query($sql);

$mes = array(
        "message" => "Deleted all reports from your account"
      );
      $send = json_encode($mes, true);
      die($send);

