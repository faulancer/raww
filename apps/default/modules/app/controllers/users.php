<?php

class UsersController extends AuthController{

    public function before_filter(){
      
      if($this->action != "login"){
        Auth::check();
      }
      
    }

  public function login(){
    
    $this->layout = 'login';
    
    if(isset($this->params['post']['User'])){
      
      $params = $this->params['post']['User'];
      
      $user = $this->M('User')->find_by_email_and_password($params['email'],md5($params['password']));
      
      if(!empty($user)){
        
        Auth::login($user);
        
        $this->redirect('/');
      }
    }
  }
  
  public function logout(){
    Auth::logout();
    $this->redirect('/users/login');
  }
  
}

?>