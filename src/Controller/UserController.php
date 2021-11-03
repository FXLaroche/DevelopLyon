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
                echo "<script>alert(\"Vous êtes inscrits, maintenant il ne vous reste plus qu'à vous connecter !\")</script>";
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
        $userManager = new UserManager();
        $connexion = $userManager->login($user);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = array_map('trim', $_POST);
            if (isset($user['email']) && $user['password'])
            {
                if($connexion == 1) 
                {
                    return $this->twig->render('User/index.html.twig', [
                        'user' => $user,
                    ]);
                }
     
        }
        }
    }
}
