let map = null;
let _mapLoadCallbacks = [];

const DEFAULT_SHOWN_MARKS = [
    "TrashMark-",
    "EventMark-",
];

/**
 * Выполняет настройку карты с помощью Yandex Maps SDK.
 */
function mapSetup() {
    return new Promise(resolve => {
        ymaps.ready(() => {
            $('#map-spinner, #map-spinner *').hide();
            const inputSearch = new ymaps.control.SearchControl();
            map = new ymaps.Map("map", {
                center: [53.38709910397884, 58.98567106587102],
                zoom: 11,
                controls: ['geolocationControl', inputSearch, 'typeSelector', 'zoomControl']
            });

            // Меняем местами элементы ymaps и панель меток в #map
            let ymapsElement = $("#map > ymaps");
            let mapControls = $("#map > div");
            mapControls.insertAfter(ymapsElement);

            _mapLoadCallbacks.forEach(cb => cb());

            DEFAULT_SHOWN_MARKS.forEach(id => $(`#checkbox-${id}`).prop("checked", true).triggerHandler('change'));
            $(".mark-controls").show();
            resolve();
        });
    });
}

function callAfterMapLoad(cb) {
    if (map === null)
        _mapLoadCallbacks.push(cb);
    else
        cb();
}

const marks = {};
const markCollections = {};

/**
 * Получает список меток с сервера.
 */
async function fetchMarks(type, category) {
    const groupId = type + category;
    if (markCollections[groupId])
        return;
    markCollections[groupId] = new ymaps.Collection();
    let fetchUrl = '/api/marks?mark-type=' + type;
    if (category.length)
        fetchUrl += '&category=' + category;
    return fetch(fetchUrl)
        .then(response => response.json())
        .then(markData => {
            marks[groupId] = markData.map(obj => createMark(obj, ymaps, markCollections[groupId]));
        });
}

function drawMarks(type, category) {
    fetchMarks(type, category).then(() => {
        map.geoObjects.add(markCollections[type + category]);
    });
}

function removeMarks(type, category) {
    map.geoObjects.remove(markCollections[type + category]);
}

$(() => {
    $(".mark-class-checkbox").change(event => {
        const [_, markType, markCategory] = event.target.id.split("-");
        if (event.target.checked)
            drawMarks(markType, markCategory);
        else
            removeMarks(markType, markCategory);
    });
    mapSetup();
});

let addPlacemark = null;

callAfterMapLoad(() => {
    map.events.add('click', e => {
        if (addPlacemark !== null)
            map.geoObjects.remove(addPlacemark);

        let coords = e.get('coords');
        addPlacemark = new ymaps.Placemark(
            coords, {
                iconContent: '<div class="new-mark-mark-content">+</div>',
                hintContent: window.location.href.includes('dispatch') ?
                    '<b>Добавить новую метку</b><br>Нажмите "+", чтобы добавить метку' :
                    '<b>Сообщить о несанкционированной свалке</b>' +
                    '<br>Нажмите "+", чтобы сообщить о несанкционированной свалке'
            }, {
                iconLayout: 'default#imageWithContent',
                iconImageHref: '/assets/img/brown-mark.svg',
                iconImageSize: [34, 41],
                iconImageOffset: [-9, -41],
            }
        );
        addPlacemark.events.add('click', () => {
            $('#new-mark-modal').modal('show');
        })
        map.geoObjects.add(addPlacemark);

        $("input[name=latitude]").val(coords[0]);
        $("input[name=longitude]").val(coords[1]);
    });
});
