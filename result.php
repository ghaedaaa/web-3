+<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Результат анализа текста</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .src-text { color: #0066cc; font-style: italic; background: #f0f0f0; padding: 10px; border-left: 4px solid #0066cc; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #999; padding: 8px; vertical-align: top; }
        th { background: #ddd; }
        .btn { margin-top: 20px; display: inline-block; padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>

<?php
// Функция подсчёта вхождений каждого символа (без учёта регистра)
function test_symbs($text) {
    $symbs = array();
    $lower_text = mb_strtolower($text, 'UTF-8');
    $len = mb_strlen($lower_text, 'UTF-8');
    for ($i = 0; $i < $len; $i++) {
        $ch = mb_substr($lower_text, $i, 1, 'UTF-8');
        if (isset($symbs[$ch])) {
            $symbs[$ch]++;
        } else {
            $symbs[$ch] = 1;
        }
    }
    return $symbs;
}

// Основная функция анализа
function test_it($text) {
    // 1. Исходный текст (выделен цветом и курсивом)
    echo '<div class="src-text">' . nl2br(htmlspecialchars($text)) . '</div>';

    if (trim($text) == '') {
        echo '<p><strong>Нет текста для анализа</strong></p>';
        return;
    }

    // Подсчёт символов (включая пробелы) 
    $char_count = mb_strlen($text, 'UTF-8');

    // Подготовка групп символов
    $digits = array('0','1','2','3','4','5','6','7','8','9');
    $punctuation = array('.', ',', '!', '?', ';', ':', '-', '—', '(', ')', '[', ']', '{', '}', '"', "'", '«', '»', '…');

    $letters_lower = 0;
    $letters_upper = 0;
    $digits_count = 0;
    $punctuation_count = 0;

    $len = mb_strlen($text, 'UTF-8');
    for ($i = 0; $i < $len; $i++) {
        $ch = mb_substr($text, $i, 1, 'UTF-8');

        // Цифры
        if (in_array($ch, $digits)) {
            $digits_count++;
        }
        // Знаки препинания
        if (in_array($ch, $punctuation)) {
            $punctuation_count++;
        }
        // Буквы: проверка через ctype_alpha в UTF-8 требует предварительной замены,
        // но надёжнее – через регулярные выражения или диапазоны
        if (preg_match('/\p{L}/u', $ch)) {
            if (mb_strtolower($ch, 'UTF-8') == $ch) {
                $letters_lower++;
            } else {
                $letters_upper++;
            }
        }
    }

    $total_letters = $letters_lower + $letters_upper;

    // --- Подсчёт слов и их вхождений (разделители: пробел, знаки препинания, начало/конец строки) ---
    $delimiters = array_merge(array(' ', "\n", "\r", "\t"), $punctuation);
    // Разбиваем с сохранением разделителей – проще через preg_split с захватом
    // Но чтобы учесть русские и английские слова, используем юникодные классы.
    // Получаем массив слов (без пустых)
    $words = array();
    $current_word = '';
    $full_len = mb_strlen($text, 'UTF-8');
    for ($i = 0; $i < $full_len; $i++) {
        $ch = mb_substr($text, $i, 1, 'UTF-8');
        // Если символ – буква (любая)
        if (preg_match('/\p{L}/u', $ch)) {
            $current_word .= $ch;
        } else {
            // Разделитель – завершаем слово
            if ($current_word !== '') {
                $word_key = mb_strtolower($current_word, 'UTF-8');
                if (isset($words[$word_key])) {
                    $words[$word_key]++;
                } else {
                    $words[$word_key] = 1;
                }
                $current_word = '';
            }
        }
    }
    // Последнее слово, если текст не заканчивается разделителем
    if ($current_word !== '') {
        $word_key = mb_strtolower($current_word, 'UTF-8');
        if (isset($words[$word_key])) {
            $words[$word_key]++;
        } else {
            $words[$word_key] = 1;
        }
    }

    $word_count = count($words);

    // Сортировка слов по алфавиту (по ключам)
    ksort($words, SORT_STRING | SORT_FLAG_CASE);

    // --- Подсчёт символов (без учёта регистра) ---
    $symbs = test_symbs($text);

    // --- ВЫВОД ТАБЛИЦЫ ---
    echo '<h3>Информация о тексте</h3>';
    echo '<table>';
    echo '<tr><th>Параметр</th><th>Значение</th></tr>';
    echo '<tr><td>Количество символов (включая пробелы)</td><td>' . $char_count . '</td></tr>';
    echo '<tr><td>Количество букв</td><td>' . $total_letters . '</td></tr>';
    echo '<tr><td>Количество строчных букв</td><td>' . $letters_lower . '</td></tr>';
    echo '<tr><td>Количество заглавных букв</td><td>' . $letters_upper . '</td></tr>';
    echo '<tr><td>Количество знаков препинания</td><td>' . $punctuation_count . '</td></tr>';
    echo '<tr><td>Количество цифр</td><td>' . $digits_count . '</td></tr>';
    echo '<tr><td>Количество слов</td><td>' . $word_count . '</td></tr>';
    echo '</table>';

    // Количество вхождений каждого символа
    echo '<h3>Вхождения каждого символа (без учёта регистра)</h3>';
    echo '<table>';
    echo '<tr><th>Символ</th><th>Количество</th></tr>';
    foreach ($symbs as $ch => $cnt) {
        echo '<tr><td>' . htmlspecialchars($ch) . '</td><td>' . $cnt . '</td></tr>';
    }
    echo '</table>';

    // Список слов и количество вхождений (отсортировано по алфавиту)
    echo '<h3>Слова и количество их вхождений (по алфавиту)</h3>';
    echo '<table>';
    echo '<tr><th>Слово</th><th>Количество</th></tr>';
    foreach ($words as $word => $cnt) {
        echo '<tr><td>' . htmlspecialchars($word) . '</td><td>' . $cnt . '</td></tr>';
    }
    echo '</table>';
}

// Основная логика result.php
if (isset($_POST['data'])) {
    $original_text = $_POST['data'];
    // Сохраняем оригинал для вывода, но для корректной работы с мультибайтом используем UTF-8
    // Все функции уже работают с UTF-8 через mb_*
    if (trim($original_text) == '') {
        echo '<div class="src-text">Нет текста для анализа</div>';
        echo '<p><strong>Нет текста для анализа</strong></p>';
    } else {
        test_it($original_text);
    }
} else {
    echo '<div class="src-text">Нет текста для анализа</div>';
    echo '<p><strong>Нет текста для анализа</strong></p>';
}
?>

<br>
<a href="index.html" class="btn">Другой анализ</a>

</body>
</html>
