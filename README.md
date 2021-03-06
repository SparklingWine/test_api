## О программе

Данное приложение является простым сервисом записи логов приложения в базу.

Состоит из двух частей:
### 1. API для записи в базу
Доступно по адресу: http://test_api/api/json (где test_api - доменное имя или ip-адрес, по которому располагается данное приложение)
Этот адрес необходимо прописать в настройках клиента (в случае с примером из архива с заданием - этот адрес нужно прописать в строчку "URL=...." в скрипте test.sh)
Клиент должен передавать данные POST-запросом в виде json-строки (в POST-параметре "data").

Приложение может возвращать ответы:
- В случае успешной записи отдаст код состояния HTTP **200** и сообщение "Log entry registered with id #xxx", где xxx - ID строчки из БД
- В случае некорректного JSON отдаст код **400** и сообщение типа "Not valid JSON string (Syntax Error)"
- В случае нехватающих таблиц или общих проблем с Mysql отдаст код **500** и сообщение "DB error"


Информация о процессах (services) заносится в отдельную таблицу и связана с таблицей записи логов через т.н. pivot-таблицу.
Если от клиента приходит информация с именами процессов, которых до этого не было в базе, информация о них обновляется в их таблице.


### 2. Вывод логов
Доступно по адресу: http://test_api/ (где test_api - доменное имя или ip-адрес, по которому располагается данное приложение)
Ввиду небольшого объёма тестовых данных реализовано с помощью js-библиотеки DataTables с подгрузкой всех данных сразу.
В целях упрощения задачи не использованы механизмы авторизованного доступа - страница открыта публично.

## Тестовые данные

Приложение содержит в себе модуль [Faker](https://github.com/fzaninotto/Faker "Faker"), которое изначально наполняет БД тестовыми данными, созданными на основе test.json из тестового задания.
Создаётся  100 записей логов (таблица "log_entries") и 100 имён процессов (таблица "services"), каждая запись логов может содержать от 1 до 10 процессов, что создаёт уже больше, чем 100 связей (pivot-таблица "log_entry_service").
 - **user_id** генерится по маске `#?######-#??#-#?##-?#?#-?#??######?#`, где "`#`"-цифра, а "`?`"-число (по аналогии со строчкой "`5e640061-0cb3-4e31-a1df-e3aa466400b2`" из test.json)
 - **user_name** генерирует рандомные английские имена с обращениями Mrs., Ms., Prof., Dr., и т.п.
 - **internal_ip** генерирует действительно внутренние локальные IP (если задача была понята правильно)
 - **app_version** генерируется в пределах regex `[0-1]\.[0-9]\.[1-9]` для удобства.

## Установка

После клонирования репозирория необходимо:
1. Настроить веб-сервер так, чтобы папка /public/ была корневой директорией проекта
2. Зайти в корневую папку приложения, создать там файл .env по образцу .env.example, лежащему там же, только прописав в новый файл .env свои данные доступа к Mysql
3. В файле /config/database.php изменить данные доступа к Mysql на свои
4. В корневой папке приложения из командной строки выполнить команды:
 * `composer self-update`
 * `php artisan key generate`
 * `php artisan optimize`
 * `php artisan migrate`
 * `php artisan db:seed`

Всё, приложение содержит тестовые данные, отдаёт их по адресу http://test_api/ (где test_api - ваш хост) и готово получать на вход данные от клиента.

## Тестирование

Возможно, будет удобнее тестировать приложение, используя curl вот таким образом:

 - curl --verbose --data-urlencode "data@$1" "$URL" -o "response.html"

В этом случае создаётся файл response.html, где можно посмотреть ответы сервера.
   
## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
