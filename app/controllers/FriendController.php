<?php
	class FriendController extends AbstractController {
		public function indexAction(){
			$fingerprint = $this->session->get("fingerprint");
			
			// Get and print virtual robots ordered by name
            $friends = Friends::findFirst(array(
                "of = :of:",
                "bind" => array('of' => $fingerprint)
            ));
            

            if ($friends != false) {
                $result=$friends;
            } else {
                $result=['error'=>'Unable to retrieve friends'];
               // $this->flash->error('Unable to retrieve friends');
            }


			$this->view->disable();
			echo json_encode($result);

            return;
		}

		public function checkAction(){
            $fingerprint = $this->request->getPost('fingerprint');
            $name = $this->request->getPost('name', 'email');

			// Get and print virtual robots ordered by name
            $users = Users::findFirst(array(
                "name = :name: AND fingerprint = :fingerprint:",
                "bind" => array('name' => $name, 'fingerprint' => $fingerprint)
            ));
            
			$this->view->disable();
            if ($users != false) {
                $result=$users;
    			echo json_encode($result);
            } else {
                $result=['error'=>'Unable to retrieve friends'];
           //     $this->flash->error('Unable to retrieve friends');
	    		echo json_encode($result);
                return false;
            }


            return;
		}

		public function getkeyAction(){
			// Check if request has made with POST
			if($this->request->isPost()==true){
				if(!$this->request->isAjax()){
					$this->flash->error('The request is not ajax');
				}
				else{
                    $fingerprint = $this->request->getPost('fingerprint');
                    $name = $this->request->getPost('name', 'email');
        
        			// Get and print virtual robots ordered by name
                    $users = Users::findFirst(array(
                        "name = :name: AND fingerprint = :fingerprint:",
                        "bind" => array('name' => $name, 'fingerprint' => $fingerprint)
                    ));
                    
        			$this->view->disable();
                    if ($users != false) {
                        $result=$users->key;
            			echo json_encode($result);
                    } else {
                        $result=['error'=>'Unable to retrieve friends'];
                     //   $this->flash->error('Unable to retrieve friends');
        	    		echo json_encode($result);
                        return false;
                    }
        
        
                    return;
				}
			}
		}

        public function addAction(){
            
			$fingerprint = $this->session->get("fingerprint");
            // Get and print virtual robots ordered by name
            $friends = Friends::find(array(
                "of = :of:",
                "bind" => array('of' => $fingerprint)
            ));

            if ($friends != false) {
                $friends->delete();
            }

			$friends = new Friends();
            $fingerprint = $this->session->get("fingerprint");
            $friendList = $this->request->getPost('friends');

            //Store and check for errors
            $success = $friends->save(array('of'=>$fingerprint, 
            'friends' => $friendList), array('of', 'friends'));
    
            $result = [];
            if ($success) {
                //echo "Thanks for register!";
            } else {
                
                //echo "Sorry, the following problems were generated: ";
                foreach ($friends->getMessages() as $message) {
                    $result[] = $message->getMessage();
                }
            }
        
			$this->view->disable();
			echo json_encode($result);

            return;
		}
	}
