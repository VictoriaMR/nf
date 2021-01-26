<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use frame\Html;

class LoginController extends Controller
{
	public function index()
	{	
		Html::addCss();
		Html::addJs();
		return view();
	}

	public function loginCode()
	{
		header('Content-Type: image/png');
		$imageService = make('App/Services/Common/ImageService');
		\frame\Session::set('admin_login_code', $imageService->verifyCode());
	    exit();
	}
}