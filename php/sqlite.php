<?php
    $dbh = new PDO("sqlite:../" . $_GET["db_path"]);
    $sth = $dbh->prepare($_GET["query_str"]);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    echo(json_encode($result));
?>