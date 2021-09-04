<?php
session_start();
?>
<html>

<body>
    <?php

    $log = $_SESSION["log"];
    $entity = $_GET['entity'];

    // parse filters
    $filters = $_GET['filters'];
    if ($filters == "") {
        $filters = [];
    } else {
        $filters = explode(',', $_GET['filters']);
    }

    $db = new PDO("sqlite:../" . $_SESSION["db_path"]);
    $entity_ids = [];
    $all_relation_conditions = [
        "and" => [],
        "or" => []
    ];
    foreach ($filters as $filter) {
        list($filter_entity, $filter_id, $logical_op) = explode('.', $filter);
        if ($filter_entity == $entity) {
            array_push($entity_ids, $filter_id);
        } else {
            $cond1 = "(relations.table1 == '" . $entity . "' AND relations.id1 == '" . $entity . "'.id AND relations.table2 == '" . $filter_entity . "' AND relations.id2 == " . $filter_id . ")";
            $cond2 = "(relations.table2 == '" . $entity . "' AND relations.id2 == '" . $entity . "'.id AND relations.table1 == '" . $filter_entity . "' AND relations.id1 == " . $filter_id . ")";
            array_push($all_relation_conditions[$logical_op], "EXISTS(SELECT * FROM relations WHERE (" . $cond1 . " OR " . $cond2 . "))");
        }
    }
    if (count($entity_ids) > 0)
        $entity_ids_condition = "id IN (" . implode(',', array_map('intval', $entity_ids)) . ")";
    else
        $entity_ids_condition = "TRUE";
    $relation_condition = [
        "and" => "TRUE",
        "or" => "FALSE"
    ];
    foreach ($all_relation_conditions as $logical_op => $relation_conditions) {
        if (count($relation_conditions) > 0)
            $relation_condition[$logical_op] = "(" . implode(" " . $logical_op . " ", $relation_conditions) . ")";
    }
    $relation_condition = implode(" OR ", array_values($relation_condition));
    $sql = "SELECT * FROM " . $entity . " WHERE " . $entity_ids_condition . " AND " . $relation_condition . " ORDER BY name ASC";
    if ($log)
        file_put_contents('../log.txt', date("Y-m-d:H:i:s") . ": sql='" . $sql . "'" . PHP_EOL, FILE_APPEND);
    $result = $db->query($sql);

    echo "<div id='" . $entity . ".entities.list' class='entities-list'>";
    echo "  <table>";
    foreach ($result as $row) {
        echo "  <tr><td><button onclick='editEntity(\"" . $entity . "\",\"" . $row["id"] . "\")'>" . $row["name"] . "</button></td></tr>";
    }
    echo "  </table>";
    echo "</div>";
    ?>
</body>

</html>