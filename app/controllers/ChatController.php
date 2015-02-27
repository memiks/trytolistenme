<?php
class ChatController extends AbstractController{
	
	public function indexAction(){
		//Javascripts in the header

		$name = $this->session->get("name");
        //Find for the user in the database
	}

	public function sentAction(){
		// Check if request has made with POST
		if($this->request->isPost()==true){
			if(!$this->request->isAjax()){
				$this->flash->error('The request is not ajax');
			}
			else{
				$chat=new Chat();

				//Receiving the variables sent by POST
                
				$from=$this->session->get("fingerprint");
				$friend=$this->request->getPost('friend','email');
				$message=$this->request->getPost('message');

				//Store and check for errors
				$success=$chat->save(array('fromuser'=>$from,'friend'=>$friend,'message'=>$message, 'date'=>time()),array('fromuser','friend','message','date'));

				if($success){
					$result=['message'=>'sent'];
				}
				else{
					$result=['message'=>'error'];
					foreach($chat->getMessages() as $message){
						$result[]=$message->getMessage();
					}
				}
				$this->view->disable();
				echo json_encode($result);
			}
		}
		else{
			$this->flash->error('This is not POST request');
		}
		return ;
	}

	public function getAction(){
		// Check if request has made with POST
		if($this->request->isPost()==true){
			if(!$this->request->isAjax()){
				$this->flash->error('The request is not ajax');
			}
			else{
				//$chat=new Chat();

				//Receiving the variables sent by POST
				$friend=$this->session->get("fingerprint");
				$from=$this->request->getPost('friend','email');
				$min=$this->request->getPost('min','email');
				$max=$this->request->getPost('max','email');
				$num=$this->request->getPost('num','email');

                $query = "fromuser = :from: AND friend = :friend:";
                //$query = "fromuser in ( :from: , :friend: ) AND friend in ( :from: , :friend: )";
                $bind = array('from' => $from, 'friend' => $friend);
                if($min>0) {
                    $query .= " AND id > :min:";
                    $bind["min"] = $min;
                }
                if($max>0) {
                    $query .= " AND id < :max:";
                    $bind["max"] = $max;
                }
                if($num>0) {
                    $query .= " AND limit :num:";
                    $bind["num"] = $num;
                }

				//Store and check for errors
                $chats = Chat::find(array(
                    $query,
                    "bind" => $bind,
                    "limit" => 100
                ));

                foreach ($chats as $chat) {
                    $result[] = $chat;
                }

				$this->view->disable();
				echo json_encode($result);
			}
		}
		else{
			$this->flash->error('This is not POST request');
		}
		return ;
	}

	public function deleteAction(){
		// Check if request has made with POST
		if($this->request->isPost()==true){
			if(!$this->request->isAjax()){
				$this->flash->error('The request is not ajax');
			}
			else{
				//Receiving the variables sent by POST
				$friend=$this->session->get("fingerprint");
				$from=$this->request->getPost('friend','email');
				//$ids=$this->request->getPost("ids");
				$ids=$this->request->getPost("ids");

				$query = "fromuser = :from: AND friend = :friend: AND id = :id:";
                $bind = array('from' => $from, 'friend' => $friend, "id" => -1);

				foreach($ids as $id) {
					//Store and check for errors
					$bind["id"] = $id;
                    $chats = Chat::find(array(
                        $query,
                        "bind" => $bind,
                        "limit" => 100
                    ));

                    foreach ($chats as $chat) {
                        $result[] = $chat->id;
                        $chat->delete();
                    }
				}
                $result["result"] = "ok";

				$this->view->disable();

				echo json_encode($result);
			}
		}
		else{
			$this->flash->error('This is not POST request');
		}
		return ;
	}

	public function chatFromUsers(){
		// Check if request has made with POST
		if($this->request->isPost()==true){
			if(!$this->request->isAjax()){
				$this->flash->error('The request is not ajax');
			}
			else{
				//$chat=new Chat();

				//Receiving the variables sent by POST
				$friend=$this->session->get("fingerprint");

                $query = "friend = :friend:";
                //$query = "fromuser in ( :from: , :friend: ) AND friend in ( :from: , :friend: )";
                $bind = array('friend' => $friend);
                if($min>0) {
                    $query .= " AND id > :min:";
                    $bind["min"] = $min;
                }
                if($max>0) {
                    $query .= " AND id < :max:";
                    $bind["max"] = $max;
                }
                if($num>0) {
                    $query .= " AND limit :num:";
                    $bind["num"] = $num;
                }

				//Store and check for errors
                $chats = Chat::find(array(
                    $query,
                    "bind" => $bind,
                    "limit" => 100
                ));

                foreach ($chats as $chat) {
                    $result[] = $chat;
                }

				$this->view->disable();
				echo json_encode($result);
			}
		}
		else{
			$this->flash->error('This is not POST request');
		}
		return ;
	}
}
	
	