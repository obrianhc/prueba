<?php
 
use \Sentry;
 
class HomeController extends BaseController
{
    public function doLogin()
    {
        //TODO login
    }
 
    public function login()
    {
        View::display('login.twig', array());
    }
 
    public function logout()
    {
        //TODO logout
    }
}