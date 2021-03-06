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
include "includes/apis.php";


/*====================

Set this up to show your
name and description, if
you would like...

====================*/

$desc = "The Dangerous Discord User Database is a simple and smart tool to report bad discord accounts, created by <a href='//riverside.rocks'>Riverside Rocks</a>. Find us on GitHub <a href='//github.com/DangerousUserDB'>here</a>.";

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

if($_SESSION["discord_username"] == ""){
    $reporter_username = "Anonymous";
  }else{
    $reporter_username = $conn -> real_escape_string(xss($_SESSION["discord_username"]));
}

$time = time();

$sql = "INSERT INTO `log`(`discord_username`, `epoch`) VALUES ('${reporter_username}', '${time}')";
$result = $conn->query($sql);

echo "<h2>About Us</h2>";
echo $desc;


//echo "<h3>Total Reports:</h3>";

$sql = "SELECT * FROM `reports`";
$result = $conn->query($sql);

$times = 0;

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $times = $times + 1;
    }
}

//echo "<strong><h4>" . $times . "</h4></strong><br>";

$time_now = time();

$hours = $time_now - 86400;

$sql = "SELECT * FROM `log` WHERE epoch > ${hours}";
$result = $conn->query($sql);

$times = 0;

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $times = $times + 1;
    }
}


$time_now = time();

$mins = $time_now - 3600;

$sql = "SELECT * FROM `log` WHERE epoch > ${mins}";
$result = $conn->query($sql);

$timez = 0;

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $timez = $timez + 1;
    }
}

$time_now = time();

$newtime = $time_now - 60;

$sql = "SELECT * FROM `log` WHERE epoch > ${newtime}";
$result = $conn->query($sql);

$mi = 0;

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $mi = $mi + 1;
    }
}

echo "<h3>Visits</h3>";
echo "<strong><h4>Last Minute: " . $mi . "</h4></strong>";

echo "<strong><h4>Last Hour: " . $timez . "</h4></strong>";

echo "<strong><h4>Last 24 Hours: " . $times . "</h4></strong>";

$days = $time_now - 604800;

$sql = "SELECT * FROM `log` WHERE epoch > ${days}";
$result = $conn->query($sql);

$times = 0;

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $times = $times + 1;
    }
}

echo "<strong><h4>Last 7 Days: " . $times . "</h4></strong>";

$sql = "SELECT * FROM `log`";
$result = $conn->query($sql);

$times = 0;

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $times = $times + 1;
    }
}

echo "<strong><h4>All time: " . $times . "</h4></strong>";



?>

<?php

include "includes/footer.php";
