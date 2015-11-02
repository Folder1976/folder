<?php

echo '<h1>Универсальные артиклы товаров</h1>';
set_time_limit(0);
echo '<h3><a href=\'/admin/setup.php\'>>> Настройки</a></h3>';
?>
<ul class="setup_menu">
    <li><a href="alternative_artikles/export.php">Экспортировать Excel</a></li>
    <li></li>
    <li><a href="main.php?func=alternative_artikles&import">Импортировать Excel</a></li>
</ul>
<?php

    if(isset($_GET['import'])){
        include_once('import.php');
    }

?>