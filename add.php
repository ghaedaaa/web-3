<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'friends';

$message = '';

// Обработка отправки формы
if(isset($_POST['submit']) && $_POST['submit'] == 'Добавить запись') {
    $mysqli = mysqli_connect($host, $user, $password, $database);
    
    if(mysqli_connect_errno()) {
        $message = '<div class="error">Ошибка подключения к БД: ' . mysqli_connect_error() . '</div>';
    } else {
        mysqli_set_charset($mysqli, "utf8");
        
        $surname = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['surname']));
        $name = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['name']));
        $patronymic = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['patronymic']));
        $gender = mysqli_real_escape_string($mysqli, $_POST['gender']);
        $birth_date = mysqli_real_escape_string($mysqli, $_POST['birth_date']);
        $phone = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['phone']));
        $address = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['address']));
        $email = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['email']));
        $comment = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['comment']));
        
        $query = "INSERT INTO friends (surname, name, patronymic, gender, birth_date, phone, address, email, comment) 
                  VALUES ('$surname', '$name', '$patronymic', '$gender', '$birth_date', '$phone', '$address', '$email', '$comment')";
        
        if(mysqli_query($mysqli, $query)) {
            $message = '<div class="ok">✓ Запись добавлена</div>';
        } else {
            $message = '<div class="error">✗ Ошибка: запись не добавлена</div>';
        }
        
        mysqli_close($mysqli);
    }
}
?>

<h2>Добавление новой записи</h2>

<?php echo $message; ?>

<form method="post" action="/lab9/index.php?p=add">
    <input type="text" name="surname" placeholder="Фамилия" required>
    <input type="text" name="name" placeholder="Имя" required>
    <input type="text" name="patronymic" placeholder="Отчество">
    
    <select name="gender" required>
        <option value="Мужской">Мужской</option>
        <option value="Женский">Женский</option>
    </select>
    
    <input type="date" name="birth_date" required>
    <input type="tel" name="phone" placeholder="Телефон" required>
    <input type="text" name="address" placeholder="Адрес">
    <input type="email" name="email" placeholder="Email">
    <textarea name="comment" rows="3" placeholder="Комментарий"></textarea>
    
    <input type="submit" name="submit" value="Добавить запись">
</form>
