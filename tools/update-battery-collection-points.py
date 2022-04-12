#!/usr/bin/env python3
import os
import re

import click
import requests
from bs4 import BeautifulSoup
import pymysql.cursors


@click.command()
@click.option("-u", "--db-user", default="cr71175_cleanmap")
@click.option("-p", "--db-password", default="cleanmap")
@click.option("-d", "--db-name", default="cr71175_cleanmap")
def main(db_user, db_password, db_name):
    connection = pymysql.connect(
        host="localhost",
        user=db_user,
        password=db_password,
        db=db_name,
        charset="utf8mb4",
        cursorclass=pymysql.cursors.DictCursor
    )

    with connection.cursor() as cursor, requests.Session() as s:
        cursor.execute("DELETE FROM marks WHERE category='BATTERIES'")

        html = requests.get("http://eco2eco.ru/map").text
        assert "ymaps.ready(init);" in html
        for match in re.finditer(
            r"Placemark\(\[([\d.]*),([\d.]*)\], ?{balloonContentHeader: '([^']*?)',balloonContentFooter: 'Магнитогорск, ([^']*?)',hintContent: '([^']*?)'}",
            re.sub(r" {2,}|\n|\t", "", html)
        ):
            latitude, longitude, name, address, _ = match.groups()
            cursor.execute(
                "INSERT INTO marks (type, category, latitude, longitude, name, address) VALUES ('App\\\\RecyclableMark', 'BATTERIES', %s, %s, %s, %s)",
                (float(latitude), float(longitude), name, address)
            )

    connection.commit()
    connection.close()


if __name__ == '__main__':
    main()
