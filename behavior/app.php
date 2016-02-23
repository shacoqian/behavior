<?php
#--------------------------------
#@description  接口
#--------------------------------

include_once('conf.php');
if (! empty($_POST)) {
    if (! isset($_POST['act'])) {
        rtnData(300, '参数错误！可能是程序出错了！', array());	
    }
    $act = trim($_POST['act']);
    if ($act == 'category') {
    	  if (! isset($_POST['gid'])) {
    	      rtnData(300, '参数错误！', array());	
    	  }
    	  
    	  $gid = intval(trim($_POST['gid']));
    	  
        $sql = " select * from category where gid = ".$gid;
        $datas = $mysql->fetchAll($sql);
        rtnData(200, '查询成功！', $datas);
    	
    } else if($act == 'insert') {
    
        if (! isset($_POST['description']) || ! isset($_POST['money']) || ! isset($_POST['who']) || ! isset($_POST['id'])) {
            rtnData(300, '参数错误！可能是程序出错1！', array());		
        }
    
        $description = trim($_POST['description']);
        $who = intval(trim($_POST['who']));
        $money = trim($_POST['money']);
    		$cid = intval(trim($_POST['id']));
        $year  = date('Y', time());
        $month = date('m', time());
        $day   = date('d', time());
        $ctime = date('H:i:s', time());
    
        $sql = " insert into behavior(description, who, year, month, day, ctime, money, cid)  
        values('".$description."', $who, $year, '".$month."', '".$day."', '".$ctime."', $money, $cid)";
    		
    		
        if (mysql_query($sql)) {
            rtnData(200, '记录成功！', array());		
        } else {
        	  rtnData(300, '数据库错误！', array());		
        }
    } else if ($act == 'who') {
    		#rtnData(300, $_POST, $_POST);	
        //获取人物信息
        if ( isset($_POST['name']) && isset($_POST['passwd'])) {
            $sql = " select a.password, b.gid, b.name, b.id from `group` a left join personnel b on a.id = b.gid where 
            b.name = '".trim($_POST['name'])."' and password = '".md5(trim($_POST['passwd']))."' ";
            $data = $mysql->fetchOne($sql);
            if ( empty($data)) {
                rtnData(300, '11  ', array());	
            } else {
                rtnData(200,'success', $data);	
            }
        } else {
            rtnData(300, '参数错误', array());	
        }	  
    	
    }
    		
}



function rtnData($status, $msg, $datas) {
    exit(json_encode(array($status, $msg, $datas)) );
}


//mysql_close($mysql->conn); 