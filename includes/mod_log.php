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

/*========================================================================

This is a simple mod used to log requests to the website. We only use to
get an idea of our traffic and report bad requests.

========================================================================*/

$access = fopen("access.log", "w") or die("Failed");

if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];

$ip = $_SERVER['REMOTE_ADDR'];
$epoch = time() - 14400;
$time = date("m-d-Y H:i:s", time());
$req_t = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER["REQUEST_URI"];
$code = $_SERVER["REDIRECT_STATUS"];
$ref = $_SERVER["HTTP_REFERER"];
$agent = $_SERVER["HTTP_USER_AGENT"];

$request = "${ip} - - ${time} '${req_t} ${path} ${code}'  '${ref}'  '${agent}' \n";
fwrite($access, $request);
fclose($access);