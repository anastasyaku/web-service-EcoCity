"""Скрипт, обновляющий состояние атмосферы на месяц."""

import re

import requests
from bs4 import BeautifulSoup


def get_status_page_url():
    """Получает URL страницы со статусом атмосферы с http://chelpogoda.ru (вдруг URL сменится?)."""
    response = requests.get("http://chelpogoda.ru")
    soup = BeautifulSoup(response.text, "html.parser")
    a_string = soup.find(string="Состояние атмосферного воздуха за месяц")
    a_tag = a_string.parent
    a_href = a_tag.href
    return "http://chelpogoda.ru" + a_href


def get_status_page_text():
    """Получает текст страницы со статусом атмосферы."""
    return requests.get(get_status_page_url()).text.replace("*", "")


def get_magnitogorsk_text():
    """Извлекает абзац про Магнитогорск из текста, получаемого :func:`get_status_page_text`."""
    return re.search(r"г\. Магнитогорск(.*?)г\.Златоуст", get_status_page_text()).group(1)

# TODO
