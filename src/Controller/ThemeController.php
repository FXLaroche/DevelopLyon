<?php

namespace App\Controller;

use App\Model\ThemeManager;
use App\Model\CategoryManager;

class ThemeController extends AbstractController
{
    /**
     * List items
     */
    public function index(): string
    {
        $themeManager = new ThemeManager();
        $themes = $themeManager->selectAll();

        return $this->twig->render('Theme/index.html.twig', ['themes' => $themes]);
    }


    /**
     * Show informations for a specific item
     */
    public function show(int $id): string
    {
        $themeManager = new ThemeManager();
        $theme = $themeManager->selectOneById($id);

        return $this->twig->render('Theme/show.html.twig', ['theme' => $theme]);
    }


    /**
     * Edit a specific item
     */
    public function edit(int $id): string
    {
        $themeManager = new ThemeManager();
        $theme = $themeManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $theme = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, update and redirection
            $themeManager->update($theme);
            header('Location: /themes/show?id=' . $id);
        }
        $categoryManager = new CategoryManager();
        $categorys = $categoryManager->selectAll();

        return $this->twig->render('Theme/edit.html.twig', [
            'theme' => $theme,
            'categorys' => $categorys
        ]);
    }


    /**
     * Add a new item
     */
    public function add(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $theme = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, insert and redirection
            $themeManager = new ThemeManager();
            $id = $themeManager->insert($theme);
            header('Location:/themes/show?id=' . $id);
        }
        $categoryManager = new CategoryManager();
        $categorys = $categoryManager->selectAll();

        return $this->twig->render('Theme/add.html.twig', ['categorys' => $categorys]);
    }


    /**
     * Delete a specific item
     */
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = trim($_POST['id']);
            $themeManager = new ThemeManager();
            $themeManager->delete((int)$id);
            header('Location:/themes');
        }
    }
}
