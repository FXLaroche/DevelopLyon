<?php

namespace App\Controller;

use App\Model\UserManager;

class UserController extends AbstractController
{
    public function add(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = array_map('trim', $_POST);

            if (empty($user['nickname'])) {
                $errors = 'The nickname is required';
                return $errors;
            }

            elseif (empty($user['email'])) {
                $errors = 'The e-mail is required';
                return $errors;
            }

            elseif (empty($user['password'])) {
                $errors = 'The password is required';
                return $errors;
            }
            
            if (empty($errors)) {
            $userManager = new UserManager();
            $userManager->insert($user);
            header('Location:/users');
            }
        }

        return $this->twig->render('User/add.html.twig');
    }

    public function index(): string
    {
        $userManager = new UserManager();
        $users = $userManager->selectAll('nickname');

        return $this->twig->render('User/index.html.twig', ['users' => $users]);
    }

    public function show(int $id): string
    {
        $userManager = new UserManager();
        $user = $userManager->selectOneById($id);

        return $this->twig->render('User/show.html.twig', ['user' => $user]);
    }

    public function edit(int $id): string
    {
        $userManager = new UserManager();
        $user = $userManager->selectOneById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = array_map('trim', $_POST);
            $userManager->update($user);
            header('Location: /user/show?id=' . $id);
        }
        return $this->twig->render('User/edit.html.twig', [
            'user' => $user,
        ]);
    }
}
