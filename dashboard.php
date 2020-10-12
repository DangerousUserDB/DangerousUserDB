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
    $reporter_username = "Anonymous";
  }else{
    $reporter_username = $conn -> real_escape_string(xss($_SESSION["discord_username"]));
}

$time = time();

$sql = "INSERT INTO `log`(`discord_username`, `epoch`) VALUES ('${reporter_username}', '${time}')";
$result = $conn->query($sql);

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
echo "<br><br>";
  }
}

if($count == 0){
    echo "No reports yet!";
}

echo "<h3>API Keys</h3>";

$sql = "SELECT * FROM keysa WHERE discord_id='${reqid}'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<br><h4><code>" . $row["keya"] . "</code></h4><br>";
    }
}


include "includes/footer.php";