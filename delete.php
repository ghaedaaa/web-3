<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'friends';

$message = '';
$deletedSurname = '';

// Подключение к БД
$mysqli = mysqli_connect($host, $user, $password, $database);

if(mysqli_connect_errno()) {
    echo '<div class="error">Ошибка подключения к БД: ' . mysqli_connect_error() . '</div>';
    exit();
}

mysqli_set_charset($mysqli, "utf8");

// Обработка удаления записи
if(isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    
    // Сначала получаем фамилию для сообщения
    $result = mysqli_query($mysqli, "SELECT surname, name, patronymic FROM friends WHERE id=$id");
    if($row = mysqli_fetch_assoc($result)) {
        $deletedSurname = htmlspecialchars($row['surname']);
        $deletedName = htmlspecialchars($row['name']);
        $deletedPatronymic = htmlspecialchars($row['patronymic']);
        
        // Удаляем запись
        if(mysqli_query($mysqli, "DELETE FROM friends WHERE id=$id")) {
            $message = '<div class="ok">✓ Запись с фамилией ' . $deletedSurname . ' ' . $deletedName . ' ' . $deletedPatronymic . ' удалена</div>';
        } else {
            $message = '<div class="error">✗ Ошибка: запись не удалена</div>';
        }
    } else {
        $message = '<div class="error">✗ Запись не найдена</div>';
    }
}

// Получаем список всех записей
$result = mysqli_query($mysqli, "SELECT id, surname, name, patronymic FROM friends ORDER BY surname, name");
?>

<h2>Удаление записи</h2>

<?php echo $message; ?>

<?php
if(mysqli_num_rows($result) == 0) {
    echo '<div class="error">Записей пока нет</div>';
    mysqli_close($mysqli);
    return;
}
?>

<div id="edit_links">
    <strong>Выберите запись для удаления:</strong><br><br>
    <?php
    while($row = mysqli_fetch_assoc($result)) {
        // Формируем фамилию и инициалы
        $surname = htmlspecialchars($row['surname']);
        $nameInitial = mb_substr(htmlspecialchars($row['name']), 0, 1, 'UTF-8');
        $patronymicInitial = mb_substr(htmlspecialchars($row['patronymic']), 0, 1, 'UTF-8');
        
        $displayName = $surname . ' ' . $nameInitial . '.' . $patronymicInitial . '.';
        
        echo '<a href="/lab9/index.php?p=delete&delete_id=' . $row['id'] . '" 
              onclick="return confirm(\'Вы уверены, что хотите удалить запись ' . $surname . '?\')" 
              style="background:#dc3545;">' . $displayName . '</a>';
    }
    ?>
</div>

<?php mysqli_close($mysqli); ?>
