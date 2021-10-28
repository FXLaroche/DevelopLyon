<?php

namespace App\Controller;

use App\Model\PostManager;

class PostController extends AbstractController
{
    public function add(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPost = array_map('trim', $_POST);
            $newPost = array_map('htmlentities', $newPost);

            $postManager = new PostManager();
            $id = $postManager->create($newPost);

            header('Location:/post/show?id=' . $id);
        }
        return $this->twig->render('Post/add.html.twig');
    }
}
