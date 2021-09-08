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
    }
}

function get_html_attrs_str(html_attrs) {
    if (html_attrs == null)
        return "";

    let res = "";
    for (const [key, value] of Object.entries(this.html_attrs))
        res += " " + key + "='" + value + "'";
    return res;
}

class EntitiesTable {
    constructor(type = null) {
        this.type = type;
    }

    get html() {
        if (this.type == null)
            return new Table(entities).html;

        let entities_filtered = [];
        for (let entity of entities) {
            if (entity["type"] == this.type) {
                entities_filtered.push(entity);
            }
        }
        return new Table(entities_filtered).html;
    }
}

class Button {
    constructor(text = "", html_attrs = null) {
        this.text = text;
        this.html_attrs = html_attrs;
    }

    get html() {
        return "<button" + get_html_attrs_str(this.html_attrs) + "><p>" + text + "</p><button>";
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
    constructor(table) {
        this.table = table;
    }

    get html() {
        let html = "<table>";
        for (let row of this.table) {
            html += "<tr>";
            for (let col of Object.values(row))
                html += "<td>" + col + "</td>";
            html += "</tr>";
        }
        html += "</table>";
        return html;
    }
}