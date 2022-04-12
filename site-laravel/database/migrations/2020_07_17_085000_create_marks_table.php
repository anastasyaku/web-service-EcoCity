<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->double("latitude")
                ->nullable(true)
                ->comment("Географическая широта метки.");

            $table->double("longitude")
                ->nullable(true)
                ->comment("Географическая долгота метки.");

            $table->string("address", 100)
                ->nullable(true)
                ->comment("Адрес улицы метки.");

            $table->string("description", 500)
                ->nullable(true)
                ->comment("Комментарий.");

            $table->string("photo_file", 100)
                ->nullable(true)
                ->comment("Путь к файлу фотографии.");

            $table->string("type")
                ->nullable(true)
                ->comment('Тип метки.');

            // Колонки для свалок

            $table->unsignedBigInteger("sender_id")
                ->nullable(true)
                ->comment("[Свалка] Идентификатор пользователя.");

            $table->enum("status", ["ACTIVE", "IN_PROGRESS", "DONE"])
                ->default("ACTIVE")
                ->nullable(true)
                ->comment("[Свалка] Статус заявки: активная / в процессе решения / решено.");

            // Колонки для событий

            $table->string("name", 100)
                ->nullable(true)
                ->comment("[Событие/Пункт приема] Название мероприятия или пункта приема.");

            $table->dateTime("due")
                ->nullable(true)
                ->comment("[Событие] Дата события.");

            $table->string("site", 500)
                ->nullable(true)
                ->comment("[Событие] Ссылка на сайт мероприятия.");

            // Колонки для пунктов приема

            $table->enum(
                "category",
                [
                    "Cloth",
                    "Glass",
                    "Plastic",
                    "Paper",
                    "Scrap",
                    "Tech",
                    "Batteries",
                    "Bulbs"
                ]
            )
                ->nullable(true)
                ->comment("[Пункт приема] Тип пункта приема.");

            $table->string("phone", 50)
                ->nullable(true)
                ->comment("[Пункт приема] Телефон пункта приема.");

            $table->string("email", 50)
                ->nullable(true)
                ->comment("[Пункт приема] Электронная почта пункта приема.");

            // Колонки для мусорных контейнеров

            $table->string("owner", 300)
                ->nullable(true)
                ->comment("[Контейнер] Компания-владелец контейнера.");

            $table->boolean("full")
                ->nullable(true)
                ->default(false)
                ->comment("[Контейнер] Заполнен ли контейнер?");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marks');
    }
}
