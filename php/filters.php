<?php
session_start();
?>
<html>

<body>
    <?php
    $log = $_SESSION["log"];
    $entity = $_GET['entity'];

    $db = new PDO("sqlite:../" . $_SESSION["db_path"]);
    $sql = "SELECT * FROM " . $entity . " ORDER BY name ASC";
    if ($log)
        file_put_contents('../log.txt', date("Y-m-d:H:i:s") . ": sql='" . $sql . "'" . PHP_EOL, FILE_APPEND);

    $logical_ops = array("and", "or");

    echo "<div id='" . $entity . ".filters.list' class='filters-list'>";
    foreach ($logical_ops as $logical_op) {
        $result = $db->query($sql);
        foreach ($result as $row) {
            $filter_id = $entity . "." . $row["id"];
            echo "<div id='filters." . $filter_id . "." . $logical_op . "' class='filter-entry' data-active=0 data-logical=" . $logical_op . ">";
            echo    "<button onmousedown='removeFilter(\"" . $filter_id . "\",\"" . $logical_op . "\")'>" . $row["name"] . "</button>";
            echo "</div>";
        }
    }
    echo "</div>";
    ?>
</body>

</html>