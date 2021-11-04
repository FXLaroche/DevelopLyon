<?php

namespace App\Controller;

use App\Model\PostManager;

class PostController extends AbstractController
{
    public function index(): string
    {
        $postManager = new PostManager();
        $posts = $postManager->selectAll();

        return $this->twig->render('Post/show.html.twig', ['posts' => $posts]);
    }

    public function show(int $id): string
    {
        $postManager = new PostManager();
        $post = $postManager->selectOneById($id);

        return $this->twig->render('Post/show.html.twig', ['post' => $post]);
    }
}
