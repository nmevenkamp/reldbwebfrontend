<?php
session_start();
?>
<html>

<body>
    <?php
    $entity = $_GET['entity'];

    $db = new PDO("sqlite:" . $_SESSION["db_path"]);
    $sql = "SELECT * FROM " . $entity . " ORDER BY name ASC";
    $result = $db->query($sql);

    echo "<div id='" . $entity . ".filters.list' class='filters-list'>";
    foreach ($result as $row) {
        $filter_id = $entity . "." . $row["id"];
        echo "<div id='filters." . $filter_id . "' class='filter-entry' data-active=0>";
        echo    "<a>" . $row["name"] . "</a>";
        echo    "<button class='remove-filter-button' onmousedown='removeFilter(\"" . $filter_id . "\")'>x</button>";
        echo "</div>";
    }
    echo "</div>";
    ?>
</body>

</html>