<?php

namespace App\Controller;

use App\Model\PostManager;
use App\Model\SearchManager;
use App\Model\ThemeManager;
use App\Controller\UserController;
use App\Model\CategoryManager;
use App\Model\MessageManager;

class PostController extends AbstractController
{

    private PostManager $postManager;
    public function __construct()
    {
        parent::__construct();
        $this->postManager = new PostManager();
    }

    public function show(int $id): string
    {
        $post = $this->postManager->selectOnePostById($id);
        $messageManager = new MessageManager();
        $messages = $messageManager->selectAllMessageForOnePost($id);

        return $this->twigRender('Post/show.html.twig', ['post' => $post, 'messages' => $messages]);
    }

    public function themeIsOk($themeId): bool
    {
        if (!isset($themeId)) {
            return false;
        }

        $themeManager = new ThemeManager();
        $themeList = $themeManager->selectAll();

        foreach ($themeList as $theme) {
            if ($theme['id'] === $themeId) {
                return true;
            }
        }
        return false;
    }

    /**
     * Adds a new post
     */
    public function add(): string
    {
        $userController = new UserController();
        $userController->checkAuthentification();

        $error = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPost = array_map('trim', $_POST);
            $newPost['user_id'] = $_SESSION['id'];

            if (strlen($newPost['subject']) > 255) {
                $error[] = "Le titre est trop long!";
            }
            if (empty($newPost['subject'])) {
                $error[] = "Le titre est obligatoire";
            }

            if (empty($newPost['message'])) {
                $error[] = "Vous devez rentrer un message.";
            }

            if (strlen($newPost['keyword']) > 255) {
                $error[] = "Vous avez trop de mots-clés!";
            }

            if (empty($error)) {
                $postId = $this->postManager->create($newPost);
                header('Location:/post/show?id=' . $postId);
            }
        }

        $categoryManager = new CategoryManager();
        $themeManager = new ThemeManager();

        $categories = $categoryManager->selectAll();
        $themes = $themeManager->selectAll();

        return $this->twigRender('Post/add.html.twig', [
            'errors' => $error,
            'themes' => $themes,
            'categories' => $categories,
        ]);
    }

    public function edit($id): string
    {
        $userController = new UserController();
        $userController->checkAuthentification();
        $postData = $this->postManager->selectOneById($id);

        if (!$userController->hasSameUserId($postData['user_id']) && $_SESSION['role'] !== 'admin') {
            header('Location:/post/show?id=' . $id);
        }

        $error = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $editedPost = array_map('trim', $_POST);

            if (strlen($editedPost['subject']) > 255) {
                $error[] = "Le titre est trop long.";
            }
            if (empty($editedPost['subject'])) {
                $error[] = "Le titre est obligatoire.";
            }

            if (empty($editedPost['message'])) {
                $error[] = "Vous devez rentrer un message.";
            }

            if (strlen($editedPost['keyword']) > 255) {
                $error[] = "Vous avez trop de mots-clés.";
            }

            if (empty($error)) {
                $editedPost['id'] = $postData['id'];
                $this->postManager->edit($editedPost);
                header('Location:/post/show?id=' . $id);
            }
        }

        $categoryManager = new CategoryManager();
        $themeManager = new ThemeManager();

        $categories = $categoryManager->selectAll();
        $themes = $themeManager->selectAll();

        return $this->twigRender('Post/add.html.twig', [
            'postContent' => $postData,
            'errors' => $error,
            'themes' => $themes,
            'categories' => $categories,
        ]);
    }

    public function delete($id)
    {
        $userController = new UserController();
        $userController->checkAuthentification();
        $postData = $this->postManager->selectOneById($id);
        if (!$userController->hasSameUserId($postData['user_id']) && $_SESSION['role'] !== 'admin') {
            header('Location:/post/show?id=' . $id);
        } else {
            $themeId = $postData['theme_id'];
            $this->postManager->delete($id);
            header('Location:/posts/index?theme=' . $themeId);
        }
    }

    /**
     * List result search
     */
    public function search(): string
    {

        // clean $_POST data
        $searchInput = array_map('trim', $_POST);

        // TODO validations (length, format...)
        if (empty($searchInput)) {
            $error = "veuillez saisir un critère de recherche";
            return $this->twigRender('Post/search.html.twig', ['error' => $error]);
        } else {
            // if validation is ok, insert and redirection
            $postManager = new PostManager();
            $results = $postManager->search($searchInput['maRecherche']);
            // traitement of search
            $searchManager = new SearchManager();
            $searchs = $searchManager->selectAll();
            // rechercher si le mot cle est dans la table search
            $key = false;
            foreach ($searchs as $searchtab) {
                if (array_search($searchInput['maRecherche'], $searchtab)) {
                    $key = true;
                    // si oui alors mise à jour des données date et nb_searched
                    $updateSearch = [];
                    $updateSearch['id'] = $searchtab['id'];
                    $updateSearch['date_last'] = date("Y-m-d H:i:s");
                    $updateSearch['nb_searched'] = $searchtab['nb_searched'] + 1;
                    $id = $searchManager->update($updateSearch);
                    if (!$id) {
                        $error = "Problème technique lors de l'update search";
                    }
                    break;
                }
            }
            if (!$key) {
                // si non alors création d'un nouvel enregistrement dans la table search
                $newSearch = [];
                $newSearch['word'] = $searchInput['maRecherche'];
                $newSearch['date_last'] = date("Y-m-d H:i:s");
                $newSearch['nb_searched'] = 1;
                $id = $searchManager->insert($newSearch);
                if (!$id) {
                    $error = "Problème technique lors de l'insert search";
                }
            }

            return $this->twigRender('Post/search.html.twig', ['results' => $results]);
        }
    }

    /**
     * Lists the posts of a theme
     */
    public function index(int $idCategory): string
    {
        $postManager = new PostManager();
        $posts = $postManager->selectAllById($idCategory);

        return $this->twigRender('Post/index.html.twig', ['posts' => $posts]);
    }
}
