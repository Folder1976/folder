<?php

$id=$_REQUEST['id'];

echo "
<boby>";

$count=0;
while($count<20){

    echo "<br><img src=\"http://folder.com.ua/resources/products/$id/$id.$count.large.jpg\" height=100px>";

$count++;
 } 
echo "\n</body>";

?>
