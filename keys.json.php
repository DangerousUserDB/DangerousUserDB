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

include "includes/apis.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

/*====================================================

Hello, Riverside Rocks here. It should be noted that
this is an admin only endpoint, in other words, this
should only be accessed by people who run the instance.

This endpoint returns the API key(s) for any given user.

You need to set the admin only API key for this in the 
.env file.

ADMIN_KEY=averysecurekeynobodycanguess

====================================================*/


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

$admin_token = $_ENV["ADMIN_KEY"];

if($_GET["key"] !== $admin_token){
    $mes = array(
        "message" => "Invalid Key. Please note that this endpoint can only be accessed by admins."
      );
      $send = json_encode($mes, true);
      die($send);
}

if($_GET["id"] == ""){
    $mes = array(
        "message" => "Please provide a user ID"
      );
      $send = json_encode($mes, true);
      die($send);
}

$lookup = $conn -> real_escape_string(xss($_GET['id']));

$sql = "SELECT * FROM reports";
$result = $conn->query($sql);

$times = 0;

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if($row["discord_id"] == $lookup){
            $api_key = $row["keya"];
            break;
        }
    }
}

$mes = array(
    "key" => $api_key
);
$send = json_encode($mes, true);
die($send);