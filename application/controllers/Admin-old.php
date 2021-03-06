<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'core/Auth_Controller.php';

class Admin extends Auth_Controller
{
	public function __construct()
	{
		parent::__construct();

		// Force SSL
		//$this->force_ssl();

		// Form and URL helpers always loaded (just for convenience)
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('session');
		
    }
    
    public function index()
	{
		
		if( $this->is_logged_in() and $this->require_role('admin') )
			$this->to_admin_template( 'pages/admin/dashboard');
		else
			redirect("admin/login");
		
    }
    
    public function login()
	{
		// Method should not be directly accessible
	//if( $this->uri->uri_string() == 'admin/login')
	//		show_404();
		
		if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' )
		{
			$this->require_min_level(1);
			$users_data =
			[
				'auth_user_id' => $this->auth_user_id,
				'auth_username' => $this->auth_username,
				'auth_level' => $this->auth_level,
				'auth_role' => $this->auth_role,
				'auth_email' => $this->auth_email,
				'acl' => $this->acl
			];
			$this->session->set_userdata(  $users_data );
			//dump( $_SESSION );
			redirect('admin');
		}
			

		$this->setup_login_form();
		$this->load->view('pages/admin/login');
    }
    
    public function logout()
	{
		$this->authentication->logout();
		session_write_close();
		redirect( 'admin/login' );
	}

	protected function to_simple_template( $view_path, $data = [] ) 
    { 
        $this->load_template( 'templates/simple/index', $view_path, $data ); 
    } 
 
    protected function to_admin_template( $view_path, $data = [] ) 
    { 
        $this->load_template( 'templates/admin/content', $view_path, $data ); 
    } 
 
    protected function load_template( $template_path, $view_path = null, $data = []) 
    { 
        if ( !$view_path and ! file_exists(APPPATH.'views/pages/'.$view_path.'.php') ) 
            show_404(); 
 
        $this->load->view( $template_path, ['content' => $this->load->view( $view_path, $data, true)]); 
    } 





}