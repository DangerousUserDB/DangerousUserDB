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

$r_discord_username = XSS($api["username"]);

    echo "<br>";
    echo "<h2>User Profile - ${r_discord_username}</h2>";
    ?>
    <div class="card">
        <h2 class="card-title">
        <?php
    echo "<h3>Null</h3>"
    ?>
  </h2>
  <p>
    The weather forecast didn't say that, but the steel plate in his hip did. He had learned over the years to trust his hip over the weatherman. It was going to rain, so he better get outside and prepare...
  </p>
  <div class="text-right"> <!-- text-right = text-align: right -->
    More
  </div>
</div>