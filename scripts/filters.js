
function showFilters(entity) {
    div = document.getElementById(entity + ".filters.list");
    div.style.display = "block";
    a = div.getElementsByTagName("a");
    for (i = 0; i < a.length; i++) {
        a[i].style.display = "block";
    }
    filterFunction(entity);
}
function hideFilters(entity) {
    div = document.getElementById(entity + ".filters.list");
    div.style.display = "none";
    a = div.getElementsByTagName("a");
    for (i = 0; i < a.length; i++) {
        a[i].style.display = "none";
    }
}
function filterFunction(entity) {
    var input, filter, ul, li, a, i;
    input = document.getElementById(entity + ".filters.input");
    filter = input.value.toUpperCase();
    div = document.getElementById(entity + ".filters.list");
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