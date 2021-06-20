<script async defer src="https://scripts.simpleanalyticscdn.com/latest.js"></script>
<noscript><img src="https://queue.simpleanalyticscdn.com/noscript.gif" alt=""/></noscript>
<br>

<center>
<?php
    if($r_discord_username){
    echo "<title>${r_discord_username} - Dangerous User Database</title>";
  }else{
  echo "<title>" . createTitleFromURI($_SERVER['REQUEST_URI']) . " - Dangerous User Database</title>";
  }
  
  echo date("Y", time()) . " - <a href='//riverside.rocks'>Riverside Rocks</a> - <a href='https://github.com/DangerousUserDB/DangerousUserDB'>Github</a>";
?>
<br>
