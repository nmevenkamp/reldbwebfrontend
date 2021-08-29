<?php
  session_start();
  $_SESSION["db_path"] = "data/my_db.db";
  $_SESSION["entities"] = ["projects"];
?>
<html>
  <head>
    <link rel="stylesheet" href="styles/filters.css">
    <script type="text/javascript" src="scripts/filters.js"></script>
    <script type="text/javascript">
      function onload() {
        loadFilters("projects");
      }
      function loadFilters(entity) {
        var xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              document.getElementById(entity + ".filters").innerHTML = this.responseText;
            }
          };
          xmlhttp.open("GET","filters.php?entity="+entity,true);
          xmlhttp.send();
      }
    </script>
  </head>
  <body onload="onload()">
    <?php
      foreach ($_SESSION["entities"] as $entity) {
        echo "<div id='".$entity.".filters'></div>";
      }
    ?>
  </body>
</html>