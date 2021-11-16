<?php

namespace App\Controller;

use App\Model\MessageManager;

class MessageController extends ItemController
{
    public function add(): string
    {
        $error = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $message = array_map('trim', $_POST);

            // TODO validations (length, format...)
            if (empty($message['message'])) {
                $error[] = "Please put a message, it's better.";
            }


            // if validation is ok, insert and redirection
            $messageManager = new MessageManager();
            $id = $messageManager->insert($message);
            header('Location:/posts/show?id=' . $id);
        }

        return $this->twigRender('Message/add.html.twig', ['errors' => $error]);
    }
}
