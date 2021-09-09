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
            if (typeof object.addFunctions === 'function')
                object.addFunctions();
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
    for (const [key, value] of Object.entries(this.htmlAttrs)) {
        if (value != null)
            res += " " + key + "='" + value + "'";
    }
    return res;
}

class EntitiesTable {
    constructor(cols=["name"], filterFunc=null) {
        this.cols = cols;
        this.filterFunc = filterFunc;
    }

    get html() {
        let entitiesFiltered = [];
        for (const entity of entities) {
            if (this.filterFunc == null || this.filterFunc(entity)) {
                let row = {"entityId": entity["id"], "entityName": entity["name"]};
                for (const col of this.cols)
                    row[col] = entity[col];
                entitiesFiltered.push(row);
            }
        }
        return new Table(entitiesFiltered, ["entityId", "entityName"]).html;
    }
}

class EntitiesSearch {
    constructor(filterFunc=null, placeholder=null, ) {
        this.filterFunc = filterFunc;
        this.placeholder = placeholder;

        this.divId = uuid();

        this.activeSet = [];
    }
    
    get html() {
        let html = "<div" + getHTMLAttrStr({"id": this.divId}) + ">";
        html += "<input" + getHTMLAttrStr({"data-id": "input", "type": "text", "placeholder": this.placeholder}) + ">";
        html += "</div>";
        html += "<div" + getHTMLAttrStr({"data-id": "search-list"}) + ">";
        html += new EntitiesTable(["name"], this.filterFunc).html;
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

    addFunctions() {
        this.input.ondocus = () => this.show();
        this.input.onfocusout = () => this.hide();
        this.input.onkeyup = () => this.update();
    }

    show() {
        this.searchList.style.display = "block";
    }

    hide () {
        this.searchList.style.display = "none";
    }

    update () {
        const inputStr = this.input.value.toUpperCase();
        let rows = this.searchList.getElementsByTagName("tr");
        hasEntries = false;
        matchedEntry = false;
        for (let row of rows) {
            entityId = row.getAttribute("data-entityId");
            if (this.activeSet.includes(entityId))
                continue;
            entityName = row.getAttribute("data-entityName");
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
    }
}

class Table {
    constructor(table, dataAttrKeys=null) {
        this.table = table;
        this.dataAttrKeys = dataAttrKeys;
    }

    get html() {
        let html = "<table>";
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