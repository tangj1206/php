<?php
	/**
	 * 
	 * 公共函数库
	 * @author 你
	 * @data ....
	 * 
	 * 
	 */

	/**
	 * 单文件上传
	 * @param [type] string $upfile [descript] 上传文件的参数(标签名) 如：$_FILES['myfile']
	 * @param [type] string $savePath [descript] 保存到指定目录  如果为空  那么取 uploads 作为默认目录，否则取本身
	 * @param [type] array $allowType [descript] 允许的类型  如果允许类型等于空，我们不限制
	 * @param [type] int $maxSize [descript] 允许的文件大小 如果等于 0 代表不限制
	 * @return [array] $returnResult [descript] 返回给用户的信息
	 */
	function upload($upFile,$savePath = '' ,$allowType = array(),$maxSize = 0){

		// 如果等于 0 代表不限制
		// $maxSize = 0;
		
		// 获取文件上传信息到新的变量
		$userFile = $_FILES[$upFile];

		// 返回给用户的信息
		$returnResult = array('status'=> false , 'msg' => '');

		// 如果允许类型等于空，我们不限制
		//$allowType = array();
		//$allowType = array('image/gif','image/png');
	

		// 保存目录
		//$savePath =   ''; // 如果为空，用户没有指定上传的目录   ./save ./save/
		//$savePath = 'savefile';
		// 如果为空  那么取 uploads 作为默认目录，否则取本身
		$savePath = empty($savePath) ? 'uploads' : $savePath ;
		
		// 处理一下路径
		$savePath = rtrim($savePath,'/') . '/';

		// 如果这个文件不存在 则创建
		if(!file_exists($savePath)){
			mkdir($savePath,0777,true);
		}
	
		// 1.是否上传成功？
		if($userFile['error'] > 0){
			switch($userFile['error']){
				case 1:
					$info = '值为1：表示上传文件的大小超出了约定值。文件大小的最大值是在PHP配置文件中指定的，该指令是：upload_max_filesize。';
					break;
				case 2:
					$info = '值表示上传文件大小超出了HTML表单隐藏域属性的MAX＿FILE＿SIZE元素所指定的最大为2：值。';
					break;
				case 3:
					$info = '值为3：表示文件只被部分上传。';
					break;
				case 4:
					$info = '值为4：表示没有上传任何文件。';
					break;
				case 6:
					$info = '值为6：表示找不到临时文件夹。PHP 4.3.10 和 PHP 5.0.3 引进。';
					break;
				case 7:
					$info = '值为7：表示文件写入失败。PHP 5.1.0 引进。';
					break;
				default :
					$info = '未知错误';
					break;
			}
			// 将错误信息存储在 下表 msg  里面
			$returnResult['msg'] = $info;
			return $returnResult; // 返回信息
		}
		
		
		// 2.验证大小
		if($maxSize > 0){ // 如果大于 0 表示已经限定大小了
			if($userFile['size'] > $maxSize){
				$returnResult['msg'] = '超出大小限制,最大为:' . $maxSize;
				return $returnResult;
			}
		}

		// 3.验证类型
		if(count($allowType) > 0){
			// 在判断类型
			if(!in_array($userFile['type'],$allowType)){
				$returnResult['msg'] = '文件类型不符合';
				return $returnResult;
			}
		}

		// 4.
		// 4.1生成扩展名
		$ext = pathinfo($userFile['name'],PATHINFO_EXTENSION);
		// 4.2随机文件名字
		do{
			$newName = md5(time() . uniqid() . mt_rand(1,9999999)) . '.' . $ext;
		// 如果这个文件已经存在，再干一次
		}while(file_exists($savePath . $newName));
		
		// 5.执行上传
		// 判断文件是否是通过 HTTP POST 上传
		if(is_uploaded_file($userFile['tmp_name'])){
			// 移动上传的文件
			if(move_uploaded_file($userFile['tmp_name'],$savePath . $newName)){
				// 这里就等于  完全成功了
				$returnResult['status'] = true;
				$returnResult['imageName'] = $newName;

				return $returnResult;
			}
		}else{

			$returnResult['msg'] = '非法上传!';
			return $returnResult;
		}
	}


	/**
	 * 获取网络资源或者本地资源
	 * @param [type] string $url [description] 地址
	 * @return true
	 *
	 */

	function getNetSource($url)
	{
		// 源  如果是网络资源，那么打开方式只能是 r 
		//$path = 'https://ss1.baidu.com/6ONXsjip0QIZ8tyhnq/it/u=1562117557,2028538133&fm=58';
		// 打开
		$source = fopen($url,'r');
		
		$content = '';

		while(!feof($source)){
			// 把字节读取到 content
			$content .= fread($source, 8192);
			//测试文件指针是否到了文件结束的位置 
		}

		fclose($source);
		
		// 打开一个新的资源，（不存在）
		$sourceTwo = fopen(basename($url),'a+');

		// 把$content 写入到新的资源
		fwrite($sourceTwo, $content);

		fclose($sourceTwo);

		// 告诉用户，任务完成
		return true;
	}

	/**
	 * 图片裁剪
	 * @param [type] string $picpath [description] 图片路径
	 * @param [type] int    $x [description] 图片的起始坐标x
	 * @param [type] int    $y [description] 图片的起始坐标y
	 * @param [type] int    $cutWidth  [description] 要切割的宽
	 * @param [type] int    $cutHeight [description] 要切割的高
	 * @return null
	 */

	function cut($picpath,$x,$y,$cutWidth,$cutHeight)
	{

		// 图片的信息
		$imageInfo = getimagesize($picpath);
		// 原图宽
		$w = $imageInfo[0];
		// 原图宽高
		$h = $imageInfo[1];
		
		// 如果要切割的图片比原图还要大，则停止
		if($cutWidth > $w | $cutHeight > $h){
			exit('图片尺寸不合适');
		}

		// 切割后缀
		$mime = explode('/',$imageInfo['mime']);
		// 获取图片的后缀
		$subType = $mime[1];
		
		
		// 打开要裁剪的图片
		$imagefrom = 'imagecreatefrom' . $subType;
			// imagecreatefromjpeg();
			// imagecreatefrompng();

		// $iamgefrom 就是一个变量函数
		$imagesrc = $imagefrom($picpath);
		// $imagesrc = imagecreatefromjpeg($picpath);

		// 新建画布
		$imagedes = imagecreatetruecolor($cutWidth,$cutHeight);
		
		// 重采样拷贝部分图像并调整大小
		imagecopyresampled(
			$imagedes, // 画布
			$imagesrc, // 切割的原图
			0,0, // 从画布的开始坐标
			$x,$y, // 原图的开始坐标
			$cutWidth, // 画布的宽
			$cutHeight, // 画布的高

			$cutWidth, // 从原图身上的切割的宽

			$cutHeight // 从原图身上的切割的高
		);

		// 保存输出
		header('Content-Type:image/' . $subType);

		//  就是一个变量函数
		$imageecho = 'image'.$subType;
		$imageecho($imagedes);
		//imagejpeg($imagedes);

		// 释放资源
		imagedestroy($imagesrc);
		imagedestroy($imagedes);
	
	}
	
	/**
	 * 图片缩放
	 * @param [type] string $imagePath [description] 图片路径
	 * @param [type] $savePath [description] 缩放后的图片保存路径
	 * @param [type] number $zoomSize [description] 缩放的大小
	 * @param [type] number $zoomHeight [description] 如果不为空，就是指定宽高缩放
	 * @return null
	 */

	function zoom($imagePath,$savePath,$zoomSize,$zoomHeight = null)
	{

		// 获取图片的信息
		$imageInfo = getimagesize($imagePath);
		
		// 原图的宽
		$imageWidth = $imageInfo[0];
		// 原图的高
		$imageHeight = $imageInfo[1];
		
		// 获取图片的后缀
		$suffix = explode('/',$imageInfo['mime'])[1];

		/*
			图片缩放两种情况
				宽值比高要大
					那么宽的值直接等于缩放后的值
				高值比宽要大
				那么高的值直接等于缩放后的值

				
		$zoomWith = 400;
		$zoomHeight ?  按照比例计算

		$zoomHeight = 400
		$zoomWith ? 按照比例计算

		*/
		if(!$zoomHeight){
			// 求缩放后的宽高
			if($imageWidth > $imageHeight)
			{
				$zoomWidth = $zoomSize;
				$zoomHeight = ($imageHeight / $imageWidth) * $zoomWidth ;
				//100 / 200 = 200 / 400;
			}else{
				$zoomHeight = $zoomSize;
				$zoomWidth =  ($imageWidth / $imageHeight) * $zoomHeight;
			}
		}else{
			$zoomWidth = $zoomSize;
		}



		// 打开要处理的图片
		$imagefrom = 'imagecreatefrom' . $suffix;
		//$imagesrc = imagecreatefromjpeg($imagePath);
		$imagesrc = $imagefrom($imagePath);

		// 创建指定尺寸的画布
		$canvas = imagecreatetruecolor($zoomWidth,$zoomHeight);
		
		imagecopyresampled(
			$canvas, // 画布
			$imagesrc, // 原图
			0,0,  // 画布的起始
			0,0,  // 原图的起始

			$zoomWidth, // 画布的size
			$zoomHeight,

			$imageWidth,//imagesx($imagesrc), // 原图的宽
			$imageHeight//imagesy($imagesrc) // 原图的高
		);



		// header('content-type:image/' . $suffix); 不让输出
		$imageecho = 'image' . $suffix;
		//imagejpeg($canvas);
		// $imageecho($canvas); 不让输出

		// 处理路径
		$savePath = trim($savePath , '/') . '/';

		// 如果保存的目录不存在，则新建之
		if(!file_exists($savePath)){
			mkdir($savePath,0777,true);
		}

		// 随机图片名
		$imageName = md5( mt_rand(1,9999999) . uniqid()) .'.' .$suffix;

		// 另存为图片
		$imageecho($canvas, $savePath.$imageName);

		imagedestroy($imagesrc);
		imagedestroy($canvas);

		// 返回上传后的图片名
		return $imageName;

	}


	// 专门用于  增  删 改
	function execu($sql){
		// 1.连接
		$link = @mysqli_connect(HOST,USER,PASS,DB) or exit('连接失败！');
		// 设置字符集
		mysqli_set_charset($link,CHARSET);

		// 发送执行
		$result = mysqli_query($link,$sql);
		// 如果执行成功 和受影响行大于 0
		if($result && mysqli_affected_rows($link) > 0){

			return mysqli_insert_id($link) ? mysqli_insert_id($link) : mysqli_affected_rows($link);

		}
		mysqli_close($link);
	}


	// 专门用于数据库的查询
	function query($sql){
		// 1.连接
		$link = @mysqli_connect(HOST,USER,PASS,DB) or exit('连接失败！');
		// 设置字符集
		mysqli_set_charset($link,CHARSET);

		// 发送执行
		$result = mysqli_query($link,$sql);


		// 添加判断，如果有结果，循环结果集
		if($result){
			$list = array();
			// 循环遍历结果集
			while($row = @mysqli_fetch_assoc($result)){
				$list[] = $row;
			}

			// 释放资源
			@mysqli_free_result($result);
			// 关闭连接
			mysqli_close($link);

			// 直接把遍历好的数据返回
			return $list;
		}
		return false;
	}
