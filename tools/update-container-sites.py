#!/usr/bin/env python3

from os import unlink
from collections import namedtuple

import requests
import openpyxl
import pymysql.cursors
import re
import click


def download_spreadsheet():
    response = requests.get("https://www.magnitogorsk.ru/storage/app/media/docs/gorodskoe-hozyajstvo/ZHKH/REESTR_KONTEJNERNYH_PLOShADOK.xlsx")
    response.raise_for_status()
    with open("registry.xlsx", "wb") as f:
        f.write(response.content)


DumpsterRecord = namedtuple("DumpsterRecord", "address latitude longitude owner")


class InsertedRowsCounter:
    def __init__(self):
        self._rows_inserted = 0

    def increment(self):
        self._rows_inserted += 1

    def finish(self):
        print(f"Готово, внесено {self._rows_inserted} контейнеров.")


def fix_coordinate_str(coord):
    return float(coord.replace(",", "."))


REDUNDANT_SPACES_REGEX = re.compile(" {2,}")

def fix_address_or_owner(string):
    return re.sub(REDUNDANT_SPACES_REGEX, " ", string).strip()


class Parser:
    def __init__(self, db_updater):
        self._worksheet = openpyxl.open("registry.xlsx").worksheets[0]
        self._db_updater = db_updater
        self._counter = InsertedRowsCounter()

        self._headers_defined = False
        self._address_col = None
        self._latitude_col = None
        self._longitude_col = None
        self._owner_col = None

    def parse(self):
        for row in self._worksheet.iter_rows():
            self._parse_row(row)
        self._finish()

    def _finish(self):
        self._db_updater.finish()
        self._counter.finish()

    def _parse_row(self, row):
        if self._is_header(row):
            self._update_data_columns(row)
        if self._is_dumpster_record(row):
            self._handle_record(row)

    def _is_header(self, row):
        try:
            self._find_col_with_text(row, "широта")
            return True
        except ValueError:
            return False

    def _is_dumpster_record(self, row):
        if not self._headers_defined:
            return False
        return row[self._address_col].value \
            and self._validate_coordinates(row) \
            and row[self._owner_col].value \
            and len(row[self._address_col].value) <= 100

    def _validate_coordinates(self, row):
        try:
            lat = fix_coordinate_str(row[self._latitude_col].value)
            long = fix_coordinate_str(row[self._longitude_col].value)
        except ValueError:
            return False
        except TypeError:
            return False
        except AttributeError:
            return False
        return 53 <= lat <= 55 and 58 <= long <= 60

    def _find_col_with_text(self, row, text):
        text = text.lower()
        for cell in row:
            if not isinstance(cell.value, str):
                continue
            if text in cell.value.lower():
                return cell.column - 1
        raise ValueError("не найдено: " + text)

    def _update_data_columns(self, row):
        self._headers_defined = True
        self._address_col = self._find_col_with_text(row, "адрес")
        # Да, они перепутали широту с долготой
        self._latitude_col = self._find_col_with_text(row, "долгота")
        self._longitude_col = self._find_col_with_text(row, "широта")
        self._owner_col = self._find_col_with_text(row, "полное наименование")

    def _handle_record(self, row):
        self._db_updater.insert(DumpsterRecord(
            fix_address_or_owner(row[self._address_col].value),
            fix_coordinate_str(row[self._latitude_col].value),
            fix_coordinate_str(row[self._longitude_col].value),
            fix_address_or_owner(row[self._owner_col].value),
        ))
        self._counter.increment()


class DumpsterDBUpdater:
    def __init__(self, db_user, db_password, db_name):
        self._connection = pymysql.connect(
            host="localhost",
            user=db_user,
            password=db_password,
            db=db_name,
            charset='utf8',
        )
        self._cursor = self._connection.cursor()

        self._delete_old()

    def _delete_old(self):
        self._cursor.execute("DELETE FROM marks WHERE type = 'App\\\\DumpsterMark'")

    def insert(self, record):
        self._cursor.execute(
            "INSERT INTO marks (type, address, latitude, longitude, owner) VALUES ('App\\\\DumpsterMark', %s, %s, %s, %s)",
            record
        )

    def finish(self):
        self._connection.commit()
        self._connection.close()


def convert(db_settings):
    Parser(DumpsterDBUpdater(**db_settings)).parse()


@click.command()
@click.option("-u", "--db-user", default="cr71175_cleanmap")
@click.option("-p", "--db-password", default="cleanmap")
@click.option("-d", "--db-name", default="cr71175_cleanmap")
def main(**kwargs):
    download_spreadsheet()
    convert(kwargs)
    unlink("registry.xlsx")


if __name__ == "__main__":
    main()
