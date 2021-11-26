<?php

namespace App\Controller;

use App\Model\CategoryManager;

class CategoryController extends AbstractController
{

    private CategoryManager $categoryManager;


    public function __construct()
    {
        parent::__construct();
        $this->categoryManager = new CategoryManager();
    }

    /**
     * List category
     */
    public function index(): string
    {
        $categorys = $this->categoryManager->selectAll();

        return $this->twigRender('Category/index.html.twig', ['categorys' => $categorys]);
    }
}
