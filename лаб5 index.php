<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ихкайма Гайдаа - Группа 241-352 - Лабораторная работа №5</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
// Устанавливаем часовой пояс Москвы
date_default_timezone_set('Europe/Moscow');

// ============================================================================
// ПОЛУЧЕНИЕ ПАРАМЕТРОВ ИЗ URL
// ============================================================================

// Тип вёрстки: TABLE (табличная) или DIV (блочная)
// По умолчанию (если параметр не передан) — табличная
$html_type = 'TABLE';
if (isset($_GET['html_type']) && ($_GET['html_type'] == 'TABLE' || $_GET['html_type'] == 'DIV')) {
    $html_type = $_GET['html_type'];
}

// Содержимое таблицы: число от 2 до 9 или пусто (полная таблица)
$content = null;
if (isset($_GET['content']) && is_numeric($_GET['content']) && $_GET['content'] >= 2 && $_GET['content'] <= 9) {
    $content = (int)$_GET['content'];
}

// ============================================================================
// ФУНКЦИЯ: Преобразует число в ссылку (если число от 2 до 9)
// ============================================================================
/**
 * Возвращает HTML-код числа как ссылки на таблицу умножения этого числа
 * @param int $num Число для преобразования
 * @return string HTML-код (ссылка или просто число)
 */
function outNumAsLink($num) {
    if ($num >= 2 && $num <= 9) {
        // Ссылка на таблицу умножения этого числа (без типа вёрстки)
        return '<a href="?content=' . $num . '" class="num-link">' . $num . '</a>';
    } else {
        // Числа вне диапазона (например, результат умножения >9) выводятся просто текстом
        return '<span class="num-link-static">' . $num . '</span>';
    }
}

// ============================================================================
// ФУНКЦИЯ: Выводит один столбец таблицы умножения
// ============================================================================
/**
 * Выводит столбец таблицы умножения для заданного числа
 * @param int $n Число, для которого выводится таблица умножения
 */
function outRow($n) {
    for ($i = 2; $i <= 9; $i++) {
        $result = $i * $n;
        // Используем функцию outNumAsLink для каждого числа
        echo outNumAsLink($n) . ' x ' . outNumAsLink($i) . ' = ' . outNumAsLink($result) . '<br>';
    }
}

// ============================================================================
// ФУНКЦИЯ: Выводит всю таблицу умножения (8 столбцов)
// ============================================================================
function outFullTable() {
    for ($col = 2; $col <= 9; $col++) {
        echo '<div class="block-col">';
        echo '<h3>Таблица на ' . $col . '</h3>';
        outRow($col);
        echo '</div>';
    }
}

// ============================================================================
// ФУНКЦИЯ: Выводит один столбец (для выбранного числа)
// ============================================================================
function outSingleColumn($num) {
    echo '<div class="single-block">';
    echo '<h3>Таблица умножения на ' . $num . '</h3>';
    outRow($num);
    echo '</div>';
}

// ============================================================================
// ВЫВОД СТРАНИЦЫ
// ============================================================================
?>

<!-- ШАПКА САЙТА -->
<header>
    <div class="header-content">
        <div class="logo">Ихкайма Гайдаа (241-352) | ЛР №5</div>
        <div class="main-menu">
            <?php
            // Формируем ссылку для "Табличная верстка" с сохранением content
            $link_table = '?html_type=TABLE';
            if ($content !== null) {
                $link_table .= '&content=' . $content;
            }
            
            // Формируем ссылку для "Блочная верстка" с сохранением content
            $link_div = '?html_type=DIV';
            if ($content !== null) {
                $link_div .= '&content=' . $content;
            }
            ?>
            <a href="<?php echo $link_table; ?>" <?php if ($html_type == 'TABLE') echo 'class="selected"'; ?>>Табличная верстка</a>
            <a href="<?php echo $link_div; ?>" <?php if ($html_type == 'DIV') echo 'class="selected"'; ?>>Блочная верстка</a>
        </div>
    </div>
</header>

<!-- ОСНОВНОЙ КОНТЕЙНЕР -->
<div class="container">
    <!-- БОКОВОЕ МЕНЮ -->
    <div class="side-menu">
        <?php
        // Ссылка "Всё" (полная таблица)
        $link_all = '?';
        if ($html_type !== null) {
            $link_all .= 'html_type=' . $html_type;
        }
        ?>
        <a href="<?php echo $link_all; ?>" <?php if ($content === null) echo 'class="selected"'; ?>>Всё</a>
        
        <?php
        // Ссылки для цифр 2-9
        for ($i = 2; $i <= 9; $i++) {
            $link = '?content=' . $i;
            if ($html_type !== null) {
                $link .= '&html_type=' . $html_type;
            }
            $selected = ($content === $i) ? 'class="selected"' : '';
            echo '<a href="' . $link . '" ' . $selected . '>' . $i . '</a>';
        }
        ?>
    </div>
    
    <!-- ОСНОВНОЙ КОНТЕНТ (ТАБЛИЦА УМНОЖЕНИЯ) -->
    <div class="content">
        <?php
        if ($html_type == 'TABLE') {
            // Табличная вёрстка
            echo '<div class="table-layout">';
            if ($content === null) {
                // Вся таблица умножения
                echo '<table>';
                echo '<tr><th>×</th>';
                for ($col = 2; $col <= 9; $col++) {
                    echo '<th>' . $col . '</th>';
                }
                echo '</tr>';
                
                for ($row = 2; $row <= 9; $row++) {
                    echo '<tr>';
                    echo '<th>' . $row . '</th>';
                    for ($col = 2; $col <= 9; $col++) {
                        $result = $row * $col;
                        echo '<td>' . outNumAsLink($row) . '×' . outNumAsLink($col) . '=' . outNumAsLink($result) . '</td>';
                    }
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                // Один столбец
                echo '<table>';
                echo '<tr><th>×</th><th>' . $content . '</th></tr>';
                for ($row = 2; $row <= 9; $row++) {
                    echo '<tr>';
                    echo '<th>' . $row . '</th>';
                    $result = $row * $content;
                    echo '<td>' . outNumAsLink($row) . '×' . outNumAsLink($content) . '=' . outNumAsLink($result) . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            }
            echo '</div>';
        } else {
            // Блочная вёрстка (DIV)
            echo '<div class="block-layout">';
            if ($content === null) {
                outFullTable();
            } else {
                outSingleColumn($content);
            }
            echo '</div>';
        }
        ?>
    </div>
</div>

<!-- ПОДВАЛ -->
<footer>
    <?php
    // Определяем тип вёрстки для подвала
    if ($html_type == 'TABLE') {
        $type_text = 'Табличная верстка';
    } else {
        $type_text = 'Блочная верстка';
    }
    
    // Определяем содержимое таблицы
    if ($content === null) {
        $content_text = 'Таблица умножения полностью';
    } else {
        $content_text = 'Таблица умножения на ' . $content;
    }
    
    // Текущая дата и время (Москва)
    $datetime = date('d.m.Y H:i:s');
    ?>
    <p>Тип верстки: <?php echo $type_text; ?> | Содержимое: <?php echo $content_text; ?></p>
    <p>Ихкайма Гайдаа (Группа 241-352) | Время: <?php echo $datetime; ?> (МСК)</p>
</footer>

</body>
</html>
