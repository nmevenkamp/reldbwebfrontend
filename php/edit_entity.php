<?php
session_start();
?>
<html>

<body>
    <?php

    $entity = $_GET['entity'];
    $action = $_GET['a'];

    switch ($action) {
        case "new":
            $id = "none";
            $name = $_GET['name'];
            break;
        case "edit":
            $id = $_GET['id'];
            $db = new PDO("sqlite:../" . $_SESSION["db_path"]);
            $sql = "SELECT * FROM " . $entity . " WHERE id='" . $id . "' LIMIT 1";
            $result = $db->query($sql);

            foreach ($result as $row) {
                $name = $row["name"];
                break;
            }
            break;
        case "update":
            $id = $_GET['id'];
            $db = new PDO("sqlite:../" . $_SESSION["db_path"]);
            $vals = [
                "name" => $_POST["edit-entity-name"]
            ];
            if ($id == "none") {
                // add new entry
                $cols_str = "(" . implode(",", array_keys($vals)) . ")";
                $vals_str = "(" . implode(",", array_values($vals)) . ")";
                $sql = "INSERT INTO " . $entity . $cols_str . " VALUES " . $vals_str;
            } else {
                // update existing entry
                $cols_vals_arr = [];
                foreach ($vals as $col => $val) {
                    array_push($cols_vals_arr, $col . " = '" . $vak . "'");
                }
                $cols_vals_str = implode(", ", $cols_vals_arr);
                $sql = "UPDATE " . $entity . "SET " . $cols_vals_str . " WHERE id=" . $id;
            }
            $db->query($sql);
    }

    if ($action == "new" || $action == "edit") {
        echo "<div id='edit-entity-container'>";
        echo "  <button id='edit-entity-x-button' onclick='cancelEditEntity()'>x</button>";
        echo "  <h1>" . ucfirst($_SESSION["entity_singulars"][$entity]) . "</h1><br/>";
        echo "  <form id='edit-entity-form'>";
        echo "      <span id='edit-entity-name-label'>Title: </span><input id='edit-entity-name' type='text' value='" . $name . "'></br>";
        echo "  </form>";
        echo "  <div id='edit-entity-post-buttons'>";
        echo "      <button id='edit-entity-ok-button' onclick='acceptEditEntity(\"" . $entity . "\",\"" . $id . "\")'>Ok</button>";
        echo "      <button id='edit-entity-cancel-button' onclick='cancelEditEntity()'>Cancel</button>";
        echo "      <button id='edit-entity-delete-button' onclick='deleteEntity(\"" . $entity . "\",\"" . $id . "\")'>Delete</button>";
        echo "  </div>";
        echo "</div>";
    }

    ?>
</body>

</html>