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

include "includes/apis.php";


if($_SERVER['REQUEST_METHOD'] !== "POST"){
    $mes = array(
        "message" => "Error, please use POST for this endpoint."
    );
    $send = json_encode($mes, true);
    die($send);
}

// The code from the report page, but its the backend only.

if(isset($_POST["id"])){
    /*======================
    |   Define Variables   |
    ======================*/
    $discord_id = $conn -> real_escape_string(xss($_POST["id"]));
    if(isset($_SESSION["discord_id"])){
      $reporter_id = $conn -> real_escape_string(xss($_SESSION["discord_id"]));
    }else{
      $reporter_id = "0";
    }
    if($_SESSION["discord_username"] == ""){
      $reporter_username = "Anonymous";
    }else{
      $reporter_username = $conn -> real_escape_string(xss($_SESSION["discord_username"]));
    }

    $cat = $conn -> real_escape_string(xss($_POST["cat"]));
    /*====================
    | Define Catagories  |
    ====================*/
    $cats = array(
      "spam" => "0",
      "trolling" => "0",
      "mass-ads" => "0",
      "grabbers" => "0",
      "raid" => "0"
    );
    if($cats[$cat] !== "0"){
      die("<br>400: Bad Request<br>");
    }
    $epoch = time();
    $details = $conn -> real_escape_string(xss($_POST["details"]));
    if (!ctype_digit($discord_id)) {
        die("<br>Sorry, but that doesn't look like a valid discord ID. Please try again.");
    }
    if(!isset($discord_id)){
      die("Bad Request");
    }
    $discord_token = $_ENV['BOT_TOKEN'];


    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => "https://discord.com/api/v8/users/${discord_id}",
        CURLOPT_USERAGENT => 'Dangerous User DB'
    ]);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Authorization: Bot ${discord_token}",
    ));
    $resp = curl_exec($curl);
    curl_close($curl);

    $api = json_decode($resp, true);
    if($api["username"] == ""){
        die("<br><br>User does not exsist. Perhaps they deleted their account?");
    }
    
    $sql = "INSERT INTO reports (discord_id, reporter_discord_id, reporter_discord_username, cat, details, epoch) VALUES ('${discord_id}', '${reporter_id}', '${reporter_username}', '${cat}', '${details}', '${epoch}')";
    $result = $conn->query($sql);

}

?>