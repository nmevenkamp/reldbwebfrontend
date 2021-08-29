function showFilterSearch(entity) {
    div = document.getElementById(entity + ".filter-search.list");
    div.style.display = "block";
    entries = div.getElementsByClassName("filter-search-entry");
    for (entry of entries) {
        if (entry.getAttribute("data-active") == 1)
            continue;
        entry.style.display = "block";
    }
    updateFilterSearch(entity);
}

function hideFilterSearch(entity) {
    div = document.getElementById(entity + ".filter-search.list");
    div.style.display = "none";
    entries = div.getElementsByClassName("filter-search-entry");
    for (entry of entries) {
        entry.style.display = "none";
    }
}

function updateFilterSearch(entity) {
    input = document.getElementById(entity + ".filter-search.input");
    filter = input.value.toUpperCase();
    div = document.getElementById(entity + ".filter-search.list");
    entries = div.getElementsByClassName("filter-search-entry");
    for (entry of entries) {
        if (entry.getAttribute("data-active") == 1)
            continue;
        a = entry.getElementsByTagName("a")[0];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            entry.style.display = "block";
        } else {
            entry.style.display = "none";
        }
    }
}

function addFilter(filter_id) {
    entry = document.getElementById("filter-search." + filter_id);
    entry.setAttribute("data-active", 1);
    entry = document.getElementById("filters." + filter_id);
    entry.setAttribute("data-active", 1);
    entry.style.display = "block";
}

function removeFilter(filter_id) {
    entry = document.getElementById("filter-search." + filter_id);
    entry.setAttribute("data-active", 0);
    entry = document.getElementById("filters." + filter_id);
    entry.setAttribute("data-active", 0);
    entry.style.display = "none";
}