class ContextManager {
    constructor(divs) {
        this.divs = divs;
    }

    updateDivs() {
        for (let div of this.divs)
            div.update();
    }

    updateEntities(result) {
        entities = result;
        this.updateDivs();
    }

    updateRelations(result) {
        relations = result;
        this.updateDivs();
    }
}

class Div {
    constructor(divId, objects) {
        this.div = document.getElementById(divId);
        if (this.div == null)
            throw "Div: HTMLElement with id '" + divId + "' does not exist";
        this.objects = objects;
    }

    update() {
        let html = "";
        for (let object of this.objects)
            html += object.html;
        this.div.innerHTML = html;

        for (let object of this.objects) {
            if (typeof object.init === 'function')
                object.init();
        }
    }
}

function uuid() {
    return '_' + Math.random().toString(36).substr(2, 9);
};

function getHTMLAttrStr(htmlAttrs) {
    if (htmlAttrs == null)
        return "";

    let res = "";
    for (const [key, value] of Object.entries(htmlAttrs)) {
        if (value != null)
            res += " " + key + "='" + value + "'";
    }
    return res;
}

class EntitiesExplorer  {
    constructor() {
        let cols = [];
        for (let entityType of entityTypes) {
            cols.push(new EntitiesColumn(entityType));
        }
        this.colDivs = new ColumnDivs(cols);
    }

    get html() {
        let html = "<div class='entitiesExplorer'>";
        html += this.colDivs.html;
        html += "</div>";
        return html;
    }

    init() {
        this.colDivs.init();
    }
}

class EntitiesColumn {
    constructor(entityType) {
        this.entityType = entityType;

        function filterFunc(entity) {
            return entity["type"] == entityType;
        }

        this.search = new EntitiesSearch(filterFunc, "filter " + entityTypePlurals[this.entityType] + "..");
        this.filters = new EntitiesTable(["name"], 'filters', filterFunc);
        this.entities = new EntitiesTable(["name"], 'entities', filterFunc);
    }

    get html() {
        let html = "<div class='entitiesColumn'>";
        html += "<h1>" + capitalizeFirstLetter(entityTypePlurals[this.entityType]) + "</h1>";
        html += this.search.html;
        html += this.filters.html;
        html += this.entities.html;
        html += "</div>";
        return html;
    }

    init() {
        this.search.init();
    }
}

class ColumnDivs {
    constructor(cols, addSeparators = true) {
        this.cols = cols;
        this.addSeparators = addSeparators;
    }

    get html() {
        let html = "<div class='columns'>";
        for (let [index, col] of this.cols.entries()) {
            if (index > 0 && this.addSeparators)
                html += "<div class='sep'></div>";
            html += "<div class='column'>";
            html += col.html;
            html += "</div>";
        }
        html += "</div>";
        return html;
    }

    init() {
        for (let col of this.cols) {
            if (typeof col.init === 'function')
                col.init();
        }
    }
}

class EntitiesTable {
    constructor(cols = ["name"], className = null, filterFunc = null) {
        this.cols = cols;
        this.className = className;
        this.filterFunc = filterFunc;
    }

    get html() {
        let entitiesFiltered = [];
        for (const entity of entities) {
            if (this.filterFunc == null || this.filterFunc(entity)) {
                let row = {
                    "entityId": entity["id"],
                    "entityName": entity["name"]
                };
                for (const col of this.cols)
                    row[col] = entity[col];
                entitiesFiltered.push(row);
            }
        }
        return new Table(entitiesFiltered, this.className, ["entityId", "entityName"]).html;
    }
}

class EntitiesSearch {
    constructor(filterFunc = null, placeholder = null) {
        this.filterFunc = filterFunc;
        this.placeholder = placeholder;

        this.divId = uuid();

        this.activeSet = [];
    }

    get html() {
        let html = "<div" + getHTMLAttrStr({
            "id": this.divId,
            "class": 'entitiesSearch'
        }) + ">";
        html += "<div>";
        html += "<input" + getHTMLAttrStr({
            "data-id": "input",
            "type": "text",
            "placeholder": this.placeholder
        }) + ">";
        html += "</div>";
        html += "<div" + getHTMLAttrStr({
            "data-id": "searchList"
        }) + ">";
        html += new EntitiesTable(["name"], 'searchItems', this.filterFunc).html;
        html += "</div>";
        html += "</div>";
        return html;
    }

    get div() {
        return document.getElementById(this.divId);
    }

    get input() {
        return this.div.querySelector("[data-id='input']");
    }

    get searchList() {
        return this.div.querySelector("[data-id='searchList']");
    }

    init() {
        this.hide();

        this.input.addEventListener("focusin", () => this.show());
        this.input.addEventListener("focusout", () => this.hide());
        this.input.addEventListener("keyup", () => this.update());
    }

    show() {
        this.searchList.style.display = "block";
    }

    hide() {
        this.searchList.style.display = "none";
    }

    update() {
        const inputStr = this.input.value.toUpperCase();
        let rows = this.searchList.getElementsByTagName("tr");
        let hasEntries = false;
        let matchedEntry = false;
        for (let row of rows) {
            let entityId = row.getAttribute("data-entityId");
            if (this.activeSet.includes(entityId))
                continue;
            let entityName = row.getAttribute("data-entityName").toUpperCase();
            if (entityName.indexOf(inputStr) > -1) {
                row.style.display = "block";
                hasEntries = true;
            } else
                row.style.display = "none";
            if (entityName == inputStr)
                matchedEntry = true;
        }
        if (hasEntries)
            this.show();
        else
            this.hide();
        // button = document.getElementById(entity + ".filter-search.new");
        // if (filter == "" || matchedEntry)
        //     button.style.display = "none";
        // else
        //     button.style.display = "block";
    }
}

class Button {
    constructor(text = "", htmlAttrs = null) {
        this.text = text;
        this.htmlAttrs = htmlAttrs;
    }

    get html() {
        return "<button" + getHTMLAttrStr(this.htmlAttrs) + "><p>" + text + "</p><button>";
    }
}

class MultiButton {
    constructor(text, buttons) {
        this.text = text;
        this.buttons = buttons;
    }

    get html() {
        let html = "<div>";
        for (let button of this.buttons)
            html += button.html;
        html += "<p class='invisible'>" + this.text + "</p>";
        html += "<p class='overlay'>" + this.text + "</p>";
        html += "</div>";
        return html;
    }
}

class Table {
    constructor(table, className = null, dataAttrKeys = null) {
        this.table = table;
        this.className = className;
        this.dataAttrKeys = dataAttrKeys;
    }

    get html() {
        let html = "<table";
        if (this.className != null)
            html += " class='" + this.className + "'";
        html += ">";
        for (let row of this.table) {
            html += "<tr";
            if (this.dataAttrKeys != null) {
                for (const [key, col] of Object.entries(row)) {
                    if (this.dataAttrKeys.includes(key))
                        html += " data-" + key + "='" + col + "'";
                }
            }
            html += ">";
            for (const [key, col] of Object.entries(row)) {
                if (this.dataAttrKeys == null || !this.dataAttrKeys.includes(key))
                    html += "<td>" + col + "</td>";
            }
            html += "</tr>";
        }
        html += "</table>";
        return html;
    }
}