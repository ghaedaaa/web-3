<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'friends';

$message = '';
$currentROW = null;

// Подключение к БД
$mysqli = mysqli_connect($host, $user, $password, $database);

if(mysqli_connect_errno()) {
    echo '<div class="error">Ошибка подключения к БД: ' . mysqli_connect_error() . '</div>';
    exit();
}

mysqli_set_charset($mysqli, "utf8");

// Обработка изменения записи
if(isset($_POST['submit']) && $_POST['submit'] == 'Изменить запись') {
    $id = (int)$_POST['id'];
    $surname = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['surname']));
    $name = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['name']));
    $patronymic = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['patronymic']));
    $gender = mysqli_real_escape_string($mysqli, $_POST['gender']);
    $birth_date = mysqli_real_escape_string($mysqli, $_POST['birth_date']);
    $phone = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['phone']));
    $address = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['address']));
    $email = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['email']));
    $comment = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['comment']));
    
    $query = "UPDATE friends SET 
              surname='$surname', 
              name='$name', 
              patronymic='$patronymic', 
              gender='$gender', 
              birth_date='$birth_date', 
              phone='$phone', 
              address='$address', 
              email='$email', 
              comment='$comment' 
              WHERE id=$id";
    
    if(mysqli_query($mysqli, $query)) {
        $message = '<div class="ok">✓ Данные успешно изменены</div>';
        // Эмулируем переход по ссылке на изменяемую запись
        $_GET['id'] = $id;
    } else {
        $message = '<div class="error">✗ Ошибка: данные не изменены</div>';
    }
}

// Получаем текущую запись
$currentROW = null;

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
    $result = mysqli_query($mysqli, "SELECT * FROM friends WHERE id=$id LIMIT 0, 1");
    $currentROW = mysqli_fetch_assoc($result);
}

// Если текущая запись не найдена, берем первую
if(!$currentROW) {
    $result = mysqli_query($mysqli, "SELECT * FROM friends LIMIT 0, 1");
    $currentROW = mysqli_fetch_assoc($result);
}

// Получаем список всех записей для ссылок
$result = mysqli_query($mysqli, "SELECT id, surname, name FROM friends ORDER BY surname, name");
?>

<h2>Редактирование записи</h2>

<?php echo $message; ?>

<?php
if(mysqli_num_rows($result) == 0) {
    echo '<div class="error">Записей пока нет</div>';
    mysqli_close($mysqli);
    return;
}
?>

<!-- Список ссылок -->
<div id="edit_links">
    <strong>Выберите запись для редактирования:</strong><br><br>
    <?php
    while($row = mysqli_fetch_assoc($result)) {
        $fullName = htmlspecialchars($row['surname']) . ' ' . htmlspecialchars($row['name']);
        if($currentROW && $currentROW['id'] == $row['id']) {
            // Текущая запись - выделяем
            echo '<div>' . $fullName . '</div>';
        } else {
            // Ссылка на другую запись
            echo '<a href="/lab9/index.php?p=edit&id=' . $row['id'] . '">' . $fullName . '</a>';
        }
    }
    ?>
</div>

<?php if($currentROW): ?>
<!-- Форма редактирования -->
<form method="post" action="/lab9/index.php?p=edit&id=<?php echo $currentROW['id']; ?>">
    <input type="hidden" name="id" value="<?php echo $currentROW['id']; ?>">
    
    <input type="text" name="surname" placeholder="Фамилия" 
           value="<?php echo htmlspecialchars($currentROW['surname']); ?>" required>
    
    <input type="text" name="name" placeholder="Имя" 
           value="<?php echo htmlspecialchars($currentROW['name']); ?>" required>
    
    <input type="text" name="patronymic" placeholder="Отчество" 
           value="<?php echo htmlspecialchars($currentROW['patronymic']); ?>">
    
    <select name="gender" required>
        <option value="Мужской" <?php echo ($currentROW['gender'] == 'Мужской') ? 'selected' : ''; ?>>Мужской</option>
        <option value="Женский" <?php echo ($currentROW['gender'] == 'Женский') ? 'selected' : ''; ?>>Женский</option>
    </select>
    
    <input type="date" name="birth_date" 
           value="<?php echo htmlspecialchars($currentROW['birth_date']); ?>" required>
    
    <input type="tel" name="phone" placeholder="Телефон" 
           value="<?php echo htmlspecialchars($currentROW['phone']); ?>" required>
    
    <input type="text" name="address" placeholder="Адрес" 
           value="<?php echo htmlspecialchars($currentROW['address']); ?>">
    
    <input type="email" name="email" placeholder="Email" 
           value="<?php echo htmlspecialchars($currentROW['email']); ?>">
    
    <textarea name="comment" rows="3" placeholder="Комментарий"><?php 
        echo htmlspecialchars($currentROW['comment']); 
    ?></textarea>
    
    <input type="submit" name="submit" value="Изменить запись">
</form>
<?php endif; ?>

<?php mysqli_close($mysqli); ?>
