<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use frame\Html;

class IndexController extends Controller
{
	public function index()
	{	
		Html::addCss();
		Html::addJs();
		return view();
	}
}