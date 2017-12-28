<?php 
//调用WP的配置文件,别小看这个文件哦,这里改成你的blog的路径. 
require_once('wp-config.php'); 
//这个函数从中文工具箱中copy的 
//调用wp-config.php文件的目的主要是使用他的db查询功能,可以自己写连接MYSQL部份,但是觉得没有必要. 
//主要应用在$wpdb变量中 
function get_recent_posts($no_posts = 5, $before = '<li>', $after = '</li>', $show_pass_post = false, $skip_posts = 0) { 
    global $wpdb, $tableposts; 
    $request = "SELECT ID, post_title, post_date, post_content FROM $wpdb->posts WHERE post_status = 'publish' "; 
        if(!$show_pass_post) { $request .= "AND post_password ='' "; } 
    $request .= "ORDER BY post_date DESC LIMIT $skip_posts, $no_posts"; 
    $posts = $wpdb->get_results($request); 
    $output = ''; 
    foreach ($posts as $post) { 
        $post_title = stripslashes($post->post_title); 
//         $post_date = mysql2date('j.m.Y', $post->post_date); 
        $permalink = get_permalink($post->ID); 
        $output .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' . $before . '<a href="' . $permalink . '" rel="bookmark" title="Permanent Link: ' . $post_title . '">' . $post_title . '</a>'. $after; 
    } 
    return $output; 
}

function mul_excerpt ($excerpt) { 
     $myexcerpt = substr($excerpt,0,255); 
     return utf8_trim($myexcerpt) . '... '; 
}

//执行函数,输出结果,这里你可以去掉下面这行,通过包含本文件来调用get_recent_comments();函数。 
//get_recent_posts();

$content = get_recent_posts(); 
echo $content; 
$countfile="recentposts.html";  //生成一个recentposts文件 
if(!file_exists($countfile))  
{  
    fopen($countfile,"wt+,ccs=UTF-8"); //如果此文件不存在，则自动建立一个  
}  
$fp=fopen($countfile,"rt+,ccs=UTF-8");  
$fp=fopen($countfile,"wt+,ccs=UTF-8");  
fwrite($fp,$content); //更新其值  
fclose($fp);  
?> 
更新完毕，返回<a href="index.php">首页</a>