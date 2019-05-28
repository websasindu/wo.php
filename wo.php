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

