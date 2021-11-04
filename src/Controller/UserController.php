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
            } elseif (empty($user['email'])) {
                $errors = 'The e-mail is required';
                return $errors;
            } elseif (empty($user['password'])) {
                $errors = 'The password is required';
                return $errors;
            } else {
                $userManager = new UserManager();
                $userManager->registerUser($user);
                header('Location:/user/login');
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
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = trim($_POST['id']);
            $userManager = new UserManager();
            $userManager->delete((int)$id);
            header('Location:/users');
        }
    }

    public function connect($user)
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = array_map('trim', $_POST);
            if (isset($login['email']) && $login['password']) {
                if (password_verify($user['password'], $login['password'])) {
                    $userManager = new UserManager();
                    $userManager->login($login);
                    header('Location: /users');
                } else {
                    echo "Le mot de passe n'est pas le bon";
                }
            }
            return $this->twig->render('User/login.html.twig');
        }
    }
}
