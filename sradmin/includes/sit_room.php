<?php 
session_start();

error_reporting(0);
require_once "MysqliDb.php";


class sit_room extends MysqliDb
{
    // connection variables
    const DB_host = "localhost";
    const DB_user = "situatio_product";
    const DB_password = "sr*123";
    const DB_database = "situatio_prod";

    // useful variables
    static $main_title = "Situation Room";
    static $base_url = "http://situationroom.ewn.com.au/sradmin/";
    static $default_logo = "";
    public $data;
    public $arr = array();
     
    public function __construct($host,$user,$password,$database)
    {
        $connection = mysql_connect($host,$user,$password) or die(mysql_error());
        mysql_select_db($database) or die(mysql_error());
    } 

    public function get_one_client($client_id)
    {
        $this->data = mysql_query("SELECT * FROM `clients` WHERE `id` = '".$client_id."' LIMIT 1 ");
        return  mysql_fetch_assoc($this->data);
    }

    public function get_one_layer($layer_id)
    {
        $this->data = mysql_query("SELECT * FROM `layers` WHERE `id` = '".$layer_id."' LIMIT 1 ");
        return  mysql_fetch_assoc($this->data);
    }

     public function get_one_layer_details($layer_id)
    {
        $this->data = mysql_query("SELECT * FROM `layer_datas` WHERE `layer_data_id` = '".$layer_id."' LIMIT 1 ");
        return  mysql_fetch_assoc($this->data);
    }

    public function get_layer_name($layer_id)
    {
        $this->data = mysql_query("SELECT `layer_type` FROM `layer_datas` WHERE `layer_id` = '".$layer_id."' LIMIT 1");
        $row = mysql_fetch_assoc($this->data);
        return $row['layer_type'];
    }

    public function get_client_layer($client_id)
    {
        $this->data = mysql_query("SELECT `layer_id` FROM `client_layers` WHERE `client_id` = '".$client_id."'");
        if(mysql_num_rows($this->data) > 0)  {
            while ($row = mysql_fetch_assoc($this->data)) {
                $this->arr[] = $row['layer_id'];
            }
            return $this->arr;
        }
        else {
            return 0;
        }
    }

    public function get_count($table,$where){
        if($table == "layer_datas") {
            $count_value = "layer_data_id";
        } else {
            $count_value = "id";
        }
        $this->data = mysql_query("SELECT count(`$count_value`) as `count_rows` FROM $table $where");
        $row = mysql_fetch_assoc($this->data);
        return $row['count_rows'];
    }
  
    public function getAllClient(){
       $this->data = mysql_query("SELECT id,username FROM `clients` WHERE is_deleted = 0 and is_admin!=1 and account_status=1 ") ;
       //$this->data = mysql_query("SELECT id,username FROM `clients` WHERE is_deleted = 0 and is_admin!=1 ") ;
       $clientData = array();
       while($row = mysql_fetch_assoc($this->data)){
          $clientData[$row['id']] = $row['username'];
      }
      return $clientData;
  }
  
