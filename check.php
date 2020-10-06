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

$id = $_GET["id"];
$discord_token = $_ENV['BOT_TOKEN'];


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

$r_discord_username = xss($api["username"]);
$sql_discord = $conn -> real_escape_string($r_discord_username);
$sql = "SELECT * FROM reports WHERE discord_id='${sql_discord}'";
$result = $conn->query($sql);

echo $sql_discord;

$times = 0;

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $times = $times + 1;
    }
}

if($times == 0){
    $symbol = '<i class="fas fa-check-circle" style="color:green"></i>';
    $message = "Good news, this user looks fine.";
}

if($times > 1 && $times < 3){
    $symbol = '<i class="fas fa-exclamation-triangle" style="color:yellow"></i>';
    $message = "Keep your eyes on this user, we have at least one report.";
}else if($times > 3 && $times < 5){
    $symbol = '<i class="fas fa-exclamation-circle" style="color:red"></i>';
    $message = "This user is likey a malicous user, they have more than 3 reports.";
}else if($times > 5){
    $symbol = '<i class="fas fa-radiation-alt" style="color:red"></i>';
    $message = "This user is a malicous user, remove them from your server as soon as possible.";

}


    echo "<br>";
    echo "<h2>User Profile - ${r_discord_username}</h2>";
    ?>
    <div class="card">
        <h2 class="card-title">
        <?php
    echo "<h3>${times} - Total Reports</h3><br>${symbol}"
    ?>
  <p>
    <?php
    echo $message;
    ?>
  </p>
  <div class="text-right"> <!-- text-right = text-align: right -->
    More
  </div>
</div>