<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Записная книжка - Лабораторная работа №9</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php
        // Подключаем модуль меню
        require 'menu.php';
        
        // Создаем объект меню и выводим его
        $menu = new Menu();
        echo $menu->render();
        
        // Определяем, какой модуль загрузить
        if(!isset($_GET['p']) || $_GET['p'] == 'viewer') {
            $_GET['p'] = 'viewer';
            include 'viewer.php';
        }
        else if($_GET['p'] == 'add') {
            include 'add.php';
        }
        else if($_GET['p'] == 'edit') {
            include 'edit.php';
        }
        else if($_GET['p'] == 'delete') {
            include 'delete.php';
        }
        else {
            echo '<div class="error">Ошибка: неверный параметр</div>';
        }
        ?>
    </div>
</body>
</html>
