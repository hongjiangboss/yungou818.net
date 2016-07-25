<?php

 class up_file_sign1 extends SystemAction {

	public function init(){
		$db = System::load_sys_class("model");
		$q = $db->Query("
ALTER TABLE  `@#_shoplist` ADD  `yuanjia` decimal(11,2) NOT NULL,
ADD  `cardId` varchar(50) DEFAULT NULL,
ADD  `cardPwd` varchar(50) DEFAULT NULL,
ADD  `leixing` int(11) NOT NULL,
ADD  `pv` int(11) NOT NULL DEFAULT '1';
		");

		if($q){
			unlink(__FILE__);
			_message("升级成功");
		}

	}

 }

?>