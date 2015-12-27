<?php
class BookGroupModel  extends Model{
	protected $tableName = 'bookgroup';

	public function genId()
	{
		$g = D('BookGroup')->field('id')->where(['status'=>0])->order('id desc')->find();

		if (!$g['id'])
		{
			$data = [
				'name'=>date('Y-m'),
				'add_time'=>date('Y-m-d H:i:s'),
				'status'=>'0',
			];
			$id = D('BookGroup')->add($data);
			return $id;
		}
		return $g['id'];
	}
}
