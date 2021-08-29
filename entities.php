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

    $sql1 = "";
    if (count($filters) == 0) {
        // no filters specified
        // -> show everything
        $sql = "SELECT * FROM " . $entity . " ORDER BY name ASC";
    } else {
        // first check if only one filter is specified and if it applies to this entity
        list($filter_entity, $filter_id) = explode('.', $filters[0]);
        if (count($filters) == 1 && $filter_entity == $entity) {
            // only one filter is specified and belongs to this entity
            // -> show the specified entry
            $sql = "SELECT * FROM " . $entity . " WHERE id == " . $filter_id;
        } else {
            // one or more filters specified that belong to this or other entities
            // -> combine all filters and show only what's left (might be nothing)
            $conditions = array();
            foreach ($filters as $filter) {
                list($filter_entity, $filter_id) = explode('.', $filter);
                $cond1 = "(table1 == '" . $entity . "' AND table2 == '" . $filter_entity . "' AND id2 == " . $filter_id . ")";
                $cond2 = "(table2 == '" . $entity . "' AND table1 == '" . $filter_entity . "' AND id1 == " . $filter_id . ")";
                array_push($conditions, "(" . $cond1 . " OR " . $cond2 . ")");
            }
            $condition_str = implode(" OR ", $conditions);
            $sql1 = "SELECT * FROM relations WHERE (table1 == '" . $entity . "' OR table2 == '" . $entity . "') AND (" . $condition_str . ")";
            $result = $db->query($sql1);
            

            $ids = array();
            foreach ($result as $row) {
                if ($row["table1"] == $entity)
                    array_push($ids, $row["id1"]);
                if ($row["table2"] == $entity)
                    array_push($ids, $row["id2"]);
            }
            $sql = "SELECT * FROM " . $entity . " WHERE id IN (" . implode(',', array_map('intval', $ids)) . ") ORDER BY name ASC";
        }
    }
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