<?php 
	error_reporting(E_ALL);	
	include_once('conf.php');
		//print_r($_GET);exit;
    if (! isset($_GET['gid']) || ! isset($_GET['passwd'])) {
        echo '哈哈！搞错了吧！';
        exit;	
    } else {
    		$group = intval(trim($_GET['gid']));
    		$passwd = trim($_GET['passwd']);
        $sql = " select * from `group` where id = $group and password = '$passwd' ";
        $result = $mysql->fetchOne($sql);
        if (empty($result)) {
            echo '哈哈！搞错了吧';
        		exit;		
        }
        
        //get personnel
        $sql = " select * from personnel where gid = ".$result['id'];
        $personnel = $mysql->fetchAll($sql);
        if (empty($personnel)) {
            echo '哈哈！您还没有开通哦！';
            exit;	
        }
        
    }
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $result['gname'];?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="http://cdn.bootcss.com/twitter-bootstrap/3.0.3/css/bootstrap.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.min.js"></script>
        <script src="http://cdn.bootcss.com/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body style="width:800px;margin-left:20%; " >
    <h1 class="text-center">Hello, <?php echo $result['gname'];?>!</h1>
    
<?php

// load mysql

$year = intval(date('Y',time()));
$month = intval(date('m',time()));
$day = intval(date('d',time()));

// get today info 
$sql = " select a.*, sum(b.money) as money , b.who, c.name from category a left join behavior b on a.id = b.cid 
left join personnel c on b.who = c.id  where b.year = $year and b.month = $month and b.day = $day and c.gid = ".$result['id']." 
group by b.cid,b.who order by a.id asc ";
$datas = $mysql->fetchAll($sql);

$today = getDatas($datas,$personnel);


// get month info
$sql = " select a.*, sum(b.money) as money , b.who, c.name from category a left join behavior b on a.id = b.cid 
left join personnel c on b.who = c.id  where b.year = $year and b.month = $month and c.gid = ".$result['id']."   
group by b.cid,b.who order by a.id asc ";
$month = $mysql->fetchAll($sql);
$month = getDatas($month,$personnel);
//echo '<pre>';
//print_r($month);exit;

// get year info
$sql = " select a.*, sum(b.money) as money , b.who, c.name from category a left join behavior b on a.id = b.cid 
left join personnel c on b.who = c.id  where b.year = $year and c.gid = ".$result['id']."  
group by b.cid,b.who order by a.id asc ";
$year = $mysql->fetchAll($sql);
$year = getDatas($year,$personnel);


function getDatas($datas,$personnel) {
    if (! is_array($datas)) {
        echo 'error';
        exit;	
    }
    
    $sum = array(0=>array('name'=>'total','sum'=>0));
    $money = array();
    foreach ($personnel as $k => $v) {
        $sum[$v['id']] = array('name'=>$v['nameabb'],'sum'=>0);	
        $money[$v['id']] = array('name'=>$v['nameabb'],'money'=>0);
    }    
    
    //print_r($money);exit;
    
    $data = array();
    foreach($datas as $k => $v) {
    	
        // get all total     	
        $sum[0]['sum']+= $v['money'];
        $sum[$v['who']]['sum']+=$v['money'];
        
        
        // get category total
        
        if (! isset($data[$v['id']])) {
            $data[$v['id']] = $v;
            $data[$v['id']]['money'] = $money;	
        }
        $data[$v['id']]['money'][$v['who']]['money']+= $v['money'];
        
        //get sort total
        $total = 0;
        foreach ($data[$v['id']]['money'] as $key => $val) {
            $total+=$val['money'];	
        }

        $data[$v['id']]['total'] = $total;
        unset($data['name']);	
    }
    
    return $datas = array('sum'=>$sum, 'list'=>$data);	
    
}
?>

		<h2>today</h2>
		<div class="bs-example " >
      <div class="table-responsive  table-hover table-condensed table-bordered">
        <table class="table" >
          <thead>
            <tr>
              <th>#</th>
              <th>类别</th>
              <th>Money</th>
              <th>百分比</th>
            </tr>
          </thead>
          <tbody>
<?php
        foreach($today['list'] as $k => $v) {
?>          	
            <tr>
              <td><?php echo $v['id'];?></td>
              <td><?php echo $v['cname'];?></td>
              <td><?php echo $v['total'].'('?>
           <?php
               foreach ($v['money'] as $key => $val) {
                   echo $val['name'].':'.$val['money'].'  ';	
               }
           ?>   	
              	<?php echo ')元';?></td>              	
              <td><?php echo  empty($v['total'])? 0 : round($v['total']/$today['sum'][0]['sum']*100,2);echo '%';?></td>
            </tr>
<?php
        }
