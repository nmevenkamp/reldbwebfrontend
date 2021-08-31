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
        filter_name = entry.getElementsByClassName("filter-search-name")[0];
        txtValue = filter_name.textContent || filter_name.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            entry.style.display = "block";
        } else {
            entry.style.display = "none";
        }
    }
}

function addFilter(filter_id, logical_op) {
    document.getElementById("filter-search." + filter_id).setAttribute("data-active", 1);
    document.getElementById("filters." + filter_id + "." + logical_op).setAttribute("data-active", 1);

    updateAllEntities();
    toggleFiltersListVisibility();
}

function removeFilter(filter_id, logical_op) {
    document.getElementById("filter-search." + filter_id).setAttribute("data-active", 0);
    document.getElementById("filters." + filter_id + "." + logical_op).setAttribute("data-active", 0);

    updateAllEntities();
    toggleFiltersListVisibility();
}

function toggleFiltersListVisibility() {
    lists = document.getElementsByClassName("filters-list");
    for (list of lists) {
        filters = list.querySelectorAll('[data-active="1"]');
        if (filters.length > 0)
            list.style.display = "block";
        else
        list.style.display = "none";
    }
}

function getFilters() {
    filters = [];
    entries = document.getElementsByClassName("filter-entry");
    for (entry of entries) {
        if (entry.getAttribute("data-active") == 1) {
            // remove leading 'filters.'
            filter_str = entry.id;
            filter_str = filter_str.substring(filter_str.indexOf(".") + 1);
            filters.push(filter_str);
        }
    }
    filters = filters.join(',');
    return filters;
}