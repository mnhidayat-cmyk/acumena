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
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
		// $this->load->view('welcome_message');
		redirect(base_url('dashboard'));
	}

	public function test_email(){
		$to      = 'cranam21@gmail.com';
        $subject = 'Test Lagi SendGrid via APIs';
        $html    = '<h3>Hello!</h3><p>This is a test email.</p>';
        $text    = 'Hello! This is a test email.';

        $resp = sendgrid_send($to, $subject, $html, $text, [
            'headers'         => [
                'X-App' => 'Acumena'
            ]
        ]);

        header('Content-Type: application/json');
        echo json_encode($resp);
	}
}
