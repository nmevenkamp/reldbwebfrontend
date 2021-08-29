
function showFilterSearch(entity) {
    div = document.getElementById(entity + ".filter-search.list");
    div.style.display = "block";
    a = div.getElementsByTagName("a");
    for (i = 0; i < a.length; i++) {
        a[i].style.display = "block";
    }
    filterFunction(entity);
}
function hideFilterSearch(entity) {
    div = document.getElementById(entity + ".filter-search.list");
    div.style.display = "none";
    a = div.getElementsByTagName("a");
    for (i = 0; i < a.length; i++) {
        a[i].style.display = "none";
    }
}
function updateFilterSearch(entity) {
    var input, filter, ul, li, a, i;
    input = document.getElementById(entity + ".filter-search.input");
    filter = input.value.toUpperCase();
    div = document.getElementById(entity + ".filter-search.list");
    a = div.getElementsByTagName("a");
    for (i = 0; i < a.length; i++) {
        txtValue = a[i].textContent || a[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "block";
        } else {
            a[i].style.display = "none";
        }
    }
}