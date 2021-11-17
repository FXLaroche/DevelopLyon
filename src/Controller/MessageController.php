<?php

namespace App\Controller;

use App\Model\MessageManager;
use DateTime;

class MessageController extends AbstractController
{
    public function add(): void
    {
        $error = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $message = [];
            $message = array_map('trim', $_POST);
            $date = new DateTime();
            $message['date'] = date_format($date, 'Y-m-d H:i:s');

            // TODO validations (length, format...)
            if (empty($message['message'])) {
                $error[] = "Please put a message, it's better.";
            }

            // if validation is ok, insert and redirection
            $messageManager = new MessageManager();
            $messageManager->insert($message);
            $idPost = $message['post_id'];
            header('Location:/post/show?id=' . $idPost);
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
    public function edit(int $id): void
    {
        $error = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $message = array_map('trim', $_POST);
            $message['id'] = $id;

            // TODO validations (length, format...)
            if (empty($message['message'])) {
                $error[] = "Please put a message, it's better.";
            }
            $date = new DateTime();
            $message['date'] = date_format($date, 'Y-m-d H:i:s');
            // if validation is ok, update and redirection
            $messageManager = new MessageManager();
            $messageManager->update($message);
            $idPost = $message['post_id'];
            header('Location:/post/show?id=' . $idPost);
        }
    }
}
