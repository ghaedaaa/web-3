<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ихкайма Гайдаа - Группа 241-352 - Лабораторная работа №7 - Результаты сортировки</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .array-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 10px 0;
            padding: 10px;
            background: #f1f9ff;
            border-radius: 10px;
        }
        .array-item {
            background: #3498db;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            font-weight: bold;
        }
        .iteration-box {
            background: white;
            border: 2px solid #3498db;
            border-radius: 10px;
            padding: 15px;
            margin: 15px 0;
        }
        .iteration-title {
            font-weight: bold;
            color: #3498db;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .stats-box {
            background: #2c3e50;
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
    </style>
</head>
<body>

<?php
date_default_timezone_set('Europe/Moscow');

// ============================================================================
// ФУНКЦИЯ ПРОВЕРКИ, ЯВЛЯЕТСЯ ЛИ СТРОКА ЧИСЛОМ
// ============================================================================
function isNotNumber($arg) {
    $arg = trim($arg);
    if ($arg === '') return true; // пустая строка
    
    // Проверка на отрицательное число
    $start = 0;
    if ($arg[0] === '-') {
        $start = 1;
        if (strlen($arg) == 1) return true; // только минус
    }
    
    $hasDot = false;
    for ($i = $start; $i < strlen($arg); $i++) {
        $char = $arg[$i];
        if ($char === '.' || $char === ',') {
            if ($hasDot) return true; // больше одной точки
            $hasDot = true;
        } elseif ($char < '0' || $char > '9') {
            return true; // не цифра
        }
    }
    return false; // это число
}

// ============================================================================
// ФУНКЦИИ ДЛЯ ВЫВОДА СОСТОЯНИЯ МАССИВА
// ============================================================================
$global_iteration = 0;
$iteration_output = '';

function showArray($arr, $message = '') {
    global $iteration_output, $global_iteration;
    $iteration_output .= '<div class="iteration-box">';
    $iteration_output .= '<div class="iteration-title">' . $message . '</div>';
    $iteration_output .= '<div class="array-row">';
    foreach ($arr as $index => $value) {
        $iteration_output .= '<span class="array-item">[' . $index . '] ' . $value . '</span>';
    }
    $iteration_output .= '</div>';
    $iteration_output .= '</div>';
    $global_iteration++;
}

function showArrayWithHighlight($arr, $pos1, $pos2, $message = '') {
    global $iteration_output, $global_iteration;
    $iteration_output .= '<div class="iteration-box">';
    $iteration_output .= '<div class="iteration-title">' . $message . '</div>';
    $iteration_output .= '<div class="array-row">';
    foreach ($arr as $index => $value) {
        $class = ($index == $pos1 || $index == $pos2) ? 'array-item' : 'array-item';
        $style = ($index == $pos1 || $index == $pos2) ? 'background: #e74c3c; transform: scale(1.1);' : '';
        $iteration_output .= '<span class="array-item" style="' . $style . '">[' . $index . '] ' . $value . '</span>';
    }
    $iteration_output .= '</div>';
    $iteration_output .= '</div>';
    $global_iteration++;
}

// ============================================================================
// АЛГОРИТМЫ СОРТИРОВКИ
// ============================================================================

// 1. СОРТИРОВКА ВЫБОРОМ
function selectionSort($arr) {
    global $global_iteration;
    $n = count($arr);
    for ($i = 0; $i < $n - 1; $i++) {
        $min = $i;
        for ($j = $i + 1; $j < $n; $j++) {
            if ($arr[$j] < $arr[$min]) {
                $min = $j;
            }
        }
        if ($min != $i) {
            $temp = $arr[$i];
            $arr[$i] = $arr[$min];
            $arr[$min] = $temp;
        }
        showArray($arr, "Итерация " . ($global_iteration + 1) . ": ставим элемент " . $arr[$i] . " на место [" . $i . "]");
    }
    return $arr;
}

// 2. ПУЗЫРЬКОВАЯ СОРТИРОВКА
function bubbleSort($arr) {
    global $global_iteration;
    $n = count($arr);
    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = 0; $j < $n - $i - 1; $j++) {
            if ($arr[$j] > $arr[$j + 1]) {
                $temp = $arr[$j];
                $arr[$j] = $arr[$j + 1];
                $arr[$j + 1] = $temp;
                showArrayWithHighlight($arr, $j, $j + 1, "Итерация " . ($global_iteration + 1) . ": меняем " . $arr[$j] . " и " . $arr[$j + 1]);
            }
        }
    }
    return $arr;
}

