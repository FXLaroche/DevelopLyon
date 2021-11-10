<?php

namespace App\Controller;

use App\Model\ThemeManager;

class ThemeController extends AbstractController
{
    /**
     * List category
     */
    public function index(int $id): string
    {
        $themeManager = new ThemeManager();
        $themes = $themeManager->selectAllById($id);

        return $this->twigRender('Theme/index.html.twig', ['themes' => $themes]);
    }
}
