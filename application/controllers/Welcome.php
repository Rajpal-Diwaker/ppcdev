<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {


	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('Ppc_model');
		$this->load->library('user_agent');
	}
	
	public function index()
	{
	$data = array(
		'page_title'=> 'Top Mobile App Development Company In USA & India | Techugo',
		'description'=>'',
		'keywords'=>'',
		'og'=>''
		);
		$this->load->view('templates/header');
		$this->load->view('welcome_message');
		$this->load->view('templates/footer');

	}

	public function ppc_form()
	{
		// print_r($_POST);die;
		if(!empty($_POST['user_name'])||!empty($_POST['phone'])||!empty($_POST['email'])){
		$from_email='sales@techugo.com';
		$to_email='sales@techugo.com';
		$fullname = $_POST['user_name'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];

		$requestsubject ='New Quote Request';
		$requestmessage = '<p>Hello,<br><br>Here are the Details to contact<br></br></br>
			Name : '.$fullname.'</br></br>
			Email : '.$email.'</br> </br>
			phone: '.$phone.'</br> </br> 

			<br><br><br>Thanks and Regards,<br><p>'.$fullname.'</p>';
			
			$requestbody =
		'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset='.strtolower(config_item('charset')).'" />
					<title>'.html_escape($requestsubject).'</title>
					<style type="text/css">
						body {
							font-family: Arial, Verdana, Helvetica, sans-serif;
							font-size: 16px;
						}
					</style>
				</head> 
				<body>
				'.$requestmessage.'
				</body>
				</html>';
	
		$headers = 'From:'.$from_email."\r\n".
		'Reply-To: '.$from_email. "\n" .
		'Content-type: text/html; charset=iso-8859-1' . "\r\n".
		'X-Mailer: PHP/' . phpversion();
	  //  print_r($requestbody);die;
	  $sendingemail = $this->send_mail($requestsubject,$from_email,$to_email,$headers,$requestbody);
   
	  $insertData= array(
		"name"=>$fullname?$fullname:'',
		"email"=>$email?$email:'',
		"phone"=>$phone?$phone:'',
	);
	//print_r($insertData);die;
	$response = $this->Ppc_model->insert_data($insertData);
	
	if(1 == $response){
		
		echo'1';
	}
		else
		{
		echo '2';
		}
	
	}else{
	
		$this->load->view('templates/header');
		$this->load->view('welcome_message');
		$this->load->view('templates/footer');
	}
}

function send_mail($subject,$fromemail,$toemail,$header,$msgbody){
	$this->load->library('email');
	$this->email->set_header('Header',$header);
	$this->email->set_mailtype("html");
	$this->email->set_newline("\r\n");
	$this->email->reply_to($fromemail);
	$this->email->from($fromemail);
	$this->email->to($toemail);
	$this->email->subject($subject);
	$this->email->message($msgbody);
	$this->email->send();


	}
}
