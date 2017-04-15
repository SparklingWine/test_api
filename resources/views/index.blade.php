<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Логи приложения</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css" rel="stylesheet">

    <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <style>
        table form { margin-bottom: 0; }
        form ul { margin-left: 0; list-style: none; }
        .error { color: red; font-style: italic; }
        body { padding-top: 20px; }
    </style>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable({
                "order": [[ 6, "desc" ]],
                "language": {
                    "processing": "Подождите...",
                    "search": "Поиск:",
                    "lengthMenu": "Показать _MENU_ записей",
                    "info": "Записи с _START_ до _END_ из _TOTAL_ записей",
                    "infoEmpty": "Записи с 0 до 0 из 0 записей",
                    "infoFiltered": "(отфильтровано из _MAX_ записей)",
                    "infoPostFix": "",
                    "loadingRecords": "Загрузка записей...",
                    "zeroRecords": "Записи отсутствуют.",
                    "emptyTable": "В таблице отсутствуют данные",
                    "paginate": {
                        "first": "Первая",
                        "previous": "Предыдущая",
                        "next": "Следующая",
                        "last": "Последняя"
                    },
                    "aria": {
                        "sortAscending": ": активировать для сортировки столбца по возрастанию",
                        "sortDescending": ": активировать для сортировки столбца по убыванию"
                    }

                },
                "initComplete": function( settings, json ) {
                    //$('div.loading').remove();
                    $('.col-sm-5:first').addClass('col-sm-4').removeClass('col-sm-5');
                    $('.col-sm-7:first').addClass('col-sm-8').removeClass('col-sm-7');
                }
            });
        });

    </script>
</head>
<body>
<div class="container">
    @yield('content')

    <h2>Отображение последних записей из логов</h2>

    <table id="example" class="display" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>ID записи</th>
            <th>ID пользователя</th>
            <th>Имя пользователя</th>
            <th>Версия API</th>
            <th>Внутренний IP</th>
            <th>Службы</th>
            <th>Дата создания</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>ID записи</th>
            <th>ID пользователя</th>
            <th>Имя пользователя</th>
            <th>Версия API</th>
            <th>Внутренний IP</th>
            <th>Службы</th>
            <th>Дата создания</th>
        </tr>
        </tfoot>
        <tbody>

        @foreach($logEntries as $logEntry)
            <tr>
                <td>{{$logEntry['id']}}</td>
                <td>{{$logEntry['user_id']}}</td>
                <td>{{$logEntry['user_name']}}</td>
                <td>{{$logEntry['app_version']}}</td>
                <td>{{$logEntry['internal_ip']}}</td>

                <td>
                    {{ implode(',', array_column($logEntry['services']->toArray(), 'name')) }}

                </td>
                <td>{{$logEntry['created_at']}}</td>
            </tr>
        @endforeach
        <tbody>
    </table>
</div>
</body>
</html>