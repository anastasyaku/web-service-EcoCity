class AbstractMark {
    constructor(data, ymaps, collection) {
        if (new.target === AbstractMark) {
            throw new TypeError("Нельзя создать объект абстрактного класса AbstractMark");
        }
        this.data = data;
        this.mapObject = new ymaps.Placemark(
            [data.latitude, data.longitude],
            { balloonContentBody: DISPATCH ? this.getDispatcherHtml() : this.getVisitorHtml() },
            { iconLayout: 'default#image', iconImageSize: [50, 71], iconImageHref: this.getPreset() }
        );
        collection.add(this.mapObject);
    }
}

const RECYCLABLE_CATEGORIES_NAMES = {
    Cloth: "Одежда",
    Glass: "Стеклотара и алюминий",
    Plastic: "Пластик",
    Paper: "Макулатура",
    Scrap: "Металлолом",
    Tech: "Бытовая техника",
    Batteries: "Аккумуляторы",
    Bulbs: "Ртутные лампы",
}

class RecyclableMark extends AbstractMark {
    getPreset() {
        return `assets/img/marks/recyclables/${this.data.category.toLowerCase()}.png`;
    }

    getVisitorHtml() {
        return `
            <div class="mark-details">
                <div>
                    <header>
                        <h2>${RECYCLABLE_CATEGORIES_NAMES[this.data.category]}</h2>
                        ${this.data.address}
                    </header>
                    ${this.data.name}</br>
                    ${this.data.description || ''}
                </div>
            </div>`;
    }

    getDispatcherHtml() {
        return `
            <div class="mark-details" id="can-${this.data.id}">
                <div>
                   <header>
                        <header>
                        <h2>${this.data.category}</h2>
                        ${this.data.address}
                    </header>
                    ${this.data.name}</br>
                    ${this.data.description}
                    <div class="controls">
                        <button title="Удалить" class="image-button" onclick="deleteMark(${this.data.id}, 'recyclable')"><img src="assets/img/delete.png"></button>
                    </div>
                </div>
            </div>`;
    }
}

const MARK_STATUS_TO_COLOR = Object.freeze({
    ACTIVE: 'red',
    IN_PROGRESS: 'yellow',
    DONE: 'darkGreen'
});

const MARK_CLASSES = Object.freeze({
    TrashMark: class extends AbstractMark {
        getPreset() {
            return `/assets/img/marks/trash/${this.data.status}.png`;
        }

        getVisitorHtml() {  // TODO: Geocoding
            return `
            <div class="mark-details" style="flex-direction: column">
                <h2>${this.data.address}</h2>
                ${this.data.created_at}
            </div>`;
        }

        getDispatcherHtml() {
            return `
            <div class="mark-details">
                <img src="storage/${this.data.photo_file}">
                <div>
                    <header>
                        <a href="#">
                            <h2>${this.data.address || "(Не указан)"}</h2>
                        </a>
                    </header>
                    ${this.data.created_at}<br>
                    <b>Комментарий:</b>${this.data.description || "<i>Отсутствует</i>"}
                    <small>ID отправителя: ${this.data.sender_id}</small>
                    <div class="controls" mark-type="trash" mark-id="${this.data.id}">
                        <select id="mark-status-select-${this.data.id}" onchange="setMarkStatus()">
                            <option value="ACTIVE" ${this.data.status == 'ACTIVE' ? 'selected' : ''}>\u{01f534} Активна</option>
                            <option value="IN_PROGRESS" ${this.data.status == 'IN_PROGRESS' ? 'selected' : ''}>\u{01f7e1} В процессе</option>
                            <option value="DONE" ${this.data.status == 'DONE' ? 'selected' : ''}>\u{01f7e2} Выполнено</option>
                        </select>
                        <button title="Удалить" class="image-button" onclick="deleteMark(${this.data.id})">
                            <img src="assets/img/delete.png">
                        </button>
                        <button title="Заблокировать отправителя" class="image-button" onclick="blockSender(${this.data.sender_id})">
                            <img src="assets/img/block.png">
                        </button>
                    </div>
                </div>
            </div>`;
        }
    },

    EventMark: class extends AbstractMark {
        getPreset() {
            return 'assets/img/marks/event.png';
        }

        getVisitorHtml() {
            return `
            <div class="mark-details">
                <img src="storage/${this.data.photo_file}">
                <div>
                    <header>
                        <h2>${this.data.name}</h2>
                        ${this.data.address}
                    </header>
                    ${this.data.due}
                    <br>
                    ${this.data.description}
                    <br>
                    ${this.data.site}
                </div>
            </div>`;
        }

        getDispatcherHtml() {
            return `
            <div class="mark-details" id="event-${this.data.id}">
                <img src="storage/${this.data.photo_file}">
                <div>
                    <header>
                        <h2>${this.data.name}</h2>
                        ${this.data.address}
                    </header>
                    ${this.data.due}
                    <br>
                    ${this.data.description}
                    <br>
                    ${this.data.site}
                    <div class="controls">
                        <button title="Удалить" class="image-button" onclick="deleteMark(${this.data.id}, 'event')"><img src="assets/img/delete.png"></button>
                    </div>
                </div>
            </div>`;
        }
    },

    DumpsterMark: class extends AbstractMark {
        getPreset() {
            let prefix = this.data.full ? "ACTIVE" : "empty";
            return `assets/img/marks/trash/${prefix}.png`;
        }

        getVisitorHtml() {
            return `
            <div class="mark-details">
                <div>
                    <header>
                        <h2>Мусорка</h2>
                        ${this.data.address}
                    </header>
                    ${this.data.owner}
                </div>
            </div>`;
        }

        getDispatcherHtml() {
            return `
            <div class="mark-details" id="trash-container-${this.data.id}">
                <div>
                    <header>
                        <h2>Мусорка</h2>
                        ${this.data.adress}
                    </header>
                    ${this.data.owner}
                    <div class="controls">
                        <button title="Удалить" class="image-button" onclick="deleteMark(${this.data.id}, 'trash-container')"><img src="assets/img/delete.png"></button>
                    </div>
                </div>
            </div>`;
        }
    },

    ClothMark: class extends RecyclableMark {
        CATEGORY = "Cloth";
    },

    GlassMark: class extends RecyclableMark {
        CATEGORY = "Glass";
    },

    PlasticMark: class extends RecyclableMark {
        CATEGORY = "Plastic";
    },

    PaperMark: class extends RecyclableMark {
        CATEGORY = "Paper";
    },

    ScrapMark: class extends RecyclableMark {
        CATEGORY = "Scrap";
    },

    TechMark: class extends RecyclableMark {
        CATEGORY = "Tech";
    },

    BatteriesMark: class extends RecyclableMark {
        CATEGORY = "Batteries";
    },

    BulbsMark: class extends AbstractMark {
        CATEGORY = "Bulbs";
    },
});

function createMark(markData, ymaps, collection) {
    let markClass = markData.type;
    if (markClass == "RecyclableMark") {
        markClass = markData.category + "Mark";
    }
    return new MARK_CLASSES[markClass](markData, ymaps, collection);
}
