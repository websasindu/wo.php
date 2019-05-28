<?php
/**
 * ==========================================================
 *                  WO.PHP SIMPLE AND SECURE 
 *                    BUILD IT AND DO EASY
 * ==========================================================
 * Project name : wo.php -web o/s (public-release)
 * Version : v1.0 - wop
 * Released Date: 2019 MAY 28
 * Author : Sasindu.com
 * Developer : Sasindu Jayampathi
 * Developer Email : iwantm8@gmail.com
 * ----------------------------------------------------------
 * License: Distributed under the Lesser General Public License (LGPL) 
 * http://www.gnu.org/copyleft/lesser.html
 * This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.   
 */
/*
if (version_compare(PHP_VERSION, '5.0.0', '<') ){
    exit("Sorry, this version of wo.php will only run on PHP version 5 or greater!");
}*/
$wo_live=true;//true = connect database and work with database , false = run without database
if($wo_live===TRUE){

    /**database.php */
    $servername="localhost";
    $username="root";
    $database="wophp";
    /**end database.php */

    if(!isset($servername)){
        exit("<title>wo.php version 1</title>Welcome to WO.PHP, just you missed config your database connection...so that is very simple.. To config app/db/database.php");
    }else{
        @$conn =new mysqli($servername, $username, $password, $database);
        if ($conn->connect_error) {
            die('
                <style>
                    .error_on_hold{width:60%;margin:0px auto;padding:10px;margin-top:20px;background:#000;text-align: center;border-radius:4px;}
                    .logowo{text-align: centerr;width:100%;}
                    .logowo img{width:128px;heigh:128px;}
                    .error_msg{padding:3px;}
                    .error_msg h3{color:#fff;text-align: center}
                </style>
                <div class="error_on_hold">
                    <div class="logowo">
                        <img src="wophp.png"/>
                    </div>
                    <div class="error_msg">
                        <h3>wo.php Failed Connect To Database,</h3>
                        <h3>Error : '.$conn->connect_error.'</h3>
                    </div>
                    <div class="link_area">
                        <a href="http://sasindu.com">Find docs</a>
                    </div>
                </div>
            ');
        }else{
            $wo_selfmode=false;
        }
    }
}else{
    $wo_selfmode=true;
}

function wo_query($table,$query,$orderby=false,$order=false,$limit=false){
    global $conn;
    if($table){
        if(isset($query)){
            $q=$query;
        }
        if($orderby!=false && $order !=false){
            if(isset($q)){
                $q=$q." ORDER BY ".$orderby." ".$order;
            }else{
                $q=" ORDER BY ".$orderby." ".$order;
            }
        }
        if($limit !=false){
            if(isset($q)){
                $q=$q." LIMIT ".$limit;
            }else{
                $q=" LIMIT ".$limit;
            }
        }
        if($result=$conn->query("SELECT * FROM `".$table."` ".$q)){
            return $result;
        }else{
            return false;
        }
    }else{
        return false;
    }
}
function wo_rowquery($table,$sql){
	global $conn;
	if(!empty($sql)){
	$sql2="SELECT * FROM ".$table." ".$sql;
	}else{
		$sql2="SELECT * FROM ".$table;
	}
	$sql2="SELECT * FROM ".$table." ".$sql;
	$output=array();
	$result = $conn->query($sql2);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$output=$row;
			}
			return $output;
		}else{
			return false;
		}
	
}
function wo_update($table,$value,$sql){
	global $conn;
	$query = "UPDATE ".$table." SET ".$value." ".$sql;
    if ($conn->query($query) === TRUE) {
        return true;
    }else{
        return false;
    }
}
function wo_delete($table,$sql){
	global $conn;
	if(!empty($sql)){
		$query = "DELETE FROM ".$table." ".$sql;
        if ($conn->query($query) === TRUE) {
            return true;
        }else{
            return false;
        }
	}else{
		$query = "DELETE FROM ".$table;
        if ($conn->query($query) === TRUE) {
            return true;
        }else{
            return false;
        }
	}
}
function InsertOrUpdate($table,$data,$where=false,$emptypass=false){
    global $conn;
    if($where !=false){
        $chk=wo_rowcount($table,$where);
    }else{
        $chk=0;
    }
	if($chk > 0){
		$d=array();
		if(!$emptypass){
			foreach($data as $r => $key){
				if($r !=""){
					$d[$r]=$key;
				}
			}
		}else{
			foreach($data as $r => $key){
				$d[$r]=$key;
			}
		}
		$sql='';
		foreach ($d as $key => $row) {
			if($sql ==""){
				$sql=$sql.$key."='".$row."'";
			}else{
				@$sql=$sql.",".$key."='".$row."'";
			}
		}
		$upd=wo_update($table,$sql,$where);
		if($upd){
			return true;
		}else{
			return false;
		}
	}else{
		$sql='';
		$d=array();
		if($emptypass==true){
			foreach($data as $r => $key){
				if($r !=""){
					$d[$r]=$key;
				}
			}
		}else{
			foreach($data as $r => $key){
				$d[$r]=$key;
			}
		}
		$rows="";
		$value="";
		foreach ($d as $key => $row) {
			if($rows =="" && $value==""){
				$rows="`".$key."`";
				$values="'".$row."'";
			}else{
				$rows=$rows.",`".$key."`";
				$values=$values.",'".$row."'";
			}
		}
		$inst=$conn->query("INSERT INTO ".$table." (".$rows.") VALUES (".$values.")");
		if($inst=="Ok"){
			$last_id_query=$conn->insert_id;
			return $last_id_query;
		}else{
			return false;
		}

	}
}
function verifyajax() {
	if ($_SERVER['HTTP_X_REQUESTED_WITH'] != "XMLHttpRequest") {
		header("location: " . $_SERVER['HTTP_REFERER']);
		exit();
	}
}
function getToken($token_name) {
	$token = md5(uniqid(rand(), true));
	$_SESSION['token']=array();
	$token_time = time();
	$_SESSION['token'][$token_name] = $token;
	$_SESSION['token'][$token_name] = array("token" => $token, "time" => $token_time);
	return $token;
}

function verifyToken($token_name, $token) {
	if (!isset($_SESSION['token'][$token_name])) {
		return false;
	}
	if ($_SESSION['token'][$token_name]['token'] != $token) {
		return false;
	}
	$token_age = time() - $_SESSION['token'][$token_name]['time'];
	if (600 <= $token_age) {
		return false;
	}
	return true;
}
function DestroyToken($token_name, $token){
	if (!isset($_SESSION['token'][$token_name])) {
		return false;
	}
	if ($_SESSION['token'][$token_name]['token'] != $token) {
		return false;
	}
	$_SESSION['token'][$token_name]['token']="";
}
function validateEmail($email) {
	if (!preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email)) {
		return false;
	}
	return true;
}
function wo_getCurrentUri(){
    $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
    $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
    if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
    $uri = '/' . trim($uri, '/');
    return $uri;
}
function count_million($input){
    $input = number_format($input);
    $input_count = substr_count($input, ',');
    if($input_count != '0'){
        if($input_count == '1'){
            return $input;
        } else if($input_count == '2'){
            return substr($input, 0, -8).'M';
        } else if($input_count == '3'){
            return substr($input, 0,  -12).'B';
        } else {
            return;
        }
    } else {
        return $input;
    }
}
function wo_dot_limit_char($x, $length)
{
  if(strlen($x)<=$length)
  {
    return $x;
  }
  else
  {
    $y=substr($x,0,$length) . '...';
    return $y;
  }
}
function wo_non_limit_char($x, $length)
{
  if(strlen($x)<=$length)
  {
    return $x;
  }
  else
  {
    $y=substr($x,0,$length);
    return $y;
  }
}
function wo_array($string,$input,$sparete,$qute,$qmode,$up){
	/**
	 * $string = Elements need to add
	 * $input= Exiting Elemts
	 * $sparete = 0 =, 1=. 2=/ 3=| 4=-
	 * $qute = if true add array start and end
	 * $qmode = 0 = [array] 1= {array}
	 */
	if($up==true){
		$up="'";
	}else{
		$up="";
	}
	if($qute == true && $qmode==0){
		$start="[";
		$end="]";
	}
	if($qute == true && $qmode==1){
		$start="{";
		$end="}";
	}
	if(!isset($start) && !isset($end)){
		$start="";$end="";
	}
	if($sparete ==0){
		$sparete=",";
	}elseif($sparete==1){
		$sparete=".";
	}elseif($sparete==2){
		$sparete="/";
	}elseif($sparete==3){
		$sparete="|";
	}elseif($sparete==4){
		$sparete="-";
	}
	if(!isset($sparete)){
		$sparete=",";
	}
	$fl=substr($input,0,1);
	if($fl!=","){
		$sparete="";
	}else{
		$sparete=$sparete;
	}
	if($input){
		$out=$start.$sparete.$up.$string.$up.$end;
	}else{
		$out=$start.$string.$end;
	}
	return $out;
}
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
function wo_time_ago($timestamp){  
      $time_ago =$timestamp;  
      $current_time = time();
	  $this_m=date("h:i a",$time_ago);
	  $riginal=date("Y-m-d h:i a",$time_ago);
      $time_difference = $current_time - $time_ago;  
      $seconds = $time_difference;  
      $minutes      = round($seconds / 60 );                // value 60 is seconds  
      $hours           = round($seconds / 3600);           //value 3600 is 60 minutes * 60 sec  
      $days          = round($seconds / 86400);           //86400 = 24 * 60 * 60;  
      $weeks          = round($seconds / 604800);        // 7*24*60*60;  
      $months          = round($seconds / 2629440);     //((365+365+365+365+366)/5/12)*24*60*60  
      $years          = round($seconds / 31553280);    //(365+365+365+365+366)/5 * 24 * 60 * 60  
    if($seconds <= 60) {  
        return "Just Now";  
    }  
      else if($minutes <=60)  
      {  
     if($minutes==1)  
           {  
       return "one minute ago";  
     }  
     else  
           {  
       return "$minutes minutes ago";  
     }  
   }  
      else if($hours <=24)  
      {  
     if($hours==1)  
           {  
       return "today ".$this_m;  
     }  
           else  
           {  
       return "$hours hours ago";  
     }  
   }  
      else if($days <= 7)  
      {  
     if($days==1)  
           {  
       return "yesterday ".$this_m;  
     }  
           else  
           {  
       return "yesterday ".$this_m;  
     }  
   }  
      else if($weeks <= 4.3) //4.3 == 52/12  
      {  
     if($weeks==1)  
           {  
       return $riginal;  
     }  
           else  
           {  
       return $riginal;  
     }  
   }  
       else if($months <=12)  
      {  
     if($months==1)  
           {  
       return $riginal;  
     }  
           else  
           {  
       return $riginal;  
     }  
   }  
      else  
      {  
     if($years==1)  
           {  
       return $riginal;  
     }  
           else  
           {  
       return $riginal;  
     }  
   }  
 }
function escape_string($string){
	global $conn;
	if($string !=""){
		$out=mysqli_real_escape_string($conn,$string);
		return $out;
	}else{
		return false;
	}
}
function HideNumberX($number){
	if($number){
		$all=str_split($number);
		$out_number='';
		$count=0;
		foreach($all as $key=> $val){
			if($count < 4){
				$out_number=$out_number.$val;
			}else{
				$out_number=$out_number."X";
			}
			$count=$count +1;
		}
		return $out_number;
	}else{
		return false;
	}
}
function wo_get_ramdom_string($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>
