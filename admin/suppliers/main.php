<?php

echo '<h1>Универсальные артиклы товаров</h1>';
set_time_limit(0);
echo '<h3><a href=\'/admin/setup.php\'>>> Настройки</a></h3>';
?>
<ul class="setup_menu">
    <!--li><a href="suppliers/export_items.php">Экспортировать шаблон импорта цен и остатков Excel</a></li-->
    <li></li>
    <li><a href="main.php?func=suppliers&import_items">Импортировать цены и остатки Excel</a></li>
</ul>
<?php

    if(isset($_GET['import_items'])){
        include_once('import_items.php');
    }

?>