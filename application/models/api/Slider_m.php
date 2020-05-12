<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Slider_m extends CI_Model {
   public function getSlider()
   {
      return $this->db->get('sliders')->result();
   }
}