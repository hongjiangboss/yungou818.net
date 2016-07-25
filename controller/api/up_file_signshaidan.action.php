<?php

 class up_file_signshaidan extends SystemAction {

	public function init(){
		$db = System::load_sys_class("model");
		$q = $db->Query("
ALTER TABLE  `@#_member_go_record` 
ADD  `auto_user` int(11) DEFAULT NULL;
		");

		if($q){
			unlink(__FILE__);
			_message("升级成功");
		}

	}

 }

?>