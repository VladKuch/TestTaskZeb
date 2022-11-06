<h2>Тестовое задание</h2>

<b>Nginx, PHP 8, Phalcon 5(фреймвёрк), MySQL 8.</b>

В качестве авторизации для выполнения API запросов используется Basic Auth: username=zebra, password=qwerty123.<br>
<pre>
     GET: http://localhost/api/tendеr/get/{Номер} - Получить тендер по номеру.
     GET: http://localhost/api/tendеr/fetch?name=Название&date=05.11.2022&order=desc - Получить список всех тендеров.
          Допускается использование query-параметров name и date для фильтрации по названию и дате соответственно.
          Также можно использовать query параметр order для сортировки по дате изменеия. Значения: asc и desc. По умолчанию asc.
     POST http://localhost/api/tendеr/add - Добавить новый тендер.
          POST параметры: number(Номер - обязательный параметр), status (Статус- имеет варианты 'Открыто', 'Закрыто', 'Отменено' - значение по умолчанию 'Открыто'), 
          name(Название - обязательный параметр)
     POST http://localhost/api/tendеr/import - Импорт тендоров из CSV файла. Формат файда как test_task_data.csv
</pre>
