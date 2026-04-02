<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ихкайма Гайдаа - Группа 241-352 - Лабораторная работа №3</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f0f4f8;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            width: 450px;
            text-align: center;
        }

        h1 {
            font-size: 22px;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .result {
            background: #ecf0f1;
            border: 2px solid #bdc3c7;
            border-radius: 15px;
            padding: 20px;
            font-size: 48px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
            min-height: 100px;
            word-wrap: break-word;
        }

        .buttons {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .button {
            background: #3498db;
            color: white;
            text-decoration: none;
            padding: 20px 0;
            border-radius: 15px;
            font-size: 28px;
            font-weight: bold;
            transition: background 0.3s, transform 0.1s;
            display: block;
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        }

        .button:hover {
            background: #2980b9;
            transform: scale(1.05);
        }

        .reset {
            background: #e74c3c;
            grid-column: span 3;
        }

        .reset:hover {
            background: #c0392b;
        }

        footer {
            margin-top: 30px;
            color: #7f8c8d;
            font-size: 16px;
            border-top: 1px solid #bdc3c7;
            padding-top: 15px;
        }

        .click-counter {
            margin: 15px 0 5px;
            font-size: 20px;
            color: #2c3e50;
            font-weight: bold;
            background: #ecf0f1;
            padding: 10px;
            border-radius: 10px;
        }

        .total-clicks {
            font-size: 16px;
            color: #27ae60;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ихкайма Гайдаа (241-352) - ЛР №3</h1>

        <?php
        // Устанавливаем часовой пояс Москвы
        date_default_timezone_set('Europe/Moscow');

        // ===== ЛОГИКА РАБОТЫ =====
        
        // Параметры:
        // store - текущее значение на экране
        // total - общее количество нажатий (НИКОГДА НЕ СБРАСЫВАЕТСЯ)
        
        if (!isset($_GET['store'])) {
            $_GET['store'] = '';
        }
        
        if (!isset($_GET['total'])) {
            $_GET['total'] = 0;
        }
        
        // Обработка нажатия кнопки
        if (isset($_GET['key'])) {
            // Увеличиваем общее количество нажатий (total)
            // total НИКОГДА не сбрасывается!
            $_GET['total']++;
            
            if ($_GET['key'] == 'reset') {
                // Сброс: очищаем store, НО total остаётся
                $_GET['store'] = '';
            } else {
                // Добавляем цифру к строке
                $_GET['store'] .= $_GET['key'];
            }
        }
        
        // Текущие значения
        $current_value = $_GET['store'];
        $total_clicks = $_GET['total'];
        ?>

        <!-- Окно результата -->
        <div class="result">
            <?php echo $current_value !== '' ? htmlspecialchars($current_value) : '&nbsp;'; ?>
        </div>

        <!-- Кнопки цифр -->
        <div class="buttons">
            <?php for ($i = 1; $i <= 9; $i++): ?>
                <a class="button" href="?key=<?php echo $i; ?>&store=<?php echo urlencode($current_value); ?>&total=<?php echo $total_clicks; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
            
            <!-- Кнопка 0 -->
            <a class="button" href="?key=0&store=<?php echo urlencode($current_value); ?>&total=<?php echo $total_clicks; ?>">0</a>
            
            <!-- Кнопка СБРОС -->
            <a class="button reset" href="?key=reset&store=&total=<?php echo $total_clicks; ?>">СБРОС</a>
        </div>

        <!-- عداد الضغطات الكلي -->
        <div class="click-counter">
            Всего нажатий: <?php echo $total_clicks; ?>
        </div>

        <footer>
            © 2026 Кафедра информационных технологий<br>
            Время: <?php echo date('d.m.Y H:i:s'); ?> (МСК)
        </footer>
    </div>
</body>
</html>
