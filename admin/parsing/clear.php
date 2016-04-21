<?php
set_time_limit(0);

//Подчистим базу
	$sql = "SELECT t1.description_tovar_id FROM tbl_description AS t1 WHERE t1.description_tovar_id NOT IN (SELECT t2.tovar_id FROM tbl_tovar AS t2)";
	$r = $folder->query($sql);
	if($r->num_rows > 0){
		echo '<br>Описания - <b>'.$r->num_rows.'</b>';
		$por = array();
		while($tmp = $r->fetch_assoc()){
			$por[] = $tmp['description_tovar_id'];
		}
		$sql = "DELETE FROM tbl_description WHERE description_tovar_id IN (".implode(',',$por).");";
		$r = $folder->query($sql);
	}

	$sql = "SELECT t1.tovar_id FROM tbl_tovar_suppliers_items AS t1 WHERE t1.tovar_id NOT IN (SELECT t2.tovar_id FROM tbl_tovar AS t2)";
	$r = $folder->query($sql);
	if($r->num_rows > 0){
		echo '<br>Цены поставщиков - <b>'.$r->num_rows.'</b>';
		$por = array();
		while($tmp = $r->fetch_assoc()){
			$por[] = $tmp['tovar_id'];
		}
		$sql = "DELETE FROM tbl_tovar_suppliers_items WHERE tovar_id IN (".implode(',',$por).");";
		$r = $folder->query($sql);
	}

	$sql = "SELECT t1.product_id FROM tbl_tovar_links AS t1 WHERE t1.product_id NOT IN (SELECT t2.tovar_id FROM tbl_tovar AS t2)";
	$r = $folder->query($sql);
	if($r->num_rows > 0){
		echo '<br>Линки на поставщиков - <b>'.$r->num_rows.'</b>';
		$por = array();
		while($tmp = $r->fetch_assoc()){
			$por[] = $tmp['product_id'];
		}
		$sql = "DELETE FROM tbl_tovar_links WHERE product_id IN (".implode(',',$por).");";
		$r = $folder->query($sql);
	}

	$sql = "SELECT seo_url FROM tbl_seo_url WHERE seo_url like 'tovar_id=%';";
	$r = $folder->query($sql);
	$dell = 0;
	if($r->num_rows > 0){
		while($tmp = $r->fetch_assoc()){
			
			$product_id = str_replace('tovar_id=', '', $tmp['seo_url']);
			$sql = 'SELECT tovar_id FROM tbl_tovar WHERE tovar_id = "'.$product_id.'";';
			$rt = $folder->query($sql);	
			
			if($rt->num_rows == 0){
				$sql = "DELETE FROM tbl_seo_url WHERE seo_url = 'tovar_id=$product_id';";
				//echo $sql.'<br>';
				$folder->query($sql);
				$dell++;
			}
			
		}
		if($dell > 0){
			echo '<br>Алиасы - <b>'.$dell.'</b>';
		}
	}
	
echo '<br>Почистил';	