?>            
	
						<tr>
              <td colspan=4 >共计消费：<?php echo $today['sum'][0]['sum'];?>元
              	&nbsp;
         <?php 
             foreach($today['sum'] as $k => $v) {
                 if ($k != 0 ) {	  
         ?>     	
              	
              	     <?php echo $v['name']?>：<?php echo $v['sum'];?>元
              	    （<?php echo empty($v['sum'])? 0 : round($v['sum']/$today['sum'][0]['sum']*100,2); echo '%'; ?>） 
        <?php 
        	      }
            }
        ?>      	
             </td>
          	</tr>	
          </tbody>
        </table>
      </div><!-- /.table-responsive -->
      
      
			<h2>this month</h2>
      <div class="table-responsive  table-hover table-condensed table-bordered">
        <table class="table" >
          <thead>
            <tr>
              <th>#</th>
              <th>类别</th>
              <th>Money</th>
              <th>百分比</th>
            </tr>
          </thead>
          <tbody>
<?php
        foreach($month['list'] as $k => $v) {
?>          	
            <tr>
              <td><?php echo $v['id'];?></td>
              <td><?php echo $v['cname'];?></td>
              <td><?php echo $v['total'].'('?>
           <?php
               foreach ($v['money'] as $key => $val) {
                   echo $val['name'].':'.$val['money'].'  ';	
               }
           ?>   	
              	<?php echo ')元';?></td>
              <td><?php echo  empty($v['total'])? 0 : round($v['total']/$month['sum'][0]['sum']*100,2);echo '%';?></td>
            </tr>
<?php
        }
?>            
	
						<tr>
              <td colspan=4 >共计消费：<?php echo $month['sum'][0]['sum'];?>元 
              	&nbsp;
         <?php 
             foreach($month['sum'] as $k => $v) {
                 if ($k != 0 ) {	  
         ?>     	
              	
              	     <?php echo $v['name']?>：<?php echo $v['sum'];?>元
              	    （<?php echo empty($v['sum'])? 0 : round($v['sum']/$month['sum'][0]['sum']*100,2); echo '%'; ?>） 
        <?php 
        	      }
            }
        ?>      	
             </td>
          	</tr>	
          </tbody>
        </table>
      </div><!-- /.table-responsive -->
      
      <h2>this year</h2>
      <div class="table-responsive  table-hover table-condensed table-bordered">
        <table class="table" >
          <thead>
            <tr>
              <th>#</th>
              <th>类别</th>
              <th>Money</th>
              <th>百分比</th>
            </tr>
          </thead>
          <tbody>
<?php
        foreach($year['list'] as $k => $v) {
?>          	
            <tr>
              <td><?php echo $v['id'];?></td>
              <td><?php echo $v['cname'];?></td>
              <td><?php echo $v['total'].'('?>
           <?php
               foreach ($v['money'] as $key => $val) {
                   echo $val['name'].':'.$val['money'].'  ';	
               }
           ?>   	
              	<?php echo ')元';?></td>
              <td><?php echo  empty($v['total'])? 0 : round($v['total']/$year['sum'][0]['sum']*100,2);echo '%';?></td>
            </tr>
<?php
        }
?>            
	
						<tr>
              <td colspan=4 >共计消费：<?php echo $year['sum'][0]['sum'];?>元 
              	&nbsp;
         <?php 
             foreach($year['sum'] as $k => $v) {
                 if ($k != 0 ) {	  
         ?>     	
              	
              	     <?php echo $v['name']?>：<?php echo $v['sum'];?>元
              	    （<?php echo empty($v['sum'])? 0 : round($v['sum']/$year['sum'][0]['sum']*100,2); echo '%'; ?>） 
        <?php 
        	      }
            }
        ?>      	
             </td>
          	</tr>	
          </tbody>
        </table>
      </div><!-- /.table-responsive -->
      
		</div>


		<div class="table-responsive  table-hover table-condensed table-bordered">
        <table class="table" >
        	<tr>
        		<td colspan=4 align="center" >
        			@by <a href="http://cnblogs.com/darktime">河边的老牛</a>
        		</td>
        	</tr>
        </table>
    </div>    	
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="http://cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="http://cdn.bootcss.com/twitter-bootstrap/3.0.3/js/bootstrap.min.js"></script>
  </body>
</html>

 
