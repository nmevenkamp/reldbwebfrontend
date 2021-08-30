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

    echo "<div id='" . $entity . ".filter-search.dropdown' class='filter-search-dropdown'>";
    echo "<input id='" . $entity . ".filter-search.input' type='text' placeholder='Filter " . $entity . "..' onfocus='showFilterSearch(\"" . $entity . "\")' onfocusout='hideFilterSearch(\"" . $entity . "\")' onkeyup='updateFilterSearch(\"" . $entity . "\")'>";
    echo "<div id='" . $entity . ".filter-search.list' class='filter-search-list'>";
    foreach ($result as $row) {
        $filter_id = $entity . "." . $row["id"];
        echo "<div id='filter-search." . $filter_id . "' class='filter-search-entry' data-active=0>";
        echo    "<a>" . $row["name"] . "</a>";
        echo    "<button class='filter-search-or-button' onmousedown='addFilter(\"" . $filter_id . "\",\"or\")'>or</button>";
        echo    "<button class='filter-search-and-button' onmousedown='addFilter(\"" . $filter_id . "\",\"and\")'>and</button>";
        echo "</div>";
    }
    echo "</div>";
    echo "</div>";
    ?>
</body>

</html>