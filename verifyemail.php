<?php  
set_time_limit(0);
ini_set('memory_limit','2048M'); 
require 'vendor/autoload.php';
//这个方法非常高级 非常准确！ 判定是valid的 可信度高；判定为invalid的 可以弄台服务器上 配好dns，在上面运行这个脚本来解决
//https://github.com/hbattat/verifyEmail
//要配置spf 然后验证邮箱 script要在这个服务器上跑才行的！要有个固定的ip 并且 还要spf dkim这些都能通过才行 看一下上面这个帖子如何说的
//这个似乎用box 那个服务器来跑 才靠谱呢！好好想一下 最好能用阿里云来跑！

//比较麻烦 先mailgun来弄吧！

require_once('./../basic.php');
verify();
function verify(){
	$url = "http://www.baidu.com"; 
	if(!varify_url($url)){ 
		echo "\n".'network broken!';exit;	
	}
	global $db;
	$record=$db->get('xixi_emails_better',['email','id'],[
		'valid_hbattat'=>'no_hbattat',
		]);
	if(!$record){
		echo 'all done!';
		exit;
	}
	$result=verifyEmail($record['email'],$record['id']);
	if($result){
		$resultText='yes_hbattat2'."\n";
	}else{
		$resultText='no_hbattat2'."\n";
	}
	echo ' | '.$resultText;
	$db->update('xixi_emails_better',['valid_hbattat'=>$resultText],[
			'id'=>$record['id'],
			]);
	
	
	verify();

}

function verifyEmail($email,$id){

$ve = new hbattat\VerifyEmail($email, 'gjenjoying@me.com');

print_r($ve->get_errors());
echo $id.' '.$email.' done';
	return $ve->verify();
}

function varify_url($url){ 
$check = @fopen($url,"r"); 
if($check){ 
 $status = true; 
}else{ 
 $status = false; 
}  
return $status; 
}