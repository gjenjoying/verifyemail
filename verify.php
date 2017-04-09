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

$ve = new hbattat\VerifyEmail($email, 'me@reachlinked.org');

print_r($ve->get_errors());
echo $id.' '.$email.' done';
	return $ve->verify();
}