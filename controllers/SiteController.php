<?php
declare(strict_types=1);

namespace Apex\controllers;

use Apex\src\Controller\Controller;
use Apex\src\Response;

class SiteController extends Controller
{
    public function storeContact(): Response
    {
        return $this->view('contact', ['body' => $this->request->input()]);
    }

    public function showContact(): Response
    {
        return $this->view('contact');
    }

    public function home(): Response
    {
//        return $this->view('home');
        return $this->asJson(['test' => 'hi']);
    }
}