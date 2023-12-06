<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$conn = new mysqli("localhost", "root", "", "backlink_laravel");
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$file=fopen("taxonomy.json","r");
$categories=fread($file,filesize("taxonomy.json"));
$categories = json_decode($categories);
//echo "<pre>";
$parent_category_level="";
foreach($categories as $category){
  $category_level="";
  $category_name="";
  foreach($category as $category_level=>$data){
    //echo $category_level.":";
    $data=(array)$data;
    if(is_object($data[key($data)])){
      $data=(array)$data[key($data)];
      if(!is_object($data[key($data)]))
        $category_name=$data[key($data)];
      else{
        $data=(array)$data[key($data)];
        if(!is_object($data[key($data)]))
          $category_name=$data[key($data)];
        //echo "<br />";
      }
    }
    else
      $category_name= $data[key($data)];



    //parent category level finding
    $levels=explode(".",$category_level);
    //echo "<br />Level:";
    //echo $category_name."<br />";
    //print_r($levels);
    //echo "<br />-----<br />";
    unset($levels[count($levels)-1]);
    if(count($levels)>0)
    {
      $parent_category_level=implode(".",$levels);
      //echo $parent_category_level;
    }
    else
      $parent_category_level= 0;

      $sql = "INSERT INTO blmkt_categories (title, level, parent_level) values ('".$conn->real_escape_string($category_name)."', '".$category_level."', '".$parent_category_level."')";
      if ($conn->query($sql) === TRUE) {

      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
  }
  //echo "<br />";
}
$conn->close();
?>
