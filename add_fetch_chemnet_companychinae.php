<?php 
require_once('./../basic.php');
require_once './../EmailExtract.php';

$table='fetch_chemnet_companychinae';
$email_field='con_mail2';
//每一个读出来 存进去
for ($i=22; $i < 4015; $i++) { 
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

