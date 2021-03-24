<?php

/*************************
*
*    List of Discord Accounts
*    we don't belive are good
*
**************************/

header("Content-type: application/json");

$accounts = array("466262009256869889", "764485265775263784");

echo json_encode($accounts, true);
