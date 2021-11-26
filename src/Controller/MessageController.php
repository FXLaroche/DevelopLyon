<?php

namespace App\Controller;

use App\Model\MessageManager;
use App\Model\PostManager;
use DateTime;

class MessageController extends AbstractController
{
    public function add(): string
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $message = [];
            $message = array_map('trim', $_POST);
            $date = new DateTime();
            $message['date'] = date_format($date, 'Y-m-d H:i:s');
            $postManager = new PostManager();
            $post = $postManager->selectOnePostById((int)$message['post_id']);
            $messageManager = new MessageManager();
            $messages = $messageManager->selectAllMessageForOnePost((int)$message['post_id']);

            // TODO validations (length, format...)
            if ($message['message'] === "") {
                $errors[] = "Veuillez mettre un message avant de valider.";
                return $this->twigRender('Post/show.html.twig', ['post' => $post,
                'messages' => $messages, 'errors' => $errors]);
            }

            // if validation is ok, insert and redirection
            $messageManager = new MessageManager();
            $messageManager->insert($message);
            $messages = $messageManager->selectAllMessageForOnePost((int)$message['post_id']);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            return $this->twigRender('Post/show.html.twig', ['post' => $post, 'messages' => $messages]);
        } else {
            $errors[] = "Erreur technique.";
            return $this->twigRender('Post/show.html.twig', ['errors' => $errors]);
        }
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $message = array_map('trim', $_POST);

            $messageManager = new MessageManager();
            $idMessage = (int)$message['id'];
            $messageManager->delete($idMessage);
            $idPost = $message['post_id'];
            header('Location:/post/show?id=' . $idPost);
        }
    }

    /**
     * Edit a specific message
     */
    public function edit(int $id): string
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $message = array_map('trim', $_POST);
            $message['id'] = $id;
            $postManager = new PostManager();
            $post = $postManager->selectOnePostById((int)$message['post_id']);
            $messageManager = new MessageManager();
            $messages = $messageManager->selectAllMessageForOnePost((int)$message['post_id']);

            // TODO validations (length, format...)
            if (empty($message['message'])) {
                $errors[] = "Please put a message, it's better.";
                return $this->twigRender('Post/show.html.twig', ['post' => $post,
                'messages' => $messages, 'errors' => $errors]);
            }
            $date = new DateTime();
            $message['date'] = date_format($date, 'Y-m-d H:i:s');
            // if validation is ok, update and redirection
            $messageManager = new MessageManager();
            $messageManager->update($message);
            $messages = $messageManager->selectAllMessageForOnePost((int)$message['post_id']);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            return $this->twigRender('Post/show.html.twig', ['post' => $post, 'messages' => $messages]);
        } else {
            $errors[] = "Erreur technique.";
            return $this->twigRender('Post/show.html.twig', ['errors' => $errors]);
        }
    }
}
