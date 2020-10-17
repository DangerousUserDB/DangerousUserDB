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

require 'vendor/autoload.php';



/*===============================================

Reports alone are not really the best way to tell how 
abusive a user is. The best way is to see how long ago 
the last report was and the "density" of reports. The
function score() calculates a score from 0-100. It
takes three arguments, the first being the total number
of unique reports, the second being the first time a report
was sent in, and the third being the last time a report
was sent in. This uses PHP's default time function which
returns times in UTC.

================================================*/

function score($number)
{
    $numbers = array(
        "0" => "0%",
        "1" => "14%",
        '2' => "24%",
        '3' => "38%",
        '4' => "68%",
        '5' => "86%",
        '6' => "100%",
        '7' => "100%",
        '8' => "100%",
        '9' => "100%",
        '10' => "100%",
        '11' => "100%",
        '12' => "100%",
        '13' => "100%",
        '14' => "100%",
        '15' => "100%",
        '16' => "100%",
        '17' => "100%"
    );
    $abuse = $numbers[$number];
    return $abuse;
    die();
}

function xss($text)
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function rawscore($number)
{
    $numbers = array(
        "0" => "0",
        "1" => "14",
        '2' => "24",
        '3' => "38",
        '4' => "68",
        '5' => "86",
        '6' => "100",
        '7' => "100",
        '8' => "100",
        '9' => "100",
        '10' => "100",
        '11' => "100",
        '12' => "100",
        '13' => "100",
        '14' => "100",
        '15' => "100",
        '16' => "100",
        '17' => "100"
    );
    $abuse = $numbers[$number];
    return $abuse;
    die();
}