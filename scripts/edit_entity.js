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
    var form = document.forms.namedItem("edit-entity-form");
    form_data = new FormData(form);

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            hideEditEntityDialog();
            updateAllEntities();
        }
    };
    xmlhttp.open("POST", "php/edit_entity.php?entity=" + entity + "&id=" + id + "&a=update", true);
    xmlhttp.send(form_data);
}

function deleteEntity(entity, id) {
    alert("Dummy: you sure you wanna delete '" + id + "' from " + entity + "?");
    hideEditEntityDialog();
}

function showRelationSearch(entity) {
    div = document.getElementById(entity + ".relation-search.list");
    div.style.display = "block";
    entries = div.getElementsByClassName("filter-search-entry");
    for (entry of entries) {
        if (entry.getAttribute("data-active") == 1)
            continue;
        entry.style.display = "block";
    }
    updateRelationSearch(entity);
}

function hideRelationSearch(entity) {
    div = document.getElementById(entity + ".relation-search.list");
    div.style.display = "none";
    entries = div.getElementsByClassName("filter-search-entry");
    for (entry of entries) {
        entry.style.display = "none";
    }
}

function updateRelationSearch(entity) {
    input = document.getElementById(entity + ".relation-search.input");
    relation = input.value.toUpperCase();
    div = document.getElementById(entity + ".relation-search.list");
    entries = div.getElementsByClassName("filter-search-entry");
    has_entries = false;
    for (entry of entries) {
        if (entry.getAttribute("data-active") == 1)
            continue;
        relation_button = entry.getElementsByClassName("relation-search-add-button")[0];
        txtValue = (relation_button.getAttribute("data-name")).toUpperCase();
        if (txtValue.indexOf(relation) > -1) {
            entry.style.display = "block";
            has_entries = true;
        } else {
            entry.style.display = "none";
        }
    }
    if (has_entries)
        div.style.display = "block";
    else
        div.style.display = "none";
}

function addRelation(relation_id) {
    document.getElementById("relation-search." + relation_id).setAttribute("data-active", 1);
    document.getElementById("relations." + relation_id).setAttribute("data-active", 1);
    toggleFiltersListVisibility();
}

function removeFilter(relation_id) {
    document.getElementById("relation-search." + relation_id).setAttribute("data-active", 0);
    document.getElementById("relations." + relation_id).setAttribute("data-active", 0);
    toggleFiltersListVisibility();
}