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

if($_SESSION["discord_username"] == ""){
  $reporter_username = "Anonymous";
}else{
  $reporter_username = $conn -> real_escape_string(xss($_SESSION["discord_username"]));
}

$time = time();

$sql = "INSERT INTO `log`(`discord_username`, `epoch`) VALUES ('${reporter_username}', '${time}')";
$result = $conn->query($sql);

?>
<h1>Remove Dangerous Users From Your Server - Fast</h1>
<h5>With our robust API, you will never have to worry about raids again</h5>
<div class="container-fluid">
  <div class="row">
    <div class="col-sm">
<div class="w-400"> <!-- w-400 = width: 40rem (400px), mw-full = max-width: 100% -->
  <div class="card">
    <h2 class="card-title">
      Get Started
    </h2>
    <p class="text-muted">
        Create an account, get an API, and add our bot to your Discord server.
    </p>
    <div class="text-right"> <!-- text-right = text-align: right -->
    <a href="/login" class="btn">Sign Up / Sign In</a>

    </div>
  </div>
</div>
</div>
<div class="col-sm">
<div class="w-400"> <!-- w-400 = width: 40rem (400px), mw-full = max-width: 100% -->
  <div class="card">
    <h2 class="card-title">
      API Docs
    </h2>
    <p class="text-muted">
        Learn how to use our fine API, which is free to use.
    </p>
    <div class="text-right"> <!-- text-right = text-align: right -->
      <a href="/docs/index.php" class="btn">Read More</a>
    </div>
  </div>
</div>
</div>
</div>
<center>
<div class="container-fluid">
  <div class="row">
<div class="col-sm">
<div class="w-600"> <!-- w-400 = width: 40rem (400px), mw-full = max-width: 100% -->
  <div class="card">
    <h2 class="card-title">
      Report a User
    </h2>
    <p class="text-muted">
        Raided? Trolled? Report a user now.
    </p>
    <div class="text-right"> <!-- text-right = text-align: right -->
      <a href="/report" class="btn">Report</a>
    </div>
  </div>
</div>
</div>
</div>
    <h3>Recently reported users</h3>
    <table class="table"><tbody>
    <tr>
    <?php
$sql = "SELECT * FROM reports ORDER BY epoch DESC LIMIT 3";
$result = $conn->query($sql);

$timez = array();
$total = 0;

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
       echo "<th><a href='/check?id=" . htmlspecialchars($row["discord_id"]) . "&ref=homepage'>" . htmlspecialchars($row["discord_id"]) . "</a></th>";
    }
}
        
        ?>
        </tr>
        </tbody>
    </table>
    <a href="/reports">View more recent reports</a>
</center>
<?php
include "includes/footer.php";
