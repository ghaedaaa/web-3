<?php
class Menu {
    private $currentPage;
    private $currentSort;
    
    public function __construct() {
        $this->currentPage = isset($_GET['p']) ? $_GET['p'] : 'viewer';
        $this->currentSort = isset($_GET['sort']) ? $_GET['sort'] : 'byid';
        
        // Проверка допустимых значений
        $allowedPages = ['viewer', 'add', 'edit', 'delete'];
        if(!in_array($this->currentPage, $allowedPages)) {
            $this->currentPage = 'viewer';
        }
        
        $allowedSorts = ['byid', 'bysurname', 'bybirth'];
        if(!in_array($this->currentSort, $allowedSorts)) {
            $this->currentSort = 'byid';
        }
    }
    
    public function render() {
        $html = '<div id="menu">';
        
        // Пункт "Просмотр"
        $html .= '<a href="/lab9/index.php?p=viewer&sort=' . $this->currentSort . '"';
        if($this->currentPage == 'viewer') $html .= ' class="selected"';
        $html .= '>Просмотр</a>';
        
        // Пункт "Добавление записи"
        $html .= '<a href="/lab9/index.php?p=add"';
        if($this->currentPage == 'add') $html .= ' class="selected"';
        $html .= '>Добавление записи</a>';
        
        // Пункт "Редактирование записи"
        $html .= '<a href="/lab9/index.php?p=edit"';
        if($this->currentPage == 'edit') $html .= ' class="selected"';
        $html .= '>Редактирование записи</a>';
        
        // Пункт "Удаление записи"
        $html .= '<a href="/lab9/index.php?p=delete"';
        if($this->currentPage == 'delete') $html .= ' class="selected"';
        $html .= '>Удаление записи</a>';
        
        $html .= '</div>';
        
        // Подменю для просмотра
        if($this->currentPage == 'viewer') {
            $html .= '<div id="submenu">';
            
            $html .= '<a href="/lab9/index.php?p=viewer&sort=byid"';
            if($this->currentSort == 'byid') $html .= ' class="selected"';
            $html .= '>По умолчанию</a>';
            
            $html .= '<a href="/lab9/index.php?p=viewer&sort=bysurname"';
            if($this->currentSort == 'bysurname') $html .= ' class="selected"';
            $html .= '>По фамилии</a>';
            
            $html .= '<a href="/lab9/index.php?p=viewer&sort=bybirth"';
            if($this->currentSort == 'bybirth') $html .= ' class="selected"';
            $html .= '>По дате рождения</a>';
            
            $html .= '</div>';
        }
        
        return $html;
    }
}
?>