  public function clientDropDown($selectedClient = null){
    $clientData = $this->getAllClient();
    $htmlContent = '';
    foreach($clientData as $client_id => $client_usernames){
        $selected = "";
        if($selectedClient==$client_id){
            $selected = 'selected';
        }
        $htmlContent.='<option value="'.$client_id.'" '.$selected.'>'.ucfirst($client_usernames).'</option>';
    }
    
    return $htmlContent;
}

function pagination($tbl_name,$where,$targetpage,$page=null,$limit=null,$getUnique=null){
    $adjacents = 5;
    $get = "*";
    if($getUnique !=""){
        $get =$getUnique;
        $where = "";
    }
    $query = "SELECT COUNT($get) as num FROM $tbl_name $where";
    $total_pages = mysql_fetch_array(mysql_query($query));
    $total_pages = $total_pages['num'];
    if($limit==""){
        $limit = 10;
    }
    if($page) {
        $start = ($page - 1) * $limit;          
    }
    else{
        $start = 0;                             
    }
    
    if ($page == 0) {
        $page = 1;                  
    }
    $prev = $page - 1;                          
    $next = $page + 1;                          
    $lastpage = ceil($total_pages/$limit);      
    $lpm1 = $lastpage - 1;                      
    $pagination = "";
    if($lastpage > 1)
    {   
        $delimiter= "?";
        if(strstr($targetpage, "?")!==false){
            $delimiter= "&";
        }
        $pagination .= "<ul class=\"pagination\">";
        
        if ($page > 1){
            $pagination.= "<li><a href='".$targetpage.$delimiter."page=".$prev."'><i class=\"fa fa-chevron-left\"></i></a></li>";
        }
        else{
            $pagination.= "<li class=\"disabled\"><a href=\"javascript:;\"><i class=\"fa fa-chevron-left\"></i></a></li>";  
        }
        
        
        if ($lastpage < 7 + ($adjacents * 2)){  
            for ($counter = 1; $counter <= $lastpage; $counter++){
                if ($counter == $page){
                    $pagination.= "<li class=\"active\"><a href=\"javascript:;\">$counter</a></li>";
                }
                else{
                    $pagination.= "<li><a href='".$targetpage.$delimiter.'page='.$counter."'>$counter</a></li>";                    
                }
            }
        }
        elseif($lastpage > 5 + ($adjacents * 2)){
            if($page < 1 + ($adjacents * 2)){
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
                    if ($counter == $page){
                        $pagination.= "<li class=\"active\"><a href=\"javascript:;\">$counter</a></li>";
                    }
                    else{
                        $pagination.= "<li><a href='".$targetpage.$delimiter.'page='.$counter."'>$counter</a></li>";                    
                    }
                }
                $pagination.= "...";
                $pagination.= "<li><a href='".$targetpage.$delimiter.'page='.$lpm1."'>$lpm1</a></li>";
                $pagination.= "<li><a href='".$targetpage.$delimiter.'page='.$lastpage."'>$lastpage</a></li>";      
            }
            elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)){
                $pagination.= "<li><a href='".$targetpage.$delimiter.'page=1'."'>1</a></li>";
                $pagination.= "<li><a href='".$targetpage.$delimiter.'page=2'."'>2</a></li>";
                $pagination.= "...";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++){
                    if ($counter == $page){
                        $pagination.= "<li class=\"active\"><a href=\"javascript:;\">$counter</a></li>";
                    }
                    else{
                        $pagination.= "<a href='".$targetpage.$delimiter.'page='.$counter."'>$counter</a>";                 
                    }
                }
                $pagination.= "...";
                $pagination.= "<li><a href='".$targetpage.$delimiter.'page='.$lpm1."'>$lpm1</a></li>";
                $pagination.= "<li><a href='".$targetpage.$delimiter.'page='.$lastpage."'>$lastpage</a></li>";      
            }
            else {
                $pagination.= "<li><a href='".$targetpage.$delimiter.'page=1'."''>1</a></li>";
                $pagination.= "<li><a href='".$targetpage.$delimiter.'page=2'."''>2</a></li>";
                $pagination.= "...";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++){
                    if ($counter == $page){
                        $pagination.= "<li class=\"active\"><a href=\"javascript:;\">$counter</a></li>";
                    }
                    else{
                        $pagination.= "<a href='".$targetpage.$delimiter.'page='.$counter."'>$counter</a>";                 
                    }
                }
            }
        }
        
        if ($page < $counter - 1) {
            $pagination.= "<li><a href='".$targetpage.$delimiter.'page='.$next."'><i class=\"fa fa-chevron-right\"></i></a></li>";
        }
        else{
            $pagination.= "<li class=\"disabled\"><a href=\"javascript:;\"><i class=\"fa fa-chevron-right\"></i></a></li>";  
        }
        $pagination.= "</ul>\n";        
    }
    
    return array(
        "start"=>$start,
        "limit"=>$limit,
        "pagination"=>$pagination,
        );
}


}





// object invoking 
$db = new sit_room(sit_room::DB_host,sit_room::DB_user,sit_room::DB_password,sit_room::DB_database);

 
 ?>