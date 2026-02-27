<?php
defined('BASEPATH') OR exit('No direct script access allowed');   

class Override extends CI_Controller {
                                 
    public function __construct()
    {
        parent::__construct();   
        error_reporting(E_ALL);
		date_default_timezone_set('Asia/Jakarta');
        $this->db->query("SET time_zone = '+07:00'");
        $this->core->https();
    } 

    public function index(){
        echo 'Override Start';
    }
  
    public function url(){
        header('Content-Type: application/javascript');
        $q = "select * from cms_pages where presence = 1";
        $query = $this->db->query($q);           
        foreach ($query->result() as $row)
        { 
            $url = strtolower( url_title($row->name));
            $data = array(
                "url" => $url,
            );
         
            echo json_encode($data)."\n";
            
            $this->db->update('cms_pages',$data,'id='.$row->id);
        }
        
        $q = "select * from cms_content where presence = 1";
        $query = $this->db->query($q);           
        foreach ($query->result() as $row)
        { 
            $url = strtolower( url_title($row->name));
            $data = array(
                "url" => $url.'.html',
            );
            echo json_encode($data)."\n";
            
            $this->db->update('cms_content',$data,'id='.$row->id);
        }

        $q = "select * from ec_catalog where presence = 1";
        $query = $this->db->query($q);           
        foreach ($query->result() as $row)
        { 
            $url = strtolower( url_title($row->name));
            $data = array(
                "url" => $url,
            );
            echo json_encode($data)."\n";
            
            $this->db->update('ec_catalog',$data,'id='.$row->id);
        }

        $q = "select * from ec_product where presence = 1";
        $query = $this->db->query($q);           
        foreach ($query->result() as $row)
        { 
            $url = strtolower( url_title($row->name));
            $data = array(
                "url" => $url,
            );
            echo json_encode($data)."\n";
            
            $this->db->update('ec_product',$data,'id='.$row->id);
        }
    }

    public function url_content($a=0, $b=100){
        header('Content-Type: application/javascript');
      
        $q = "select * from cms_content where presence = 1 limit ".$a.", ".$b;
        $query = $this->db->query($q);           
        foreach ($query->result() as $row)
        { 
            $url = strtolower( url_title($row->name));
            $data = array(
                "url" => $url.'.html',
            );
            echo json_encode($data)."\n";
            
            $this->db->update('cms_content',$data,'id='.$row->id);
        }

    }

    public function recyclebin(){
        echo 'recycle bin done';
    }

    public function sitemap(){
        $xml = new SimpleXMLElement('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"/>');
        
        $query = $this->db->query("
           SELECT *
           FROM  cms_pages
           WHERE status = 1 and presence = 1 
           order by sorting ASC, input_date DESC
        ");
        foreach ($query->result() as $row)
        {
            $link_pages = ""; 
            $link_pages = base_url().$row->url;
            if($row->idefault == 1){
                $link_pages = base_url();
            }
               
            $track = $xml->addChild('url');
            $track->addChild('loc', $link_pages); 
             
            $query2 = $this->db->query("
               SELECT *
               FROM  cms_content
               WHERE status = 1 and presence = 1 and id_pages = ".$row->id."
               ORDER BY sorting ASC, input_date DESC
               LIMIT 200
            ");
            foreach ($query2->result() as $row2)
            {
               $name = "";
               $link  = "";
               $link = base_url().$row2->url;    
               $track = $xml->addChild('url');
               $track->addChild('loc', $link);
            }
        }
        
        Header('Content-type: text/xml');
        print($xml->asXML());       
    }

    public function rss(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: *");
        header('Access-Control-Allow-Methods: GET, POST, PUT');
        header('Content-Type: application/json');
        $limit = 3; $where_id_pages = "";
        if($this->input->get('list') ){
            $limit = $this->input->get('list');
        }

        if($this->input->get('idp') ){
            $where_id_pages = ' and p.id = '.$this->input->get('idp').' ';
        }

        $q = '
        select  c.name, c.title, c.content, c.metadata_description, c.input_date, c.url , c.img, p.name as "pages_name" , p.id 
        from cms_pages as p 
        join cms_content as c on p.id = c.id_pages
        where p.post = 1 and p.presence = 1 and p.`status` = 1 and c.presence =1 and c.`status` = 1 '.$where_id_pages.'
        order by c.input_date DESC 
        limit '.$limit;
        $item=[];
        $query = $this->db->query($q);
        foreach ($query->result() as $row)
        {

            if(!$row->metadata_description){
                $content = addslashes(strip_tags( $row->content ) ); 
                $content = preg_replace( "/\s|&nbsp;/", " ", $content );
                $content = $this->core->substrwords($content,'160');
            }

            $temp = array(
                "title"         => $row->title ? $row->title : $row->name,
                "pages"         => $row->pages_name,
                "link"          => base_url().$row->url,
                "description"   => $row->metadata_description ? preg_replace( "/\r|\n/", "", $row->metadata_description ) : $content,
                "image"        => $row->img,
                "date"          => $row->input_date,
            );
            array_push($item,$temp);
        }

        $rss = array(
            "title" => $this->core->select('title','cms_pages','idefault=1') ? $this->core->select('title','cms_pages','idefault=1') : $this->core->select('name','cms_pages','idefault=1'),

            "description" => $this->core->select('metadata_description','cms_pages','idefault=1'),
            "keywords" => $this->core->select('metadata_keywords','cms_pages','idefault=1'),

            "link" => base_url(),
            "item" => $item,
        );

        echo json_encode($rss, JSON_PRETTY_PRINT);

    }

    public function infojs(){
        header('Content-Type: application/javascript');
        $data = array(
            "CI"        => CI_VERSION, 
            "Mirrel"     => "4.1.3",
            "PHP"        => phpversion(),
        );

        echo json_encode($data , JSON_PRETTY_PRINT);
    }

    public function reset_title(){
        header('Content-Type: application/javascript');
        $q = "select * from cms_content where presence = 1";
        $query = $this->db->query($q);           
        foreach ($query->result() as $row)
        { 
          
            $data = array(
                "title" => $row->name,
            );
            echo json_encode($data)."\n";
            
            $this->db->update('cms_content',$data,'id='.$row->id);
        }
    }
     
    public function content_html_entity_decode(){
        header('Content-Type: application/javascript');
      
        $q = "select * from cms_content";
        $query = $this->db->query($q);           
        foreach ($query->result() as $row)
        { 
         
            $data = array(
                "content" => html_entity_decode($row->content),
            );
            echo json_encode($data)."\n";
            
            $this->db->update('cms_content',$data,'id='.$row->id);
        }
    }

    public function install(){
        $tables = $this->db->list_tables();
        if( count($tables) < 1){
            echo 'Install Done, go to <a href="'.base_url().'">'.base_url().'</a>';
            
            $sql = file_get_contents("./admin/mirrel5-prod.sql");

            $sqls = explode(';', $sql);
            array_pop($sqls);

            foreach($sqls as $statement){
                $statment = $statement . ";";
                $this->db->query($statement);   
            }
        }else{
            echo 'already install';
        }
       
    }
}