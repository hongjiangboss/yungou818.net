<?php

 class up_file_signglqx extends SystemAction {

	public function init(){
		$db = System::load_sys_class("model");
		$q = $db->Query("
ALTER TABLE  `@#_admin` ADD    `xitong` int(11) NOT NULL DEFAULT '0',
ADD  `yuny` int(11) NOT NULL DEFAULT '0',
ADD  `artic` int(11) NOT NULL DEFAULT '0',
ADD  `shop` int(11) NOT NULL DEFAULT '0',
ADD  `exchange` int(11) NOT NULL DEFAULT '0',
ADD  `bus` int(11) NOT NULL DEFAULT '0',
ADD  `user` int(11) NOT NULL DEFAULT '0',
ADD  `tp` int(11) NOT NULL DEFAULT '0',
ADD  `pl` int(11) NOT NULL DEFAULT '0',
ADD  `guanli` int(11) NOT NULL DEFAULT '0';
		");

		if($q){
			unlink(__FILE__);
			_message("升级成功");
		}

	}

 }

?>