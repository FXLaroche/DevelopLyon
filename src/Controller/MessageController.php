<?php

namespace App\Controller;

use App\Model\MessageManager;

class MessageController extends AbstractController
{
    public function add(): string
    {
        $error = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            // clean $_POST data
            $message = [];
            $message['user_id'] = ;
            $message['date'] = date("Y-m-d H:i:s");
            $message['post_id'] = ;
            $message['message'] = array_map('trim', $_POST);

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
