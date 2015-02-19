<?php

class SignupController extends AbstractController
{

    public function indexAction()
    {
    }
    
	public function registerAction()
    {
		$user = new Users();

        //Receiving the variables sent by POST
        $name = $this->request->getPost('name', 'email');
        $fingerprint = $this->request->getPost('fingerprint');
        $key = $this->request->getPost('key');

        //Store and check for errors
        $success = $user->save(array('name'=>$name, 
        'fingerprint' => $fingerprint, 'key' => $key), array('name', 'fingerprint', 'key'));

        if ($success) {
            echo "Thanks for register!";
        } else {
            echo "Sorry, the following problems were generated: ";
            foreach ($user->getMessages() as $message) {
                echo $message->getMessage(), "<br/>";
            }
        }
    }

}