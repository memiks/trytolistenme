<?php

class LoginController extends AbstractController
{
    public function indexAction() {

        if ($this->request->isPost()) {

            //Receiving the variables sent by POST
            $name = $this->request->getPost('name', 'email');
            $fingerprint = $this->request->getPost('fingerprint');

            //Find for the user in the database
            $user = Users::findFirst(array(
                "name = :name: AND fingerprint = :fingerprint:",
                "bind" => array('name' => $name, 'fingerprint' => $fingerprint)
            ));

            if ($user != false) {
                //$this->_registerSession($user);
                $this->flash->success('Welcome ' . $user->name);
                //Set a session variable
                $this->session->set("name", $user->name);
                $this->session->set("fingerprint", $user->fingerprint);

                //Redirect to the 'chat' controller if the user is valid
                $this->view->disable();
                $this->response->redirect('chat/index');
            }
            $this->flash->error('Wrong name/fingerprint');

        }

        //Forward to the login form again
        $this->view->disable();
        $this->response->redirect('/');
    }
}