<?php
/**
 * Created by hebo on 2015/10/26.
 */
class IndexAction extends BaseAction
{
    public function index(){
		error_log('index', 3, '/tmp/buy.log');
		$this->checkLogin();
		$M = M('Book');
		$book_list = $M->where(['uid'=>$this->mid])->order('id desc')->select();
		foreach($book_list as &$value)
		{
			$g = D('BookGroup')->field('status,name')->where(['id'=>$value['gid']])->find();
			$value['gname']=$g['name'];
			$value['can_del']=$g['status'] > 0 ? false : true;
		}
        $this->assign('book_list', $book_list);
        $this->assign('title', '我的');
        $this->display('');
    }

    public function team(){
        $this->assign('title', '团队');
		$ing_groups = D('BookGroup')->where(['status'=>0])->select();
		Log::record(print_r($ing_groups, true));
		foreach($ing_groups as &$ing_g)
		{
			$books = D('Book')->where(['gid'=>$ing_g['id']])->order('uid asc,id desc')->select();
			foreach($books as &$book)
			{
				$user = D('User')->where(['id'=>$book['uid']])->find();
				$book['uname']=$user['name'];
			}
			$ing_g['books'] =$books;
		}

		$ed_groups = D('BookGroup')->where(['status'=>1])->select();
		foreach($ed_groups as &$ed)
		{
			$books = D('Book')->where(['gid'=>$ed['id']])->order('uid asc,id desc')->select();
			foreach($books as &$book)
			{
				$user = D('User')->where(['id'=>$book['uid']])->find();
				$book['uname']=$user['name'];
			}
			$ed['books'] =$books;
		}
		$this->assign('ing_groups', $ing_groups);
		$this->assign('ed_groups', $ed_groups);
        $this->display('');
    }

	public function deleteBook()
	{
		$this->checkLogin();
        $id = $this->_param('id');
        $M = M('Book');
		$res = $M->where(['id'=>$id])->delete();
		if($res){
			$this->ajaxReturn($res,'删除成功',1);
		}else{
			$this->ajaxReturn(0,'删除失败',0);
		}
	}

	public function addBook()
	{
		$this->checkLogin();
        $name = $this->_param('name');
        $url = $this->_param('url');
		$add_time = date('Y-m-d H:i:s');
		$gid = D('BookGroup')->genId();
		$data = array(
			'name'=>$name,
			'url'=>$url,
			'add_time'=>$add_time,
			'uid'=>$this->mid,
			'gid'=>$gid,
		);
        $M = M('Book');
		$res = $M->add($data);
		if($res){
			$this->ajaxReturn($res,'添加成功',1);
		}else{
			$this->ajaxReturn(0,'添加失败',0);
		}
	}

    public function finishGroup(){
		$this->checkLogin();
        $id = $this->_param('id');
        $M = M('bookgroup');
		$res = $M->where(['id'=>$id])->save(['status'=>1]);
		if($res){
			$this->ajaxReturn($res,'成功',1);
		}else{
			$this->ajaxReturn(0,'失败',0);
		}
    }


    public function login(){
        //dump(session('loginId'));
        $this->display();
    }

    public function loginPost(){
        $username = $this->_param('username');
        $password = $this->_param('password'); $M = M('User');
        $condition['name'] = $username;
        $users = $M->where($condition)->limit(1)->select();
        $user = $users[0];
        if(!$user){
            $this->ajaxReturn(0,"用户不存在！",0);
        }else{
            if($user['password'] != md5($password)){
                session('loginId',null);
                $this->ajaxReturn(0,"密码不正确！",0);
            }else{
				$auth = ['uid'=>$user['id'], 'username'=>$user['name']];
				session('user_auth', $auth);
				session('user_auth_sign', data_auth_sign($auth));
                $this->ajaxReturn(0,"登录成功！",1);
            }
        }

    }

	public function logout()
	{
		if (is_login())
		{
			session('user_auth', null);
			session( 'user_auth_sign', null );
			cookie('uid', null);
		}
		$this->redirect('login');
	}
}
