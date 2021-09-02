<?php
session_start();
?>
<html>

<body>
    <?php

    $entity = $_GET['entity'];
    $action = $_GET['a'];

    if ($action == "new") {
        $name = $_GET['name'];
    } elseif ($action == "edit") {
        $id = $_GET['id'];
        $db = new PDO("sqlite:../" . $_SESSION["db_path"]);
        $name = "none";
    }

    echo "<div id='edit-entity-container'>";
    echo "  <button id='edit-entity-x-button' onclick='cancelEditEntity()'>x</button>";
    echo "  <h1>" . ucfirst($_SESSION["entity_singulars"][$entity]) . "</h1><br/>";
    echo "  <span id='edit-entity-name-label'>Title: </span><input id='edit-entity-name' type='text' value='" . $name . "'></br>";
    echo "  <div id='edit-entity-post-buttons'>";
    echo "      <button id='edit-entity-ok-button' onclick='acceptEditEntity()'>Ok</button>";
    echo "      <button id='edit-entity-cancel-button' onclick='cancelEditEntity()'>Cancel</button>";
    echo "      <button id='edit-entity-delete-button' onclick='deleteEntity()'>Delete</button>";
    echo "  </div>";
    echo "</div>";

    ?>
</body>

</html>