<?php 
require 'vendor/autoload.php';
require_once 'medoo.php';
$db = new Medoo([
	'database_type' => 'sqlite',
	'database_file' => 'verify_by_do.sqlite'
]);



set_time_limit(0);
ini_set('memory_limit','2048M'); 

verify();
function verify(){

	global $db;
	$record=$db->get('xixi_emails_better',['email','id'],[
		'valid_hbattat'=>'no_hbattat',
		]);
	
	if(!$record){
		echo 'all done!';
		exit;
	}
	
	$db->update('xixi_emails_better',[
		'valid_hbattat'=>'doing'
		],[
		'id'=>$record['id'],
		]);
	
	$result=verifyEmail($record['email'],$record['id']);
	if($result){
		$resultText='yes_hbattat_do2';
	}else{
		$resultText='no_hbattat_do2';
	}
	echo ' | '.$result['id'].' '.$resultText."\n";
	$db->update('xixi_emails_better',['valid_hbattat'=>$resultText],[
			'id'=>$record['id'],
			]);
	
	
	verify();

}

function verifyEmail($email,$id){

//设置！
$names=array('david','black','joe','rose','mike','brown','zend','yii','laravel','thinkphp','basf','trumb');
$domains=array('@vip.jhclothes.com','@info.jhclothes.com','@email.jhclothes.com','@mail.jhclothes.com','@notcie.jhclothes.com');//这个要真设置了才能写的！

$from=$names[array_rand($names,1)].rand(10,100).$domains[array_rand($domains,1)];

$ve = new hbattat\VerifyEmail($email, $from);

print_r($ve->get_errors());
echo $id.' '.$email.' done';
	return $ve->verify();
}