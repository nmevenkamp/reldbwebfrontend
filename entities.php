<?php
session_start();
?>
<html>

<body>
    <?php

    $entity = $_GET['entity'];

    // parse filters
    $filters = $_GET['filters'];
    if ($filters == "") {
        $filters = [];
    } else {
        $filters = explode(',', $_GET['filters']);
    }

    $db = new PDO("sqlite:" . $_SESSION["db_path"]);
    $entity_ids = array();
    $relation_conditions = array();
    foreach ($filters as $filter) {
        list($filter_entity, $filter_id) = explode('.', $filter);
        if ($filter_entity == $entity) {
            array_push($entity_ids, $filter_id);
        } else {
            $cond1 = "(relations.table1 == '" . $entity . "' AND relations.id1 == '" . $entity . "'.id AND relations.table2 == '" . $filter_entity . "' AND relations.id2 == " . $filter_id . ")";
            $cond2 = "(relations.table2 == '" . $entity . "' AND relations.id2 == '" . $entity . "'.id AND relations.table1 == '" . $filter_entity . "' AND relations.id1 == " . $filter_id . ")";
            array_push($relation_conditions, "EXISTS(SELECT * FROM relations WHERE (" . $cond1 . " OR " . $cond2 . "))");
        }
    }
    if (count($entity_ids) > 0)
        $entity_ids_condition = "id IN (" . implode(',', array_map('intval', $entity_ids)) . ")";
    else
        $entity_ids_condition = "TRUE";
    if (count($relation_conditions) > 0)
        $relation_condition = implode(" AND ", $relation_conditions); // TODO: allow OR connection here
    else
        $relation_condition = "TRUE";
    $sql = "SELECT * FROM " . $entity . " WHERE " . $entity_ids_condition . " AND " . $relation_condition . " ORDER BY name ASC";
    $result = $db->query($sql);

    echo "<div id='" . $entity . ".entities.list' class='entities-list'>";
    echo "<table>";
    foreach ($result as $row) {
        echo    "<tr><td>" . $row["name"] . "</td></tr>";
    }
    echo "</table>";
    echo "</div>";
    ?>
</body>

</html>