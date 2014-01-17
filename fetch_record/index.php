<?php
//コンソールのphp対策
ini_set('display_errors','on');
mb_internal_encoding("UTF-8");

//autoloader読込
require __DIR__.'/core/ClassLoader.php';
$class_loader = new ClassLoader([__DIR__.'/core',__DIR__.'/country',__DIR__.'/../lib']);

//国名からインスタンス生成→実行
//$countries = ['ning','guta','gutap','morphe','perjury','xebec','crazy','cafe','ciel','melon'];
$countries = ['ning'];
foreach($countries as $ctry)
{
  $name = ucfirst($ctry);
  ${$ctry} = new $name;
}
