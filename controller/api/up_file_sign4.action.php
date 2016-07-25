<?php

 class up_file_sign4 extends SystemAction {

	public function init(){
		$db = System::load_sys_class("model");
		$q = $db->Query("
ALTER TABLE  `@#_member_del` 
ADD `money1` decimal(11,2) NOT NULL,
ADD `yongjin` decimal(11,2) NOT NULL;
		");

		if($q){
			unlink(__FILE__);
			_message("升级成功");
		}

	}

 }

?>