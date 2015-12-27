<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><link rel="stylesheet" href="__PUBLIC__/css/ui.css"><link rel="stylesheet" href="__PUBLIC__/css/main.css"><link rel="stylesheet" href="__PUBLIC__/js/datetimepicker/jquery.datetimepicker.css"><style>
	body {
		width: 800px;
		margin: 20px auto;
	}
	</style><script src="__PUBLIC__/js/jquery-2.1.4.min.js"></script><script src="__PUBLIC__/js/datetimepicker/jquery.datetimepicker.full.min.js"></script><title><?php echo ($title); ?>-创新部图书申请系统</title></head><body><span style="width:250px;"><span style="font-size:25px;">创新部图书购买申请系统</span><span style="padding-left:50px;font-size:14px;color:#0E2D5F;">hi, <?php echo ($user['name']); ?><a href="<?php echo U('Index/logout');?>">退出</a></span></span><style>
table.book_list tr td {
	    padding: 2px 5px;
		    border-collapse: collapse;
			    text-align: center;
				    font-weight: bold;
	background: #CBE2FB;
	border: 1px solid white;
	vertical-align: top;
}
table.book_list tr th {
	    padding: 2px 5px;
		    border-collapse: collapse;
			    text-align: center;
				    font-weight: bold;
	background: #CBE2FB;
	border: 1px solid white;
	vertical-align: top;
}
</style><div class="ui-tab"><ul class="ui-tab-items"><li class="ui-tab-item ui-tab-item-current"><a href="index.php?c=Index&a=index">我的</a></li><?php  if ($user['role'] == 2){ ?><li class="ui-tab-item"><a href="index.php?c=Index&a=team">团队</a></li><?php }?></ul></div><div class='add_book' style='padding-top:10px;'><div class="error" style="color:red;"></div><input type='text' id='book_name' placeholder="请填写书名"/><input type='text' id='book_url' placeholder="请填写购买网址"/><button id='btn_add_book'>添加</button></div><div class='book_list' style="padding-top:10px;"><table class='book_list'><tr><th>id</th><th>批次</th><th>书名</th><th>购买地址</th><th>添加时间</th><th>删除</th></tr><?php foreach($book_list as $book){?><tr><td class="book_id"><?=$book['id'];?></td><td><?=$book['gname'];?></td><td><?=$book['name'];?></td><td><?=$book['url'];?></td><td><?=$book['add_time'];?></td><td><?php if ($book['can_del']){?><span><a class="del_book" style="color:red;">delete</a></span><?php } else {?><span style="color:gray;">finished</span><?php }?></td></tr><?php
 } ?></table></div><script type="text/javascript">
	$(function(){
		$("#btn_add_book").click(function(){
			var $this = $(this).parent();
			var nameVal = $('#book_name').val();
			var urlVal = $('#book_url').val();
			if ($.trim(nameVal) == "") {
				$this.find('.error').html('书名不能为空');
				$('#book_name').focus();
				return false;
			}
			if ($.trim(urlVal) == "") {
				$this.find('.error').html('购买地址不能为空！');
				$('#book_url').focus();
				return false;
			}
			$.ajax({
				url: 'index.php?c=Book&a=addBook',
				data: {name:nameVal,url:urlVal},
				type: 'post',
				dataType: "json",
				error: function (ret, error) {
					$this.find('.error').html('添加失败');
				},
				success: function (ret) {
					if (ret.status === 1) {
						if(document.referrer){
							location.href = 'index.php?c=Index&a=index';
						}else{
							location.href = 'index.php?c=Index&a=index';
						}
						$this.find('.error').html('添加成功');
					} else {
						$this.find('.error').html(ret.info);
					}
				}
			});
		});
	});
	$(".del_book").click(function(){
		var book_id = $(this).parent().parent().siblings('.book_id').html();
		$.ajax({
				url: 'index.php?c=Book&a=deleteBook',
				data: {id:book_id},
				type: 'post',
				dataType: "json",
				error: function (ret, error) {
					$this.find('.error').html('删除失败');
				},
				success: function (ret) {
					if (ret.status === 1) {
						if(document.referrer){
							location.href = 'index.php?c=Index&a=index';
						}else{
							location.href = 'index.php?c=Index&a=index';
						}
						$this.find('.error').html('删除成功');
					} else {
						$this.find('.error').html(ret.info);
					}
				}
			});
	})
	</script></body></html>