// 3. ШЕЙКЕРНАЯ СОРТИРОВКА
function shakerSort($arr) {
    global $global_iteration;
    $left = 0;
    $right = count($arr) - 1;
    
    while ($left <= $right) {
        for ($i = $right; $i > $left; $i--) {
            if ($arr[$i - 1] > $arr[$i]) {
                $temp = $arr[$i - 1];
                $arr[$i - 1] = $arr[$i];
                $arr[$i] = $temp;
                showArrayWithHighlight($arr, $i - 1, $i, "Итерация " . ($global_iteration + 1) . ": шейкер (справа налево)");
            }
        }
        $left++;
        
        for ($i = $left; $i < $right; $i++) {
            if ($arr[$i] > $arr[$i + 1]) {
                $temp = $arr[$i];
                $arr[$i] = $arr[$i + 1];
                $arr[$i + 1] = $temp;
                showArrayWithHighlight($arr, $i, $i + 1, "Итерация " . ($global_iteration + 1) . ": шейкер (слева направо)");
            }
        }
        $right--;
    }
    return $arr;
}

// 4. СОРТИРОВКА ВСТАВКАМИ
function insertSort($arr) {
    global $global_iteration;
    $n = count($arr);
    for ($i = 1; $i < $n; $i++) {
        $val = $arr[$i];
        $j = $i - 1;
        while ($j >= 0 && $arr[$j] > $val) {
            $arr[$j + 1] = $arr[$j];
            $j--;
        }
        $arr[$j + 1] = $val;
        showArray($arr, "Итерация " . ($global_iteration + 1) . ": вставляем элемент " . $val . " на позицию " . ($j + 1));
    }
    return $arr;
}

// 5. СОРТИРОВКА ГНОМА
function gnomeSort($arr) {
    global $global_iteration;
    $i = 1;
    $n = count($arr);
    
    while ($i < $n) {
        if ($i == 0 || $arr[$i - 1] <= $arr[$i]) {
            $i++;
        } else {
            $temp = $arr[$i];
            $arr[$i] = $arr[$i - 1];
            $arr[$i - 1] = $temp;
            showArrayWithHighlight($arr, $i - 1, $i, "Итерация " . ($global_iteration + 1) . ": гном меняет местами");
            $i--;
        }
    }
    return $arr;
}

// 6. СОРТИРОВКА ШЕЛЛА
function shellSort($arr) {
    global $global_iteration;
    $n = count($arr);
    $k = ceil($n / 2);
    
    while ($k >= 1) {
        for ($i = $k; $i < $n; $i++) {
            $val = $arr[$i];
            $j = $i - $k;
            while ($j >= 0 && $arr[$j] > $val) {
                $arr[$j + $k] = $arr[$j];
                $j -= $k;
            }
            $arr[$j + $k] = $val;
            showArray($arr, "Итерация " . ($global_iteration + 1) . ": Шелл (шаг = $k)");
        }
        $k = ceil($k / 2);
    }
    return $arr;
}

// 7. БЫСТРАЯ СОРТИРОВКА
function quickSort(&$arr, $left, $right) {
    global $global_iteration;
    if ($left >= $right) return;
    
    $l = $left;
    $r = $right;
    $point = $arr[floor(($left + $right) / 2)];
    
    do {
        while ($arr[$l] < $point) $l++;
        while ($arr[$r] > $point) $r--;
        
        if ($l <= $r) {
            $temp = $arr[$l];
            $arr[$l] = $arr[$r];
            $arr[$r] = $temp;
            showArrayWithHighlight($arr, $l, $r, "Итерация " . ($global_iteration + 1) . ": быстрая сортировка (опорный = $point)");
            $l++;
            $r--;
        }
    } while ($l <= $r);
    
    if ($left < $r) quickSort($arr, $left, $r);
    if ($l < $right) quickSort($arr, $l, $right);
}

function quickSortWrapper($arr) {
    quickSort($arr, 0, count($arr) - 1);
    return $arr;
}

// ============================================================================
// ОСНОВНАЯ ОБРАБОТКА
// ============================================================================

echo '<div class="container">';
echo '<h1>Ихкайма Гайдаа (241-352) - ЛР №7</h1>';
echo '<h2>Результаты сортировки</h2>';

// Проверка наличия данных
if (!isset($_POST['element0'])) {
    echo '<div class="warning">⚠ Массив не задан, сортировка невозможна</div>';
    echo '<a href="index.php" class="nav-link">← Вернуться к вводу массива</a>';
    echo '</div>';
    exit();
}

// Получаем количество элементов
$arrLength = isset($_POST['arrLength']) ? (int)$_POST['arrLength'] : 1;

// Собираем массив и проверяем числа
$rawArray = [];
$hasError = false;

for ($i = 0; $i < $arrLength; $i++) {
    $key = 'element' . $i;
    if (isset($_POST[$key])) {
        $value = trim($_POST[$key]);
        if (isNotNumber($value)) {
            echo '<div class="warning">⚠ Элемент массива "' . htmlspecialchars($value) . '" (индекс ' . $i . ') - не число</div>';
            $hasError = true;
        } else {
            // Заменяем запятую на точку
            $value = str_replace(',', '.', $value);
            $rawArray[] = (float)$value;
        }
    }
}

