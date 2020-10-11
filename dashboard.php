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
    die(header("Location: /"));
}

?>
<br>
<h2>Dashboard - <?php echo xss($_SESSION["discord_username"]) ?> </h2>
<center>
<th>Your Reports</th>
</center>
</tr>
</thead>
<tbody>
<?php
$reqid = $conn -> real_escape_string(xss($_SESSION["discord_id"]));
$sql = "SELECT * FROM reports WHERE reporter_discord_id='${reqid}' ORDER BY epoch DESC";
$result = $conn->query($sql);

$count = 0;

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
      $count = $count + 1;
      $t = $row["epoch"];
      echo "<tr>";
echo "<th>" . $row["reporter_discord_username"] . "</th>";
echo "<td><span class='badge badge-danger'>" . $row["cat"] . "</span></td>";
echo "<td>" . $row["details"] . "</td>";
echo "<td>" . date("m-d-y",$t) . "</td>";
echo "</tr>";
  }
}

if($count == 0){
    echo "No reports yet!";
}

$sql = "SELECT * FROM keysa WHERE discord_id='${req_id}'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<br><h3>API Key: <code>" . $row["keya"] . "</code></h3>";
        if($row["keya"] !== ""){
            break;
        }else{
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
            $sql = "SELECT * FROM keys WHERE discord_id='${req_id}'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<br><h3>API Key: <code>" . $row["keya"] . "</code></h3>";
                }
            }
        }
    }
}


include "includes/footer.php";