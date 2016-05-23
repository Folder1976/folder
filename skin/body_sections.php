<?php
    $sql = 'SELECT * FROM tbl_info WHERE info_link="press" ORDER BY info_date DESC, info_id DESC LIMIT 0,2;';
	$r = $folder->query($sql);
	//echo $sql;
	$news = array();
    $count = 0;
	while($tmp = $r->fetch_assoc()){
	
		$news[$count]['info_id'] 		= $tmp['info_id'];
		$news[$count]['h1'] 			= $tmp['info_header_1'];
		$news[$count]['title'] 		    = $tmp['info_header_2'];
		$news[$count]['description'] 	=  strip_tags($tmp['info_memo_1']);
		$news[$count]['text'] 		    = str_replace('elFinder-master','/admin/elFinder-master',$tmp['info_memo_1']);
		$news[$count]['date'] 		    = $tmp['info_date'];

        
        preg_match_all("/<img[^>]+>/i", $tmp['info_memo_1'], $out_s);
        
        if(isset($out_s[0][0]) AND strpos($out_s[0][0], 'src="') !== false){
            $tmp = explode('src="', $out_s[0][0]);
            $tmp = explode('"', $tmp[1]);
            $src = $tmp[0];
            $news[$count]['img'] = '/admin/'.$src;
        }
    
      
		$count++;
		
	}
    
          //echo "<pre>";  print_r(var_dump( $news )); echo "</pre>";
        
?>

<article class="section">
    <header class="row section__header">
        <div class="medium-18 columns">
            <h2 class="section__title">Статьи и обзоры</h2>
        </div>

        <div class="medium-6 columns text-right small-only-text-left">
            <a href="/press.html" class="btn btn_light">Читать архив</a>
        </div>
    </header>
    <style>
        .small_row{
            height: 250px;
            overflow: hidden;
        }
    </style>
    <div class="row">
        <div class="medium-12 columns small_row">
            <div class="row announce">
                <div class="large-10 medium-8 columns announce__img">
                    <a href="/press.html">
                        <img src="<?php echo $news[0]['img'];?>" alt="">
                    </a>
                </div>

                <div class="large-14 medium-16 columns">
                    <h3 class="announce__title"><a href="/press.html" class="announce__title-link"><?php echo $news[0]['h1']; ?></a></h3>
                    <div class="announce__sub-head"><time class="announce__date" datetime="<?php echo $news[0]['date']; ?>"><?php echo $news[0]['date']; ?></time>
                        <a href="/press.html">&nbsp;</a></div>
                    <div class="announce__summary"><?php echo $news[0]['description']; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="medium-12 columns small_row">
            <div class="row announce">
                <div class="large-10 medium-8 columns announce__img">
                    <a href="/press.html">
                        <img src="<?php echo $news[1]['img'];?>" alt="">
                    </a>
                </div>

                <div class="large-14 medium-16 columns">
                    <h3 class="announce__title"><a href="/press.html" class="announce__title-link"><?php echo $news[1]['h1']; ?></a></h3>
                    <div class="announce__sub-head"><time class="announce__date" datetime="<?php echo $news[1]['date']; ?>"><?php echo $news[1]['date']; ?></time>
                        <a href="/press.html">&nbsp;</a></div>
                    <div class="announce__summary"><?php echo $news[1]['description']; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>