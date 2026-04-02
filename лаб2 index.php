<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ихкайма Гайдаа - Группа 241-352 - Лабораторная работа №2</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="logo.png">
    <style>
        .error { color: #e74c3c; font-weight: bold; }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <img src="logo.png" alt="Логотип университета" class="logo">
            <div class="header-text">
                <h1>Ихкайма Гайдаа</h1>
                <p>Группа: 241-352 | Лабораторная работа №2 | Вариант 3</p>
            </div>
        </div>
    </header>

    <main>
        <?php
        // ===== московское время =====
        date_default_timezone_set('Europe/Moscow');
        
        // ===== ПЕРЕМЕННЫЕ =====
        $start_x = -5;           // начальное значение аргумента
        $count = 25;             // количество вычисляемых значений
        $step = 1.2;             // шаг изменения аргумента
        $min_f = -50;            // минимальное значение функции
        $max_f = 50;             // максимальное значение функции
        $layout_type = 'D';      // тип верстки: A, B, C, D, E
        
        // если тип изменен по ссылке
        if (isset($_GET['type']) && in_array($_GET['type'], ['A','B','C','D','E'])) {
            $layout_type = $_GET['type'];
        }
        
        $current_x = $start_x;
        $values = [];            // массив для хранения значений функции
        $sum = 0;
        
        // ===== ФУНКЦИЯ №3 =====
        function calculateFunction($x) {
            if ($x <= 10) {
                // f(x) = 3*x³ + 2
                return 3 * pow($x, 3) + 2;
            } elseif ($x > 10 && $x < 20) {
                // f(x) = 5*x + 7
                return 5 * $x + 7;
            } else { // x >= 20
                // f(x) = x/(22 - x) - x
                if ((22 - $x) == 0) {
                    return "error";
                }
                return ($x / (22 - $x)) - $x;
            }
        }
        
        // ===== ВЫЧИСЛЕНИЯ =====
        echo "<div class='layout-$layout_type'>";
        
        // Начало структуры в зависимости от типа
        if ($layout_type == 'B') echo "<ul>";
        if ($layout_type == 'C') echo "<ol>";
        if ($layout_type == 'D') {
            echo "<table>";
            echo "<tr><th>№</th><th>x</th><th>f(x)</th></tr>";
        }
        
        // Цикл вычислений
        for ($i = 0; $i < $count; $i++, $current_x += $step) {
            // Проверка остановки по min/max
            if ($i > 0 && !empty($values)) {
                $last_value = end($values);
                if ($last_value >= $max_f || $last_value <= $min_f) {
                    break;
                }
            }
            
            // Вычисление значения функции
            $result = calculateFunction($current_x);
            $rounded_result = ($result == "error") ? "error" : round($result, 3);
            
            // Сохранение для статистики
            if ($result != "error" && is_numeric($result)) {
                $values[] = $rounded_result;
                $sum += $rounded_result;
            }
            
            // Вывод согласно типу верстки
            switch ($layout_type) {
                case 'A':
                    echo "f($current_x) = ";
                    echo ($rounded_result == "error") ? 
                         "<span class='error'>error</span>" : $rounded_result;
                    if ($i < $count - 1) echo "<br>";
                    break;
                    
                case 'B':
                    echo "<li>f($current_x) = ";
                    echo ($rounded_result == "error") ? 
                         "<span class='error'>error</span>" : $rounded_result;
                    echo "</li>";
                    break;
                    
                case 'C':
                    echo "<li>f($current_x) = ";
                    echo ($rounded_result == "error") ? 
                         "<span class='error'>error</span>" : $rounded_result;
                    echo "</li>";
                    break;
                    
                case 'D':
                    $num = $i + 1;
                    echo "<tr>";
                    echo "<td>$num</td>";
                    echo "<td>" . round($current_x, 2) . "</td>";
                    echo "<td>";
                    echo ($rounded_result == "error") ? 
                         "<span class='error'>error</span>" : $rounded_result;
                    echo "</td>";
                    echo "</tr>";
                    break;
                    
                case 'E':
                    echo "<div>";
                    echo "f(" . round($current_x, 2) . ")<br>= ";
                    echo ($rounded_result == "error") ? 
                         "<span class='error'>error</span>" : $rounded_result;
                    echo "</div>";
                    break;
            }
        }
        
        // Закрытие структуры
        if ($layout_type == 'B') echo "</ul>";
        if ($layout_type == 'C') echo "</ol>";
        if ($layout_type == 'D') echo "</table>";
        
        echo "</div>";
        
        // ===== СТАТИСТИКА =====
        if (!empty($values)) {
            $min = min($values);
            $max = max($values);
            $average = round($sum / count($values), 3);
            $total_sum = round($sum, 3);
        ?>
        
        <div class="stats">
            <h2>📊 Статистика вычислений (Функция №3)</h2>
            <div class="stat-grid">
                <div class="stat-item">
                    Минимальное f(x)<br>
                    <span class="stat-value"><?php echo $min; ?></span>
                </div>
                <div class="stat-item">
                    Максимальное f(x)<br>
                    <span class="stat-value"><?php echo $max; ?></span>
                </div>
                <div class="stat-item">
                    Среднее арифметическое<br>
                    <span class="stat-value"><?php echo $average; ?></span>
                </div>
                <div class="stat-item">
                    Сумма всех значений<br>
                    <span class="stat-value"><?php echo $total_sum; ?></span>
                </div>
            </div>
        </div>
        
        <?php } else { ?>
        <div class="stats">
            <p class="error">Нет вычисленных значений для статистики.</p>
        </div>
        <?php } ?>
        
        <div class="controls">
            <h3>⚙️ Параметры вычислений</h3>
            <p><strong>Функция №3:</strong><br>
               f(x) = 3x³ + 2, если x ≤ 10<br>
               f(x) = 5x + 7, если 10 < x < 20<br>
               f(x) = x/(22 - x) - x, если x ≥ 20
            </p>
            <p>Начальное x: <?php echo $start_x; ?> | Количество: <?php echo $count; ?> | Шаг: <?php echo $step; ?></p>
            <p>Ограничения: f(x) ∈ [<?php echo $min_f; ?>, <?php echo $max_f; ?>]</p>
            <p>Вычислено значений: <?php echo count($values); ?></p>
            
            <div class="layout-switcher">
                <h4>Изменить тип верстки:</h4>
                <p>
                    <?php
                    $types = ['A' => 'Текст', 'B' => 'Маркированный список', 
                              'C' => 'Нумерованный список', 'D' => 'Таблица', 
                              'E' => 'Блочная верстка'];
                    foreach ($types as $key => $name) {
                        $active = ($layout_type == $key) ? "style='background: #e67e22;'" : "";
                        echo "<a href='?type=$key' style='margin: 0 5px; padding: 5px 10px; background: #3498db; color: white; border-radius: 5px; text-decoration: none;' $active>$name ($key)</a> ";
                    }
                    ?>
                </p>
            </div>
        </div>
    </main>

    <footer>
        <?php
        // московское время
        $moscow_time = date('d.m.Y H:i:s');
        ?>
        <p>© 2024 Кафедра информационных технологий | Лабораторная работа №2</p>
        <p>Тип верстки: <span class="layout-type"><?php echo $layout_type; ?></span> | 
           Всего значений: <?php echo count($values); ?> | 
           Время (МСК): <?php echo $moscow_time; ?></p>
    </footer>
</body>
</html>
