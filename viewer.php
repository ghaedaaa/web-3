<?php
// Параметры подключения к БД
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'friends';

// Проверка параметров
if(!isset($_GET['pg']) || $_GET['pg'] < 0) {
    $_GET['pg'] = 0;
}
$page = (int)$_GET['pg'];
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'byid';

// Функция получения списка контактов
function getFriendsList($sort, $page) {
    global $host, $user, $password, $database;
    
    // Подключение к БД
    $mysqli = mysqli_connect($host, $user, $password, $database);
    
    if(mysqli_connect_errno()) {
        return '<div class="error">Ошибка подключения к БД: ' . mysqli_connect_error() . '</div>';
    }
    
    mysqli_set_charset($mysqli, "utf8");
    
    // Определяем сортировку
    $orderBy = 'id';
    if($sort == 'bysurname') {
        $orderBy = 'surname, name';
    }
    else if($sort == 'bybirth') {
        $orderBy = 'birth_date';
    }
    
    // Получаем общее количество записей
    $result = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM friends");
    $row = mysqli_fetch_assoc($result);
    $totalRecords = $row['total'];
    
    if($totalRecords == 0) {
        mysqli_close($mysqli);
        return '<div class="error">В таблице нет данных</div>';
    }
    
    // Вычисляем количество страниц
    $recordsPerPage = 10;
    $totalPages = ceil($totalRecords / $recordsPerPage);
    
    // Проверяем корректность страницы
    if($page >= $totalPages) {
        $page = $totalPages - 1;
    }
    if($page < 0) {
        $page = 0;
    }
    
    $offset = $page * $recordsPerPage;
    
    // Запрос данных
    $query = "SELECT * FROM friends ORDER BY $orderBy LIMIT $offset, $recordsPerPage";
    $result = mysqli_query($mysqli, $query);
    
    if(!$result) {
        mysqli_close($mysqli);
        return '<div class="error">Ошибка выполнения запроса</div>';
    }
    
    // Формируем HTML таблицы
    $html = '<h2>Список контактов</h2>';
    $html .= '<table>';
    $html .= '<tr>';
    $html .= '<th>Фамилия</th>';
    $html .= '<th>Имя</th>';
    $html .= '<th>Отчество</th>';
    $html .= '<th>Пол</th>';
    $html .= '<th>Дата рождения</th>';
    $html .= '<th>Телефон</th>';
    $html .= '<th>Адрес</th>';
    $html .= '<th>Email</th>';
    $html .= '<th>Комментарий</th>';
    $html .= '</tr>';
    
    while($row = mysqli_fetch_assoc($result)) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($row['surname']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['name']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['patronymic']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['gender']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['birth_date']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['phone']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['address']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['email']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['comment']) . '</td>';
        $html .= '</tr>';
    }
    
    $html .= '</table>';
    
    // Пагинация
    if($totalPages > 1) {
        $html .= '<div id="pages">';
        for($i = 0; $i < $totalPages; $i++) {
            if($i != $page) {
                $html .= '<a href="/lab9/index.php?p=viewer&sort=' . $sort . '&pg=' . $i . '">' . ($i + 1) . '</a>';
            } else {
                $html .= '<span>' . ($i + 1) . '</span>';
            }
        }
        $html .= '</div>';
    }
    
    mysqli_close($mysqli);
    return $html;
}

// Выводим результат
echo getFriendsList($sort, $page);
?>
