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

        return $this->twigRender('User/add.html.twig', ['errors' => $errors]);
    }

    public function index(): string
    {
        $userManager = new UserManager();
        $users = $userManager->selectAll('nickname');
        if (isset($_POST['suppr'])) {
            $ids = implode(",", $_POST["suppr"]);
            $userManager->deleteAll($ids);
            header('Location:/users');
        }

        return $this->twigRender('User/index.html.twig', ['users' => $users]);
    }

    public function show(int $id): string
    {
        $userManager = new UserManager();
        $user = $userManager->selectOneById($id);

        return $this->twigRender('User/show.html.twig', ['user' => $user]);
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
        return $this->twigRender('User/edit.html.twig', [
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
                header("Location:/");
            }
            $errors[]  = "Email or password invalid!";
        }

        return $this->twigRender('User/login.html.twig', ['errors' => $errors]);
    }

    public function logout()
    {
        session_destroy();
        header("Location:/");
    }
}
