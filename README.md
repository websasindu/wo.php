# wo.php
Web O/S Simple And Secure PHP Library And Framework

#usege 
<code>
<?php
include("wo.php");

$res=wo_query("table","WHERE id=1");
if($res){
  while($row=$res->fetch_assoc()){
  //Do anything
  }
}else{
  echo' res not found';
}

$data_insert=array(
  "id"  => 1,
  "username"  =>  "sasindu",
  "email" =>  "info@sasindu.com"
);
if(is_array($data_insert)){
  $inst=InsertOrUpdate("table",$data_insert);
  if($inst){
    echo 'Last id:".$inst;
  }else{
    echo 'SQL error';
  }
}

$data_update=array(
  "id"  => 1,
  "username"  =>  "sasindu",
  "email" =>  "wophp@sasindu.com"
);
if(is_array($data_insert)){
  $inst=InsertOrUpdate("table",$data_update,"WHERE id=1");
  if($inst){
    echo 'Update id:".$inst;
  }else{
    echo 'SQL error';
  }
}
</code>
