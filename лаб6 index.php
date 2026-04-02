<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ихкайма Гайдаа - Группа 241-352 - Лабораторная работа №6</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
// ===== توقيت موسكو =====
date_default_timezone_set('Europe/Moscow');

// ============================================================================
// ПОЛУЧЕНИЕ ПАРАМЕТРОВ ИЗ GET (для повторного заполнения формы)
// ============================================================================
$saved_fio = isset($_GET['fio']) ? htmlspecialchars($_GET['fio']) : '';
$saved_group = isset($_GET['group']) ? htmlspecialchars($_GET['group']) : '';

// ============================================================================
// ФУНКЦИИ ДЛЯ РЕШЕНИЯ МАТЕМАТИЧЕСКИХ ЗАДАЧ
// ============================================================================

/**
 * Вычисляет площадь треугольника по трем сторонам (формула Герона)
 */
function triangleArea($a, $b, $c) {
    if ($a + $b > $c && $a + $c > $b && $b + $c > $a) {
        $p = ($a + $b + $c) / 2;
        $area = sqrt($p * ($p - $a) * ($p - $b) * ($p - $c));
        return round($area, 2);
    } else {
        return "Треугольник не существует";
    }
}

/**
 * Вычисляет периметр треугольника
 */
function trianglePerimeter($a, $b, $c) {
    return $a + $b + $c;
}

/**
 * Вычисляет объем прямоугольного параллелепипеда
 */
function parallelepipedVolume($a, $b, $c) {
    return $a * $b * $c;
}

/**
 * Вычисляет среднее арифметическое трех чисел
 */
function arithmeticMean($a, $b, $c) {
    return round(($a + $b + $c) / 3, 2);
}

/**
 * Вычисляет сумму квадратов трех чисел
 */
function sumOfSquares($a, $b, $c) {
    return pow($a, 2) + pow($b, 2) + pow($c, 2);
}

/**
 * Вычисляет среднее геометрическое трех чисел
 */
function geometricMean($a, $b, $c) {
    return round(pow($a * $b * $c, 1/3), 2);
}

/**
 * Вычисляет максимальное из трех чисел
 */
function maxOfThree($a, $b, $c) {
    return max($a, $b, $c);
}

/**
 * Вычисляет минимальное из трех чисел
 */
function minOfThree($a, $b, $c) {
    return min($a, $b, $c);
}

// ============================================================================
// ОБРАБОТКА ДАННЫХ ФОРМЫ (ЕСЛИ ОНИ БЫЛИ ОТПРАВЛЕНЫ)
// ============================================================================

$show_form = true;
$report_text = '';
$processed = false;

if (isset($_POST['A'])) {
    $processed = true;
    
    $fio = trim($_POST['fio']);
    $group = trim($_POST['group']);
    $about = trim($_POST['about']);
    $A = str_replace(',', '.', $_POST['A']);
    $B = str_replace(',', '.', $_POST['B']);
    $C = str_replace(',', '.', $_POST['C']);
    $user_answer = str_replace(',', '.', trim($_POST['user_answer']));
    $email = trim($_POST['email']);
    $task = $_POST['task'];
    $send_mail = isset($_POST['send_mail']);
    $view_type = $_POST['view_type'];
    
    $A = is_numeric($A) ? (float)$A : 0;
    $B = is_numeric($B) ? (float)$B : 0;
    $C = is_numeric($C) ? (float)$C : 0;
    
    $computed_result = '';
    $task_name = '';
    
    switch ($task) {
        case 'triangle_area':
            $task_name = 'Площадь треугольника';
            $computed_result = triangleArea($A, $B, $C);
            break;
        case 'triangle_perimeter':
            $task_name = 'Периметр треугольника';
            $computed_result = trianglePerimeter($A, $B, $C);
            break;
        case 'parallelepiped_volume':
            $task_name = 'Объем параллелепипеда';
            $computed_result = parallelepipedVolume($A, $B, $C);
            break;
        case 'arithmetic_mean':
            $task_name = 'Среднее арифметическое';
            $computed_result = arithmeticMean($A, $B, $C);
            break;
        case 'sum_of_squares':
            $task_name = 'Сумма квадратов';
            $computed_result = sumOfSquares($A, $B, $C);
            break;
        case 'geometric_mean':
            $task_name = 'Среднее геометрическое';
            $computed_result = geometricMean($A, $B, $C);
            break;
        case 'max_of_three':
            $task_name = 'Максимальное из трех';
            $computed_result = maxOfThree($A, $B, $C);
            break;
        case 'min_of_three':
            $task_name = 'Минимальное из трех';
            $computed_result = minOfThree($A, $B, $C);
            break;
    }
    
    if (is_numeric($computed_result)) {
        $computed_result = round($computed_result, 2);
    }
    
    $test_result = '';
    if ($user_answer === '') {
        $test_result = 'Задача самостоятельно решена не была';
    } elseif (!is_numeric($user_answer)) {
        $test_result = 'ОШИБКА: Введен нечисловой ответ';
    } elseif (is_numeric($computed_result) && abs((float)$user_answer - (float)$computed_result) < 0.01) {
        $test_result = 'ТЕСТ ПРОЙДЕН';
    } else {
        $test_result = 'ОШИБКА: ТЕСТ НЕ ПРОЙДЕН';
    }
    
    $report_text = '';
    $report_text .= "ФИО: " . htmlspecialchars($fio) . "\n";
    $report_text .= "Группа: " . htmlspecialchars($group) . "\n";
    if (!empty($about)) {
        $report_text .= "\nО себе: " . htmlspecialchars($about) . "\n";
    }
    $report_text .= "\nРешаемая задача: " . $task_name . "\n";
    $report_text .= "Входные данные: A = $A, B = $B, C = $C\n";
    $report_text .= "Ваш ответ: " . ($user_answer === '' ? 'не введен' : $user_answer) . "\n";
    $report_text .= "Правильный ответ: " . $computed_result . "\n";
    $report_text .= "\nРЕЗУЛЬТАТ: " . $test_result . "\n";
    
    if ($send_mail && !empty($email)) {
        $to = $email;
        $subject = "Результаты тестирования - Лабораторная работа №6";
        $message = str_replace("\n", "\r\n", $report_text);
        $headers = "From: auto@lab6.ru\r\n";
        $headers .= "Content-Type: text/plain; charset=utf-8\r\n";
        
        if (mail($to, $subject, $message, $headers)) {
            $email_sent = true;
        } else {
            $email_sent = false;
        }
    }
    
    if ($view_type == 'print') {
        $show_form = false;
    } else {
        $show_form = false;
    }
}
?>