if ($hasError || empty($rawArray)) {
    if (empty($rawArray)) {
        echo '<div class="warning">⚠ Массив пуст, сортировка невозможна</div>';
    }
    echo '<a href="index.php" class="nav-link">← Вернуться к вводу массива</a>';
    echo '</div>';
    exit();
}

// Определяем алгоритм
$algorithm = isset($_POST['algorithm']) ? $_POST['algorithm'] : 'selection';

// Названия алгоритмов
$algorithmNames = [
    'selection' => 'Сортировка выбором',
    'bubble' => 'Пузырьковый алгоритм',
    'shaker' => 'Шейкерная сортировка',
    'insert' => 'Сортировка вставками',
    'gnome' => 'Алгоритм садового гнома',
    'shell' => 'Алгоритм Шелла',
    'quick' => 'Быстрая сортировка',
    'php_sort' => 'Встроенная функция PHP (sort)',
    'php_rsort' => 'Встроенная функция PHP (rsort)'
];

$algorithmName = isset($algorithmNames[$algorithm]) ? $algorithmNames[$algorithm] : 'Неизвестный алгоритм';

echo '<div class="sort-info">';
echo '<p><strong>Алгоритм:</strong> ' . $algorithmName . '</p>';
echo '<p><strong>Исходный массив:</strong> [' . implode(', ', $rawArray) . ']</p>';
echo '<p><strong>Проверка:</strong> Все элементы являются числами, сортировка возможна ✓</p>';
echo '</div>';

// Сохраняем копию исходного массива
$arrayToSort = $rawArray;

// Засекаем время
$startTime = microtime(true);
$global_iteration = 0;
$iteration_output = '';

// Выполняем сортировку
switch ($algorithm) {
    case 'selection':
        $sortedArray = selectionSort($arrayToSort);
        break;
    case 'bubble':
        $sortedArray = bubbleSort($arrayToSort);
        break;
    case 'shaker':
        $sortedArray = shakerSort($arrayToSort);
        break;
    case 'insert':
        $sortedArray = insertSort($arrayToSort);
        break;
    case 'gnome':
        $sortedArray = gnomeSort($arrayToSort);
        break;
    case 'shell':
        $sortedArray = shellSort($arrayToSort);
        break;
    case 'quick':
        $sortedArray = quickSortWrapper($arrayToSort);
        break;
    case 'php_sort':
        sort($arrayToSort);
        $sortedArray = $arrayToSort;
        showArray($sortedArray, "Результат после sort()");
        break;
    case 'php_rsort':
        rsort($arrayToSort);
        $sortedArray = $arrayToSort;
        showArray($sortedArray, "Результат после rsort()");
        break;
    default:
        $sortedArray = $arrayToSort;
}

$endTime = microtime(true);
$executionTime = round($endTime - $startTime, 6);

// Выводим итерации
echo '<h3>Процесс сортировки:</h3>';
echo $iteration_output;

// Выводим результат
echo '<div class="stats-box">';
echo '<h3 style="color: white;">Сортировка завершена</h3>';
echo '<p>Проведено итераций: <strong>' . $global_iteration . '</strong></p>';
echo '<p>Сортировка заняла: <strong>' . $executionTime . ' сек.</strong></p>';
echo '<p>Отсортированный массив: [' . implode(', ', $sortedArray) . ']</p>';
echo '</div>';

// Сравнение со встроенной функцией
if ($algorithm != 'php_sort' && $algorithm != 'php_rsort') {
    $testArray = $rawArray;
    $startNative = microtime(true);
    sort($testArray);
    $endNative = microtime(true);
    $nativeTime = round($endNative - $startNative, 6);
    
    echo '<div class="sort-info">';
    echo '<p><strong>Сравнение:</strong> Встроенная функция sort() выполнилась за ' . $nativeTime . ' сек.</p>';
    if ($executionTime < $nativeTime) {
        echo '<p>✓ Ваш алгоритм быстрее встроенной функции</p>';
    } else {
        echo '<p>⚠ Встроенная функция быстрее вашего алгоритма</p>';
    }
    echo '</div>';
}

echo '<a href="index.php" class="nav-link">← Новая сортировка</a>';

echo '<footer>';
echo '<p>© 2026 Кафедра информационных технологий</p>';
echo '<p>Студент: Ихкайма Гайдаа (Группа 241-352)</p>';
echo '<p>Время: ' . date('d.m.Y H:i:s') . ' (МСК)</p>';
echo '</footer>';

echo '</div>';
?>

</body>
</html>
