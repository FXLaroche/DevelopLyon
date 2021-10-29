<?php
namespace App\Controller;

use App\Model\ItemManager;

class MessageController extends AbstractController
{
    public function add(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $message= array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, insert and redirection
            $messageManager = new MessageMnager();
            $id = $messageManager->insert($message);
            header('Location:/message');
        }

        return $this->twig->render('Message/add.html.twig');
    }

}