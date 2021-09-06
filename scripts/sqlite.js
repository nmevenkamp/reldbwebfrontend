class SQLite {
    constructor(db_path) {
        this.db_path = db_path
    }

    query(query_str, response_callback) {
        // response_callback("");
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                response_callback(JSON.parse(this.responseText));
            }
        };
        xmlhttp.open("GET", "php/sqlite.php?db_path=" + this.db_path + "&query_str=" + query_str, true);
        xmlhttp.send();
    }
}