<div class="container">
    <h1>Ихкайма Гайдаа (241-352) - ЛР №6</h1>
    
    <?php if ($processed && !$show_form): ?>
        <div class="report">
            <h2>Результаты тестирования</h2>
            <?php
            echo str_replace("\n", "<br>", htmlspecialchars($report_text));
            
            if (strpos($report_text, 'ТЕСТ ПРОЙДЕН') !== false) {
                echo '<p class="success">✓ ТЕСТ ПРОЙДЕН</p>';
            } elseif (strpos($report_text, 'ОШИБКА') !== false) {
                echo '<p class="error">✗ ОШИБКА: ТЕСТ НЕ ПРОЙДЕН</p>';
            }
            
            if (isset($send_mail) && $send_mail && !empty($email)) {
                if (isset($email_sent) && $email_sent) {
                    echo '<p class="email-info">✓ Результаты теста были автоматически отправлены на e-mail: ' . htmlspecialchars($email) . '</p>';
                } else {
                    echo '<p class="email-info" style="background: #f8d7da; color: #721c24;">✗ Ошибка отправки email на адрес: ' . htmlspecialchars($email) . '</p>';
                }
            }
            ?>
        </div>
        
        <?php if ($view_type == 'browser'): ?>
            <div style="text-align: center;">
                <a href="?fio=<?php echo urlencode($fio); ?>&group=<?php echo urlencode($group); ?>" class="btn-repeat">🔄 Повторить тест</a>
            </div>
        <?php endif; ?>
        
    <?php endif; ?>
    
    <?php if ($show_form): ?>
        <?php
        // Генерируем случайные числа для A, B, C
        $randomA = mt_rand(10, 50) / 10;
        $randomB = mt_rand(10, 50) / 10;
        $randomC = mt_rand(10, 50) / 10;
        
        $form_fio = $saved_fio ?: '';
        $form_group = $saved_group ?: '';
        ?>
        
        <form method="post" action="">
            <div class="form-group">
                <label for="fio">ФИО:</label>
                <input type="text" id="fio" name="fio" value="<?php echo htmlspecialchars($form_fio); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="group">Номер группы:</label>
                <input type="text" id="group" name="group" value="<?php echo htmlspecialchars($form_group); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="A">Значение А:</label>
                <input type="text" id="A" name="A" value="<?php echo $randomA; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="B">Значение В:</label>
                <input type="text" id="B" name="B" value="<?php echo $randomB; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="C">Значение С:</label>
                <input type="text" id="C" name="C" value="<?php echo $randomC; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="about">Немного о себе:</label>
                <textarea id="about" name="about"></textarea>
            </div>
            
            <div class="form-group">
                <label for="task">Выберите задачу:</label>
                <select id="task" name="task" required>
                    <option value="triangle_area">Площадь треугольника</option>
                    <option value="triangle_perimeter">Периметр треугольника</option>
                    <option value="parallelepiped_volume">Объем параллелепипеда</option>
                    <option value="arithmetic_mean">Среднее арифметическое</option>
                    <option value="sum_of_squares">Сумма квадратов</option>
                    <option value="geometric_mean">Среднее геометрическое</option>
                    <option value="max_of_three">Максимальное из трех</option>
                    <option value="min_of_three">Минимальное из трех</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="user_answer">Ваш ответ:</label>
                <input type="text" id="user_answer" name="user_answer" placeholder="Введите ваш ответ">
            </div>
            
            <div class="form-group checkbox">
                <input type="checkbox" id="send_mail" name="send_mail" onclick="toggleEmailField()">
                <label for="send_mail">Отправить результат теста по e-mail</label>
            </div>
            
            <div class="form-group email-group" id="email_group">
                <label for="email">Ваш e-mail:</label>
                <input type="email" id="email" name="email" placeholder="example@mail.ru">
            </div>
            
            <div class="form-group">
                <label for="view_type">Версия отображения:</label>
                <select id="view_type" name="view_type" required>
                    <option value="browser" selected>Версия для просмотра в браузере</option>
                    <option value="print">Версия для печати</option>
                </select>
            </div>
            
            <div class="button-container">
                <button type="submit" class="btn-check">✓ Проверить</button>
            </div>
        </form>
    <?php endif; ?>
    
    <footer>
        <p>© 2026 Кафедра информационных технологий</p>
        <p>Студент: Ихкайма Гайдаа (Группа 241-352)</p>
        <p>Время: <?php echo date('d.m.Y H:i:s'); ?> (МСК)</p>
    </footer>
</div>

<script>
function toggleEmailField() {
    var checkbox = document.getElementById('send_mail');
    var emailGroup = document.getElementById('email_group');
    
    if (checkbox.checked) {
        emailGroup.classList.add('visible');
    } else {
        emailGroup.classList.remove('visible');
    }
}
</script>

</body>
</html>
