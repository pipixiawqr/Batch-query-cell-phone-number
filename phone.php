<?php
	error_reporting(0);
	//关闭错误报告
	//自定义函数，访问查询接口，并匹配出手机号归属地信息
	function address($url){
		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_HEADER,0);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$res=curl_exec($ch);
		preg_match_all('/carrier:\'(.*?)\'/is', $res, $matches);
		return $matches[1][0];
		curl_close($ch);	
	}
	//使用淘宝查询手机归属地接口
	$url='https://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel=';
	//手机号批量存在文件中
	//需要读取的文件绝对路径
	$fh = fopen('C:\Users\pipix\Desktop\phone.txt', 'r'); 
		while (!feof($fh)) { 
	    	$line = fgets($fh); 
	    	//按行读取文件过程中，会将行字符"\r\n"一并读取，所以将其替换为空格
	    	$line=str_replace(array("\n","\r"),"",$line);
	    	$phone=$url.$line; 
	    	$res=address($phone);
			if (!$res) {
				continue;
			}
			$res=$line."	".$res."\r\n";
			//写入的文件，写绝对路径
			file_put_contents('C:\Users\pipix\Desktop\res.txt', $res,FILE_APPEND);	
	 	}    
	 fclose($fh); 
?>