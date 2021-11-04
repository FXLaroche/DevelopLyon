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

        // clean $_POST data
        $searchInput = array_map('trim', $_POST);

        // TODO validations (length, format...)
        if (empty($searchInput)) {
            $error = "veuillez saisir un critère de recherche";
            return $this->twig->render('Post/search.html.twig', ['error' => $error]);
        } else {
            // if validation is ok, insert and redirection
            $postManager = new PostManager();
            $results = $postManager->search($searchInput['maRecherche']);

            return $this->twig->render('Post/search.html.twig', ['results' => $results]);
        }
    }
}
