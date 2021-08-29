<?php
    session_start();
?>
<html>
    <head>
        <link rel="stylesheet" href="styles/filters.css">
    </head>
    <body>
        <?php
            $entity = $_GET['entity'];

            $db = new PDO("sqlite:".$_SESSION["db_path"]);
            $sql = "SELECT * FROM projects";
            $result = $db->query($sql);

            echo "<div id='".$entity.".filters.dropdown' class='filters-dropdown'>";
                echo "<input id='".$entity.".filters.input' type='text' placeholder='Filter ".$entity."..' onfocus='showFilters(\"".$entity."\")' onfocusout='hideFilters(\"".$entity."\")' onkeyup='filterFunction(\"".$entity."\")'>";
                echo "<div id='".$entity.".filters.list' class='filters-list'>";
                    foreach ($result as $row) {
                        echo "<a href='#".$row["name"]."'>".$row["name"]."</a>";
                    }
                echo "</div>";
            echo "</div>";
        ?>
    </body>
</html>