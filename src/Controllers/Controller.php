<?php

namespace Semeformation\Mvc\Cinema_crud\Controllers;

use Symfony\Component\HttpFoundation\Request;

class Controller {

    /**
     * Se charge d'extraire les données POST de la requête sous forme de tableau trop bon!
     * @param Request $request Requête HTTP POST
     * @param array $variables Tableau de noms de variables à récupérer
     * @return array Tableau de variables extraites de la requête POST
     */
    protected function extractArrayFromPostRequest(Request $request, array $variables): array {
        $entries = array();
// boucle de parcours des variables à extraire
        foreach ($variables as $variable) {
            $entries[$variable] = $request->request->get($variable);
        }
        return $entries;
    }

    /**
     * Se charge d'extraire les données GET de la requête sous forme de tableau
     * @param Request $request Requête HTTP GET
     * @param array $variables Tableau de noms de variables à récupérer
     * @return array Tableau de variables extraites de la requête POST
     */
    protected function extractArrayFromGetRequest(Request $request, array $variables): array {
        $entries = array();
// boucle de parcours des variables à extraire
        foreach ($variables as $variable) {
            $entries[$variable] = $request->query->get($variable);
        }
        return $entries;
    }
}
