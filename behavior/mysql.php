<?php

/**
 * @description  mysql 基础类 
 * @author 河边的老牛
 * @email qianfeng5511@163.com
 */
     
class Mysql {
	
    public $conn = false;
    
    public function __construct($host='', $port, $username='', $password='', $dbname) {
    	
        $this->conn = mysql_connect("{$host}:{$port}", $username, $password);
        
        if (! $this->conn) {
            die('Could not connect: ' . mysql_error());	
        }
        
        if (! mysql_select_db($dbname, $this->conn)) {
            die ("Can\'t use ".$dbname." : " . mysql_error());
        }
        
        mysql_query("set names utf8",$this->conn);    	
    }
    
    #-------------------------------------
    #@description 查询一条
    #-------------------------------------
    public function fetchOne($sql) {
        $query  = mysql_query($sql, $this->conn);
        if ($row = @mysql_fetch_assoc($query) ) {
            return $row;		
        }	else {
        		return array();	
        }
    }
    
    #-------------------------------------
    #@description 查询所有
    #------------------------------------- 
    public function fetchAll($sql) {
        $query = mysql_query($sql, $this->conn);
        $datas = array();
        while($row = @mysql_fetch_assoc($query) ) {
            $datas[] = $row;	
        }
        return $datas;	
    }
    
            	    	
}