<?php
session_start();
$_SESSION["db_path"] = "data/my_db.db";
$_SESSION["entities"] = ["projects", "technologies"];
?>
<html>

<head>
  <link rel="stylesheet/less" href="styles/index.css">
  <link rel="stylesheet" href="styles/entities.css">
  <link rel="stylesheet" href="styles/filters.css">
  <link rel="stylesheet" href="styles/filter_search.css">
  <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
  <!-- <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script> -->
  <script type="text/javascript" src="scripts/filters.js"></script>
  <script type="text/javascript">
    entities = ["projects", "technologies"];

    function onload() {
      for (entity of entities) {
        loadFilterSearch(entity);
        loadFilters(entity);
      }
      updateAllEntities();
    }

    function loadFilterSearch(entity) {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById(entity + ".filter-search").innerHTML = this.responseText;
        }
      };
      xmlhttp.open("GET", "filter_search.php?entity=" + entity, true);
      xmlhttp.send();
    }

    function loadFilters(entity) {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById(entity + ".filters").innerHTML = this.responseText;
        }
      };
      xmlhttp.open("GET", "filters.php?entity=" + entity, true);
      xmlhttp.send();
    }

    function updateEntities(entity, filters) {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById(entity + ".entities").innerHTML = this.responseText;
        }
      };
      xmlhttp.open("GET", "entities.php?entity=" + entity + "&filters=" + filters, true);
      xmlhttp.send();
    }

    function updateAllEntities() {
      filters = getFilters();
      for (entity of entities) {
        updateEntities(entity, filters);
      }
    }
  </script>
</head>

<body onload="onload()">
  <div id="entity-cols">
    <?php
    foreach ($_SESSION["entities"] as $entity) {
      echo "<div id='" . $entity . ".column' class='entity-col'>";
      echo "  <h1 id='" . $entity . ".title' class='entity-title'>" . ucfirst($entity) . "</h1>";
      echo "  <div id='" . $entity . ".filter-search'></div>";
      echo "  <div id='" . $entity . ".filters'></div>";
      echo "  <div id='" . $entity . ".entities'></div>";
      echo "</div>";
    }
    ?>
  </div>
</body>

</html>