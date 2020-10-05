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

include "includes/header.php";

$id = $_GET["id"];
$discord_token = $_ENV['BOT_TOKEN'];


$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => "https://discord.com/api/v8/users/${id}",
    CURLOPT_USERAGENT => 'Dangerous User DB'
]);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bot ${discord_token}"
));
$resp = curl_exec($curl);
curl_close($curl);

$api = json_decode($resp, true);

$r_discord_username = $api["username"];

if(!isset($r_discord_username)){
    ?>
    <br>
    <h2>That User Does Not Exist</h2>
    <?php
}else{
    echo $r_discord_username;
}
?>