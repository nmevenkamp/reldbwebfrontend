<?php
session_start();
?>
<html>

<head>
    <link rel="stylesheet" href="styles/filter_search.css">
</head>

<body>
    <?php
    $entity = $_GET['entity'];

    $db = new PDO("sqlite:" . $_SESSION["db_path"]);
    $sql = "SELECT * FROM " . $entity;
    $result = $db->query($sql);

    echo "<div id='" . $entity . ".filter-search.dropdown' class='filter-search-dropdown'>";
    echo "<input id='" . $entity . ".filter-search.input' type='text' placeholder='Filter " . $entity . "..' onfocus='showFilterSearch(\"" . $entity . "\")' onfocusout='hideFilterSearch(\"" . $entity . "\")' onkeyup='updateFilterSearch(\"" . $entity . "\")'>";
    echo "<div id='" . $entity . ".filter-search.list' class='filter-search-list'>";
    foreach ($result as $row) {
        echo "<a href='#" . $row["name"] . "'>" . $row["name"] . "</a>";
    }
    echo "</div>";
    echo "</div>";
    ?>
</body>

</html>