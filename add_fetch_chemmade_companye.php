<?php 
require_once('./../basic.php');
require_once './../EmailExtract.php';

//每一个读出来 存进去
for ($i=457; $i < 5300; $i++) { 
	$emailrecord=$db1->get('fetch_chemmade_companye',['company','email'],[
		'id'=>$i
		]);
	if(empty($emailrecord['email'])){
		continue;
	}

	$emails = EmailExtract::getEmails($emailrecord['email']);
	if(count($emails)>0){
		foreach ($emails as $key => $email) {
			saveemail($email,$emailrecord['company']);
		}
	}

}


function saveemail($email,$company){
	global $db;
	
	$exist=$db->get('xixi_emails_better','id',[
		'email'=>$email
		]);
	if(!$exist){
		$db->insert('xixi_emails_better',[
			'email'=>$email,
			'bounced'=>'unknown',
			'company'=>$company,
			'note'=>'from fetch_chemmade_companyc',
		]);
		echo $email.' added'."\n";
	}	
}

