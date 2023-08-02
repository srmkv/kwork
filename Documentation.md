## QUALIFITERRA.RU
![alt text](https://qualifiterra.ru/img/logo.svg)

Общие ссылки проекта

- [Боевой стенд](https://qualifiterra.ru).
- [Дев 1](https://dev.qualifiterra.ru)
- [Дев 2](https://front.qualifiterra.ru)
- [ТЗ 1](https://docs.google.com/document/d/1-qq1Eyr8Cb4lq22lcXfoxzufMOwDYTTxFk8Kz55uPHc/edit#heading=h.7fdlydq774jr)
- [ТЗ 2](https://docs.google.com/document/d/1TZEKB0t-a_Wz1_VVNBQW91BYOQGWSMG6CitUNXMlhWc/edit#heading=h.7fdlydq774jr)
- [ТЗ 3](https://docs.google.com/document/d/1GqNXC5PtQhu5fWHrnLJ2YtIM_K1w2XPtD2XKW-ktoSE/edit#heading=h.r8mu0ss4bs4m)
- [ТЗ 4](https://docs.google.com/document/d/1OybBPCMImZkilf_Y5ayykbtYrJAcAAnjPWDHkosd6Hw/edit)
- [Свалка всех макетов](https://www.figma.com/file/ZWJ5Qhg3ePVnO9ObVOehE5/Квалифитера-Дизайн?t=IuZBrysnZsP6oPLa-0)
- [Документация api postman](https://qualifiterra.postman.co/home)

Документация на бесплатном командном тарифе для 3х участников (И.Загорин, А.Колесников, Е.Трегубенко), актуальная версия предоствляюется по запросу(на сегодня это 1.16)

### Команда
- Беляков Павел - pm
- Артем Колесников - back dev
- Егор Трегубенко - back dev

### Развернуть проект :

Основная ветка в гите master, ответвляемся и клонируем от неё
в корне проекта прописываем стандартные команды для laravel/php

```bash
composer install
npm install && npm run dev
php artisan migrate
```
### Дополнительные команды после установки
В проекте множество сидов и фабрик и консольных команд, ниже примеры некоторых из них

```bash
# очистка устаревших конвертированных изоражений 
php artisan media-library:clean
# создание параметров для фильтров и каталога
php artisan redis:createBitMapCatalog
# сокеты работают через супервизор, но отдельно запустить можно так:
php artisan websockets:serve
# очистка устаревших пинов/юзеров (на кроне)
php artisan common:clearing
```


