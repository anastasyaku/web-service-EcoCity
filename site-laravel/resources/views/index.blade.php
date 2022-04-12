@extends('map-base')

@section('head')
    @parent

    <script src="/assets/js/visit.js"></script>

    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/instructions.css">
    <link rel="stylesheet" href="assets/css/mark.css">
@endsection

@section('body')
    <!-- Модальное окно -->
    <div class="modal fade" id="manual-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <h1 class="modal-title pl-3">Инструкция пользователя</h1>
                <div class="modal-body">
                    <div id="page-content">
                        <p>Данный сервис предназначен для отправки жалоб о несанкционированных свалках, а также для информирования городского населения об экологических объектах.</p>
                        <h2>Шаг 1</h2>
                        <p>Установите <a href='TODO' download>приложение ЭкоМагнитогорск</a></p>
                        <h2>Шаг 2</h2>
                        <p>Отправьте жалобу через приложение </p>
                        <h2>Шаг 3</h2>
                        <p>Меняй фильтры на карте, чтобы отобразить больше меток</p>
                        <img width='200px' src="assets/img/filter.png">
                        <h2>Шаг 4</h2>
                        <p>Нажми на метку, чтобы узнать подробную информацию</p>
                        <img width='200px' src="assets/img/mark_info.png">
                        <h2>Шаг 5</h2>
                        <p>Следи за мероприятиями</p>
                        <img width='200px' src="assets/img/ev.png">
                        <h2>Шаг 6</h2>
                        <p>Отправь отзыв о работе сервиса :)</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <div id="map">
        <div id="map-spinner">
            <div class="spinner-border text-success" role="status"></div>
        </div>
        <x-map-mark-class-selector />
    </div>
    <div id="mark-selection-mobile" class="card mark-controls shadow-lg"></div>
    <!-- Page Content -->
    <div id="page-content">
<!--
        <section id="banner">
            <div class="block has-dark-background background-color-default-darker center text-banner">
                <div class="container">
                    <h1 class="no-bottom-margin no-border">Здесь будет находиться баннерная реклама</h1>
                </div>
            </div>
        </section>
-->
        <section id="price-drop" class="block">
            <div class="container">
                <header class="section-title">
                    <strong>
                        <h2>Ближайшие мероприятия</h2>
                    </strong>
                    <a href="blog.php" class="link-arrow">Посмотреть все мероприятия</a>
                </header>
                <div class="row" id="row"></div>
            </div>
        </section>
        <hr>
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <section>
                        <h3>Наши отзывы</h3>
                        <div class="testimonials-carousel small">
                            <blockquote class="testimonial">
                                <figure>
                                    <div class="image">
                                        <img alt="" src="assets/img/client-01.jpg">
                                    </div>
                                </figure>
                                <aside class="cite">
                                    <p>Благодаря этому приложения я позволяю себе вносить свою долю в экологию моего дома</p>
                                    <footer>Анастасия Кухаренко</footer>
                                </aside>
                            </blockquote>
                            <blockquote class="testimonial">
                                <figure>
                                    <div class="image">
                                        <img alt="" src="assets/img/client-01.jpg">
                                    </div>
                                </figure>
                                <aside class="cite">
                                    <p>Большое спасибо разработчикам за такой неоценимый вклад в общее дело</p>
                                    <footer>Максим Малов</footer>
                                </aside>
                            </blockquote>
                        </div>
                    </section>
                </div>
                <div class="col-md-7">
                    <h3>Напишите нам сообщение!</h3>
                    <div class="agent-form">
                        <form method="post" action="/feedback" role="form" id="form-contact-agent" class="clearfix">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="form-contact-agent-name">Ваше имя<em>*</em></label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="form-contact-agent-email">Ваша почта<em>*</em></label>
                                        <input type="email" class="form-control" name="email" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="form-contact-agent-message">Ваше сообщение<em>*</em></label>
                                        <textarea class="form-control" rows="5" name="text" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group clearfix">
                                <button type="submit" class="btn pull-right btn-default" id="form-button">Отправить сообщение</button>
                            </div>
                            <div id="form-rating-status"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="new-mark-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Сообщить о несанкционированной свалке</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="/api/trash" id="new-mark-form--trash" class="new-mark-form" enctype="multipart/form-data">
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
                </div>
            </div>
        </div>
    </div>
@endsection
