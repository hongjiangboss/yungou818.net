<?php

 class up_file_signschy extends SystemAction {

	public function init(){
		$db = System::load_sys_class("model");
		$q = $db->Query("
ALTER TABLE  `@#_member_del` ADD  `openid` varchar(255) NOT NULL DEFAULT '';
		");

		if($q){
			unlink(__FILE__);
			_message("升级成功");
		}

	}

 }

?>