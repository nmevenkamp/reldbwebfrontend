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