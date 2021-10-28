<?php

namespace App\Controller;

use App\Model\CategoryManager;

class CategoryController extends AbstractController
{
    /**
     * List items
     */
    public function index(): string
    {
        $categoryManager = new CategoryManager();
        $categorys = $categoryManager->selectAll();

        return $this->twig->render('Category/index.html.twig', ['categorys' => $categorys]);
    }


    /**
     * Show informations for a specific item
     */
    public function show(int $id): string
    {
        $categoryManager = new CategoryManager();
        $category = $categoryManager->selectOneById($id);

        return $this->twig->render('Category/show.html.twig', ['category' => $category]);
    }


    /**
     * Edit a specific item
     */
    public function edit(int $id): string
    {
        $categoryManager = new CategoryManager();
        $category = $categoryManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $category = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, update and redirection
            $categoryManager->update($category);
            header('Location: /categorys/show?id=' . $id);
        }

        return $this->twig->render('Category/edit.html.twig', [
            'category' => $category,
        ]);
    }


    /**
     * Add a new item
     */
    public function add(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $category = array_map('trim', $_POST);
            if (isset($_FILES['picture_link'])) {
                $tmpName = $_FILES['picture_link']['tmp_name'];
                $name = $_FILES['picture_link']['name'];
                $size = $_FILES['picture_link']['size'];
                $error = $_FILES['picture_link']['error'];

                $tabExtension = explode('.', $name);
                $extension = strtolower(end($tabExtension));
                //Tableau des extensions que l'on accepte
                $extensions = ['jpg', 'png', 'jpeg', 'gif'];
                //Taille max que l'on accepte
                $maxSize = 400000;
                if (in_array($extension, $extensions) && $size <= $maxSize && $error == 0) {
                    $uniqueName = uniqid('', true);
                    //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
                    $file = $uniqueName . "." . $extension;
                    //$file = 5f586bf96dcd38.73540086.jpg
                    move_uploaded_file($tmpName, './upload/' . $file);
                } else {
                    $error .= "Mauvaise extension";
                }
            }

            // TODO validations (length, format...)
            if (empty($category['name'])) {
                $error = "name is required";
            } else {
                // if validation is ok, insert and redirection
                $categoryManager = new CategoryManager();
                $id = $categoryManager->insert($category);
                header('Location:/categorys/show?id=' . $id);
            }
        }

        return $this->twig->render('Category/add.html.twig');
    }


    /**
     * Delete a specific item
     */
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = trim($_POST['id']);
            $categoryManager = new CategoryManager();
            $categoryManager->delete((int)$id);
            header('Location:/categorys');
        }
    }
}
