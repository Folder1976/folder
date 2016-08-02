<?php
header ('Content-Type: text/html; charset=utf8');
include 'init.lib.php';
connect_to_mysql();
session_start();
?>
<h1>Cписок товаров которые ожидают клиенты</h1>

<?php
   $sql = 'SELECT T.tovar_artkl,
			  T.tovar_name_1 AS name,
			  T.tovar_id,
			  TW.email
		FROM tbl_tovar_wait TW
		LEFT JOIN tbl_tovar T ON T.tovar_artkl = TW.tovar_artkl
		ORDER BY tovar_name_1, TW.email;';
   $r = $folder->query($sql) or die($sql);
   
	if($r->num_rows > 0){ ?>
        <table>
            <tr>
                <th>id</th>
                <th>Артикл</th>
                <th>Название</th>
                <th>email</th>
            </tr>
        
		<?php while($tmp = $r->fetch_assoc()){ ?>
            <tr>
                <td><?php echo $tmp['tovar_id'];?></td>
                <td><?php echo $tmp['tovar_artkl'];?></td>
                <td><?php echo $tmp['name'];?></td>
                <td><?php echo $tmp['email'];?></td>
            </tr>
		  
		  
		<?php } ?>
	  
      </table>
	<?php }
    
    
    
    
    ?>
<style>
    td{
        padding: 5px;
    }
</style>