<?php
	class AccountController extends AbstractController{
		public function indexAction(){
			$name = $this->session->get("name");
		}
	}