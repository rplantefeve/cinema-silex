<?php

namespace Semeformation\Mvc\Cinema_crud\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Semeformation\Mvc\Cinema_crud\Controllers\Controller;
use Semeformation\Mvc\Cinema_crud\DAO\FilmDAO;
use Semeformation\Mvc\Cinema_crud\Views\View;
use Psr\Log\LoggerInterface;
use Silex\Application;

/**
 * Description of MovieController
 *
 * @author User
 */
class MovieController extends Controller{

    private $filmDAO;

    public function __construct(LoggerInterface $logger=null) {
        $this->filmDAO = new FilmDAO($logger);
    }

    /**
     * Route Liste des films
     */
    function moviesList() {
        $isUserAdmin = false;

        session_start();
        // si l'utilisateur est pas connecté et qu'il est amdinistrateur
        if (array_key_exists("user", $_SESSION) and $_SESSION['user'] == 'admin@adm.adm') {
            $isUserAdmin = true;
        }
        // on récupère la liste des films ainsi que leurs informations
        $films = $this->filmDAO->getMoviesList();

        // On génère la vue films
        $vue = new View("MoviesList");
        // En passant les variables nécessaires à son bon affichage
        return $vue->generer($request,[
            'films'       => $films,
            'isUserAdmin' => $isUserAdmin]);
    }

    /**
     * Route Supprimer un film
     */
    public function deleteMovie(Request $request = null, Application $app = null) {
        session_start();
        // si l'utilisateur n'est pas connecté ou sinon s'il n'est pas amdinistrateur
        if (!array_key_exists("user", $_SESSION) or $_SESSION['user'] !== 'admin@adm.adm') {
            // renvoi à la page d'accueil
            return $app->redirect($request->getBasePath() . '/home');
        }

        // si la méthode de formulaire est la méthode POST
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "POST") {

            // on "assainit" les entrées
                $entries = $this->extractArrayFromPostRequest($request, ['filmID']);
            // suppression de la préférence de film
            $this->filmDAO->deleteMovie($entries['filmID']);
        }
        // redirection vers la liste des films
       return $app->redirect($request->getBasePath() . '/movie/list');
    }

    /**
     * Route Ajouter / Modifier un film
     */
    function editMovie(Request $request = null, Application $app = null) {
        session_start();
        // si l'utilisateur n'est pas connecté ou sinon s'il n'est pas amdinistrateur
        if (!array_key_exists("user", $_SESSION) or $_SESSION['user'] !== 'admin@adm.adm') {
            // renvoi à la page d'accueil
           return $app->redirect($request->getBasePath() . '/home');
        }

        // variable qui sert à conditionner l'affichage du formulaire
        $isItACreation = false;

        // si la méthode de formulaire est la méthode POST
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "POST") {

            // on "assainit" les entrées
            $entries = $this->extractArrayFromPostRequest($request, ['backToList','filmID','titre','titreOriginal','modificationInProgress']);

            // si l'action demandée est retour en arrière
            if ($entries['backToList'] !== null) {
                // on redirige vers la page des films
                return $app->redirect($request->getBasePath() . '/movie/list');
            }
            // sinon (l'action demandée est la sauvegarde d'un film)
            else {

                // et que nous ne sommes pas en train de modifier un film
                if ($entries['modificationInProgress'] == null) {
                    // on ajoute le film
                    $this->filmDAO->insertNewMovie($entries['titre'],
                            $entries['titreOriginal']);


                }
                // sinon, nous sommes dans le cas d'une modification
                else {
                    // mise à jour du film
                    $this->filmDAO->updateMovie($entries['filmID'],
                            $entries['titre'], $entries['titreOriginal']);
                }
                // on revient à la liste des films
                return $app->redirect($request->getBasePath() . '/movie/list');
            }

        }// si la page est chargée avec $_GET
        elseif (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "GET") {

 // on "assainit" les entrées
            $entries = $this->extractArrayFromGetRequest($request, ['filmID']);
             if ($entries && $entries['filmID'] !== null && $entries['filmID'] !=='') {
                // on récupère les informations manquantes
                $film = $this->filmDAO->getMovieByID($entries['filmID']);
            }
            // sinon, c'est une création
            else {
                $isItACreation = true;
                $film          = null;
            }
        }

        // On génère la vue films
        $vue = new View("EditMovie");
        // En passant les variables nécessaires à son bon affichage
        return $vue->generer($request,[
            'film'          => $film,
            'isItACreation' => $isItACreation]);
    }

}
