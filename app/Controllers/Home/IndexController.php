<?php

namespace App\Controllers\Home;

use App\Controllers\Controller;
use frame\Html;

class IndexController extends Controller
{
	public function index()
	{	
		view();
	}

	public function register()
	{
		$post = $_POST;
		dd($post);
	}

	public function registerInfo()
	{
		header("Content-type: application/json; charset=utf-8");
		echo json_encode(['challenge' => 'l2uezcWQMgtEr67ViwnRXC3Ob9qEHUP3Ox+fE+zWw60=', 'id' => '+asLAAAAAAAAAA==']);
		exit();
	}
}