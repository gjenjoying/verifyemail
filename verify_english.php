<?php 
//这个是专门给xixisys e-chem用的
//rl的另外弄一个 因为db中的field有些不同了 文件名称也不同
require 'vendor/autoload.php';
require_once 'medoo.php';
$db = new Medoo([
	'database_type' => 'sqlite',
	'database_file' => 'verify_by_do_english.sqlite'
]);



set_time_limit(0);
ini_set('memory_limit','2048M'); 

verify();
function verify(){

	global $db;
	$record=$db->get('xixi_new_email',['email','id'],[
		'valid'=>null,
		]);
	
	if(!$record){
		echo 'all done!';
		exit;
	}
	
	$db->update('xixi_new_email',[
		'valid'=>'doing'
		],[
		'id'=>$record['id'],
		]);
	
	$result=verifyEmail($record['email'],$record['id']);
	if($result){
		$resultText='yes';
	}else{
		$resultText='no';
	}
	date_default_timezone_set('Asia/Shanghai');
	echo ' | '.$result['id'].' '.$resultText.' at '.date('Y-m-d H:i:s')."\n";
	$db->update('xixi_new_email',['valid'=>$resultText],[
			'id'=>$record['id'],
			]);
	
	
	verify();

}

function verifyEmail($email,$id){

//设置！
$names=array('david','joe','rose','mike','brown','trumb','amy','ellen','Aaron','Abbas','Abelard','Ableson','Abner','Adolph','Aesculapius','Aelfric','Baal','Bacchus','Balaam','Caldwell','Carlson','Carney','Dailey','Damian','Dante');
$domains=array('@vip.findokay.com','@info.findokay.com','@email.findokay.com','@mail.findokay.com','@notice.findokay.com','@shanghai.findokay.com','@beijing.findokay.com','@nanjing.findokay.com','@hangzhou.findokay.com','@chengdu.findokay.com','@shenzhen.findokay.com','@guangzhou.findokay.com','@chongqing.findokay.com');//这个要真设置了才能写的！

$from=$names[array_rand($names,1)].rand(10,100).$domains[array_rand($domains,1)];

$ve = new hbattat\VerifyEmail($email, $from);

print_r($ve->get_errors());
echo $id.' '.$email.' veirfied by '.$from.' result: ';
	return $ve->verify();
}