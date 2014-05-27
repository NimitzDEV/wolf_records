<?php

trait Rgl_Auto
{
  protected function check_rgl_tes1($nop)
  {
    switch(true)
    {
      case ($nop  >= 13):
        $this->village->rglid = Data::RGL_TES1;
        break;
      case ($nop <=12 && $nop >= 8):
        $this->village->rglid = Data::RGL_S_2;
        break;
      default:
        $this->village->rglid = Data::RGL_S_1;
        break;
    }
  }
  protected function check_rgl_tes2($nop)
  {
    switch(true)
    {
      case ($nop  >= 10):
        $this->village->rglid = Data::RGL_TES2;
        break;
      case ($nop  === 8 || $nop  === 9):
        $this->village->rglid = Data::RGL_S_2;
        break;
      default:
        $this->village->rglid = Data::RGL_S_1;
        break;
    }
  }
  protected function check_rgl_c($nop)
  {
    switch(true)
    {
      case ($nop  >= 16):
        $this->village->rglid = Data::RGL_C;
        break;
      case ($nop  === 15):
        $this->village->rglid = Data::RGL_S_C3;
        break;
      case ($nop <=14 && $nop >= 10):
        $this->village->rglid = Data::RGL_S_C2;
        break;
      case ($nop  === 8 || $nop === 9):
        $this->village->rglid = Data::RGL_S_2;
        break;
      default:
        $this->village->rglid = Data::RGL_S_1;
        break;
    }
  }
  protected function check_rgl_f($nop)
  {
    switch(true)
    {
      case ($nop  >= 16):
        $this->village->rglid = Data::RGL_F;
        break;
      case ($nop  === 15):
        $this->village->rglid = Data::RGL_S_3;
        break;
      case ($nop <=14 && $nop >= 8):
        $this->village->rglid = Data::RGL_S_2;
        break;
      default:
        $this->village->rglid = Data::RGL_S_1;
        break;
    }
  }
  protected function check_rgl_g($nop)
  {
    switch(true)
    {
      case ($nop  >= 16):
        $this->village->rglid = Data::RGL_G;
        break;
      case ($nop  <= 15 && $nop >= 13):
        $this->village->rglid = Data::RGL_S_3;
        break;
      case ($nop <=12 && $nop >= 8):
        $this->village->rglid = Data::RGL_S_2;
        break;
      default:
        $this->village->rglid = Data::RGL_S_1;
        break;
    }
  }
}
