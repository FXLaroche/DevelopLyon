<?php

namespace App\Controller;

use App\Model\UserManager;

class UserController extends AbstractController
{
    public function add(): string
    {
        $errors = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = array_map('trim', $_POST);


            if (empty($user['nickname'])) {
                $errors = 'The nickname is required';
            } elseif (empty($user['email'])) {
                $errors = 'The e-mail is required';
            } elseif (empty($user['password'])) {
                $errors = 'The password is required';
            } else {
                $userManager = new UserManager();
                if ($userManager->registerUser($user) === 0) {
                    $errors = 'The email is already in use!!';
                } else {
                    header('Location: /user/login');
                }
            }
        }

        return $this->twig->render('User/add.html.twig', ['errors' => $errors]);
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

    public function login()
    {
        $errors  = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $loginData = array_map('trim', $_POST);
            $email = $loginData['email'];
            $password = $loginData['password'];

            $userManager = new UserManager();
            try {
                $loginFromDataBase = $userManager->getLoginData($email);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }

            if (isset($loginFromDataBase) && password_verify($password, $loginFromDataBase['password'])) {
                foreach ($loginFromDataBase as $key => $value) {
                    $_SESSION[$key] = $value;
                }
                //$this->twig->addGlobal('nickname', $_SESSION['nickname']);
                //$this->twig->addGlobal('connectionStatus', "Se déconnecter");
                //$this->twig->addGlobal('connectionLink', "logout");

                header("Location:/");
            }
            $errors[]  = "Email or password invalid!";
        }

        return $this->twig->render('User/login.html.twig', ['errors' => $errors]);
    }

    public function logout()
    {
        session_destroy();
        //$this->twig->addGlobal('nickname', '');
        //$this->twig->addGlobal('connectionStatus', "Se Connecter");
        //$this->twig->addGlobal('connectionLink', "login");
        header("Location:/");
    }
}
