<?php
session_start();
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

function base64_rand() {
                $chars = "1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM_-";
                $number1 = rand(1,62);
                $number2 = $number1 - 1;
                $letter = substr($chars, $number2, $number1);
                return $letter[0];
            }
            $key = "0";
            for ($x = 0; $x <= 50; $x++) {
                $append = base64_rand();
                $key .= $append;
            }
            $sql = "INSERT INTO keysa (discord_id, `keya`) VALUES ('${req_id}', '${key}')";
            $result = $conn->query($sql);
            echo "<br><br>Your API Key is: " . $key;