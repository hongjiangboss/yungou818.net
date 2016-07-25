<?php

 class up_file_sign2 extends SystemAction {

	public function init(){
		$db = System::load_sys_class("model");
		$q = $db->Query("
ALTER TABLE  `@#_member_go_record` 
ADD  `money` decimal(11,2) NOT NULL,
ADD  `zhuanhuan` decimal(11,2) NOT NULL,
ADD  `wei` int(11) NOT NULL COMMENT '是否转换 ',
ADD  `leixing` int(11) NOT NULL COMMENT '商品类型',
ADD  `cardId` varchar(50) DEFAULT NULL,
ADD  `cardPwd` varchar(50) DEFAULT NULL,
ADD  `shouhuo` int(11) DEFAULT NULL,
ADD  `qq` varchar(50) DEFAULT NULL,
ADD  `sheng` varchar(50) DEFAULT NULL,
ADD  `shi` varchar(50) DEFAULT NULL,
ADD  `xian` varchar(50) DEFAULT NULL,
ADD  `jiedao` varchar(100) DEFAULT NULL,
ADD  `youbian` int(11) DEFAULT NULL,
ADD  `shouhuoren` varchar(50) DEFAULT NULL,
ADD  `tell` varchar(50) DEFAULT NULL,
ADD  `mobile` varchar(50) DEFAULT NULL,
ADD  `email` varchar(50) DEFAULT NULL,
ADD  `shipRemark` varchar(100) DEFAULT NULL,
ADD  `shipTime` varchar(50) DEFAULT NULL,
ADD  `fhtime` varchar(30) DEFAULT NULL,
ADD  `yuanjia` varchar(30) DEFAULT NULL;
		");

		if($q){
			unlink(__FILE__);
			_message("升级成功");
		}

	}

 }

?>