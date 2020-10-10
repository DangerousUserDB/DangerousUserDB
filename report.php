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

?>
<h2>File a Report</h2>
<?php
if(! $_SESSION["discord_username"]){
  echo "<br>Note, since you are not signed in, you are reporting anonymously.<br>";
}else{
  echo "<br>Reporting as " . $_SESSION["discord_username"] . "<br>";
}
?>
<form method="post" class="w-400 mw-full"> <!-- w-400 = width: 40rem (400px), mw-full = max-width: 100% -->
  <!-- Input -->
  <div class="form-group">
    <label for="full-name" class="required">ID</label>
    <?php
    if(isset($_GET['id'])){
      $get_id = xss($_GET["id"]);
      echo "<input type='text' class='form-control' id='id' name='id' placeholder='93829473824...' value='${get_id}' required='required'>";
    }else{
      echo "<input type='text' class='form-control' id='id' name='id' placeholder='93829473824...' required='required'>";
    }
    ?>
  </div>
  <!-- Multi-select -->
  <div class="form-group">
    <label for="languages" class="required">Abuse Type</label>
    <select class="form-control" id="cat" name="cat" multiple="multiple" required="required" size="5">
      <option value="spam">Spam</option>
      <option value="mass-ads">Mass Ads</option>
      <option value="trolling">Trolling</option>
      <option value="raid">Raid</option>
      <option value="grabbers">IP Grabbers</option>
    </select>
  </div>

  <!-- Textarea -->
  <div class="form-group">
    <label for="description">Details</label>
    <textarea class="form-control" id="details" name="details" placeholder="Details, if needed..."></textarea>
  </div>
        <i>By submitting this form, I understand that this is not run by Discord staff and thus accounts reported here will not be taken down. This is simply a tool to warn other server owners about malicous users.</i>
        <br>
        <!-- Submit button -->
  <input class="btn btn-primary" type="submit" value="Submit">
</form>
<?php
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
    header("Location: /check?id=${discord_id}");
    die();
    }
