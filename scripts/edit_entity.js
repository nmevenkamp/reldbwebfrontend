function addEntity(entity) {
    entry_name = capitalizeFirstLetter(document.getElementById(entity + ".filter-search.input").value);
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('edit-entity-dialog').innerHTML = this.responseText;
            showEditEntityDialog();
        }
    };
    xmlhttp.open("GET", "php/edit_entity.php?entity=" + entity + "&name=" + entry_name + "&a=new", true);
    xmlhttp.send();
}

function editEntity(entity, id) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('edit-entity-dialog').innerHTML = this.responseText;
            showEditEntityDialog();
        }
    };
    xmlhttp.open("GET", "php/edit_entity.php?entity=" + entity + "&id=" + id + "&a=edit", true);
    xmlhttp.send();
}

function showEditEntityDialog() {
    document.getElementById("edit-entity-dialog").style.display = "block";
    document.getElementById("entity-cols").setAttribute("data-disabled", 1);
}

function hideEditEntityDialog() {
    document.getElementById("edit-entity-dialog").style.display = "none";
    document.getElementById("entity-cols").setAttribute("data-disabled", 0);
}

function cancelEditEntity() {
    hideEditEntityDialog();
}

function acceptEditEntity(entity, id) {
    // build form data
    form_data = new FormData(document.getElementById("edit-entity-form"));

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            hideEditEntityDialog();
            updateAllEntities();
        }
    };
    xmlhttp.open("GET", "php/edit_entity.php?entity=" + entity + "&id=" + id + "&a=update", true);
    xmlhttp.send(form_data);
}

function deleteEntity(entity, id) {
    alert("Dummy: you sure you wanna delete '" + id + "' from " + entity + "?");
    hideEditEntityDialog();
}