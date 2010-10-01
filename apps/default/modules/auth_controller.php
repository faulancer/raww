<?php
  
  class AuthController extends AppController{
  
        public function before_filter(){
            Auth::check();
        }
  }