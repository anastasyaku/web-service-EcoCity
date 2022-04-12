<footer>
    <columns>
        <column>
            <h3>Проект "ЭкоМагнитка"</h3>

            <p>Проект разработан при поддержке Фонда Содействия Инновациям по программе "Умник-Сбербанк"  и представляет собой платформу для поддержания экологической обстановки в Магнитогорске.</p>
            <hr>
            <p><a href="http://fasie.ru/">Подробнее...</a></p>
        </column>
        <column>
            <h3>Наши партнёры</h3>

            <a href="http://kvantorium.su">
                <partner>
                    <img src="assets/img/kvant.jpg">
                    <partner-details>
                        <h4>Кванториум</h4>
                        <address>ул. Ленина 36а</address>
                    </partner-details>
                </partner>
            </a>
            <a href="https://magtu.ru">
                <partner>
                    <img src="assets/img/mgtu.png">
                    <partner-details>
                        <h4>МГТУ им. Носова</h4>
                        <address>ул. Ленина 36а</address>
                    </partner-details>
                </partner>
            </a>
        </column>
        <column>
            <h3>Контакты</h3>

            <small>Единая диспетчерская служба:</small>
            <h5>+7-908-818-96-54</h5>

            <p>
                ул. Ленина 36а
                <a href="mailto:ecomgn@example.com">ecomgn@example.com</a>
            </p>
        </column>
        <column>
            <h3>Полезные ссылки</h3>

            <links>
                <a href="https://www.magnitogorsk.ru/">Сайт г. Магнитогорска</a>

                <a href="http://fasie.ru/">Фонд содействия инновациям </a>
            </links>
        </column>
    </columns>
    <footer>
        <copyright>© {{ date('Y') }}. Все права защищены.</copyright>
        @isset($mapElemId)
            <a href="{{ $mapElemId }}">К карте</a>
        @else
            <null></null>
        @endisset
    </footer>
</footer>
