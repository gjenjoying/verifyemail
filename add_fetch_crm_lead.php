<?php 
require_once('./../basic.php');
require_once './../EmailExtract.php';

set_time_limit(0);
ini_set('memory_limit','2048M'); 

$table='fetch_crm_lead';
$email_field='email1';
//每一个读出来 存进去
for ($i=1; $i < 676016; $i++) { //676016
	$emailrecord=$db1->get($table,['company',$email_field],[
		'id'=>$i
		]);
	if(empty($emailrecord[$email_field])){
		continue;
	}

	$emails = EmailExtract::getEmails($emailrecord[$email_field]);

	if(count($emails)>0){
		foreach ($emails as $key => $email) {
			saveemail($email,$emailrecord[$email_field]);
		}
	}
	echo $i.' ';

}


function saveemail($email,$company){
	global $db;
	global $table;
	$exist=$db->get('xixi_emails_better','id',[
		'email'=>$email
		]);
	if(!$exist){
		$db->insert('xixi_emails_better',[
			'email'=>$email,
			'bounced'=>'unknown',
			'company'=>$company,
			'note'=>'from '.$table,
		]);
		echo $email.' added'."\n";
	}	
}

