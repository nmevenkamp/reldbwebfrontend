<?php
session_start();
?>
<html>

<body>
    <?php

    $log = $_SESSION['log'];
    $entity = $_GET['entity'];
    $action = $_GET['a'];
    $entities = $_SESSION["entities"];
    $entity_s = $_SESSION["entity_singulars"][$entity];
    $db = new PDO("sqlite:../" . $_SESSION["db_path"]);

    switch ($action) {
        case "new":
            $id = "none";
            $name = $_GET['name'];
            $ok_button_text = "Add " . $entity_s;
            break;
        case "edit":
            $id = $_GET['id'];
            $sql = "SELECT * FROM " . $entity . " WHERE id='" . $id . "' LIMIT 1";
            $result = $db->query($sql);
            foreach ($result as $row) {
                $name = $row["name"];
                break;
            }
            $ok_button_text = "Ok";
            break;
        case "update":
            // TOOD: get and update relations
            $id = $_GET['id'];
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
        echo "  <div id='edit-entity-form-container' class='flex-vmin'>";
        echo "      <h1>" . ucfirst($entity_s) . "</h1></br>";
        echo "      <form name='edit-entity-form'>";
        echo "          <div class='input-div'><span id='edit-entity-name-label'>Title: </span><input id='edit-entity-name' name='name' type='text' value='" . $name . "'></div>";
        echo "          <div class='input-div'><span id='edit-entity-owner-label'>Owner: </span><input id='edit-entity-owner' name='owner' type='text' value=''></div>";
        echo "          <div class='input-div'><span class='edit-entity-last-edited'>Last edited: <b>2021-09-04 13:40:01</b></span></div>";
        echo "          </br></br>";
        echo "          <p id='edit-entity-description-label'>Description:</p><textarea id='edit-entity-description' name='description' type='text'></textarea>";
        echo "      </form>";
        echo "  </div>";
        echo "  <div id='edit-entity-relations-container' class='flex-vmax'>";
        echo "      <div class='entity-cols'>";
        foreach (array_values($entities) as $idx => $entity) {
            echo "      <div class='entity-col box'>";
            echo "          <span class='entity-title'>Related " . $entity . "</span>";
            echo "          <div id='" . $entity . ".relation-search.dropdown' class='relation-search-dropdown flex-vmin'>";
            echo "              <div class='relation-search-input-container'>";
            echo "                  <input id='" . $entity . ".relation-search.input' type='text' placeholder='add " . $entity . "..' onfocus='showRelationSearch(\"" . $entity . "\")' onfocusout='hideRelationSearch(\"" . $entity . "\")' onkeyup='updateRelationSearch(\"" . $entity . "\")'>";
            echo "              </div>";
            echo "              <div id='" . $entity . ".relation-search.list' class='relation-search-list'>";

            # TODO: check which relations actually exist and
            #           activate corresponding relation rows
            #           deactivate corresponding relation search entries

            $sql = "SELECT * FROM " . $entity . " ORDER BY name ASC";
            $result = $db->query($sql);
            foreach ($result as $row) {
                $relation_id = $entity . "." . $row["id"];
                echo "              <div id='relation-search." . $relation_id . "' class='relation-search-entry' data-active=0>";
                echo "                  <button class='relation-search-add-button' data-name='" . $row["name"] . "' onmousedown='addRelation(\"" . $relation_id . "\")'>" . $row["name"] . "</button>";
                echo "              </div>";
            }
            echo "              </div>";
            echo "          </div>";

            $sql = "SELECT * FROM " . $entity . " ORDER BY name ASC";
            $result = $db->query($sql);
            echo "          <div id='" . $entity . ".relations.list' class='relations-list flex-vmax'>";
            echo "              <table>";
            foreach ($result as $row) {
                $relation_id = $entity . "." . $row["id"];
                echo "              <tr id='relations." . $relation_id . "' data-active=1><td><button onclick='removeRelation(\"" . $relation_id . "\")'>" . $row["name"] . "</button></td></tr>";
            }
            echo "              </table>";
            echo "          </div>";
            echo "      </div>";
            if ($idx < count($entities) - 1)
                echo "<div class='sep'></div>";
        }
        echo "      </div>";
        echo "  </div>";
        echo "  <div id='edit-buttons-container' class='flex-vmin'>";
        echo "      <button id='edit-entity-delete-button' onclick='deleteEntity(\"" . $entity . "\",\"" . $id . "\")'>Delete " . $entity_s . "</button>";
        echo "      <button id='edit-entity-cancel-button' onclick='cancelEditEntity()'>Cancel</button>";
        echo "      <button id='edit-entity-ok-button' onclick='acceptEditEntity(\"" . $entity . "\",\"" . $id . "\")'>" . $ok_button_text . "</button>";
        echo "  </div>";
        echo "</div>";
    }

    ?>
</body>

</html>