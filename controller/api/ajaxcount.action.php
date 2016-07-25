<?php

class ajaxcount extends SystemAction {

	//ajax
	// public function buy_count(){
		// $db = System::load_sys_class('model');
		// $info = $db->GetOne("select sum(gonumber) as count from `@#_member_go_record`");
		// if(empty($info)){
			// echo json_encode(array("status"=>0,"count"=>12345));die;
		// }
		// echo json_encode(array("status"=>0,"count"=>$info['count']));die;
	// }
	
	public function buy_count(){

		$db = System::load_sys_class('model');

		$info = $db->GetOne("select sum(gonumber) as count from `@#_member_go_record`");

		$arr['state'] = 0;

		$arr['count'] = $info['count'] ? $info['count']+1000100000 : 1000100000;

		$arr['fundTotal'] = $info['count'] ? $info['count']/100 : '1000010.00';

		$fun = $this->segment(4);

		$fun = explode('=', $fun);

		$fun = $fun[1];

		$fun = explode('&', $fun);

		$fun = $fun[0];

		echo $fun.'('.json_encode($arr).')';die;

	}

}

?>