#!/bin/sh

set -e

### Цветной вывод ###
error() {
    echo '[1;91m[!][0m' $*
    exit 1
}

info() {
    echo '[1;94m[i][0m' $*
}

done_() {
    echo '[1;92m[√][0m' $*
}
#####################
if [ "$(dirname $(realpath $0))" != "$HOME/trash" ]; then
    error "Репозиторий проекта должет быть склонирован в $HOME/trash."
fi

cd $HOME/trash

### Разбираемся с Python ###
which python3 || error 'Не установлен python3.'

install_pip() {
    # Ставим pip в ~/.local/bin
    curl https://bootstrap.pypa.io/get-pip.py | python3
    # Добавляем ~/.local/bin в $PATH, если его там нет
    if ! [[ $PATH =~ "$HOME/.local/bin" ]]; then
        echo 'export PATH=$PATH:$HOME/.local/bin' >> ~/.profile
    fi
    . ~/.profile  # Применяем изменения
}

which pip3 || {
    info 'Pip не установлен. Устанавливаю в ~/.local/bin...'
    install_pip
}

info 'Установка необходимых модулей Python...'
pip3 install --user -r tools/requirements.txt
done_ 'Модули установлены.'
############################

SYSTEMD_USER_UNITS_DIR=~/.config/systemd/user

info 'Установка таймеров для обновления реестра контейнерных площадок...'
mkdir -p $SYSTEMD_USER_UNITS_DIR
cp systemd-units/update-container-sites.* $SYSTEMD_USER_UNITS_DIR
systemctl --user daemon-reload
systemctl --user enable --now $(ls $SYSTEMD_USER_UNITS_DIR/update-container-sites.*)
done_ 'Готово.'

done_ 'Установка завершена.'
