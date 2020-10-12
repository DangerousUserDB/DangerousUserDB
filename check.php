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
include "includes/mod_log.php";
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
$sql_discord = $conn -> real_escape_string($_GET["id"]);
$sql = "SELECT * FROM reports WHERE discord_id='${sql_discord}'";
$result = $conn->query($sql);

$times = 0;

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $times = $times + 1;
    }
}




if($times == "0"){
    if($api["username"] == ""){
        die(header("Location: /?notfound=true"));
    }
    $symbol = '<i class="fas fa-check-circle fa-5x" style="color:green;font-size:18px;"></i>'; 
    $message = "All clear! Nothing looks wrong!";
}else{
    $symbol = '<i class="fas fa-radiation-alt fa-5x" style="color:red;font-size:18px;"></i>'; 
    $message = "Warning: We have recived ${times} report(s) about this user.";
    if($api["username"] == ""){
        ?>
        <script type="text/javascript">
  function toastAlert() {
    var alertContent = "Notice: The discord user request has deleted their account. These reports are shown for historical reasons.";
    halfmoon.initStickyAlert({
      content: alertContent,      // Required, main content of the alert, type: string (can contain HTML)
      title: "Notice"      // Optional, title of the alert, default: "", type: string
    })
  }
  toastAlert();
  </script>
        <?php
    }
}


    echo "<br>";
    if($api["username"] !== ""){
        echo "<h2>User Profile - ${r_discord_username}</h2>";
    }
    ?>
    <div class="card">
        <h2 class="card-title">
        <?php
    echo "<h3>${times} - Total Reports</h3><br>${symbol}";
    if($times !== "0"){
        $score = rawscore($times);
        ?>

<?php echo "<h3>Abuse Score: ${score}%</h3>"; ?>
  <?php echo "<br><div class='progress'><div class='progress-bar' role='progressbar' style='width: ${score}%' aria-valuenow='${score}' aria-valuemin='0' aria-valuemax='100'></div></div><br>"; ?>
  <?php echo "<a href='/report?id=${sql_discord}'>" ?><button class="btn btn-danger" type="button">Report this user</button></a>
</div>
        <?php
    }
    ?>
    <table class="table">
  <thead>
    <tr>
    <center>
      <th>List of Reports - Showing Last Five</th>
      </center>
      </tr>
  </thead>
  <tbody>
      <?php
    $reqid = $conn -> real_escape_string(xss($_GET["id"]));
    $sql = "SELECT * FROM reports WHERE discord_id='${reqid}' ORDER BY epoch DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $t = $row["epoch"];
            echo "<tr>";
      echo "<th>" . $row["reporter_discord_username"] . "</th>";
      echo "<td><span class='badge badge-danger'>" . $row["cat"] . "</span></td>";
      echo "<td>" . $row["details"] . "</td>";
      echo "<td>" . date("m-d-y",$t) . "</td>";
      echo "</tr>";
        }
    }
      ?>
  <p>
  </p>
</div>
<br>
<p>If any of the information here is not true, please contact us <a href="//riverside.rocks/contact">here</a>.
<br>
<?php
include "includes/footer.php";