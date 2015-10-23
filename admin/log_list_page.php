<?php


echo "\n<frameset id='log_" , $_REQUEST['ip'], "' rows='150,*'>";
echo "\n<frame name='logh_" , $_REQUEST['ip'] , "' src='log_list_page_header.php?ip=" , $_REQUEST['ip'] , "'>";
echo "\n<frame name='logview_" , $_REQUEST['ip'] , "' src=''>";
echo "\n</frameset></html>";

?>
