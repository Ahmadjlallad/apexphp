<?php
declare(strict_types=1);
namespace Apex\controllers;
class SiteController
{
    public $test = 'asdf';
    public function store()
    {
        return 'handling submitted data';
    }
    public function view()
    {
        return 'contact';
    }
}