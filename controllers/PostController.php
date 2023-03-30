<?php

namespace Apex\controllers;

use Apex\models\Categories;
use Apex\models\CategoryOption;
use Apex\models\Options;
use Apex\src\Controller\Controller;
use Apex\src\Request;

class PostController extends Controller
{
    public function createChooseCategory(Request $request): \Apex\src\Response
    {
        $category = Categories::select()
            ->where('name', 'like', "%{$request->input()['category']}%" ?? null)
            ->get();
        return $this->view('post.create.category', ['categories' => $category]);
    }

    public function createFillOptions(Request $request): \Apex\src\Response
    {
        $request->sessionValidate($request->input(), ['category_id' => 'required']);
        $category = Categories::select()->firstWhere(['category_id' => $request->input()['category_id']]);
        $options  = $category->options;
        return $this->view('post.create.options', ['categories' => $category, 'options' => $options]);
    }
}