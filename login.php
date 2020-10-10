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

$login_shutoff = "true";

if($login_shutoff == "true"){
    die("Login is not available at this time.");
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Xwilarg\Discord\OAuth2;

// CLIENT-ID-HERE: Replace this with the Client ID of the application
// SECRET: Replace this with the Secret of the application
// CALLBACK-URL: Replace this with the redirect URL (URL called after the user is logged in, must be registered in https://discordapp.com/developers/applications/[YourAppId]/oauth)
// Enter your Discord Oauth details here:

// Replace this with your site's homepage:
$callback = "https://discord.riverside.rocks" . "/login";

$oauth2 = new OAuth2($_ENV["DISCORD_CLIENT"], $_ENV["DISCORD_SECRET"], $callback);

if ($oauth2->isRedirected() === false) { // Did the client already logged in ?
    // The parameters can be a combination of the following: connections, email, identity or guilds
    // More information about it here: https://discordapp.com/developers/docs/topics/oauth2#shared-resources-oauth2-scopes
    // The others parameters are not available with this library
    $oauth2->startRedirection(['identify', 'connections']);
} else {
    // We preload the token to see if everything happened without error
    $ok = $oauth2->loadToken();
    if ($ok !== true) {
        // A common error can be to reload the page because the code returned by Discord would still be present in the URL
        // If this happen, isRedirected will return true and we will come here with an invalid code
        // So if there is a problem, we redirect the user to Discord authentification
        $oauth2->startRedirection(['identify', 'connections']);
    } else {
        // ---------- USER INFORMATION
        $answer = $oauth2->getUserInformation(); // Same as $oauth2->getCustomInformation('users/@me')
        if (array_key_exists("code", $answer)) {
            print_r($answer);
        } else {
            print_r($answer);
            echo $answer["username"];
            echo $answer["id"];
            $_SESSION["discord_username"] = $answer["username"];
            $_SESSION["discord_id"] = $answer["id"];
            //echo $_SESSION["discord_username"];
            header("Location: /");
            die();
        }

        echo '<br/><br/>';
        // ---------- CONNECTIONS INFORMATION
        $answer = $oauth2->getConnectionsInformation();
        if (array_key_exists("code", $answer)) {
            exit("An error occured: " . $answer["message"]);
        } else {
            foreach ($answer as $a) {
                echo $a["type"] . ': ' . $a["name"] . '<br/>';
            }
        }
    }
}
?>
