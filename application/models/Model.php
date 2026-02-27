<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Model extends CI_Model {
  
  function __construct(){
    /* Call the Model constructor */
    parent::__construct();
  }
  
  function custom($theme="index"){
    switch ($theme) {
        case "home":
            $data = array(
                "banner" => $this->core->cms_widget('banner'),
                "banner_action" => $this->core->cms_widget_action('banner'), 

                "title"    => $this->core->cms_label('title'),

                "home3" => $this->core->cms_widget('home3'),
                "home3_action" => $this->core->cms_widget_action('home3'),
               
                "widget" => $this->core->cms_widget('widget'),
                "widget_action" => $this->core->cms_widget_action('widget'), 
            );
            return $data;
            break;
            case "industry":
              $data = array(
                "industryBanner" => $this->core->cms_widget('industryBanner'),
                "industry1" => $this->core->cms_widget('industry1'),
                "industry1_action" => $this->core->cms_widget_action('industry1'),
              );
              return $data;
              break;
     
        default:
            return false;
    }
      
  }
 
}