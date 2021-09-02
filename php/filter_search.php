<?php
session_start();
?>
<html>

<body>
    <?php
    $log = $_SESSION["log"];
    $entity = $_GET['entity'];

    $db = new PDO("sqlite:../" . $_SESSION["db_path"]);
    $sql = "SELECT * FROM " . $entity . " ORDER BY name ASC";
    if ($log)
        file_put_contents('../log.txt', date("Y-m-d:H:i:s") . ": sql='" . $sql . "'" . PHP_EOL, FILE_APPEND);
    $result = $db->query($sql);

    echo "<div id='" . $entity . ".filter-search.dropdown' class='filter-search-dropdown'>";
    echo "  <div class='filter-search-input-container'>";
    echo "      <input id='" . $entity . ".filter-search.input' type='text' placeholder='add or filter " . $entity . "..' onfocus='showFilterSearch(\"" . $entity . "\")' onfocusout='hideFilterSearch(\"" . $entity . "\")' onkeyup='updateFilterSearch(\"" . $entity . "\")'>";
    echo "      <button id='" . $entity . ".filter-search.new' class='filter-search-new-button' onclick='addEntity(\"" . $entity . "\")'>&#43;</button>";
    echo "  </div>";
    echo "  <div id='" . $entity . ".filter-search.list' class='filter-search-list'>";
    foreach ($result as $row) {
        $filter_id = $entity . "." . $row["id"];
        echo "<div id='filter-search." . $filter_id . "' class='filter-search-entry' data-active=0>";
        echo "  <button class='filter-search-add-button' data-logical='or' onmousedown='addFilter(\"" . $filter_id . "\",\"or\")'><p>or</p></button>";
        echo "  <button class='filter-search-add-button' data-logical='and' onmousedown='addFilter(\"" . $filter_id . "\",\"and\")'><p>and</p></button>";
        echo "  <p class='filter-search-name' data-positioned='0'>" . $row["name"] . "</p>";
        echo "  <p class='filter-search-name' data-positioned='1'>" . $row["name"] . "</p>";
        echo "</div>";
    }
    echo "  </div>";
    echo "</div>";
    ?>
</body>

</html>