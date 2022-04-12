<header>
    <a href="/" class="logo">
        <h1>Эк</h1>
        <img src="assets/img/icon.png" width="50px">
        <h1>Магнитка</h1>
    </a>

    @auth
        <div>
            Вы вошли как <strong>{{ Auth::user()->name }}</strong> | <a href="/logout">Выйти</a>
        </div>
    @else
        <div style="font-size: small">
            <a><strong>Поможем городу стать лучше</strong></a>
            <br>
            <a><strong>путём общих усилий!</strong></a>
        </div>
        <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#manual-modal">
            Инструкция
        </button>
    @endauth
</header>
