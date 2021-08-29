<?php
  session_start();
  $_SESSION["db_path"] = "data/my_db.db";
  $_SESSION["entities"] = ["projects", "technologies"];
?>
<html>
  <head>
    <link rel="stylesheet/less" href="styles/index.css">
    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
    <link rel="stylesheet" href="styles/filters.css">
    <script type="text/javascript" src="scripts/filters.js"></script>
    <script type="text/javascript">
      entities = ["projects", "technologies"];
      function onload() {
        for (i = 0; i < entities.length; i++) {
          loadFilters(entities[i]);
        }
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
    <div id="entity-cols">
      <?php
        foreach ($_SESSION["entities"] as $entity) {
          echo "<div id='".$entity.".column' class='entity-col'>";
          echo "  <div id='".$entity.".title' class='entity-title'>".ucfirst($entity)."</div>";
          echo "  <div id='".$entity.".filters'></div>";
          echo "</div>";
        }
      ?>
    </div>
  </body>
</html>