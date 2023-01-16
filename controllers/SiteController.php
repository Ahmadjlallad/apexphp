<?php
declare(strict_types=1);
namespace Apex\controllers;
use Apex\src\App;
use Apex\src\Controller\Controller;

class SiteController extends Controller
{
    public function storeContact(): bool|string
    {
        return $this->view('contact', ['body' => $this->request->input()]);
    }
    public function showContact(): bool|string
    {
        return $this->view('contact');
    }
    public function home(): bool|string
    {
//        App::getInstance()->session->setFlash('success', 'test');



        dd(App::getInstance()->session);
        return $this->view('home');
    }
}