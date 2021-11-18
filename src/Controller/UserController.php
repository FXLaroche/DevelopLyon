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
        $this->checkIfRoleIsAdminOrUser();
        $userManager = new UserManager();
        $users = $userManager->selectAll('nickname');
        if (isset($_POST['suppr'])) {
            $ids = implode(",", $_POST["suppr"]);
            $userManager->deleteAll($ids);
        }
        return $this->twigRender('User/index.html.twig', ['users' => $users]);
    }

    public function show(int $id): string
    {
        $userManager = new UserManager();
        $user = $userManager->selectOneById($id);
        $this->checkIfUserAsAccessToPage($id);
        return $this->twigRender('User/show.html.twig', ['user' => $user]);
    }

    public function edit(int $id): string
    {
        $userManager = new UserManager();
        $user = $userManager->selectOneById($id);
        $this->checkIfUserAsAccessToPage($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = array_map('trim', $_POST);
            $userManager->update($user);
            $extensions = ['jpg', 'png', 'jpeg', 'gif'];
            $maxSize = 400000;
            $tmpName = $_FILES['profile_image']['tmp_name'];
            $name = $_FILES['profile_image']['name'];
            $size = $_FILES['profile_image']['size'];
            $error = $_FILES['profile_image']['error'];

            $tabExtension = explode('.', $name);
            $extension = strtolower(end($tabExtension));

            if (in_array($extension, $extensions) && $size <= $maxSize && $error == 0) {
                $uniqueName = uniqid('', true);
                $file = $uniqueName . "." . $extension;
                move_uploaded_file($tmpName, 'assets/images/' . $file);
                $user = $userManager->saveNewImage($file);
            } else {
                echo "You can't upload this file";
            }

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
            session_destroy();
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
                header('Location:/user/show?id=' . $_SESSION['id']);
            }
            $errors[]  = "Email ou mot de passe invalide !";
        }

        return $this->twigRender('User/login.html.twig', ['errors' => $errors]);
    }

    public function logout()
    {
        session_destroy();
        header("Location:/");
    }

    public function checkAuthentification()
    {
        if (empty($_SESSION)) {
            header('Location: /user/login');
        }
    }

    public function checkIfRoleIsAdminOrUser()
    {
        if (!isset($_SESSION['role'])) {
            header('Location:/');
        } elseif (isset($_SESSION['role'])) {
            if ($_SESSION['role'] === 'utilisateur') {
                header('Location:/');
            }
        }
    }

    public function checkIfUserAsAccessToPage($id)
    {
        if (!isset($_SESSION) || $_SESSION['id'] != $id) {
            header('Location: /');
        }
    }

    public function hasSameUserId($id): bool
    {
        if (isset($_SESSION['id'])) {
            return $_SESSION['id'] === $id;
        }
        return false;
    }
}
