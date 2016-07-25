<?php

 class up_file_signwxts extends SystemAction {

	public function init(){
		$db = System::load_sys_class("model");
		$q = $db->Query("
ALTER TABLE  `@#_member` 
ADD  `openid` varchar(255) NOT NULL DEFAULT;
		");

		if($q){
			unlink(__FILE__);
			_message("升级成功");
		}

	}

 }

?>