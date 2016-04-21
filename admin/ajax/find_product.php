<?php
	include_once ('../config/config.php');

	$id = '';
	if(isset($_POST['id'])) $id = $_POST['id'];
	
	$value = '';
	if(isset($_POST['value'])) $value = $_POST['value'];
	
	$searchq = $value;
	
      $sql = "SELECT 	
               `tovar_artkl`,
               `tovar_model`,
               `tovar_name_1` AS tovar_name,
               T.tovar_id
               FROM 
               `tbl_tovar` T
                WHERE 
                (upper(`tovar_artkl`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%' or
                upper(`tovar_name_1`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%')
          	   and `tovar_inet_id` > 0 
               ORDER BY `tovar_name_1` ASC
               LIMIT 0, 50";
			   
	$r = $folder->query($sql) or die('find product - ' . $sql);
	
	$return = array();
	$return['id'] = $id;
	?>
		<table class="find_result">
			<tr>
				<th>ид</th>
				<th width="150px">Арткл</th>
				<th width="600px">Название</th>
				<th width="100px">Поставщик</th>
				<th width="100px">Закуп</th>
				<th width="100px">Розница</th>
				<th width="50px">Дней</th>
			</tr>
		
	<?php if($r->num_rows == 0){ ?> 
		<tr><td colspan="4">Нет продуктов</td></tr>				
	<?php }else{
		while($tmp = $r->fetch_assoc()){
			
				$sql = 'SELECT
						TSI.tovar_id,
						TSI.postav_id,
						K.klienti_name_1 AS postav_name,
						K.delivery_days,
						TSI.zakup,
						CUR.currency_name_shot AS cur_name,
						TSI.price_1,
						TSI.items
						FROM tbl_tovar_suppliers_items TSI
						LEFT JOIN tbl_currency CUR ON CUR.currency_id = TSI.zakup_curr
						LEFT JOIN tbl_klienti K ON K.klienti_id = TSI.postav_id
						WHERE TSI.tovar_id = "'.$tmp['tovar_id'].'";';
				$r1 = $folder->query($sql) or die('find product - ' . $sql);
			
			?>
		
			<tr>
				<td rowspan=""><?php echo $tmp['tovar_id']; ?></td>	
				<td><?php echo $tmp['tovar_artkl']; ?></td>	
				<td><?php echo $tmp['tovar_name']; ?></td>	
				<td colspan="4" style="padding: 0;">
					<table class="find_result postav">
					<?php while($tmp1 = $r1->fetch_assoc()){ ?>
						<tr class="select_product"
									data-id="<?php echo $tmp1['tovar_id']; ?>"
									data-postav="<?php echo $tmp1['postav_id']; ?>"
									data-days="<?php echo $tmp1['delivery_days']; ?>"
									data-operation="<?php echo $id; ?>"
									data-zakup="<?php echo $tmp1['zakup']; ?>"
									data-price="<?php echo $tmp1['price_1']; ?>"
									>
							<?php if($tmp1['items'] > 0 ){ ?>
								<td width="100px" style="color: #009B05;"><?php echo $tmp1['postav_name']; ?></td>
							<?php }else{ ?>
								<td width="100px" style="color: #D30000;"><?php echo $tmp1['postav_name']; ?></td>
							<?php } ?>
							<td width="100px" align="right"><?php echo $tmp1['zakup']; ?> <?php echo $tmp1['cur_name']; ?></td>
							<td width="100px" align="right"><?php echo $tmp1['price_1']; ?> руб.</td>
							<td width="50px" align="center"><b><?php echo $tmp1['delivery_days']; ?></b></td>
						</tr>
					<?php } ?>
					</table>
				</td>	
			</tr>
			
		<?php } 
	} ?>
	</table>


