<?php

namespace Semeformation\Mvc\Cinema_crud\Controllers;

use Semeformation\Mvc\Cinema_crud\Controllers\HomeController;
use Semeformation\Mvc\Cinema_crud\Controllers\FavoriteController;
use Semeformation\Mvc\Cinema_crud\Controllers\MovieController;
use Semeformation\Mvc\Cinema_crud\Controllers\ShowtimesController;
use Semeformation\Mvc\Cinema_crud\Controllers\CinemaController;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of Router
 *
 * @author User
 */
class Router {

    private $homeCtrl;
    private $favoriteCtrl;
    private $cinemaCtrl;
    private $movieCtrl;
    private $showtimesCtrl;

    public function __construct(LoggerInterface $logger) {
        $this->homeCtrl      = new HomeController($logger);
        $this->favoriteCtrl  = new FavoriteController($logger);
        $this->movieCtrl     = new MovieController($logger);
        $this->showtimesCtrl = new ShowtimesController($logger);
        $this->cinemaCtrl    = new CinemaController($logger);
    }

    /**
     * Route les requêtes qui arrivent au front controller
     */
    public function routeRequest() {
        try {
            // on "sainifie" les entrées
            $sanitizedEntries = filter_input_array(INPUT_GET,
                    ['action' => FILTER_SANITIZE_STRING]);
            if ($sanitizedEntries && $sanitizedEntries['action'] !== '') {
                // si l'action demandée est la liste des cinémas
                if ($sanitizedEntries['action'] == "cinemasList") {
                    // Activation de la route cinemasList
                    $this->cinemaCtrl->cinemasList();
                }
                // si l'action demandée est la modification / Ajout d'un cinéma
                elseif ($sanitizedEntries['action'] == "editCinema") {
                    // Activation de la route editCinema
                    $this->cinemaCtrl->editCinema();
                } // si l'action demandée est la Suppression d'un cinéma
                elseif ($sanitizedEntries['action'] == "deleteCinema") {
                    // Activation de la route deleteCinema
                    $this->cinemaCtrl->deleteCinema();
                } elseif ($sanitizedEntries['action'] == "moviesList") {
                    // Activation de la route moviesList
                    $this->movieCtrl->moviesList();
                } elseif ($sanitizedEntries['action'] == "editMovie") {
                    // Activation de la route editMovie
                    $this->movieCtrl->editMovie();
                } elseif ($sanitizedEntries['action'] == "deleteMovie") {
                    // Activation de la route deleteMovie
                    $this->movieCtrl->deleteMovie();
                } elseif ($sanitizedEntries['action'] == "movieShowtimes") {
                    // Activation de la route movieShowtimes
                    $this->showtimesCtrl->movieShowtimes();
                } elseif ($sanitizedEntries['action'] == "cinemaShowtimes") {
                    // Activation de la route cinemaShowtimes
                    $this->showtimesCtrl->cinemaShowtimes();
//                } elseif ($sanitizedEntries['action'] == "editShowtime") {
//                    // Activation de la route editShowtime
//                    $this->showtimesCtrl->editShowtime();
                } elseif ($sanitizedEntries['action'] == "deleteShowtime") {
                    // Activation de la route deleteShowtime
                    $this->showtimesCtrl->deleteShowtime();
                } elseif ($sanitizedEntries['action'] == "editFavoriteMoviesList") {
                    // Activation de la route editFavoriteMoviesList
                    $this->favoriteCtrl->editFavoriteMoviesList();
                } elseif ($sanitizedEntries['action'] == "editFavoriteMovie") {
                    // Activation de la route editFavoriteMovie
                    $this->favoriteCtrl->editFavoriteMovie();
                } elseif ($sanitizedEntries['action'] == "deleteFavoriteMovie") {
                    // Activation de la route deleteFavoriteMovie
                    $this->favoriteCtrl->deleteFavoriteMovie();
                } elseif ($sanitizedEntries['action'] == "createNewUser") {
                    // Activation de la route createNewUser
                    $this->homeCtrl->createNewUser();
                } elseif ($sanitizedEntries['action'] == "logout") {
                    // Activation de la route logout
                    $this->homeCtrl->logout();
                     } elseif ($sanitizedEntries['action'] == "login") {
                    // Activation de la route createNewUser
                    HomeController($logger)::login();
                } else {
                    // Activation de la route par défaut (page d'accueil)
                    $this->homeCtrl->home();
                }
            } else {
                // Activation de la route par défaut (page d'accueil)
                $this->homeCtrl->home();
            }
        } catch (Exception $e) {
            $this->homeCtrl->error($e);
        }
    }

}
