<?php

 class up_file_sign extends SystemAction {

	public function init(){
		$db = System::load_sys_class("model");
		$q = $db->Query("
ALTER TABLE  `@#_member` ADD  `sign_in_time` MEDIUMINT( 8 ) NOT NULL DEFAULT  '0' COMMENT  '连续签到天数',
ADD  `sign_in_date` CHAR( 10 ) NOT NULL DEFAULT  '' COMMENT  '上次签到日期',
ADD  `sign_in_time_all` MEDIUMINT( 8 ) NOT NULL DEFAULT  '0' COMMENT  '总签到次数';
		");

		if($q){
			unlink(__FILE__);
			_message("升级成功【福分签到】");
		}

	}

 }

?>