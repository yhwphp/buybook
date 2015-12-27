<?php
class BaseAction extends Action
{
	public function _initialize()
	{
		$this->mid = is_login();
		if ($this->mid > 0)
		{
			$user = D('User')->where(['id'=>$this->mid])->find();
			$this->assign('user', $user);
		}
		$this->assign('mid', $this->mid); 
	}

	protected function checkLogin(){
			is_login() || $this->error('你还没有登录，请先登录！', U('Index/login'), 1);
	}

}

