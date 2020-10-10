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
?>

<h2>API Documentation</h2>

<table class="table">
  <thead>
    <tr>
      <th>Request Type</th>
      <th>Path</th>
      <th>Params</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th>GET</th>
      <td>/check.json.php</td>
      <td>id</td>
      <td>Check the status of a user.</td>
    </tr>