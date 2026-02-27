<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Key, Token,  Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST');
        header('Content-Type: application/javascript');
        // error_reporting(E_ALL);
        date_default_timezone_set('Asia/Jakarta');
        $this->db->query("SET time_zone = '+07:00'");

        if ($this->core->token() == false) {
            echo $this->core->error("Error auth");
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header("HTTP/1.1 200 ");
            exit;
        }
        $this->core->https();
    }

    function index()
    {
        echo 'mirrel5Login : ' . $this->core->token();
    }


    /*
    //  PAGES
    */
    function pages($id_pages = "0")
    {

        if ($id_pages == "undefined") {
            $id_pages = 0;
        }


        $pages = [];
        $q = 'SELECT * FROM cms_pages where presence  = 1  and id_pages = ' . $id_pages . ' order by sorting ASC, input_date ASC';
        $query = $this->db->query($q);
        foreach ($query->result() as $row) {
            $url = base_url() . $row->url;
            if ($row->href) {
                $url = $row->href;
            }
            if ($row->status == '1') {
                $status = 'enable';
            } else  if ($row->status == '0') {
                $status = 'disable';
            } else if ($row->status == '2') {
                $status = 'hide';
            }
            $data = array(
                "id"        => (int) $row->id,
                "id_pages"  => (int) $row->id_pages,
                "name"      => $row->name,
                "status"    => $status,
                "url"       => $url,
                "lock"      => $row->ilock ? true : false,
                "child"  => $this->core->select('count(id)', 'cms_pages', 'presence = 1 and id_pages = ' . $row->id) ? true : false,
                'children' => self::pages_children($row->id),
            );

            array_push($pages, $data);
        }

        $bench = explode(';', $this->core->pages_benchmark($id_pages));

        $nav = [];
        $i = 0;
        foreach ($bench as $row) {
            if ($this->core->select('name', 'cms_pages', 'id="' . $row . '"')) {
                $temp = array(
                    "id" => $row,
                    "name" => $this->core->select('name', 'cms_pages', 'id="' . $row . '"'),


                );
                array_push($nav, $temp);
            }
        }

        $data = array(
            "benchmark" => $nav,
            "pages" => $pages,
        );
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    function pages_children($id_pages = "0")
    {

        $pages = [];
        $q = 'SELECT * FROM cms_pages where presence  = 1  and id_pages = ' . $id_pages . ' order by sorting ASC, input_date ASC';
        $query = $this->db->query($q);
        foreach ($query->result() as $row) {
            $url = base_url() . $row->url;
            if ($row->href) {
                $url = $row->href;
            }
            if ($row->status == '1') {
                $status = 'enable';
            } else  if ($row->status == '0') {
                $status = 'disable';
            } else if ($row->status == '2') {
                $status = 'hide';
            }
            $data = array(
                "id"        => (int) $row->id,
                "id_pages"  => (int) $row->id_pages,
                "lock"      => $row->ilock ? true : false,
                "name"      => $row->name,
                "status"    => $status,
                "url"       => $url,
                "child"     => $this->core->select('count(id)', 'cms_pages', 'presence = 1 and id_pages = ' . $row->id) ? true : false,
            );

            array_push($pages, $data);
        }

        return $pages;
    }

    function pages_load($id = "0")
    {

        $values = array();
        $map = directory_map('./application/views/themes', FALSE, TRUE);

        foreach ($map as $rec) {
            if (!is_array($rec) && substr($rec, -3, 3) == 'php') {

                if ($rec != "404.php" && $rec != "search.php") {
                    $name = str_replace('.php', '', $rec);
                    array_push($values, $name);
                }
            }
        }

        $img =  $this->core->select('img', 'cms_pages', 'id=' . $id);

        if ($img) {
            $img = explode('/',  $img);
            $imgName = end($img);
        }

        $data = array(
            'name'          => $this->core->select('name', 'cms_pages', 'id=' . $id),
            'url'          => $this->core->select('url', 'cms_pages', 'id=' . $id),
            'id_pages'          => $this->core->select('id_pages', 'cms_pages', 'id=' . $id),
            'img'          => $this->core->select('img', 'cms_pages', 'id=' . $id),
            'imgName'       => $img ?  $imgName : "",
            'post'          => $this->core->select('post', 'cms_pages', 'id=' . $id) ? true : false,
            'status'        => $this->core->select('status', 'cms_pages', 'id=' . $id),
            'themes'        => array(
                'value' => $this->core->select('themes', 'cms_pages', 'id=' . $id),
                'values' => $values,
            ),
            'idefault'         => $this->core->select('idefault', 'cms_pages', 'id=' . $id)  ? true : false,
            'href'          => $this->core->select('href', 'cms_pages', 'id=' . $id),
            'href_target_blank' => $this->core->select('href_target_blank', 'cms_pages', 'id=' . $id)  ? true : false,
            'idefault'      => $this->core->select('idefault', 'cms_pages', 'id=' . $id),
            'title' => $this->core->select('title', 'cms_pages', 'id=' . $id),
            'metadata_description' => $this->core->select('metadata_description', 'cms_pages', 'id=' . $id),
            'metadata_keywords' => $this->core->select('metadata_keywords', 'cms_pages', 'id=' . $id),


        );
        $json = array(
            "error" => 0,
            "result" => $data,
        );
        echo json_encode($json, JSON_PRETTY_PRINT);
    }

    function pages_detail($id)
    {

        if ($id) {

            $pages = [];
            $q = 'SELECT * FROM cms_pages where presence  = 1  and id = ' . $id;
            $query = $this->db->query($q);
            foreach ($query->result() as $row) {
                $data = array(
                    "id"        => $row->id,
                    "id_pages"  => $row->id_pages,
                    "parent_name"  => $this->core->select('name', 'cms_pages', 'id=' . $row->id_pages),

                    "name"      => $row->name,
                    "url"       => $row->url,
                    "themes"    => $row->themes,
                    "href"    => $row->href,
                    "href_target_blank"    => $row->href_target_blank ? true : false,
                    "img"    => $row->img,
                    "img_path"    => str_replace(base_url(), './', $row->img),


                    "title"    => $row->title,
                    "metadata_description"    => $row->metadata_description,
                    "metadata_keywords"    => $row->metadata_keywords,
                    "post" => $row->post ? true : false
                );
            }

            $themes = array();
            $map = directory_map('./application/views/themes', FALSE, TRUE);

            foreach ($map as $rec) {
                if (!is_array($rec) && substr($rec, -3, 3) == 'php') {

                    if ($rec != "404.php" && $rec != "search.php") {
                        $name = str_replace('.php', '', $rec);
                        array_push($themes, $name);
                    }
                }
            }



            $data = array(
                "error" => 0,
                "result" =>  array(
                    "data" => $data,
                    "themes" => $themes,
                ),

            );
        } else {
            $data = array(
                "error" => 100,
                "result" => [],
            );
        }

        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    function pages_sortable()
    {
        $post = json_decode(file_get_contents('php://input'), true);
        $i = 0;
        foreach ($post['data'] as $row) {
            $data = array(
                "sorting" => $i++,
            );

            $this->db->update('cms_pages', $data, "id = " . $row['id']);
        }
        $json = array(
            "error" => 0,
            "data" => $post['data'],
        );
        echo json_encode($json);
    }

    function pages_update()
    {
        $post = json_decode(file_get_contents('php://input'), true);
        $url = isset($post['data']['url']) ? strtolower(url_title($post['data']['url'])) : strtolower(url_title($post['data']['name']));

        $update = array(
            'post'          => $post['data']['post'] ? "1" : "0",
            'name'          => $post['data']['name'],
            'url'           => $url,
            'themes'        => $post['data']['themes'],
            'href'          =>  isset($post['data']['href']) ? $post['data']['href'] : "",
            'href_target_blank'  => $post['data']['href_target_blank'] ? "1" : "0",
            'title'              => $post['data']['title'],

            'metadata_keywords'  => isset($post['data']['metadata_keywords']) ? $post['data']['metadata_keywords'] : "",
            'metadata_description' => isset($post['data']['metadata_description']) ? $post['data']['metadata_description']  : "",
            'update_date'    => date('Y-m-d H:i:s'),
        );
        $this->db->update('cms_pages', $update, 'id=' . $post['id']);


        $json = array(
            "error" => 0,
            "result" => $update,
        );
        echo json_encode($json);
    }

    function pages_status()
    {
        $post = json_decode(file_get_contents('php://input'), true);
        $status = 0;
        if ($post['status'] == 'enable') {
            $status = 1;
        } else if ($post['status'] == 'disable') {
            $status = 0;
        } else if ($post['status'] == 'hide') {
            $status = 2;
        }


        $update = array(
            'status'          => $status,
            'update_date'    => date('Y-m-d H:i:s'),
        );
        $this->db->update('cms_pages', $update, 'id=' . $post['id']);

        $json = array(
            "error" => 0,
        );
        echo json_encode($json);
    }

    function pages_insert()
    {
        $post = json_decode(file_get_contents('php://input'), true);

        $name = 'New ' . date('y-m-d H:i');
        $url = strtolower(url_title($name));
        $insert = array(
            "id_pages"      => $post['id_pages'] ? $post['id_pages'] : 0,
            "name"          => $name,
            "themes"        => 'index',
            "url"           => $url,
            "status"        => 2,
            "presence"      => 1,
            "update_date"    => date('Y-m-d H:i:s'),
            "input_date"    => date('Y-m-d H:i:s'),
        );
        $this->db->insert('cms_pages', $insert);

        $id = $this->core->select('id', 'cms_pages', 'presence = 1 order by input_date DESC');


        $q = 'SELECT * FROM cms_pages where presence  = 1  and id = ' . $id;
        $query = $this->db->query($q);
        foreach ($query->result() as $row) {
            $url = base_url() . $row->url;
            if ($row->href) {
                $url = $row->href;
            }
            if ($row->status == '1') {
                $status = 'enable';
            } else  if ($row->status == '0') {
                $status = 'disable';
            } else if ($row->status == '2') {
                $status = 'hide';
            }
            $data = array(
                "id"        => $row->id,
                "id_pages"  => $row->id_pages,
                "name"      => $row->name,
                "status"    => $status,
                "url"       => $url,
                "lock"      => false,
                "child"     => false,
                'children'  => []
            );
        }


        $json = array(
            "error" => 0,
            "result"  => array(
                "data" =>  $data,
            )

        );

        echo json_encode($json);
    }

    function pages_addChild()
    {
        $post = json_decode(file_get_contents('php://input'), true);

        $name = 'New ' . date('y-m-d H:i');
        $url = strtolower(url_title($name));
        $insert = array(
            "id_pages"      => $post['id_pages'] ? $post['id_pages'] : 0,
            "name"          => $name,
            "themes"        => 'index',
            "url"           => $url,
            "status"        => 2,
            "presence"      => 1,
            "update_date"    => date('Y-m-d H:i:s'),
            "input_date"    => date('Y-m-d H:i:s'),
        );
        $this->db->insert('cms_pages', $insert);

        $id = $this->core->select('id', 'cms_pages', 'presence = 1 order by input_date DESC');


        $q = 'SELECT * FROM cms_pages where presence  = 1  and id = ' . $id;
        $query = $this->db->query($q);
        foreach ($query->result() as $row) {
            $url = base_url() . $row->url;
            if ($row->href) {
                $url = $row->href;
            }
            if ($row->status == '1') {
                $status = 'enable';
            } else  if ($row->status == '0') {
                $status = 'disable';
            } else if ($row->status == '2') {
                $status = 'hide';
            }
            $data = array(
                "child"     => false,
                "id"        => $row->id,
                "id_pages"  => $row->id_pages,
                "lock"      => false,
                "name"      => $row->name,
                "status"    => $status,
                "url"       => $url,
            );
        }


        $json = array(
            "error" => 0,
            "result"  => array(
                "data" =>  $data,
            )

        );

        echo json_encode($json);
    }

    function pages_delete()
    {
        $post = json_decode(file_get_contents('php://input'), true);
        $this->core->delete_pages($post['id']);
        $json = array(
            "error" => 0,
        );
        echo json_encode($json);
    }



    function pages_upload()
    {

        $config['upload_path']          = './public/pages/';
        $config['allowed_types']        = 'jpg|jpeg|png';
        $config['max_size']             = 4024;
        $config['max_width']            = 12024;
        $config['max_height']           = 12024;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('images')) {
            $data = array('error' => strip_tags($this->upload->display_errors()));
        } else {
            $data = array(
                'id'    => $this->input->post('id'),
                'upload_data' => $this->upload->data(),
            );


            $src = $this->core->select('img', 'cms_pages', 'id=' . $data['id']);
            if ($src) {
                unlink(str_replace(base_url(), './', $src));
            }


            $update = array(
                "img"    =>  str_replace('./', base_url(), $config['upload_path'] . $data['upload_data']['file_name']),
            );
            $this->db->update('cms_pages', $update, 'id="' . $data['id'] . '"');

            $data = array(
                'id'    => $this->input->post('id'),
                'img'   =>   $update['img'],
                'upload_data' => $this->upload->data(),
            );
        }

        echo json_encode($data);
    }


    /*
    //  SETTING
    */

    function setting_load()
    {
        $id_account = 1;
        $data = array(
            "embed" => array(
                "embed_code"    => $this->core->select('value', 'global_setting', 'id=1'),
                "header_code"   => $this->core->select('value', 'global_setting', 'id=2'),
            ),
            "smtp" => array(
                "smtp_host" => $this->core->select('value', 'global_setting', 'id=101'),
                "smtp_port" => $this->core->select('value', 'global_setting', 'id=102'),
                "smtp_user" => $this->core->select('value', 'global_setting', 'id=103'),
                "smtp_pass" => $this->core->select('value', 'global_setting', 'id=104'),
                "smtp_to"   => $this->core->select('value', 'global_setting', 'id=105'),
                "subject"   => $this->core->select('value', 'global_setting', 'id=110'),
            ),
            "account" => array(
                "email" => $this->core->select('email', 'account', 'id=' . $id_account),

            )

        );
        $json = array(
            "error" => 0,
            "result" => $data,
        );
        echo json_encode($json);
    }

    function embedCode_update()
    {
        $post = json_decode(file_get_contents('php://input'), true);

        $header_code =  $post['data']['header'];
        $header_code  = str_replace(' ', '+', $header_code);
        $header_code  = base64_decode($header_code);

        $embed_code =  $post['data']['footer'];
        $embed_code  = str_replace(' ', '+', $embed_code);
        $embed_code  = base64_decode($embed_code);


        $data = array(
            'value' =>  $header_code,
        );
        $this->db->update('global_setting', $data, 'id= 2');

        $data = array(
            'value' =>  $embed_code,
        );
        $this->db->update('global_setting', $data, 'id= 1');

        $json = array(
            "json"    => file_get_contents('php://input'),
        );
        echo json_encode($json);
    }


    function setting_smtp_update()
    {

        $postObj = json_decode(file_get_contents('php://input'), true);
        $post = $postObj['data'];
        $data = array(
            'value' =>  $post['smtp_host'],
        );
        $this->db->update('global_setting', $data, 'id = 101');


        $data = array(
            'value' =>   $post['smtp_port'],
        );
        $this->db->update('global_setting', $data, 'id = 102');


        $data = array(
            'value' =>   $post['smtp_user'],
        );
        $this->db->update('global_setting', $data, 'id = 103');

        $data = array(
            'value' =>   $post['smtp_pass'],
        );
        $this->db->update('global_setting', $data, 'id = 104');

        $data = array(
            'value' =>   $post['smtp_to'],
        );
        $this->db->update('global_setting', $data, 'id = 105');

        $data = array(
            'value' =>   $post['subject'],
        );
        $this->db->update('global_setting', $data, 'id = 110');
    }

    function setting_account_update()
    {
        $post = json_decode(file_get_contents('php://input'), true);
        $headers = apache_request_headers();
        $json = array(
            "error" => 1,
            "note" => "Wrong old password",
        );
        if (isset($post['oldPass'])) {


            if ($this->core->select('id', 'account', 'password = "' . md5($post['oldPass']) . '" and token = "' . $headers['Token'] . '" ')) {
                $data = array(
                    'email' =>  $post['email'],
                    'password' => $post['password'] ? md5($post['password']) : md5("RANDOM-" . rand(10000, 99999)),
                );
                $this->db->update('account', $data, 'token="' . $headers['Token'] . '"');
                $json = array(
                    "error" => 0,
                    "note" => "Update success",
                );
            } else {
                $json = array(
                    "error" => 1,
                    "note" => "Wrong old password",
                );
            }
        }
        echo json_encode($json);
    }


    /*
    //  CONTENT
    */

    function content_index($no=0){ 
    
        $data = array();
        $query = $this->db->query("SELECT * FROM cms_content where presence  != 0  order by input_date DESC limit $no,5000 ");
        foreach ($query->result() as $row) { 
            $temp = array(
                'id' => (int) $row->id,
                'name' => $row->name,
                'url'   => base_url().$row->url,
                'status'   => $row->status ? "Enable" : "Disable",
                'input_date' => $row->input_date,
                'pages' => $this->core->select('name','cms_pages','id = "'.$row->id_pages.'"'),
            );
            array_push($data, $temp);
        }
        $json = array(
            "error" => 0,
            "order" => array(
                "no" => $no,
                "total" => $this->core->select('count(id)','cms_content','presence  != 0  order by input_date DESC ')
            ),
            "result" => $data,
        );
        echo json_encode($json);
       
    }

    function content_pagesList_load_DELETE()
    {
        header('Content-Type: application/javascript');
        $data = array();
        $query = $this->db->query('SELECT * FROM cms_pages where presence  = 1  order by name ASC');
        foreach ($query->result() as $row) {
            $a = "";
            if ($row->status == '3') {
                $a = '*';
            }
            $temp = array(
                'value' => (int) $row->id,
                'label' => $row->name . $a,
            );
            array_push($data, $temp);
        }
        $json = array(
            "error" => 0,
            "result" => $data,
        );
        echo json_encode($json);
    }

    function content_load_DELETE($id_pages = "0")
    {
        header('Content-Type: application/javascript');
        $data = array();

        if ($this->core->select('href', 'cms_pages', 'id=' . $id_pages)) {
            $temp = array(
                'id' => "#",
                'name' => $this->core->select('href', 'cms_pages', 'id=' . $id_pages),
                'input_date' => date('d M Y, H:i A', strtotime($this->core->select('input_date', 'cms_pages', 'id=' . $id_pages))),
                'href' => '',
                'url' => $this->core->select('href', 'cms_pages', 'id=' . $id_pages),
                'delete' => false,

            );
            array_push($data, $temp);
        } else {
            $q = 'SELECT * FROM cms_content where presence  = 1  and id_pages = "' . $id_pages . '" order by sorting ASC, input_date DESC';
            $query = $this->db->query($q);
            foreach ($query->result() as $row) {
                $temp = array(
                    'id' =>  $row->id,
                    'name' => $row->name,
                    'input_date' => date('d M Y, H:i A', strtotime($row->input_date)),
                    'href' => '#!/contentDetail/' . $row->id,
                    'url' => base_url() . $row->url,
                    'delete' => true,
                );
                array_push($data, $temp);
            }
        }
        $json = array(
            "error" => 0,
            "result" => $data,
        );
        echo json_encode($json);
    }

    function content_delete()
    {
        header('Content-Type: application/javascript');
        $post = json_decode(file_get_contents('php://input'), true);
        $data = array(
            "presence" => 0,
        );
        $this->db->update('cms_content', $data, 'id=' . $post['id']);
    }

    function content_detail($id = "")
    {
        $pagesSelect = array();

        $query = $this->db->query('SELECT * FROM cms_pages where presence  != 0  order by name ASC');
        foreach ($query->result() as $row) {

            $temp = array(
                'id' => (int) $row->id,
                'name' => $row->name,
            );
            array_push($pagesSelect, $temp);
        }



        $data = array(
            "id_pages" => (int) $this->core->select('id_pages', 'cms_content', 'id="' . $id . '"'),
            "name" => $this->core->select('name', 'cms_content', 'id="' . $id . '"'),

            "h1" => $this->core->select('h1', 'cms_content', 'id="' . $id . '"'),
            "h2" => $this->core->select('h2', 'cms_content', 'id="' . $id . '"'),
            "h3" => $this->core->select('h3', 'cms_content', 'id="' . $id . '"'),

            "title" => $this->core->select('title', 'cms_content', 'id="' . $id . '"'),
            "metadata_description"  => $this->core->select('metadata_description', 'cms_content', 'id="' . $id . '"'),
            "metadata_keywords"     => $this->core->select('metadata_keywords', 'cms_content', 'id="' . $id . '"'),

            "img" => $this->core->select('img', 'cms_content', 'id="' . $id . '"'),

            "status"   => $this->core->select('status', 'cms_content', 'id="' . $id . '"'),
            "url" => $this->core->select('url', 'cms_content', 'id="' . $id . '"'),
            "img_path"    => str_replace(base_url(), './', $this->core->select('img', 'cms_content', 'id="' . $id . '"')),
        );

        $json = array(
            "error" => 0,
            "select" => array(
                "pages" =>  $pagesSelect,
                "status"   => array(
                    array(
                        "id" => "0",
                        "name" => "Disable"
                    ),
                    array(
                        "id" => "1",
                        "name" => "Enable"
                    ),

                ),

            ),
            "result" => array(
                "data" =>  $data,
            ),
        );

        echo json_encode($json);
    }

    function content_update()
    {
        $post = json_decode(file_get_contents('php://input'), true);


            $url = strtolower(url_title($post['data']['url'])); 
         
            $url = str_replace(' ', '-', $url); // Replaces all spaces with hyphens.
            $url = preg_replace('/[^A-Za-z0-9\-]/', '', $url); // Removes special chars. 
            $url =  preg_replace('/-+/', '-', $url); // Replaces multiple hyphens with single one. 
            $url = str_replace('html', '.html', $url); // Replaces all spaces with hyphens.
       



        $data = array(
            "id_pages" => $post['data']['id_pages'],
            "name" => $post['data']['name'],
            "h1" => isset($post['data']['h1']) ?  $post['data']['h1'] : "",
            "h2" => isset($post['data']['h2']) ?  $post['data']['h2'] : "",
            "h3" => isset($post['data']['h3']) ?  $post['data']['h3'] : "",
            "url" =>  $url,
            "metadata_keywords" => isset($post['data']['metadata_keywords']) ?  $post['data']['metadata_keywords'] : "",
            "metadata_description" => isset($post['data']['metadata_description']) ?  $post['data']['metadata_description'] : "",
            "title" => isset($post['data']['title']) ?  $post['data']['title'] : "",
            "status" => $post['data']['status'],
            "img" => isset($post['data']['img']) ?  $post['data']['img'] : "",
            
        );
        $this->db->update('cms_content', $data, 'id=' . $post['id']);

        $json = array(
            "error" => 0,
            "no" => 820,
            "result" =>  base_url() . $url,
        );

        echo json_encode($json);
    }

    function content_upload()
    {

        $config['upload_path']          = './public/content/';
        $config['allowed_types']        = 'jpg|jpeg|png';
        $config['max_size']             = 4024;
        $config['max_width']            = 12024;
        $config['max_height']           = 12024;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('images')) {
            $data = array('error' => strip_tags($this->upload->display_errors()));
        } else {
            $data = array(
                'id'    => $this->input->post('id'),
                'upload_data' => $this->upload->data(),
            );

            $src = $this->core->select('img', 'cms_content', 'id=' . $data['id']);
            if ($src) {
                unlink(str_replace(base_url(), './', $src));
            }

            $update = array(
                "img"    =>  str_replace('./', base_url(), $config['upload_path'] . $data['upload_data']['file_name']),
            );
            $this->db->update('cms_content', $update, 'id="' . $data['id'] . '"');

            $data = array(
                'id'    => $this->input->post('id'),
                'img'   =>   $update['img'],
                'upload_data' => $this->upload->data(),
            );
        }

        echo json_encode($data);
    }
    /**
     * update FROM IFRAME
     */
    function fn_text()
    {
        $data = $this->input->post('data');
        $update  = [];
        $content = str_replace('<br data-mce-bogus="1">', '', $this->input->post('content'));
        if ($data['table'] == 'cms_label') {
            $update = array(
                "content" =>  $content,
            );
            $this->db->update('cms_label', $update, 'id=' . $data['id']);
        } else if ($data['table'] == 'cms_widget') {
            $update = array(
                $data['column'] => $content,
            );
            $this->db->update($data['table'], $update, 'id=' . $data['id']);
        } else if ($data['table'] == 'cms_content') {

            if ($data['column'] == 'name') {
                $update = array(
                    $data['column'] => $content,
                    "url"   => strtolower(url_title($content)) . '.html',
                    "title" => $content,
                );
            } else {
                $update = array(
                    $data['column'] => $content,
                );
            }


            $this->db->update($data['table'], $update, 'id=' . $data['id']);
        }

        $json = array(
            "result" => "Update Done.",
            "data"  => $update,
            "post" => $data,
        );
        echo json_encode($json);
    }

    function fn_richtext()
    {
        $data = $this->input->post('data');

        $content =  $this->input->post('content');
        $content  = str_replace(' ', '+', $content);
        $content  = base64_decode($content);

        if ($data['table'] == 'cms_label') {
            $update = array(
                "content" =>  $content,
            );
        } else if ($data['table'] == 'cms_widget' || $data['table'] == 'cms_content') {

            $update = array(
                $data['column'] => $content,
            );
        }

        $this->db->update($data['table'], $update, 'id=' . $data['id']);
        $json = array(
            "result" => "Update Done."
        );

        $return = array(
            "result" => $json,
            "data" =>  $data,
        );
        echo json_encode($return);
    }

    function fn_insert()
    {
        $data = $this->input->post('data');

        $section    = $data['section'];
        $table      = $data['table'];

        if ($table == 'cms_widget') {
            $data = array(
                'section'   => $section,
                'h1'        => 'Add Text 1 Here !',
                'h2'        => 'Add Text 2 Here !',
                'h3'        => 'Add Text 3 Here !',
                'h4'        => 'Add Text 4 Here !',
                'content'   => 'Add Content Here !',
                'sorting'    => 0,
            );
            $this->db->insert('cms_widget', $data);
            $json = array(
                "error" => 0,
                "result" => "Add Widget Success.",
            );
        } else {
            $json = array(
                "error" => 1,
                "result" => "",
            );
        }
        echo json_encode($json);
    }

    function fn_delete()
    {
        $data = $this->input->post('data');

        if ($data['table'] == 'cms_widget') {
            $this->db->delete($data['table'], "id=" .  $data['id']);
            $json = array(
                "error" => 0,
                "result" => "Delete done.",
                "last_query" => $this->db->last_query(),
            );
        } else if ($data['table'] == 'cms_content') {
            $update = array(
                "presence" => "0",
                "update_date"   => date('Y-m-d H:i:s'),
            );
            $this->db->update($data['table'], $update, "id=" . $data['id']);
            $json = array(
                "error" => 0,
                "result" => "Delete done.",
                "last_query" => $this->db->last_query(),
            );
        } else {
            $json = array(
                "error" => 1,
            );
        }
        echo json_encode($json);
    }

    function fn_sortable()
    {
        $post = $this->input->post();
        $json = array(
            "error" => 0,
            "data" => $post['data']
        );

        echo json_encode($json);
        $i = 1;
        foreach ($post['data'] as $row) {
            $obj =  explode('-', $row['id']);
            $data = array(
                "sorting" => $i++,
            );
            $this->db->update('cms_' . $obj[0], $data, 'id=' . $obj[1]);
        }
    }

    function fn_addContent()
    {

        $data = array(
            "id_pages" => $this->input->post('id_pages'),
            "name" =>  "New " . date('d M Y, H:i'),
            "url" =>  "new-" . date('dmYHis') . '.html',
            "input_date" => date('Y-m-d H:i:s'),
            "update_date" => date('Y-m-d H:i:s'),
        );

        $this->db->insert('cms_content', $data);
        $js = array(
            "error" => 0,
        );

        echo json_encode($js);
    }

    function fn_onchanges()
    {
        $data = $this->input->post('data')['json'];
        $update  = []; 
        $content =  $this->input->post('content');
        $content  = str_replace(' ', '+', $content);
        $content  = base64_decode($content);

   
        $update = array(
            $data['column'] => $content,
        );
        $this->db->update($data['table'], $update, 'id=' . $data['id']);

        $json = array(
            "result" => "Update Done.", 
            "data" => $data,
        );
        echo json_encode($json);
    }

    function fnUpdate()
    {
        $data = $this->input->post('data');
        $update  = []; 
        $value =  $this->input->post('value'); 

   
        $update = array(
            $data['column'] => $value,
        );
        $this->db->update($data['table'], $update, 'id=' . $data['id']);

        $json = array(
            "result" => "Update Done.", 
            "value" =>   $value,
            "data" => $data,
        );
        echo json_encode($json);
    }


    function fn_changes()
    {

        $data = array(
            $this->input->post('column') => $this->input->post('value'),
        );
        $this->db->update('cms_content', $data, 'id=' . $this->input->post('id'));

        $json = array(
            "error" =>  0,
            "result" => "Update done ",
        );

        echo json_encode($json);
    }

    /**
     * Label
     */
    function labelWidget($table = "", $id = "")
    {
        header('Content-Type: application/javascript');
        if ($table == "cms_widget") {

            $img =  $this->core->select('img', 'cms_widget', 'id="' . $id . '"');

            if ($img) {
                $img = explode('/',  $img);
                $imgName = end($img);
            }



            $data = array(
                "h1" => $this->core->select('h1', 'cms_widget', 'id="' . $id . '"'),
                "h2" => $this->core->select('h2', 'cms_widget', 'id="' . $id . '"'),
                "h3" => $this->core->select('h3', 'cms_widget', 'id="' . $id . '"'),
                "h4" => $this->core->select('h4', 'cms_widget', 'id="' . $id . '"'),
                "href" => $this->core->select('href', 'cms_widget', 'id="' . $id . '"'),
                "img" => $this->core->select('img', 'cms_widget', 'id="' . $id . '"'),
                "imgName" => $img ?  $imgName : "",
                "content" => $this->core->select('content', 'cms_widget', 'id="' . $id . '"'),
                "folder" => "widget",
            );
        }

        $json = array(
            "error" => 0,
            "result" =>  $data,
        );

        echo json_encode($json);
    }

    function labelWidget_update()
    {
        header('Content-Type: application/javascript');
        $post = json_decode(file_get_contents('php://input'), true);
        if ($post['table']  == 'cms_widget') {


            $content =  $post['content'];
            $content  = str_replace(' ', '+', $content);
            $content  = base64_decode($content);

            $data = array(
                "h1" => $post['h1'] ? $post['h1'] : "",
                "h2" => $post['h2'] ? $post['h2'] : "",
                "h3" => $post['h3'] ? $post['h3'] : "",
                "h4" => $post['h4'] ? $post['h4'] : "",
                "href" => $post['href'] ? $post['href'] : "",

                "content" => $content ? $content : "",

            );
        }
        $this->db->update($post['table'], $data, "id=" . $post['id']);

        $json = array(
            "error" => 0,
        );

        echo json_encode($json);
    }

    public function upload()
    {
        $config['upload_path']          = './public/' . $this->input->post('folder');
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['max_size']             = 2000;
        $config['max_width']            = 4024;
        $config['max_height']           = 3768;
        //    $config['file_name']            = $id.date('YmdHis').rand(1000,9999);

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file')) {
            $data = array(
                'note'  => $this->upload->display_errors(),
                'error' => '1',
            );
        } else {

            $data = array(
                'img' => base_url() . 'public/' . $this->input->post('folder') . '/' . $this->upload->data('file_name'),
                "update_date" => date('Y-m-d H:i:s'),
            );

            if ($this->input->post('folder') == 'widget') {
                $src = $this->core->select('img', 'cms_widget', 'id=' . $this->input->post('id'));
                $this->db->update('cms_widget', $data, 'id="' . $this->input->post('id') . '"');
                if ($src) {
                    unlink(str_replace(base_url(), './', $src));
                }
            } else if ($this->input->post('folder') == 'pages') {
                $src = $this->core->select('img', 'cms_pages', 'id=' . $this->input->post('id'));
                $this->db->update('cms_pages', $data, 'id="' . $this->input->post('id') . '"');
                if ($src) {
                    unlink(str_replace(base_url(), './', $src));
                }
            } else if ($this->input->post('folder') == 'content') {
                $src = $this->core->select('img', 'cms_content', 'id=' . $this->input->post('id'));
                $this->db->update('cms_content', $data, 'id="' . $this->input->post('id') . '"');
                if ($src) {
                    unlink(str_replace(base_url(), './', $src));
                }
            } else if ($this->input->post('folder') == 'product') {
                $insert = array(
                    'id_product'  => $this->input->post('id'),
                    'img'         => base_url() . 'public/' . $this->input->post('folder') . '/' . $this->upload->data('file_name'),
                );
                $this->db->insert('ec_images', $insert);
            } else if ($this->input->post('folder') == 'catalog') {
                $this->db->update('ec_catalog', $data, 'id="' . $this->input->post('id') . '"');
            }

            $json = array(
                'result'  => $this->upload->data(),
                'error' => '0',
                "src" => $data['img'],
            );
        }

        echo json_encode($json);
    }

    public function fn_uploadLink()
    {
        header('Content-Type: application/javascript');
        $post = json_decode(file_get_contents('php://input'), true);

        if ($post['folder'] == 'widget') {
            $src = $this->core->select('img', 'cms_widget', 'id=' . $post['id']);
        } else if ($post['folder'] == 'pages') {
            $src = $this->core->select('img', 'cms_pages', 'id=' . $post['id']);
        } else if ($post['folder'] == 'content') {
            $src = $this->core->select('img', 'cms_content', 'id=' . $post['id']);
        } else  if ($post['folder'] == 'product') {
            $src = "null";
        } else  if ($post['folder'] == 'catalog') {
            $src = "null";
        }

        if ($src == $post['img']) {

            $json = array(
                "error" => 9999,
                "result" => 'No find extrenal url!',
            );
        } else {

            $path_parts = pathinfo($post['img']);
            $extension = strtolower($path_parts['extension']);


            $img = $path_parts['filename'] . rand(100, 999) . '.' . $path_parts['extension'];
            $newfile = './public/' . $post['folder'] . '/' . $img;

            if (empty($path_parts['extension'])) {
                $result = array(
                    "error" => 2,
                    "note"  => "*.jpg, *.jpeg, or *.png format",
                );
            } else if ($extension == 'gif' || $extension == 'png' || $extension == 'jpg' || $extension == 'jpeg') {
                if (copy($post['img'], $newfile)) {
                    $data = array(
                        "img"           => base_url() . 'public/' . $post['folder'] . '/' . $img,
                        "update_date"   => date('Y-m-d H:i:s'),
                    );
                    if ($post['folder'] == 'widget') {
                        $this->db->update('cms_widget', $data, 'id=' . $post['id']);
                        error_reporting(E_ERROR | E_PARSE);
                        unlink(str_replace(base_url(), './', $src));
                    } else if ($post['folder'] == 'pages') {
                        $this->db->update('cms_pages', $data, 'id=' . $post['id']);
                        error_reporting(E_ERROR | E_PARSE);
                        unlink(str_replace(base_url(), './', $src));
                    } else if ($post['folder'] == 'content') {
                        $this->db->update('cms_content', $data, 'id=' . $post['id']);
                        error_reporting(E_ERROR | E_PARSE);
                        unlink(str_replace(base_url(), './', $src));
                    } else if ($post['folder'] == 'product') {
                        $insert = array(
                            'id_product'  => $post['id'],
                            'img'         => base_url() . 'public/' . $post['folder'] . '/' . $img,
                        );
                        $this->db->insert('ec_images', $insert);
                    } else if ($post['folder'] == 'catalog') {
                        $this->db->update('ec_catalog', $data, 'id=' . $post['id']);
                    }
                    $error = 0;
                } else {
                    $error = 400;
                }
            } else {
                $error = 403;
            }

            $json = array(
                "error" => $error,
                "result" =>  $data
            );
        }

        echo json_encode($json);
    }


    /**
     * Widget
     */
    public function widget_section($section = "")
    {
        $data = array();
        //and id_catalog = "'.$id_catalog.'"
        $query = $this->db->query('SELECT * FROM cms_widget where  section = "' . $section . '"  order by sorting ASC');
        foreach ($query->result() as $row) {
            $temp = array(
                "id"            => $row->id,
                "h1"            => $row->h1,
                "img"           => $row->img ? $row->img : "https://dummyimage.com/300x300/666/fff&text=" . url_title($row->h1),
                "input_date"    => date('d M Y', strtotime($row->input_date)),
            );
            array_push($data, $temp);
        }
        $json = array(
            "error" => 0,
            "result" => $data,
        );

        echo json_encode($json);
    }


    public function widget_detail($id = "")
    {
        $query = $this->db->query('SELECT * FROM cms_widget where  id = "' . $id . '" ');
        foreach ($query->result() as $row) {
            $data = array(
                "id"            => $row->id,
                "section"       => $row->section,
                "h1"            => $row->h1,
                "h2"            => $row->h2,
                "h3"            => $row->h3,
                "h4"            => $row->h4,
                "content"       => $row->content,
                "img"           => $row->img,
                "href"          => $row->href,
            );
        }
        $json = array(
            "error" => 0,
            "result" => array(
                "data" => $data
            ),
        );

        echo json_encode($json);
    }

    public function widget_galleries($id = "")
    {
        header('Content-Type: application/javascript');
        $data = array();
        $query = $this->db->query('SELECT * FROM cms_widget where  id_content = "' . $id . '"  order by sorting ASC, input_date DESC');
        foreach ($query->result() as $row) {
            $temp = array(
                "id"            => $row->id,
                "h1"            => $row->h1,
                "content"       => $row->content,
                "img"           => $row->img,
                "thumb"         => $row->img,
                "sorting"       => $row->sorting,
                "input_date"    => date('d M Y', strtotime($row->input_date)),
            );
            array_push($data, $temp);
        }
        $json = array(
            "error" => 0,
            "result" => $data,
        );

        echo json_encode($json);
    }

    public function widget_urlImages()
    {
        header('Content-Type: application/javascript');
        $post = json_decode(file_get_contents('php://input'), true);

        $path_parts = pathinfo($post['img']);
        $extension = strtolower($path_parts['extension']);

        $img = $path_parts['filename'] . rand(100, 999) . '.' . $path_parts['extension'];
        $newfile = './public/' . $post['folder'] . '/' . $img;

        if (empty($path_parts['extension'])) {
            $result = array(
                "error" => 2,
                "note"  => "*.jpg, *.jpeg, or *.png format",
            );
        } else if ($extension == 'gif' || $extension == 'png' || $extension == 'jpg' || $extension == 'jpeg') {
            if (copy($post['img'], $newfile)) {
                $data = array(
                    "img"           => base_url() . 'public/' . $post['folder'] . '/' . $img,
                    "id_content"    => $post['id'],
                    "sorting"       => 0,
                    "update_date"   => date('Y-m-d H:i:s'),
                );
                $this->db->insert('cms_widget', $data);

                $error = 0;
            } else {
                $error = 400;
            }
        } else {
            $error = 403;
        }
        $data = array(
            "id"            => $this->core->select('id', 'cms_widget', 'id_content = ' . $post['id'] . ' order by input_date DESC'),
            "content"       => $this->core->select('content', 'cms_widget', 'id_content = ' . $post['id'] . ' order by input_date DESC'),
            "h1"            => $img,
            "img"           => base_url() . 'public/' . $post['folder'] . '/' . $img,
            "thumb"         => '',
            "id_content"    => $post['id'],
            "sorting"       => 0,
            "input_date"   => date('Y-m-d H:i:s'),
        );
        $json = array(
            "error" => $error,
            "result" =>  $data
        );



        echo json_encode($json);
    }

    public function widget_upload()
    {

        $config['upload_path']          = './public/widget/';
        $config['allowed_types']        = 'jpg|jpeg|png';
        $config['max_size']             = 4024;
        $config['max_width']            = 12024;
        $config['max_height']           = 12024;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('images')) {
            $data = array('error' => strip_tags($this->upload->display_errors()));
        } else {
            $data = array(
                'id'    => $this->input->post('id'),
                'upload_data' => $this->upload->data(),
            );


            $src = $this->core->select('img', 'cms_widget', 'id=' . $data['id']);
            if ($src) {
                unlink(str_replace(base_url(), './', $src));
            }


            $update = array(
                "img"    =>  str_replace('./', base_url(), $config['upload_path'] . $data['upload_data']['file_name']),
            );
            $this->db->update('cms_widget', $update, 'id="' . $data['id'] . '"');

            $data = array(
                'id'    =>  $this->input->post('id'),
                'img'   =>   $update['img'],
                'upload_data' => $this->upload->data(),
            );
        }

        echo json_encode($data);
    }


    function widget_update()
    {
        $post = json_decode(file_get_contents('php://input'), true);

        $update = array(
            'h1'          => $post['data']['h1'] ? $post['data']['h1'] : "",
            'h2'          => $post['data']['h2'] ? $post['data']['h2'] : "",
            'h3'          => $post['data']['h3'] ? $post['data']['h3'] : "",
            'h4'          => $post['data']['h4'] ? $post['data']['h4'] : "",
            'content'     => $post['data']['content'] ? $post['data']['content'] : "",
            'img'     => $post['data']['img'] ? $post['data']['img'] : "",
            'href'     => $post['data']['href'] ? $post['data']['href'] : "",

            'update_date'    => date('Y-m-d H:i:s'),
        );
        $this->db->update('cms_widget', $update, 'id=' . $post['id']);


        $json = array(
            "error" => 0,
            "result" => $update,
        );
        echo json_encode($json);
    }

    public function widget_insert()
    {
        $post = json_decode(file_get_contents('php://input'), true);

        $section = $post['data']['section'];
        $data = array(
            'h1'        => 'Add Text 1 Here !',
            'h2'        => 'Add Text 2 Here !',
            'h3'        => 'Add Text 3 Here !',
            'h4'        => 'Add Text 4 Here !',
            'content'   => 'Add Content Here !',
            "section" => $section,
            "sorting" => 100,
            "input_date" => date('Y-m-d H:i:s'),
            "update_date" => date('Y-m-d H:i:s'),

        );
        $this->db->insert('cms_widget', $data);

        $json = array(
            "error" => 0,
            "result" => $data,
        );
        echo json_encode($json);
    }

    public function widget_delete()
    {
        header('Content-Type: application/javascript');
        error_reporting(E_ERROR | E_PARSE);
        $post = json_decode(file_get_contents('php://input'), true);

        $img = $this->core->select('img', 'cms_widget', 'id=' . $post['id']);
        if ($img) {
            $link = str_replace(base_url(), './', $img);
            unlink($link);
        }

        $this->db->delete('cms_widget', "id=" . $post['id']);
    }

    public function widget_sortable()
    {
        $post = json_decode(file_get_contents('php://input'), true);
        $i = 0;
        foreach ($post['data'] as $row) {
            $data = array(
                "sorting" => $i++,
            );
            $this->db->update('cms_widget', $data, "id = " . $row['id']);
        }
        $json = array(
            "error" => 0,
            "data" => $post['data'],
        );
        echo json_encode($json);
    }
}
