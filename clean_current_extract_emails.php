<?php 

//此处是把所有的email现存的处理一下  //后面用navicat就可以把yahoo的处理掉
//以后存 要提取出email来存 而不要直接存

require_once('./../basic.php');
require_once './../EmailExtract.php';

set_time_limit(0);
ini_set('memory_limit','2048M'); 

//现有的emails_better里面 确认都是email

//1.提取每个email（用for 循环），用正则取出其中的email
//2.如果取出的email数量大于1，则把其中的每个email都 存一下（存之前 先确认是否有存在），最后把这条多记录的删除掉！
//3.如果取出的email数量等于1，不用处理了；取出的email为0，则报错 看一下

for ($i=13504; $i < 1025139 ; $i++) { 
	$emailtext=$db->get('xixi_emails_better','email',[
		'id'=>$i,
		]);

	if(empty($emailtext)){
		continue;
	}

	$emails = EmailExtract::getEmails($emailtext);

	if(count($emails)==0){
		echo "\n\n".'wrong! no email found! id is: '.$i.' '.$emailtext;
		$db->delete('xixi_emails_better',['id'=>$i]);
	}
	if(count($emails)>1){
		foreach ($emails as $key => $email) {
			saveemail($email,$i);
		}
		$db->delete('xixi_emails_better',['id'=>$i]);
	}
	//echo $i.' is done!';

}

function saveemail($email,$id){
	global $db;
	
	$exist=$db->get('xixi_emails_better','id',[
		'email'=>$email
		]);
	if(!$exist){
		$db->insert('xixi_emails_better',[
			'email'=>$email,
			'bounced'=>'unknown',
		]);
		echo $email.' added'."\n";
	}	
}