<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ихкайма Гайдаа - Группа 241-352 - Лабораторная работа №4</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f4f7fc;
            padding: 40px 20px;
            color: #2c3e50;
        }

        h1 {
            text-align: center;
            font-size: 36px;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            font-size: 20px;
            color: #7f8c8d;
            margin-bottom: 40px;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 20px;
        }

        h2 {
            font-size: 28px;
            color: #2980b9;
            margin: 30px 0 15px 0;
            border-left: 6px solid #3498db;
            padding-left: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        th, td {
            border: 1px solid #bdc3c7;
            padding: 12px 15px;
            text-align: center;
        }

        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }

        td {
            background-color: #f9f9f9;
            color: #2c3e50;
        }

        .message {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            font-size: 18px;
            text-align: center;
        }

        footer {
            margin-top: 50px;
            text-align: center;
            color: #7f8c8d;
            font-size: 16px;
            border-top: 1px solid #ecf0f1;
            padding-top: 20px;
        }
    </style>
</head>
<body>

<h1>Ихкайма Гайдаа</h1>
<div class="subtitle">Группа 241-352 | Лабораторная работа №4</div>

<?php
// Устанавливаем часовой пояс Москвы
date_default_timezone_set('Europe/Moscow');

// ============================================================================
// Функция для формирования одной строки таблицы из текста с разделителем *
// ============================================================================
/**
 * Преобразует строку вида "a*b*c" в HTML-код строки таблицы <tr>...</tr>
 * @param string $data Строка с данными, разделёнными символом *
 * @param int $cols Количество колонок в таблице
 * @return string HTML-код строки таблицы
 */
function getTR($data, $cols) {
    // Разбиваем строку на массив ячеек по разделителю *
    $cells = explode('*', $data);
    
    // Если после разбиения нет ни одной ячейки — возвращаем пустую строку
    if (count($cells) == 0) {
        return '';
    }
    
    // Начинаем формировать строку таблицы
    $row = '<tr>';
    
    // Проходим по всем колонкам от 0 до $cols-1
    for ($i = 0; $i < $cols; $i++) {
        // Если для этой колонки есть данные — используем их, иначе — пустая ячейка
        $cellValue = isset($cells[$i]) ? $cells[$i] : '&nbsp;'; // &nbsp; для пустой ячейки
        $row .= '<td>' . htmlspecialchars($cellValue) . '</td>';
    }
    
    $row .= '</tr>';
    return $row;
}

// ============================================================================
// Функция для вывода полной таблицы на основе структуры
// ============================================================================
/**
 * Выводит HTML-код таблицы на основе структуры вида "a*b*c#d*e*f"
 * @param string $structure Структура таблицы
 * @param int $tableNumber Номер таблицы для заголовка
 * @param int $cols Количество колонок
 */
function outTable($structure, $tableNumber, $cols) {
    // Если колонок 0 — выводим сообщение и выходим
    if ($cols <= 0) {
        echo "<div class='message'>⚠ Неправильное число колонок (колонок: $cols)</div>";
        return;
    }
    
    // Разбиваем структуру на строки по разделителю #
    $rows = explode('#', $structure);
    
    // Если нет ни одной строки
    if (count($rows) == 0) {
        echo "<div class='message'>⚠ В таблице №$tableNumber нет строк</div>";
        return;
    }
    
    // Переменная для накопления HTML-кода всех строк
    $allRowsHtml = '';
    $hasAnyCell = false; // флаг, есть ли хоть одна ячейка
    
    // Обрабатываем каждую строку
    foreach ($rows as $rowData) {
        // Получаем HTML строки через функцию getTR
        $rowHtml = getTR($rowData, $cols);
        
        // Если строка не пустая
        if (!empty($rowHtml)) {
            $allRowsHtml .= $rowHtml;
            // Проверяем, есть ли в этой строке ячейки с данными (не пустые)
            $cells = explode('*', $rowData);
            foreach ($cells as $cell) {
                if (trim($cell) !== '') {
                    $hasAnyCell = true;
                    break;
                }
            }
        }
    }
    
    // Если нет ни одной строки с данными
    if (empty($allRowsHtml)) {
        echo "<div class='message'>⚠ В таблице №$tableNumber нет строк с ячейками</div>";
        return;
    }
    
    // Если есть строки, но все ячейки пустые
    if (!$hasAnyCell) {
        echo "<div class='message'>⚠ В таблице №$tableNumber нет строк с ячейками</div>";
        return;
    }
    
    // Выводим заголовок таблицы
    echo "<h2>Таблица №$tableNumber</h2>";
    
    // Выводим саму таблицу
    echo '<table>';
    echo $allRowsHtml;
    echo '</table>';
}

// ============================================================================
// ОСНОВНАЯ ПРОГРАММА
// ============================================================================

// Массив со структурами таблиц (минимум 10 элементов)
$tables = array(
    'Яблоко*Банан*Апельсин#Молоко*Хлеб*Сыр',
    'Красный*Синий*Зелёный#Жёлтый*Чёрный*Белый',
    'Понедельник*Вторник*Среда#Четверг*Пятница*Суббота',
    'Кошка*Собака*Попугай#Хомяк*Рыбки*Кролик',
    'Россия*США*Китай#Германия*Франция*Италия',
    'Алексей*Мария*Дмитрий#Елена*Сергей*Анна',
    'Москва*Питер*Казань#Новгород*Сочи*Екатеринбург',
    'PHP*JavaScript*Python#Java*C++*Ruby',
    'Утро*День*Вечер#Ночь*Полночь*Рассвет',
    'Книга*Ручка*Тетрадь#Пенал*Линейка*Ластик',
    'Зима*Весна*Лето#Осень*Год*Месяц',
    'Холодно*Тепло*Жарко#Ветрено*Дождливо*Солнечно'
);

// Количество колонок в таблицах (можно менять для проверки)
$columnsCount = 3; // Можно изменить на 0, 2, 5 и т.д. для тестирования

// Вывод сообщения о количестве колонок
echo "<div style='text-align: center; margin: 20px 0; font-size: 20px;'>";
echo "📌 Количество колонок: <strong>$columnsCount</strong>";
echo "</div>";

// Переменная для подсчёта реально выведенных таблиц
$displayedTables = 0;

// Проходим по всем структурам в массиве и выводим таблицы
for ($i = 0; $i < count($tables); $i++) {
    // Вызываем функцию для вывода таблицы
    // Используем буферизацию, чтобы проверить, вывелось ли что-то
    ob_start();
    outTable($tables[$i], $i + 1, $columnsCount);
    $output = ob_get_clean();
    
    // Если функция outTable что-то вывела (не сообщение об ошибке)
    if (!empty($output) && strpos($output, 'class="message"') === false) {
        $displayedTables++;
    }
    
    // Выводим результат
    echo $output;
}

// Если ни одной таблицы не выведено из-за ошибок
if ($displayedTables == 0 && $columnsCount > 0) {
    echo "<div class='message'>⚠ Нет таблиц для отображения (возможно, все структуры пусты или содержат только пустые строки)</div>";
}

?>

<footer>
    © 2026 Кафедра информационных технологий<br>
    Студент: Ихкайма Гайдаа (Группа 241-352)<br>
    Время выполнения: <?php echo date('d.m.Y H:i:s'); ?> (МСК)
</footer>

</body>
</html>
