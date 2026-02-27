<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Core extends CI_Model
{

    public $pages_name = "";
    public $id_pages = "";
    public $id_content = "";
    public $content_name = "";
    public $table = "cms_pages";
    public $site_key = "";
    function __construct()
    {
        parent::__construct();
    }

    function https()
    {
        if ($this->db->https == TRUE) {
            if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on") {
                $url = "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                redirect($url);
                exit;
            }
        }
    }

    function site_key($key = "")
    {
        $this->site_key = $key;
    }

    function key()
    {
        $headers = apache_request_headers();
        if (isset($headers['Key'])) {
            if ($headers['Key'] == $this->db->key) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function token()
    {
        $headers = apache_request_headers();
        /*if (isset($headers['Key']) && isset($headers['Token'])  ) {
            if (  ($headers['Key'] == $this->db->key) && (self::select('token', 'account', 'token = "'. $headers['Token'].'" ' ) ) ) {
                $return =  true;
            } else {
                $return = false;
            }
        } else {
            $return = false;
        }
        return $return;*/
        if (self::select('id', 'account', 'token="' . get_cookie('mirrel5Login') . '"') || (self::select('token', 'account', 'token = "' . $headers['Token'] . '" '))) {
            return true;
        } else {
            return false;
        }
    }
    function login()
    {
        if (self::select('id', 'account', 'token="' . get_cookie('mirrel5Login') . '"')) {
            return true;
        } else {
            return false;
        }
    }

    function admin($value = "")
    {
        if (self::login() == true) {

            if ($value == "") {
                $return = true;
            } else {
                $return =  $value;
            }
            return $return;
        } else {
            return "";
        }
    }


    function error()
    {
        $data = array(
            "error" => true,
            "data" => "auth error"
        );
        echo json_encode($data);
    }


    function start()
    {
        self::setVariable();
        $form = '   <input type="hidden" name="return" value="' . current_url() . '" >
                    <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
                    <script src="https://www.google.com/recaptcha/api.js?render=' . $this->site_key . '"></script>
                    <script>
                        grecaptcha.ready(function() {
                            grecaptcha.execute(\'' . $this->site_key . '\', {action: \'homepage\'}).then(function(token) {
                                document.getElementById(\'g-recaptcha-response\').value = token;
                            });
                        });
                    </script>'; 

        $opengraph = '
    <title>' . strip_tags(self::title()) . '</title> 
    <link rel="canonical" href="'.current_url().'">

    <meta name="description" content="' . strip_tags(self::metadata_description()) . '">
    <meta name="keywords" content="' . strip_tags(self::metadata_keywords()) . '">
    <meta name="elapsed_time" content="{elapsed_time}"> 
    <meta name="themes" content="' . self::themes() . '">
    <meta name="core_version" content="' . CI_VERSION . '"> 

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="' . strip_tags(self::title()) . '">
    <meta name="twitter:description" content="' . strip_tags(self::metadata_description()). '">
    <meta name="twitter:image"        content="' .strip_tags(  self::metadata_images()) . '">
    
    <meta property="og:type" content="Article" />
    <meta property="og:url" content="' . current_url() . '" />
    <meta property="og:title" content="' . strip_tags(self::title()) . '" />
    <meta property="og:description" content="' . strip_tags(self::metadata_description()) . '">
    <meta property="og:site_name" content="' . self::domain() . '" />
    <meta property="og:image"         content="' . self::metadata_images() . '" /> 
    <meta property="og:image:width"   content="600" /> 
    <meta property="og:image:height"  content="315" />
    <meta property="og:locale" content="id_ID" />';

        //$opengraph  = preg_replace( "/\r|\n/", "", $opengraph );   
        $segment = array(
            "s1" => $this->uri->segment(1),
            "s2" => $this->uri->segment(2),
            "s3" => $this->uri->segment(3),
            "s4" => $this->uri->segment(4),
        );


        if ($this->uri->segment(2)) {

            $data = array(
                "title" => strip_tags( self::title()),
                "login" => self::login(),
                "path" => self::path(),
                "metadata" => array(
                    "keywords" => self::metadata_keywords(),
                    "description" => self::metadata_description(),
                    "images"    => self::metadata_images(),
                ),
                "opengraph" =>  $opengraph,
                "table" =>  $this->table,
                "version"    => "5." . CI_VERSION,
                "domain" => self::domain(),
                "themes" => self::themes(),
                "url" => current_url(),
                "segment" => $segment,
                "navigation" => self::navigation(),
                "contact_us" => array(
                    "return"    => '<input type="hidden" name="return" value="' . current_url() . '" >',
                    "email" => 'action="' . base_url() . 'site/send/" method="POST" ',
                    "form" =>  $this->input->get('error') == '0' ? $form . ' 
                    <div class="bg-success text-white border p-2 m-4">
                        <div class="col-12  mirrel_email_feedback text-center">
                        ' . self::select('value', 'global_setting', 'id = 110') . '
                        </div>
                    </div>' : $form,
                ),


            );
        } else {

            $content = array( 
                "id"  =>  $this->id_content,
                "login" => self::login(),
                "name" =>  self::select('status', 'cms_content', 'presence = 1 and id="' . $this->id_content . '" ') ?  $this->content_name : $this->content_name . " </span class='cms_disable text-warning'>[DISABLE]</span>",
                "h1" => self::select('h1', 'cms_content', 'presence = 1 and id="' . $this->id_content . '" ' . self::content_status()),
                "h2" => self::select('h2', 'cms_content', 'presence = 1 and id="' . $this->id_content . '" ' . self::content_status()),
                "h3" => self::select('h3', 'cms_content', 'presence = 1 and id="' . $this->id_content . '" ' . self::content_status()),
                
                "h4" => self::select('h4', 'cms_content', 'presence = 1 and id="' . $this->id_content . '" ' . self::content_status()),
                "h5" => self::select('h5', 'cms_content', 'presence = 1 and id="' . $this->id_content . '" ' . self::content_status()),
                "h6" => self::select('h6', 'cms_content', 'presence = 1 and id="' . $this->id_content . '" ' . self::content_status()),
               
                "content" => self::select('content', 'cms_content', 'presence = 1 and id="' . $this->id_content . '" ' . self::content_status()),
                "metadata_description" => self::select('metadata_description', 'cms_content', 'presence = 1 and id="' . $this->id_content . '" ' . self::content_status()),
                
                "metadata_keywords" => self::select('metadata_keywords', 'cms_content', 'presence = 1 and id="' . $this->id_content . '" ' . self::content_status()),
                "created_by" => self::select('created_by', 'cms_content', 'presence = 1 and id="' . $this->id_content . '"  ' . self::content_status()),
                "status"    => self::select('status', 'cms_content', 'presence = 1 and id="' . $this->id_content . '" '),
                "img"  => self::select('img', 'cms_content', 'presence = 1 and id="' . $this->id_content . '" ' . self::content_status()),
                "input_date"  => date('Y-m-d', strtotime(self::select('input_date', 'cms_content', 'presence = 1 and id="' . $this->id_content . '"'))),
                "subcontent" => self::subcontent(),
                "subcontent_action" =>  $this->id_content ?  self::admin("<a href=\"javascript:;\"  class=\"btn-mirrel fnInsert\" data-json='{\"table\":\"cms_widget\",\"section\":\"subcontent".$this->id_content."\"}'> <i class=\"fas fa-plus\"></i> Add Subcontent</a>") : self::admin('<small>Add Content First</small>'),

                "galleries" => self::galleries(), 
                "galleries_action" =>  $this->id_content ?  self::admin("<a href=\"javascript:;\"  class=\"btn-mirrel fnInsert\" data-json='{\"table\":\"cms_widget\",\"section\":\"".$this->id_content."\"}'> <i class=\"fas fa-plus\"></i> Add Galleries</a>") : self::admin('<small>Add galleries First</small>'),

                "banner" => self::banner(), 
                "banner_action" =>  $this->id_content ?  self::admin("<a href=\"javascript:;\"  class=\"btn-mirrel fnInsert\" data-json='{\"table\":\"cms_widget\",\"section\":\"banner".$this->id_content."\"}'> <i class=\"fas fa-plus\"></i> Add Banner</a>") : self::admin('<small>Add banner First</small>'),

                "edit" => $this->id_content ? self::admin(' 
                <a href="javascript:;"  title="Setting" class="fnModal btn-mirrel"  id="cms_content-3006" data-json=\'{"table":"content","id":"'. $this->id_content.'","title":"Content Edit"}\'><i class="fa fa-cogs" aria-hidden="true"></i>  Content Setting </a>') : self::admin('<small>Add Content First</small>'),

                "data" => array(
                    'name' => ' data-json=\'{"table":"cms_content","column":"name","id":"' . $this->id_content . '"}\'',
                  
                    'h1' => ' data-json=\'{"table":"cms_content","column":"h1","id":"' . $this->id_content . '"}\'',
                    'h2' => ' data-json=\'{"table":"cms_content","column":"h2","id":"' . $this->id_content . '"}\'',
                    'h3' => ' data-json=\'{"table":"cms_content","column":"h3","id":"' . $this->id_content . '"}\'',
                    'h4' => ' data-json=\'{"table":"cms_content","column":"h4","id":"' . $this->id_content . '"}\'',
                    'h5' => ' data-json=\'{"table":"cms_content","column":"h5","id":"' . $this->id_content . '"}\'',
                    'h6' => ' data-json=\'{"table":"cms_content","column":"h6","id":"' . $this->id_content . '"}\'',
                  
                    'metadata_description' => self::admin(' data-json=\'{"table":"cms_content","column":"metadata_description","id":"' . $this->id_content . '"}\''),
                    'metadata_keywords' => self::admin(' data-json=\'{"table":"cms_content","column":"metadata_keywords","id":"' . $this->id_content . '"}\''),
                    'content' => ' data-json=\'{"table":"cms_content","column":"content","id":"' . $this->id_content . '"}\'',
                    //   'cookies' => get_cookie('mirrel5Login'),
                ),

                "insert" => self::admin('<a href="javascript:;" class="btn-mirrel fnAddContent" data-idpages="' . $this->id_pages . '"><i class="fa fa-plus" aria-hidden="true"></i> Add Content</a>'),
                "list" => self::content_list(),
            );
            $status = self::select('status','cms_pages','id="'.$this->id_pages.'"');
            $data = array(
                "title" => strip_tags ( self::title()),
                "login" => self::login(),
                "metadata" => array(
                    "keywords" => self::metadata_keywords(),
                    "description" => self::metadata_description(),
                    "images"    => self::metadata_images(),
                ),
                "opengraph" =>  $opengraph,
                "path" => self::path(),
                "table" =>  $this->table,
                "version"    => "5." . CI_VERSION,
                "domain" => self::domain(),
                "themes" => self::themes(),
                "url" => current_url(),
                "segment" => $segment, 
                "pages" =>  $status ?  array(
                    "status" =>  $status,
                    "id" =>  $this->id_pages,
                    "name" => $this->pages_name,
                    "title" => self::select('title', 'cms_pages', 'id = "' . $this->id_pages . '" '),
                    "sub" => self::select('count(id)', 'cms_pages', 'id_pages = "' . $this->id_pages . '" '),
                    "list" => $this->pages_list(),
                ) : false, 
                "content" => $status ? $content : false,

                "navigation" => self::navigation(),
                "path" => self::path(),
                "contact_us" => array(
                    "return"    => '<input type="hidden" name="return" value="' . current_url() . '" >',
                    "action" => 'action="' . base_url() . 'site/send/" method="POST" ',
                    "recaptcha3" =>  $this->input->get('error') == '0' ? $form . ' 
                    <div class="bg-success text-white border p-2 m-4">
                        <div class="col-12  mirrel_email_feedback text-center">
                            ' . self::select('value', 'global_setting', 'id = 110') . '
                        </div>
                    </div>' : $form,
                ),

            );
        }

        return $data;
    }

    function setVariable()
    {
        $name = $this->uri->segment(1);

        if (empty($name)) {
            $name = self::select('url', 'cms_pages', 'idefault=1');
        }


        if ($this->uri->segment(2) == 'catalog') {
            echo 'catalog';
        } else if ($this->uri->segment(2) == 'product') {
            echo 'product';
        } else if (strpos($name, '.html') !== false) {
            $id_pages = self::select('id_pages', 'cms_content', 'url="' . $this->uri->segment(1) . '" order by input_date DESC');
            $name = self::select('name', 'cms_pages', 'id="' . $id_pages . '"');

            $this->table = "cms_content";

            $this->id_pages = $id_pages;
            $this->pages_name =  self::select('name', 'cms_pages', 'id="' . $id_pages . '"');

            $this->id_content = self::select('id', 'cms_content', 'url="' . $this->uri->segment(1) . '"   and presence = 1  order by input_date DESC');
            $this->content_name = self::select('name', 'cms_content', 'url="' . $this->uri->segment(1) . '"  ' . self::content_status() . '  and presence = 1   order by input_date DESC');
        } else {

            $this->table = "cms_pages";

            $this->id_pages = self::select('id', 'cms_pages', 'url="' . $name . '" and presence = 1 order by input_date DESC');
            $this->pages_name = self::select('name', 'cms_pages', 'url="' . $name . '" and presence = 1 order by input_date DESC');

            $this->id_content = self::select('id', 'cms_content', 'id_pages="' . $this->id_pages . '" and presence = 1  order by sorting asc, input_date DESC');
            $this->content_name = self::select('name', 'cms_content', 'id_pages="' . $this->id_pages . '" ' . self::content_status() . '  and presence = 1  order by sorting asc, input_date DESC');
        }
    }

    function themes()
    {
        if ($this->uri->segment(1) == 'search') {
            $themes = "search";
        } else if (self::select('themes', 'cms_pages', 'id="' . $this->id_pages . '" and status > 0')) {
            $themes =  self::select('themes', 'cms_pages', 'id="' . $this->id_pages . '"') ? self::select('themes', 'cms_pages', 'id="' . $this->id_pages . '"') : "index";
        } else {
            $themes = "404";
        }

        return $themes;
    }

    function domain()
    {
        $array = array("http://", "https://");
        $base  = str_replace($array, '', base_url());
        $base_r = explode('/', $base);
        return $base_r[0];
    }

    function title()
    {

        $data = self::select('name', 'cms_content', 'id="' . $this->id_content . '"');
        if (self::select('title', 'cms_content', 'id="' . $this->id_content . '"')) {
            $data = self::select('title', 'cms_content', 'id="' . $this->id_content . '"');
        }

        if (!$data) {
            $data = self::select('name', 'cms_pages', 'id="' . $this->id_pages . '"');

            if (self::select('title', 'cms_pages', 'id="' . $this->id_pages . '"')) {
                $data = self::select('title', 'cms_pages', 'id="' . $this->id_pages . '"');
            }
        }


        if( self::select('id', 'cms_label', 'name="title"')  &&  ( !$this->uri->segment(1) ||  $this->uri->segment(1) == 'home') ){
            $data = self::select('content', 'cms_label', 'name="title"');

        }

        return $data;
    }

    function metadata_description()
    {
        if ($this->table == 'cms_pages') {
            $data = self::select('metadata_description', 'cms_pages', 'id="' . $this->id_pages . '" and presence = 1');
            $data = preg_replace("/\r|\n/", "", $data);
            if (!$data) {
                $data = addslashes(strip_tags(self::select('content', 'cms_content', 'id="' . $this->id_content . '" and presence = 1')));
                $data = preg_replace("/\s|&nbsp;/", " ", $data);
                $data = self::substrwords($data, '160');
            }
        }
        if ($this->table == 'cms_content') {

            if( self::select('metadata_description', 'cms_content', 'id="' . $this->id_content . '" and presence = 1') ){
                $data =  self::select('metadata_description', 'cms_content', 'id="' . $this->id_content . '" and presence = 1');
            }else{
                $data = addslashes(strip_tags(self::select('content', 'cms_content', 'id="' . $this->id_content . '" and presence = 1')));
            } 
            $data = preg_replace("/\s|&nbsp;/", " ", $data);
            $data = self::substrwords($data, '160');
        }

        return ltrim($data);
    }

    function metadata_keywords()
    {
        if ($this->table == 'cms_pages') {
            $data = self::select('metadata_keywords', 'cms_pages', 'id="' . $this->id_pages . '" and presence = 1');
        }
        if ($this->table == 'cms_content') {
            $data =  self::select('metadata_keywords', 'cms_content', 'id="' . $this->id_content . '" and presence = 1');
        }
   
        $data = preg_replace("/\r|\n/", "", $data);
        return $data;
    }

    function metadata_images()
    {
        if ($this->table == 'cms_pages') {
            $data = self::select('img', 'cms_pages', 'id="' . $this->id_pages . '" and presence = 1');
            if (!$data) {
                $data = self::select('img', 'cms_content', 'id="' . $this->id_content . '" and presence = 1');
            }
        }
        if ($this->table == 'cms_content') {
            $data = self::select('img', 'cms_content', 'id="' . $this->id_content . '" and presence = 1');
        }

        return $data;
    }


    function content_list()
    {

        $data = array();
        $q = "SELECT * FROM cms_content WHERE id_pages = '" . $this->id_pages . "' and presence = 1  order by sorting ASC, input_date DESC";
        $query = $this->db->query($q);
        foreach ($query->result() as $row) {
            $content = strip_tags($row->content);
            $content = self::substrwords($content, 200);
            $content = preg_replace("/\r|\n/", "", $content);

          

            $temp = array(
                "id"        => $row->id,
                "href"      => base_url() . $row->url,
                "name"      => '<span id="content_' . $row->id . '">' . $row->name . '</span>',
                "status"    => $row->status,
                "h1"        => $row->h1,
                "h2"        => $row->h2,
                "h3"        => $row->h3,
                "content"   => $content,
                "img"       => $row->img,
                "thumb"     => $row->img ?  base_url() . 'public/thumb.php?src=' .$row->img : "",
                "day"      => date('l', strtotime($row->input_date)),
                "date"      => date('d M Y', strtotime($row->input_date)),

                "data" => array(
                    'name' => ' data-json=\'{"table":"cms_content","column":"name","id":"' . $row->id . '"}\'',
                  
                    'h1' => ' data-json=\'{"table":"cms_content","column":"h1","id":"' . $row->id . '"}\'',
                    'h2' => ' data-json=\'{"table":"cms_content","column":"h2","id":"' . $row->id . '"}\'',
                    'h3' => ' data-json=\'{"table":"cms_content","column":"h3","id":"' . $row->id . '"}\'',
                    'metadata_description' => self::admin(' data-json=\'{"table":"cms_content","column":"metadata_description","id":"' . $row->id . '"}\''),
                    'metadata_keywords' => self::admin(' data-json=\'{"table":"cms_content","column":"metadata_keywords","id":"' . $row->id . '"}\''),
                    'content' => ' data-json=\'{"table":"cms_content","column":"content","id":"' .$row->id . '"}\'', 
                ),


                "input_date" => $row->input_date,
                "action"    => self::admin(
                    '
                    <a href="javascript:;" title="Move postion" id="content-' . $row->id . '"  class="handle"> <i class="fas fa-arrows-alt-v"></i> </a>   
                    <a href="javascript:;" title="Delete" class="fnDelete"  data-json=\'{"table":"cms_content","id":"' . $row->id . '","name":"' . url_title($row->name) . '"}\' > <i class="far fa-trash-alt"></i> </a> 

                    <a href="javascript:;"  title="Setting" class="fnModal"  id="cms_content-' . $row->id . '" data-json=\'{"table":"content","id":"' . $row->id . '","title":"Content Edit"}\' > <i class="fas fa-cog"></i> </a>'
                ),

            );
            array_push($data, $temp);
        }

        return $data;
    }

    function post($limit = 10, $table = "")
    {

        if (self::select('id', 'cms_pages', 'name="' . $table . '"')) {
            $where_table = " and p.id = " . self::select('id', 'cms_pages', 'name="' . $table . '"');
        } else {
            $where_table = " and p.post = 1  ";
        }
        $data = array();
        $q = "select c.* from cms_pages as p
        join cms_content as c on p.id = c.id_pages
        where c.presence = 1 and c.presence = 1 and c.`status` != 0 and p.presence = 1 and p.status != 0 " . $where_table . " 
        order by c.input_date  DESC
        limit " . $limit;

        $query = $this->db->query($q);
        foreach ($query->result() as $row) {
            $content = strip_tags($row->content);
            $content = self::substrwords($content, 200);
            $content = preg_replace("/\r|\n/", "", $content);

            $temp = array(
                "id"    => $row->id,
                "href"  => base_url() . $row->url,
                "name"  => '<span id="content_' . $row->id . '">' . $row->name . '</span>',
                "h1"    => $row->h1,
                "h2"    => $row->h2,
                "h3"    => $row->h3,
                "content" => $content,
                "img"   => $row->img,
                "url"   => $row->url,
                "date"   => date('d M Y', strtotime($row->input_date)),
                "day"   => date('l', strtotime($row->input_date)),
            );
            array_push($data, $temp);
        }

        return $data;
    }

    function convert_to_url($string = "")
    {
        $string = strip_tags($string, "");
        $string =  strtolower(url_title($string));
        return strtolower($string);
    }

    function active($id, $active = "")
    {
        if ($id == $this->id_pages) {
            return $active;
        }
    }

    public function data($data)
    {
        if ($this->input->get('data') == 'php') {
            header('Content-Type: application/javascript');
            print_r($data);
            exit;
        } else if ($this->input->get('data') == 'json') {
            $newData = array(
                "json" =>  json_encode($data),
                "title" => $data['core']['title'],
            );
            //die($this->load->view('adminbiz/data_json', $newData, TRUE));
            header('Content-Type: application/javascript');
            die(json_encode($data, JSON_PRETTY_PRINT));
        }
    }



    function recursive_path($id, $link, $separate = " / ")
    {

        $url            = self::select('url', 'cms_pages', 'id = "' . $id . '"');
        $id_pages     = self::select('id_pages', 'cms_pages', 'id = "' . $id . '"');

        $link = ' <a href="' . base_url() . $url . '" >' . self::select('name', 'cms_pages', 'id = "' . $id . '"') . '</a> ' . $separate . $link;
        if ($id_pages > 0) {
            $link =  self::recursive_path($id_pages, $link, $separate);
        }
        return $link;
    }

    function path($separate = " / ")
    {
        $link = "";
        $benchmark =  ' <a href="' . base_url() . '" >' . self::domain() . '</a> ' . $separate . self::recursive_path($this->id_pages, $link, ' / ');

        $tales = "";
        if (strpos($this->uri->segment(1), '.html') !== false) {
            $tales = '<a href="' . base_url() . $this->uri->segment(1) . '">' . $this->content_name . '</a>';
        }
        return  $benchmark . $tales;
    }

    function galleries()
    {
        $galleries    = array();

        if ($this->id_content) {
            $i = 0;
            $query = $this->db->query("SELECT * FROM cms_widget WHERE section = " . $this->id_content . "  order by sorting asc");
            foreach ($query->result() as $row) {
                $show = '{"h1":"name","img":"images"}';
                $add = array(
                    "id"        => $row->id,
                    "h1"        => $row->h1,
                    "thumb"     => base_url() . 'public/thumb.php?src=' . $row->img,
                    "img"       => $row->img ? $row->img : "https://dummyimage.com/300x200/f4f4f4/333&text=".url_title($row->h1),
                    "content"   => $row->content,
                    "i"         => $i++,
                    "action"    => self::admin( "<a href='javascript:;' id='widget-".$row->id."' class='handle'>Sorting</a> ".
                    "<a href='javascript:;' class='fnModal' data-show='".$show."'  id='cms_widget-".$row->id."' data-json='{\"table\":\"widget\",\"id\":\"".$row->id."\",\"title\":\"Gallery Edit\", \"section\":\"galleries\"}'   >Edit</a> ".
                    "<a href='javascript:;' class='fnDelete' data-json='{\"table\":\"cms_widget\",\"id\":\"".$row->id."\",\"name\":\"".$row->h1."\"}'>Delete</a> "),
                );

                array_push($galleries, $add);
            }
        }
        return  $galleries;
    }

    function subcontent()
    {
        $data = [];

        if ($this->id_content) {
            $i = 0;
            $query = $this->db->query("SELECT * FROM cms_widget WHERE section = 'subcontent" . $this->id_content . "'  order by sorting asc");
            foreach ($query->result() as $row) {
                $show = '{"h1":"name","img":"images"}';
                $add = array(
                    "id"        => $row->id,
                    "h1"        => $row->h1,
                    "h2"        => $row->h2,
                    "h3"        => $row->h3,
                    "h4"        => $row->h4,
                    "thumb"     => base_url() . 'public/thumb.php?src=' . $row->img,
                    "img"       => $row->img ? $row->img : "https://dummyimage.com/300x200/f4f4f4/333&text=".url_title($row->h1),
                    "content"   => $row->content,
                    "i"         => $i++,
                    "data" =>  array(
                        'h1' => !self::login() ?: ' data-json=\'{"table":"cms_widget","column":"h1","id":"' . $row->id . '"}\'',
                        'h2' => !self::login() ?:' data-json=\'{"table":"cms_widget","column":"h2","id":"' . $row->id . '"}\'',
                        'h3' => !self::login() ?:' data-json=\'{"table":"cms_widget","column":"h3","id":"' . $row->id . '"}\'',
                        'h4' => !self::login() ?:' data-json=\'{"table":"cms_widget","column":"h4","id":"' . $row->id . '"}\'',
                        'content' => !self::login() ?:' data-json=\'{"table":"cms_widget","column":"content","id":"' . $row->id . '"}\'',
                    ),
    
                    "action"    => self::admin( "<a href='javascript:;' id='widget-".$row->id."' class='handle'> <i class='fas fa-arrows-alt'></i> </a> ".
                    "<a href='javascript:;' class='fnModal' data-show='".$show."'  id='cms_widget-".$row->id."' data-json='{\"table\":\"widget\",\"id\":\"".$row->id."\",\"title\":\"Gallery Edit\", \"section\":\"galleries\"}'   ><i class='far fa-edit'></i></a> ".
                    "<a href='javascript:;' class='fnDelete' data-json='{\"table\":\"cms_widget\",\"id\":\"".$row->id."\",\"name\":\"".$row->h1."\"}'><i class='far fa-trash-alt'></i></a> "),
                );

                array_push($data, $add);
            }
        }
        return  $data;
    }

    
    function banner()
    {
        $galleries    = array();

        if ($this->id_content) {
            $i = 0;
            $query = $this->db->query("SELECT * FROM cms_widget WHERE section = 'banner" . $this->id_content . "'  order by sorting asc");
            foreach ($query->result() as $row) {
                $show = '{"h1":"name","img":"images"}';
                $add = array(
                    "id"        => $row->id,
                    "h1"        => $row->h1,
                    "thumb"     => base_url() . 'public/thumb.php?src=' . $row->img,
                    "img"       => $row->img ? $row->img : "https://dummyimage.com/800x200/0176C9/fff&text=".url_title($row->h1),
                    "content"   => $row->content,
                    "i"         => $i++,
                    "action"    => self::admin( "<a href='javascript:;' id='widget-".$row->id."' class='handle'>Sorting</a> ".
                    "<a href='javascript:;' class='fnModal' data-show='".$show."'  id='cms_widget-".$row->id."' data-json='{\"table\":\"widget\",\"id\":\"".$row->id."\",\"title\":\"Gallery Edit\", \"section\":\"galleries\"}'   >Edit</a> ".
                    "<a href='javascript:;' class='fnDelete' data-json='{\"table\":\"cms_widget\",\"id\":\"".$row->id."\",\"name\":\"".$row->h1."\"}'>Delete</a> "),
                );

                array_push($galleries, $add);
            }
        }
        return  $galleries;
    }

    /** 
     * 
     * CMS_[] 
     * 
     *  */
    function cms_content()
    {
        $cms_content    = array();

        $link_pages     = base_url() . 'site/index/' . self::convert_to_url(self::pages_name($this->id_pages));

        /*************************************
             CMS_CONTENT here !! DO NOT REMOVE !!
         *************************************/
        $query = $this->db->query("SELECT * FROM cms_content WHERE id_pages = '" . $this->id_pages . "' and presence = 1 and status != 0 order by sorting asc");
        foreach ($query->result() as $row) {
            if (self::admin() == true) {
                $a_link = "javascript:;";
            } else {
                $a_link = $link_pages . '/' . self::convert_to_url($row->name) . '-' . $row->id . '.html';
            }
            $add = array(
                "id"            => $row->id,
                "name"          => $row->name,
                "url_title"     => strtolower(url_title($row->name)),
                "h1"            => $row->h1,
                "h2"            => $row->h2,
                "id_pages"      => $row->id_pages,
                "img"           => $row->img,
                "thumb"  => base_url() . 'public/thumb.php?src=' . $row->img,
                "link"          => $link_pages . '/' . self::convert_to_url($row->name) . '-' . $row->id . '.html',
                "a_link"        => $a_link,
                "id_content"    => "id_content_" . $row->id,
                "content_img"   => self::content_img($row->id),

                "sorting"   => self::admin('<a href="javascript:;" class="widget_handle mirrel_a" data-id="' . $row->id . '"><i class="fa fa-arrows" aria-hidden="true"></i> Sorting </a> '),
                "base_url"  => base_url(),

                "name_content"  => "name_content_" . $row->id,
                "content"       => self::substrwords(strip_tags($row->content), 155),
                "date"          => date('d-M-Y', strtotime($row->input_date)),
                "handle"        => self::admin('<span class="handle" data-id="' . $row->id . '"><i class="fa fa-sort" aria-hidden="true"></i></span> '),
                "sorting"        => self::admin('<span class="handle" data-id="' . $row->id . '"><i class="fa fa-sort" aria-hidden="true"></i></span> '),

                "delete"        => self::admin('<a href="javascript:;" class="fn_content_delete" title="Delete this content" data-id="' . $row->id . '"><i class="fa fa-times" aria-hidden="true"></i></a>'),
            );



            array_push($cms_content, $add);
        }
        return  $cms_content;
    }

    function cms_content_post()
    {
        $cms_content    = array();

        $link_pages     = base_url() . 'site/index/';

        /*************************************
             CMS_CONTENT here !! DO NOT REMOVE !!
         *************************************/
        $q = "select c.id, c.name, c.input_date,  p.post, p.name as 'pages_name', c.img, c.h1
                from cms_content as c 
                join cms_pages as p on p.id = c.id_pages
                where p.presence = 1 and p.`status` != 0 
                and c.presence= 1 and c.`status` != 0 and p.post = 1
                order by c.input_date DESC";

        $query = $this->db->query($q);
        foreach ($query->result() as $row) {
            if (self::admin() == true) {
                $a_link = "javascript:;";
            } else {
                $a_link = $link_pages . '/' . self::convert_to_url($row->name) . '-' . $row->id . '.html';
            }
            $add = array(
                "id"            => $row->id,
                "name"          => $row->name,
                "h1"            => $row->h1,
                "img"           => $row->img,
                "thumb"  => base_url() . 'public/thumb.php?src=' . $row->img,
                "link"          => $link_pages . self::convert_to_url($row->pages_name) . '/' . self::convert_to_url($row->name) . '-' . $row->id . '.html',

                // "content"       => $row->content,
                "date"          => date('d-M-Y', strtotime($row->input_date)),
                "date2"          => date('M', strtotime($row->input_date)),

                "edit_img"  => self::admin('<a href="javascript:;" class="mirrel_a fn_update" 
            data-width="800" data-height="600" data-table="cms_contene" data-id="' . $row->id . '" data-colomns="img" data-module="admin_content/img/' . $row->id . '" data-title="Edit Images" title="Upload" alt="upload">
            <i class="fa fa-picture-o" aria-hidden="true"></i> Edit Images</a>'),
            );


            array_push($cms_content, $add);
        }
        return  $cms_content;
    }

    function cms_label($name = "")
    {
        $content = self::select('content', 'cms_label', 'name="' . $name . '"');
        if (!$content) {
            $data = array(
                'name' => $name,
                'content' => 'Add Text Here..!',
            );
            $this->db->insert('cms_label', $data);
        }
        $id = self::select('id', 'cms_label', 'name="' . $name . '"');
        $content = array(
            "content" => self::select('content', 'cms_label', 'name="' . $name . '"'), 
            "attributes" => 'data-json=\'{"table":"cms_label","column":"content","id":"'.$id .'"}\'',
        );
        return $content;
    }

    function cms_label_delete($name = "")
    {
        $this->db->delete('cms_label', 'name="' . $name . '"');

        return true;
    }

    function edit_cms_label($name = "", $type = "", $text = "Edit Link")
    {
        // type = href, img,
        if ($type == 'link') {
            $icon =  "fa-link";
        } else if ($type == 'img') {
            $icon =  " fa-picture-o";
        } else {
            $icon =  "fa-link";
        }


        if (($this->session->userdata('token') == self::token()) &&  $this->session->userdata('id_account')) {

            $link = '<a href="javascript:;" class="mirrel_a fn_update label_' . $name . '" data-table="cms_label" 
        data-name="' . $name . '" data-module="admin_label/' . $type . '/' . $name . '" data-width="800" data-height="600"><i class="fa ' . $icon . '" aria-hidden="true"></i> ' . $text . '</a>';
        } else {
            $link = "";
        }
        return  $link;
    }

    function cms_widget($section = "")
    {
        $data = "";
        if (self::select('count(id)', 'cms_widget', 'section = "' . $section . '"') < 1) {

            $data = array(
                'section' => $section,
                'h1'        => 'Add Text 1 Here !',
                'h2'        => 'Add Text 2 Here !',
                'h3'        => 'Add Text 3 Here !',
                'h4'        => 'Add Text 4 Here !',
                'content'   => 'Add Content Here !',
                'sorting'    => 0,
            );
            $this->db->insert('cms_widget', $data);
        }
        $i = 0;
        $data = array();
        $query = $this->db->query("SELECT * FROM cms_widget WHERE section = '$section' order by sorting ASC");

        foreach ($query->result() as $row) {
            $img = $row->img;
            if (!$img) {
                $img = "https://dummyimage.com/300x200/eee/000&text=add_images";
            }
            $active = "";
            if ($i < 1) {
                $active = "active";
            }
            $attr = 'data-id="' . $row->id . '"  data-table="cms_widget"';
            $array = array(
                "id" => $row->id,
                "h1" => $row->h1,
                "h2" => $row->h2,
                "h3" => $row->h3,
                "h4" => $row->h4,
                "img" => $img,
                "content" => $row->content,
                "href" => $row->href,
                "thumb"  => base_url() . 'public/thumb.php?src=' . $row->img,
                "base_url" => base_url(),
                "active"    =>  $active,
                "i" => $i,
                "data" => array(
                    'h1' => ' data-json=\'{"table":"cms_widget","column":"h1","id":"' . $row->id . '"}\'',
                    'h2' => ' data-json=\'{"table":"cms_widget","column":"h2","id":"' . $row->id . '"}\'',
                    'h3' => ' data-json=\'{"table":"cms_widget","column":"h3","id":"' . $row->id . '"}\'',
                    'h4' => ' data-json=\'{"table":"cms_widget","column":"h4","id":"' . $row->id . '"}\'',
                    'content' => ' data-json=\'{"table":"cms_widget","column":"content","id":"' . $row->id . '"}\'',
                ),

             
                "modal" => self::admin('<a href="javascript:;" title="Edit" class="btn-mirrel fnModal" id=\'cms_widget-' . $row->id . '\' data-json=\'{"table":"widget","id":"' . $row->id . '","title":"Widget Edit", "section":"' . $row->section . '"}\'> <i class="fas fa-edit"></i> Edit </a>'),


                "delete" => self::admin('<a href="javascript:;" title="Delete" class="btn-mirrel fnDelete"  data-json=\'{"table":"cms_widget","id":"' . $row->id . '","name":"Widget"}\' > <i class="far fa-trash-alt"></i> Delete </a>'),

                "sorting" => self::admin('<a href="javascript:;" title="Move postion" id="content-' . $row->id . '" class="handle"> <i class="fas fa-arrows-alt-v"></i> </a> '),
            );
            $i++;

            array_push($data, $array);
        }

        return  $data;
    }


    function cms_widget_action($section = "")
    {
        if (self::select('id', 'account', 'token="' . get_cookie('mirrel5Login') . '"')) {
            $link = '<a href="javascript:;"  class="btn-mirrel fnInsert" data-json=\'{"table":"cms_widget","section":"' . $section . '"}\'> <i class="fas fa-plus"></i> Add ' . ucwords($section) . '</a>';

            $link .= '<a href="javascript:;" class="btn-mirrel fnRouter" id="' . $section . '-' . rand(10000, 99999) . '" data-json=\'{"router":"widget/section/' . $section . '","title":"' . ucwords($section) . '"}\'> <i class="fas fa-table"></i> Show all "' . ucwords($section) . '"</a>';
        } else {
            $link = '';
        }
        return $link;
    }
    /** 
     * TOOLS 
     *  */
    function content_status($a = "")
    {
        if (self::login() == false) {
            return " and " . $a . "status = 1 ";
        } else {
            return "";
        }
    }
    function content_status_enable($id_content = 0)
    {
        if (self::login() == true) {
            return true;
        } else {
            if (self::select('status', 'cms_content', 'id=' . $id_content) == 1) {
                return true;
            } else {
                return false;
            }
        }
    }


    function substrwords($text, $maxchar, $end = '')
    {
        if (strlen($text) > $maxchar) {
            $words = explode(" ", $text);
            $output = '';
            $i = 0;
            while (1) {
                $length = (strlen($output) + strlen($words[$i]));
                if ($length > $maxchar) {
                    break;
                } else {
                    $output = $output . " " . $words[$i];
                    ++$i;
                };
            };
        } else {
            $output = $text;
        }
        return $output . $end;
    }

    function selected($value1 = "", $value2 = "")
    {
        if ($value1 == $value2) {
            return 'selected="selected"';
        }
    }

    function select($field, $table, $where = "")
    {
        $query = "SELECT " . $field . " FROM " . $table . " WHERE " . $where;
        $query = $this->db->query($query);
        if ($query->row()) {
            $row = $query->row();
            return  $row->$field;
        }
    }

    function base64Decode($description = "")
    {
        $description  = str_replace(' ', '+', $description);
        $description  = base64_decode($description);
        return $description;
    }
    /**
     * END::TOOLS 
     * */

    function navigation($active = "", $submenuClass = "", $submenuClass2 = "")
    {
        $level1 = array();
        $query = $this->db->query("SELECT * FROM cms_pages WHERE presence = 1 and id_pages = 0 and status = 1 order by sorting ASC");
        foreach ($query->result() as $row) {
            // LEVEL 2
            $level2 = array();
            $query2 = $this->db->query("SELECT * FROM cms_pages WHERE presence = 1 and id_pages = '" . $row->id . "' and status = 1 order by sorting ASC");
            foreach ($query2->result() as $row2) {


                // LEVEL 3
                $level3 = array();
                $query3 = $this->db->query("SELECT * FROM cms_pages WHERE presence = 1 and id_pages = '" . $row2->id . "' and status = 1 order by sorting ASC");
                foreach ($query3->result() as $row3) {

                    $href =  base_url() . $row3->url;
                    if ($row3->href) {
                        $href =  $row3->href;
                    }
                    $ul1 = "";
                    $ul2 = "";

                    $nav3 = array(
                        'id'    => $row3->id,
                        'name'    => $row3->name,
                        'href'    => $href,
                        'blank'    => $row3->href_target_blank ? "_blank" : "",
                        'img'     => $row3->img,
                        'idefault'  => $row3->idefault,
                        'base_url'    => base_url(),
                    );
                    array_push($level3, $nav3);
                }
                // END LEVEL 3


                $href =  base_url() . $row2->url;
                if ($row2->href) {
                    $href =  $row2->href;
                }

                $submenu3 = "";
                if (!empty($level3)) {
                    $submenu3 = $submenuClass2;
                }
                $nav2 = array(
                    'name'    => $row2->name,
                    'id'    => $row2->id,
                    'href'    => $href,
                    'blank'    => $row2->href_target_blank ? "_blank" : "",
                    'img'     => $row2->img,
                    'base_url'    => base_url(),
                    'submenu2'  => $submenu3,
                    'idefault'  => $row2->idefault,
                    'level3'  => $level3 ? $level3 : false,
                );
                array_push($level2, $nav2);
            }
            // END LEVEL 2


            $href =  base_url() . $row->url;
            if ($row->href) {
                $href =  $row->href;
            }

            $submenu2 = "";
            if (!empty($level2)) {
                $submenu2 = $submenuClass;
            }
            $nav = array(
                'name'    => $row->name,
                'active'    => self::navigation_current($row->id),
                'id'    => $row->id,
                'href'    => $href,
                'blank'    => $row->href_target_blank ? "_blank" : "",
                'img'     => $row->img,
                'idefault'  => $row->idefault,
                'base_url'    => base_url(),
                'submenu'  => $submenu2,
                'level2'    => $level2 ? $level2 : false,
            );
            array_push($level1, $nav);
        }
        return  $level1;
    }

    function navigation_current($id = "")
    {
        $level2 = self::select('id_pages', 'cms_pages', 'id="' . $this->id_pages . '"');
        $level3 = self::select('id_pages', 'cms_pages', 'id="' . $level2 . '"');

        if ($id == $this->id_pages) {
            return true;
        } else if ($id ==   $level2) {
            return true;
        } else if ($id ==    $level3) {
            return true;
        }
    }

    function pages_list()
    {
        $pages = array();
        $query = $this->db->query("SELECT * FROM cms_pages where presence  = 1 and id_pages = '" . $this->id_pages . "' and status = 1 order by sorting ASC");
        foreach ($query->result() as $row) {
            $href =  base_url() . $row->url;
            if ($row->href) {
                $href =  $row->href;
            }

            $img =  $row->img;
            if (!$img) {
                $img = base_url() . 'admin/img/img.jpg';
            }

            $nav = array(
                'id'                    => $row->id,
                'name'                  => $row->name,
                'href'                  => $href,
                'img'                   => $img,
                "thumb"                 => base_url() . 'public/thumb.php?src=' . $img,
                'title'                 => $row->title,
                'metadata_description'  => $row->metadata_description,
                'metadata_keywords'     => $row->metadata_keywords,
            );

            array_push($pages, $nav);
        }
        return $pages;
    }

    /** 
     * FOR ADMIN TREE PAGES  
     * */
    function pages_recursive($id_pages = "", $json = "")
    {
        if (!$id_pages) $id_pages = 0;

        $json = array();

        $query = $this->db->query("SELECT * FROM cms_pages where presence  = 1 and id_pages = '" . $id_pages . "' order by sorting ASC");
        foreach ($query->result() as $row) {

            $sub = self::select('count(id)', 'cms_pages', 'presence  = 1 and id_pages = "' . $row->id . '"  order by sorting ASC');
            $style = "";
            if ($row->status == "0") {
                $style = 'background: #ccc;';
            } else if ($row->status == "3") {
                $style = 'background: #eee';
            }

            //$href=  base_url().'site/index/'.self::convert_to_url($row->name);
            $href =  base_url() . $row->url;

            if ($row->href) {
                $href = $row->href;
            }

            $data = array(
                "id" => $row->id,
                "lock" => $row->ilock,
                "name" => $row->name,
                "status"    => $row->status,
                "style" => $style,
                "href"  => $href,
                "nodes" => [],
            );

            if ($sub > 0) {
                $data['nodes'] = self::pages_recursive($row->id);
            }
            array_push($json, $data);
        }
        return $json;
    }



    function pages_benchmark($id, $link = "")
    {

        $id_pages   = self::select('id_pages', 'cms_pages', 'id = "' . $id . '"');

        if ($id_pages > 0) {
            $link =  self::pages_benchmark($id_pages, $link);
        }

        $link .=  $id . ';';

        return $link;
    }

    function site_benchmark()
    {
        $link = "";
        $benchmark =  self::recursive_benchmark($this->uri->segment(3), $link, ' / ');


        return  $benchmark;
    }


    /** 
     * END :: FOR ADMIN TREE PAGES  
     * */

    function result($q = "")
    {

        $tabel = "cms_content  as c join cms_pages as p on p.id = c.id_pages";
        $where = "c.presence = 1 and c.status != 0 and p.presence = 1 and p.`status` = 1 and 
                        (
                            c.content like '%" . $q . "%' or
                            c.name like '%" . $q . "%' or
                            p.name like '%" . $q . "%' or
                            p.metadata_description like '%" . $q . "%' or
                            p.metadata_keywords like '%" . $q . "%'
                        )";

        $result = self::select('count(c.id)', $tabel, $where);

        return 'About ' . number_format($result) . ' results ({elapsed_time} seconds)';
    }

    function content_img($id = "")
    {

        $content_img_edit =  self::admin('<a href="javascript:;" class="mirrel_a fn_update" 
            data-width="800" data-height="600" data-table="cms_widget" data-id="' . $id . '" data-colomns="img" data-module="admin_content/img/' . $id . '" data-title="Edit Images"><i class="fa fa-picture-o" aria-hidden="true"></i> Images</a>');

        $content_img_delete = self::admin('<a href="' . base_url() . 'admin_content/img_delete/' . $id . '/' . $this->id_content . '" class="mirrel_a"><i class="fa fa-times" aria-hidden="true"></i> Delete Images</a> ');

        if (self::select('img', 'cms_content', 'id="' . $id . '"')) {
            $img =  "<img src='" . self::select('img', 'cms_content', 'id="' . $id . '"') . "' class='content_img' id='content_img_" . $id . "'> ";
        } else {
            $img = "";
        }

        return $content_img_edit . " " . $content_img_delete;
    }


    /* ADMIN purpose */
    function delete_pages($id = "")
    {
        $data = array(
            "presence" => '0',
            "name"      => self::select('name', 'cms_pages', 'id=' . $id) . '-' . date('His'),
        );
        $this->db->update('cms_pages', $data, 'id =' . $id);

        $data = array(
            "presence" => '0',
        );
        $this->db->update('cms_content', $data, 'id_pages =' . $id);

        $query = $this->db->query("SELECT * FROM cms_pages WHERE id_pages='$id'");
        foreach ($query->result() as $row) {

            self::delete_pages($row->id);
        }
    }

    function auto_increment($table = "", $prefix = "")
    {
        if ($table) {
            $number = self::select('number', 'auto_increment', 'name="' . $table . '"') + 1;
            $str_pad = self::select('str_pad', 'auto_increment', 'name="' . $table . '"');

            $data = array(
                'number' => $number,
            );
            $this->db->update('auto_increment', $data, " name = '" . $table . "'");
            //   return '3'.date('ym').str_pad($number, $str_pad, '0', STR_PAD_LEFT);
            return  $prefix . str_pad($number, $str_pad, '0', STR_PAD_LEFT);
        } else {
            return false;
        }
    }

    /**
     * SEARCH 
     **/
    function search($type = 'cms')
    {
        $data = array();
        if ($this->input->get('q')  && ($type == 'cms' || $type == "")) {
            $q = preg_replace('/[^A-Za-z0-9\-]/', '',   strtolower($this->input->get('q')));

            $q = '
            select *, REPLACE( LOWER(name), "' . $q . '", "<strong>' . $q . '</strong>" ) AS "search_name"
            from cms_content 
            where 
            name   like "%' . $q . '%" 
            and     presence = 1 and status = 1 
            order by input_date DESC';
            $query = $this->db->query($q);

            foreach ($query->result() as $row) {
                $content = strip_tags($row->content);
                $content = preg_replace("/\s|&nbsp;/", " ", $content);
                $content = $this->core->substrwords($content, '200', '...');

                $temp = array(
                    "id" => $row->id,
                    "name" => ucwords($row->search_name),
                    "content" => $content,
                    "url" => base_url() . $row->url,
                    "update_date" => date('d M Y', strtotime($row->update_date)),
                    "input_date" => date('d M Y', strtotime($row->input_date)),
                );

                array_push($data, $temp);
            }
        } else if ($type == 'catalog' || $type == "") { }

        return $data;
    }

    /**
     * base64 convert to file
     */
    function base64_to_jpeg($base64_string, $output_file)
    {
        // open the output file for writing
        $ifp = fopen($output_file, 'wb');

        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode(',', $base64_string);

        // we could add validation here with ensuring count( $data ) > 1
        fwrite($ifp, base64_decode($data[1]));

        // clean up the file resource
        fclose($ifp);

        return $output_file;
    }
    function get_name($base64_string)
    {
        $data = explode(';', $base64_string);

        if ($data[0] == 'data:image/jpeg') {
            $ext =  '.jpeg';
        } else if ($data[0] == 'data:image/jpg') {
            $ext = '.jpg';
        } else if ($data[0] == 'data:image/png') {
            $ext = '.png';
        }

        return date('ymdHis') . rand(1000, 9999) . $ext;
    }
}
