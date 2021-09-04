<?php
session_start();
?>
<html>

<body>
    <?php

    $log = $_SESSION['log'];
    $entity = $_GET['entity'];
    $action = $_GET['a'];
    $entity_s = $_SESSION["entity_singulars"][$entity];

    switch ($action) {
        case "new":
            $id = "none";
            $name = $_GET['name'];
            $ok_button_text = "Add " . $entity_s;
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
            $ok_button_text = "Ok";
            break;
        case "update":
            $id = $_GET['id'];
            $db = new PDO("sqlite:../" . $_SESSION["db_path"]);
            if ($log) {
                file_put_contents('../log.txt', date("Y-m-d:H:i:s") . ": begin POST:" . PHP_EOL, FILE_APPEND);
                foreach ($_POST as $key => $val)
                    file_put_contents('../log.txt', date("Y-m-d:H:i:s") . ": \t" . $key . " => " . $val . PHP_EOL, FILE_APPEND);
                file_put_contents('../log.txt', date("Y-m-d:H:i:s") . ": end POST" . PHP_EOL, FILE_APPEND);
            }
            if ($id == "none") {
                // add new entry
                $cols_str = "(" . implode(",", array_keys($_POST)) . ")";
                $vals_str = "('" . implode("','", array_values($_POST)) . "')";
                $sql = "INSERT INTO " . $entity . $cols_str . " VALUES " . $vals_str;
                if ($log)
                    file_put_contents('../log.txt', date("Y-m-d:H:i:s") . ": sql='" . $sql . "'" . PHP_EOL, FILE_APPEND);
            } else {
                // update existing entry
                $cols_vals_arr = [];
                foreach ($_POST as $col => $val) {
                    array_push($cols_vals_arr, $col . " = '" . $val . "'");
                }
                $cols_vals_str = implode(", ", $cols_vals_arr);
                $sql = "UPDATE " . $entity . " SET " . $cols_vals_str . " WHERE id=" . $id;
                if ($log)
                    file_put_contents('../log.txt', date("Y-m-d:H:i:s") . ": sql='" . $sql . "'" . PHP_EOL, FILE_APPEND);
            }
            $db->query($sql);
    }

    if ($action == "new" || $action == "edit") {
        echo "<div id='edit-entity-container' class='box'>";
        echo "  <button id='edit-entity-x-button' onclick='cancelEditEntity()'>x</button>";
        echo "  <div id='edit-entity-form-container' class='header'>";
        echo "      <h1>" . ucfirst($entity_s) . "</h1><br/>";
        echo "      <form name='edit-entity-form'>";
        echo "          <span id='edit-entity-name-label'>Title: </span><input id='edit-entity-name' name='name' type='text' value='" . $name . "'>";
        echo "          <span id='edit-entity-owner-label'>Owner: </span><input id='edit-entity-owner' name='owner' type='text' value=''>";
        echo "          </br></br>";
        echo "          <p id='edit-entity-description-label'>Description:</p><input id='edit-entity-description' name='description' type='text' value=''>";
        echo "      </form>";
        echo "  </div>";
        echo "  <div id='edit-entity-relations-container' class='content'>";
        echo "  </div>";
        echo "  <div id='edit-buttons-container' class='footer'>";
        echo "      <button id='edit-entity-delete-button' onclick='deleteEntity(\"" . $entity . "\",\"" . $id . "\")'>Delete " . $entity_s . "</button>";
        echo "      <button id='edit-entity-cancel-button' onclick='cancelEditEntity()'>Cancel</button>";
        echo "      <button id='edit-entity-ok-button' onclick='acceptEditEntity(\"" . $entity . "\",\"" . $id . "\")'>" . $ok_button_text . "</button>";
        echo "  </div>";
        echo "</div>";
    }

    ?>
</body>

</html>