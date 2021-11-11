<?php

namespace App\Controller;

use App\Model\CategoryManager;

class CategoryController extends AbstractController
{
    /**
     * List category
     */
    public function index(): string
    {
        $categoryManager = new CategoryManager();
        $categorys = $categoryManager->selectAll();

        return $this->twigRender('Category/index.html.twig', ['categorys' => $categorys]);
    }
}
