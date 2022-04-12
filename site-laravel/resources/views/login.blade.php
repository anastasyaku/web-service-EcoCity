@extends('base')

@section('head')
    <link rel="stylesheet" href="assets/css/login.css">
@endsection

@section('body')
    <div id="page-content">
        <div class="container">
            <div class="col-md-4 col-sm-6 mx-auto">
                <header>
                    <h1>Войти в систему</h1>
                </header>

                @isset($failed)
                    <div class="alert alert-danger">
                        Неверный логин или пароль.
                    </div>
                @endisset

                <form role="form" id="form-create-account" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="form-create-account-email">Логин</label>
                        <input class="form-control" name="name" id="form-create-account-email" required>
                    </div>
                    <div class="form-group">
                        <label for="form-create-account-password">Пароль</label>
                        <input type="password" class="form-control" name="password" id="form-create-account-password" required>
                    </div>
                    <div class="form-group clearfix">
                        <button type="submit" class="btn pull-right btn-default" id="account-submit">Войти в аккаунт</button>
                    </div>
                </form>
                <hr>
                <div class="center"><a href="#">Я не помню свой пароль :С</a></div>
            </div>
        </div>
    </div>
@endsection
