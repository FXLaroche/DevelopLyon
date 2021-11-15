<?php

namespace App\Controller;

use App\Model\PostManager;
use App\Model\SearchManager;

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
        $post = $this->postManager->selectOneById($id);

        return $this->twig->render('Post/show.html.twig', ['post' => $post]);
    }

    public function add($themeId): string
    {
        $error = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPost = array_map('trim', $_POST);

            if (strlen($newPost['subject']) > 255) {
                $error[] = "I'm afraid your title is too long!";
            }
            if (empty($newPost['subject'])) {
                $error[] = "You forgot to give your post a title.";
            }

            if (empty($newPost['message'])) {
                $error[] = "Please put a message, it's better.";
            }

            if (strlen($newPost['keyword']) > 255) {
                $error[] = "Alas! you have too many keywords!";
            }

            if ($newPost['user_id'] != $_SESSION['id']) {
                $error[] = 'Who do you think you are?!';
            }

            if (empty($error)) {
                $postId = $this->postManager->create($newPost, $themeId);
                header('Location:/post/show?id=' . $postId);
            }
        }
        return $this->twigRender('Post/add.html.twig', ['errors' => $error]);
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
    public function index(int $idCategory): string
    {
        $postManager = new PostManager();
        $posts = $postManager->selectAllById($idCategory);

        return $this->twigRender('Post/index.html.twig', ['posts' => $posts]);
    }
}
