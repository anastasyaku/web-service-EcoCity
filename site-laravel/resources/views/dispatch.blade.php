@extends('map-base')

@section('head')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    <script src="assets/js/dispatch.js"></script>
    <script src="assets/js/dispatch-table.js"></script>
    <link rel="stylesheet" href="/assets/css/table.css">
@endsection

@section('body')
    <!-- Map -->
    <div id="map">
        <div id="map-spinner">
            <div class="spinner-border text-success" role="status"></div>
        </div>
        <x-map-mark-class-selector />
    </div>
    <div id="mark-selection-mobile" class="card mark-controls shadow-lg"></div>
    <!-- Page Content -->
    <div id="page-content">
        <div class="tab">
            <button class="tablinks" onclick="openTab('events')">Мероприятия</button>
            <button class="tablinks" onclick="openTab('trash')">Мусор</button>
            <button class="tablinks" onclick="openTab('feedback')">Отзывы</button>
            <button class="tablinks" onclick="openTab('cloth')">Одежда</button>
            <button class="tablinks" onclick="openTab('glass')">Стекло</button>
            <button class="tablinks" onclick="openTab('plastic')">Пластик</button>
            <button class="tablinks" onclick="openTab('paper')">Макулатура</button>
            <button class="tablinks" onclick="openTab('scrap')">Металлолом</button>
            <button class="tablinks" onclick="openTab('tech')">Бытовая техника</button>
            <button class="tablinks" onclick="openTab('batteries')">Батарейки</button>
            <button class="tablinks" onclick="openTab('bulbs')">Лампы</button>
        </div>

        <div id="tab_events" class="tabcontent" style="display: none">
            <h1>Мероприятия</h1>
            <table class="table_blur" id="events">
                <tr>
                    <th>Название</th>
                    <th>Время</th>
                    <th>Адрес</th>
                    <th>Описание</th>
                    <th>Фото</th>
                    <th></th>
                </tr>
                @foreach (App\EventMark::all() as $mark)
                    <tr>
                        <td>{{ $mark->name }}</td>
                        <td>{{ $mark->created_at }}</td>
                        <td>{{ $mark->address }}</td>
                        <td>{{ $mark->description }}</td>
                        <td>
                            <div class='td-img-center-wrapper'>
                                <img src="/storage/{{ $mark->photo_file }}">
                            </div>
                        </td>
                        <td>
                            <button title='Удалить' class='image-button' onclick="deleteObject('{{ $mark->id }}', 'event')">
                                <img src='assets/img/delete.png'>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div id="tab_trash" class="tabcontent" style="display: none">
            <h1>Мусор</h1>
            <table>
                <tr>
                    <th>Время</th>
                    <th>Адрес</th>
                    <th>Описание</th>
                    <th>Фото</th>
                    <th>Статус</th>
                    <th>ID отправителя</th>
                    <th></th>
                    <th></th>
                </tr>
                @foreach (App\TrashMark::all() as $mark)
                    <tr>
                        <td>{{ $mark->created_at }}</td>
                        <td>{{ $mark->address }}</td>
                        <td>{{ $mark->description }}</td>
                        <td>
                            <div class='td-img-center-wrapper'>
                                <img src="/storage/{{ $mark->photo_file }}">
                            </div>
                        </td>
                        <td>{{ $mark->status }}</td>
                        <td>{{ $mark->sender_id }}</td>
                        <td>
                            <button title='Удалить' class='image-button' onclick="deleteObject('{{ $mark->id }}', 'trash')">
                                <img src='assets/img/delete.png'>
                            </button>
                        </td>
                        <td>
                            <button title='Заблокировать отправителя' class='image-button' onclick="blockSender('{{ $mark->id }}')">
                                <img src='assets/img/block.png'>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div id="tab_feedback" class="tabcontent" style="display: none">
            <h1>Отзывы</h1>
            <table class="table_blur" id="feedbacks">
                <tr>
                    <th>Имя</th>
                    <th>Адрес</th>
                    <th>Отзыв</th>
                    <th></th>
                </tr>
                @foreach (App\Feedback::all() as $feedback)
                    <tr>
                        <td>{{ $feedback->name }}</td>
                        <td>{{ $feedback->email }}</td>
                        <td>{{ $feedback->text }}</td>
                        <td>
                            <button title='Удалить' class='image-button' onclick="deleteObject('{{ $feedback->id }}', 'messages')">
                                <img src='assets/img/delete.png'>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>

        @foreach ($recyclablesTitles as $recyclableCategory => $recyclableTitle)
            <div id="tab_{{ strtolower($recyclableCategory) }}" class="tabcontent" style="display: none">
                <h1>Пункты приема {{ $recyclableTitle }}</h1>
                <table class="table_blur" id="{{ strtolower($recyclableCategory) }}">
                    <tr>
                        <th>Название</th>
                        <th>Адрес</th>
                        <th>Категория</th>
                        <th>Телефон</th>
                        <th>E-Mail</th>
                    </tr>
                    @foreach (App\RecyclableMark::where("category", $recyclableCategory)->get() as $mark)
                        <tr>
                            <td>{{ $mark->name }}</td>
                            <td>{{ $mark->address }}</td>
                            <td>{{ $mark->category }}</td>
                            <td>{{ $mark->phone }}</td>
                            <td>{{ $mark->email }}</td>

                            <td>
                                <button title='Удалить' class='image-button' onclick="deleteObject('{{ $mark->id }}', 'recyclable_marks')">
                                    <img src='assets/img/delete.png'>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endforeach
    </div>

    <div class="modal fade" id="new-mark-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Новая метка</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div>Тип метки</div>
                    <select onchange="newMarkFormOnChangeType($(this))">
                        <option value="event" selected>Мероприятие</option>
                        <option value="trash">Несанкционированная свалка</option>
                        <option value="cloth">Одежда</option>
                        <option value="glass">Стекло</option>
                        <option value="plastic">Пластик</option>
                        <option value="paper">Макулатура</option>
                        <option value="scrap">Металлолом</option>
                        <option value="tech">Бытовая техника</option>
                        <option value="batteries">Батарейки</option>
                        <option value="bulbs">Лампы</option>
                    </select>

                    <form method="post" action="/api/events" id="new-mark-form--event" class="new-mark-form" enctype="multipart/form-data">
                        @csrf
                        <div>Название</div>
                        <input required name="name">

                        <div>Дата и время</div>
                        <input type="datetime-local" required name="datetime">

                        <input type="hidden" name="latitude">
                        <input type="hidden" name="longitude">

                        <div>Адрес</div>
                        <input required name="address">

                        <div>Описание</div>
                        <textarea name="description"></textarea>

                        <div>URL</div>
                        <textarea name="url"></textarea>

                        <div>Фотография</div>
                        <input type="file" name="photo">

                        <null></null>
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    </form>

                    <form method="post" action="/api/trash" id="new-mark-form--trash" class="new-mark-form" enctype="multipart/form-data" style="display: none">
                        @csrf
                        <null></null>
                        <input type="hidden" name="type" value="UNAUTHORIZED_DUMP">

                        <input type="hidden" name="latitude">
                        <input type="hidden" name="longitude">

                        <div>Адрес</div>
                        <input required name="address">

                        <div>Описание</div>
                        <textarea name="description"></textarea>

                        <div>Фотография</div>
                        <input type="file" name="photo">

                        <null></null>
                        <input type="hidden" name="sender_id" value="0">

                        <null></null>
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    </form>

                    <form method="post" action="/api/marks" id="new-mark-form--cloth" class="new-mark-form" enctype="multipart/form-data" style="display: none">
                        @csrf
                        <input type="hidden" name="type" value="App\RecyclableMark">
                        <input type="hidden" name="category" value="Cloth">

                        <div>Название</div>
                        <input required name="name">

                        <input type="hidden" name="latitude">
                        <input type="hidden" name="longitude">

                        <div>Адрес</div>
                        <input required name="address">

                        <div>Телефон</div>
                        <input name="phone">

                        <div>Эл. почта</div>
                        <input name="email" type="email">

                        <null></null>
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    </form>

                    <form method="post" action="/api/marks" id="new-mark-form--glass" class="new-mark-form" enctype="multipart/form-data" style="display: none">
                        @csrf
                        <input type="hidden" name="type" value="App\RecyclableMark">
                        <input type="hidden" name="category" value="Glass">

                        <div>Название</div>
                        <input required name="name">

                        <input type="hidden" name="latitude">
                        <input type="hidden" name="longitude">

                        <div>Адрес</div>
                        <input required name="address">

                        <div>Телефон</div>
                        <input name="phone">

                        <div>Эл. почта</div>
                        <input name="email" type="email">

                        <null></null>
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    </form>

                    <form method="post" action="/api/marks" id="new-mark-form--plastic" class="new-mark-form" enctype="multipart/form-data" style="display: none">
                        @csrf
                        <input type="hidden" name="type" value="App\RecyclableMark">
                        <input type="hidden" name="category" value="Plastic">

                        <div>Название</div>
                        <input required name="name">

                        <input type="hidden" name="latitude">
                        <input type="hidden" name="longitude">

                        <div>Адрес</div>
                        <input required name="address">

                        <div>Телефон</div>
                        <input name="phone">

                        <div>Эл. почта</div>
                        <input name="email" type="email">

                        <null></null>
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    </form>

                    <form method="post" action="/api/marks" id="new-mark-form--paper" class="new-mark-form" enctype="multipart/form-data" style="display: none">
                        @csrf
                        <input type="hidden" name="type" value="App\RecyclableMark">
                        <input type="hidden" name="category" value="Paper">

                        <div>Название</div>
                        <input required name="name">

                        <input type="hidden" name="latitude">
                        <input type="hidden" name="longitude">

                        <div>Адрес</div>
                        <input required name="address">

                        <div>Телефон</div>
                        <input name="phone">

                        <div>Эл. почта</div>
                        <input name="email" type="email">

                        <null></null>
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    </form>

                    <form method="post" action="/api/marks" id="new-mark-form--scrap" class="new-mark-form" enctype="multipart/form-data" style="display: none">
                        @csrf
                        <input type="hidden" name="type" value="App\RecyclableMark">
                        <input type="hidden" name="category" value="Scrap">

                        <div>Название</div>
                        <input required name="name">

                        <input type="hidden" name="latitude">
                        <input type="hidden" name="longitude">

                        <div>Адрес</div>
                        <input required name="address">

                        <div>Телефон</div>
                        <input name="phone">

                        <div>Эл. почта</div>
                        <input name="email" type="email">

                        <null></null>
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    </form>

                    <form method="post" action="/api/marks" id="new-mark-form--tech" class="new-mark-form" enctype="multipart/form-data" style="display: none">
                        @csrf
                        <input type="hidden" name="type" value="App\RecyclableMark">
                        <input type="hidden" name="category" value="Tech">

                        <div>Название</div>
                        <input required name="name">

                        <input type="hidden" name="latitude">
                        <input type="hidden" name="longitude">

                        <div>Адрес</div>
                        <input required name="address">

                        <div>Телефон</div>
                        <input name="phone">

                        <div>Эл. почта</div>
                        <input name="email" type="email">

                        <null></null>
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    </form>

                    <form method="post" action="/api/marks" id="new-mark-form--batteries" class="new-mark-form" enctype="multipart/form-data" style="display: none">
                        @csrf
                        <input type="hidden" name="type" value="App\RecyclableMark">
                        <input type="hidden" name="category" value="Batteries">

                        <div>Название</div>
                        <input required name="name">

                        <input type="hidden" name="latitude">
                        <input type="hidden" name="longitude">

                        <div>Адрес</div>
                        <input required name="address">

                        <div>Телефон</div>
                        <input name="phone">

                        <div>Эл. почта</div>
                        <input name="email" type="email">

                        <null></null>
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    </form>

                    <form method="post" action="/api/marks" id="new-mark-form--bulbs" class="new-mark-form" enctype="multipart/form-data" style="display: none">
                        @csrf
                        <input type="hidden" name="type" value="App\RecyclableMark">
                        <input type="hidden" name="category" value="Bulbs">

                        <div>Название</div>
                        <input required name="name">

                        <input type="hidden" name="latitude">
                        <input type="hidden" name="longitude">

                        <div>Адрес</div>
                        <input required name="address">

                        <div>Телефон</div>
                        <input name="phone">

                        <div>Эл. почта</div>
                        <input name="email" type="email">

                        <null></null>
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
