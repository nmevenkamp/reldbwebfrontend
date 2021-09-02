function updateEntities(entity, filters) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById(entity + ".entities").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "php/entities.php?entity=" + entity + "&filters=" + filters, true);
    xmlhttp.send();
}

function updateAllEntities() {
    filters = getFilters();
    for (entity of entities) {
        updateEntities(entity, filters);
    }
}