<?php

namespace App\Controller;

use App\Model\PostManager;

class PostController extends AbstractController
{
    /**
     * List result search
     */
    public function search(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $searchInput = array_map('trim', $_POST);

            // TODO validations (length, format...)
            if (empty($searchInput)) {
                $error = "veuillez saisir un critère de recherche";
            } else {

                // if validation is ok, insert and redirection
                $search = "%" . $searchInput['maRecherche'] . "%";
                $postManager = new PostManager();
                $results = $postManager->search($search);

                return $this->twig->render('Post/search.html.twig', ['results' => $results]);
            }
        }
    }
}
