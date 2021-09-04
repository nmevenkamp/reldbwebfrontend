<?php
session_start();

# define session globals
$_SESSION["log"] = true;
$_SESSION["db_path"] = "data/my_db.db";
$_SESSION["entities"] = ["projects", "technologies"];
$_SESSION["entity_singulars"] = [
  "projects" => "project",
  "technologies" => "technology"
]

?>
<html>

<head>
  <link rel="stylesheet/less" href="styles/colors.css">
  <link rel="stylesheet/less" href="styles/index.css">
  <link rel="stylesheet/less" href="styles/filters.css">
  <link rel="stylesheet/less" href="styles/filter_search.css">
  <link rel="stylesheet/less" href="styles/entities.css">
  <link rel="stylesheet/less" href="styles/edit_entity.css">
  <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
  <script type="text/javascript" src="scripts/misc.js"></script>
  <script type="text/javascript" src="scripts/filters.js"></script>
  <script type="text/javascript" src="scripts/entities.js"></script>
  <script type="text/javascript" src="scripts/edit_entity.js"></script>
  <script type="text/javascript">
    entities = ["projects", "technologies"];

    function onload() {
      for (entity of entities) {
        loadFilterSearch(entity);
        loadFilters(entity);
      }
      updateAllEntities();
    }
  </script>
</head>

<body onload="onload()">
  <div id="entity-cols" data-disabled=0 class="entity-cols">
    <?php
    $i = 0;
    foreach ($_SESSION["entities"] as $entity) {
      echo "<div id='" . $entity . ".column' class='entity-col";
      if ($i > 0)
        echo " sep";
      echo "'>";
      echo "  <h1 id='" . $entity . ".title' class='entity-title'>" . ucfirst($entity) . "</h1>";
      echo "  <div id='" . $entity . ".filter-search'></div>";
      echo "  <div id='" . $entity . ".filters'></div>";
      echo "  <div id='" . $entity . ".entities'></div>";
      echo "</div>";
      $i += 1;
    }
    ?>
  </div>
  <div id='edit-entity-dialog'></div>
</body>

</html>