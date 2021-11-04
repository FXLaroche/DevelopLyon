<?php

namespace App\Controller;

use App\Model\PostManager;
use App\Model\SearchManager;

use function Amp\Internal\getCurrentTime;

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
                    $search = new SearchManager();
                    $id = $search->update($updateSearch);
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
                $search = new SearchManager();
                $id = $search->insert($newSearch);
                if (!$id) {
                    $error = "Problème technique lors de l'insert search";
                }
            }

            return $this->twigRender('Post/search.html.twig', ['results' => $results]);
        }
    }
}
