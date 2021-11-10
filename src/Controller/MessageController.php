<?php

namespace App\Controller;

use App\Model\MessageManager;

class MessageController extends ItemController
{
    public function add(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $message = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, insert and redirection
            $messageManager = new MessageManager();
            $id = $messageManager->insert($message);
            header('Location:/posts/show?id='.$id);
        }

        return $this->twig->render('Message/add.html.twig');
    }
}
