<?php

namespace App\Controller;

use App\Model\PostManager;

class PostController extends AbstractController
{
    private PostManager $postManager;

    public function __construct()
    {
        parent::__construct();
        $this->postManager = new PostManager();
    }

    public function add($themeId): string
    {
        $error = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPost = array_map('trim', $_POST);
            $newPost = array_map('htmlentities', $newPost);

            if (strlen($newPost['subject']) > 255) {
                $error[] = "I'm afraid your title is too long!";
            }
            if (empty($newPost['subject'])) {
                $error[] = "You forgot to give your post a title.";
            }

            if (empty($newPost['message'])) {
                $error[] = "Please put a message, it's better.";
            }

            if (strlen($newPost['keyword']) > 255) {
                $error[] = "Alas! you have too many keywords!";
            }

            if ($newPost['user_id'] != $_SESSION['user_id']) {
                $error[] = 'Who do you think you are?!';
            }

            if (empty($error)) {
                $postId = $this->postManager->create($newPost, $themeId);
                header('Location:/post/show?id=' . $postId);
            }
        }
        return $this->twig->render('Post/add.html.twig', ['errors' => $error]);
    }
}
