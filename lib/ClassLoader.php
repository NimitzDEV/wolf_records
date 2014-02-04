<?php
/*
 * クラスローダー
 * @author noto
 * http://code-life.net/?p=1142
 */
class ClassLoader{

  private $dirs = [];

  function __construct($directory)
  {
    spl_autoload_register(array($this, 'loader'));
    foreach($directory as $dir)
    {
      $this->dirs[] = $dir;
    }
  }
  function loader($classname){
    foreach ($this->dirs as $dir) {
      $file = $dir.'/'.$classname.'.php';
      if(is_readable($file)){
        require $file;
        return;
      }
    }
  }
}
