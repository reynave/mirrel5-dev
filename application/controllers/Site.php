<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Site extends CI_Controller
{
    /////////////////////////////
    // EDIT HERE 
    //masukkan secret key-nya berdasarkan secret key masig-masing saat create api key nya GOOGEL CHAPTA VER 3
    public $site_key = "6LcrF5kUAAAAAL2rrZYhBWshg9rX8jEEePZGJBN-";
    public $secret_key = "6LcrF5kUAAAAAHJUwNsD_svQHkq06gQERh4CqOY3";
    public $email_subject = "Message form exaque.com.com's visitor ";
    // 
    ////////////////////////////

    public function __construct()
    {
        parent::__construct();
        error_reporting(E_ALL);
        date_default_timezone_set('Asia/Jakarta');
        $this->db->query("SET time_zone = '+07:00'");
        foreach ($_GET as $key => $value) {
            if ($key ==  "adminbiz") {
                redirect(base_url() . 'login/index');
                exit;
            }
        }
        $this->core->https();
        $this->core->site_key($this->site_key);
    }

    public function index($segment1 = "")
    {
        $data = array(
            // CORE FUNCTION -> DONT EDIT
            "core"      => $this->core->start(),
            "thumb"     => base_url() . 'public/thumb.php?src=',
            "custom" => [],  
            // STATIS THEMES -> EDIT HERE
            "footer"          => $this->core->cms_label('footer'),

        );

        
        $themes =  $data['core']['themes'] == 'index' ? $segment1 : $data['core']['themes'];
        $data['custom'] = $this->model->custom(  $themes);

 
        $this->core->data($data);
        $this->load->view('themes/global/header', $data);
        $this->load->view('themes/' . $themes, $data);
        $this->load->view('themes/global/footer', $data);
    }
    public function config()
    {
        header('Content-Type: application/javascript');
        echo '
        var base_url = "' . base_url() . '"; 
        var api = "' . base_url() . 'api/";
        var cms = "' . base_url() . 'admin/app/";
        ';
    }

    // for CMS send email at  "Hubungi kami";
    public function send()
    {



        $subject = $this->email_subject;
        $secret_key = $this->secret_key;


        $message = "";
        foreach ($this->input->post() as $name => $value) {
            if ($name != "return" && $name != "g-recaptcha-response") {
                $message .= $name . ' : ' . $value . "\n";
            }
        }

        $config['protocol']    = 'smtp';
        $config['smtp_host']    = $this->core->select('value', 'global_setting', 'id=101');
        $config['smtp_port']    = $this->core->select('value', 'global_setting', 'id=102');
        $config['smtp_user']    = $this->core->select('value', 'global_setting', 'id=103');
        $config['smtp_pass']    = $this->core->select('value', 'global_setting', 'id=104');

        $config['smtp_timeout'] = $this->core->select('value', 'global_setting', 'id=112');
        $config['charset']    = 'utf-8';
        $config['newline']    = "\r\n";
        $config['mailtype'] = 'text'; // or html
        $config['validation'] = TRUE; // bool whether to validate email or not      

        $this->email->initialize($config);

        $email  = $this->core->select('value', 'global_setting', 'id=103');
        $to     = $this->core->select('value', 'global_setting', 'id=105');
        //$to     = 'felix@cuvox.de';

        $captcha = $_POST['g-recaptcha-response'];


        $error = 'wrong captcha';
        if ($captcha != '') {

            $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secret_key) . '&response=' . $captcha;
            $recaptcha = file_get_contents($url);
            $responseData  = json_decode($recaptcha, true);
            if ($responseData['success']) {

                $this->email->from($email);
                $this->email->to($to);

                $this->email->subject($subject);
                $this->email->message($message);

                if ($this->email->send()) {
                    redirect($this->input->post('return') . '?error=0');
                } else {
                    redirect($this->input->post('return') . '?error=500');
                }
                redirect($this->input->post('return') . '?error=0');
            }
            redirect($this->input->post('return') . '?error=400');
        }
        redirect($this->input->post('return') . '?error=captcha');
        // DOC error https://www.restapitutorial.com/httpstatuscodes.html
    }
}
