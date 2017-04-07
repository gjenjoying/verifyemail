<?php 
require 'vendor/autoload.php';
require_once 'medoo.php';
$db = new Medoo([
	'database_type' => 'sqlite',
	'database_file' => 'verifyemail.sqlite'
]);

set_time_limit(0);
ini_set('memory_limit','2048M'); 

verify();
function verify(){
	// $url = "http://www.baidu.com"; 
	// if(!varify_url($url)){ 
	// 	echo "\n".'network broken!';exit;	
	// }
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
		$resultText='yes_hbattat_do'."\n";
	}else{
		$resultText='no_hbattat_do'."\n";
	}
	echo ' | '.$resultText;
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

function varify_url($url){ 
$check = @fopen($url,"r"); 
if($check){ 
 $status = true; 
}else{ 
 $status = false; 
}  
return $status; 
}