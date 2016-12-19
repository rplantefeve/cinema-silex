<?php

namespace Semeformation\Mvc\Cinema_crud\Controllers;

use Semeformation\Mvc\Cinema_crud\DAO\FilmDAO;
use Semeformation\Mvc\Cinema_crud\DAO\CinemaDAO;
use Semeformation\Mvc\Cinema_crud\DAO\SeanceDAO;
use Semeformation\Mvc\Cinema_crud\Views\View;
use Psr\Log\LoggerInterface;
use DateTime;

/**
 * Description of ShowtimesController
 *
 * @author User
 */
class ShowtimesController {

    private $seanceDAO;

    public function __construct(LoggerInterface $logger=null) {
        $this->seanceDAO = new SeanceDAO($logger);
        $this->seanceDAO->setCinemaDAO(new CinemaDAO($logger));
        $this->seanceDAO->setFilmDAO(new FilmDAO($logger));
    }

    /**
     * Route liste des séances d'un film
     */
    public function movieShowtimes() {
        $adminConnected = false;

        session_start();
        // si l'utilisateur admin est connexté
        if (array_key_exists("user", $_SESSION) and $_SESSION['user'] == 'admin@adm.adm') {
            $adminConnected = true;
        }

        // on assainit les entrées
        $sanitizedEntries = filter_input_array(INPUT_GET, ['filmID' => FILTER_SANITIZE_NUMBER_INT]);
        // si l'identifiant du film a bien été passé en GET'
        if ($sanitizedEntries && !is_null($sanitizedEntries['filmID']) && $sanitizedEntries['filmID'] !== '') {
            // on récupère l'identifiant du cinéma
            $filmID = $sanitizedEntries['filmID'];
            // puis on récupère les informations du film en question
            $film = $this->seanceDAO->getFilmDAO()->getMovieByID($filmID);

            // on récupère les cinémas qui ne projettent pas encore le film
            $cinemasUnplanned = $this->seanceDAO->getCinemaDAO()->getNonPlannedCinemas($filmID);
        }
        // sinon, on retourne à l'accueil
        else {
            return $app->redirect($request->getBasePath() . '/home');
            
        }

        // on récupère la liste des cinémas de ce film
        $cinemas = $this->seanceDAO->getCinemaDAO()->getMovieCinemasByMovieID($filmID);
        $seances = $this->seanceDAO->getAllCinemasShowtimesByMovieID($cinemas, $filmID);

        // On génère la vue séances du film
        $vue = new View("MovieShowtimes");
        // En passant les variables nécessaires à son bon affichage
        $vue->generer([
            'cinemas' => $cinemas,
            'film' => $film,
            'seances' => $seances,
            'cinemasUnplanned' => $cinemasUnplanned,
            'adminConnected' => $adminConnected]);
    }

    /**
     * Route liste des séances d'un cinéma
     */
    public function cinemaShowtimes() {
        $adminConnected = false;

        session_start();
        // si l'utilisateur admin est connexté
        if (array_key_exists("user", $_SESSION) and $_SESSION['user'] == 'admin@adm.adm') {
            $adminConnected = true;
        }

        // on assainit les entrées
        $sanitizedEntries = filter_input_array(INPUT_GET, ['cinemaID' => FILTER_SANITIZE_NUMBER_INT]);

        // si l'identifiant du cinéma a bien été passé en GET
        if ($sanitizedEntries && !is_null($sanitizedEntries) && $sanitizedEntries['cinemaID'] != '') {
            // on récupère l'identifiant du cinéma
            $cinemaID = $sanitizedEntries['cinemaID'];
            // puis on récupère les informations du cinéma en question
            $cinema = $this->seanceDAO->getCinemaDAO()->getCinemaByID($cinemaID);

            // on récupère les films pas encore projetés
            $filmsUnplanned = $this->seanceDAO->getFilmDAO()->getNonPlannedMovies($cinemaID);
        }
        // sinon, on retourne à l'accueil
        else {
            return $app->redirect($request->getBasePath() . '/home');
            
        }

        // on récupère la liste des films de ce cinéma
        $films = $this->seanceDAO->getFilmDAO()->getCinemaMoviesByCinemaID($cinemaID);
        // on récupère toutes les séances de films pour un cinéma donné
        $seances = $this->seanceDAO->getAllMoviesShowtimesByCinemaID($films, $cinemaID);

        // On génère la vue séances du cinéma
        $vue = new View("CinemaShowtimes");
        // En passant les variables nécessaires à son bon affichage
        $vue->generer([
            'cinema' => $cinema,
            'films' => $films,
            'seances' => $seances,
            'filmsUnplanned' => $filmsUnplanned,
            'adminConnected' => $adminConnected]);
    }

    /**
     * Route pour supprimer une séance
     */
    public function deleteShowtime() {
        session_start();
        // si l'utilisateur n'est pas connecté
        if (!array_key_exists("user", $_SESSION)) {
            // renvoi à la page d'accueil
            return $app->redirect($request->getBasePath() . '/home');
            
        }

        // si la méthode de formulaire est la méthode POST
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "POST") {

            // on assainie les variables
            $sanitizedEntries = filter_input_array(INPUT_POST, ['cinemaID' => FILTER_SANITIZE_NUMBER_INT,
                'filmID' => FILTER_SANITIZE_NUMBER_INT,
                'heureDebut' => FILTER_SANITIZE_STRING,
                'heureFin' => FILTER_SANITIZE_STRING,
                'version' => FILTER_SANITIZE_STRING,
                'from' => FILTER_SANITIZE_STRING
            ]);

            // suppression de la séance
            $this->seanceDAO->deleteShowtime($sanitizedEntries['cinemaID'], $sanitizedEntries['filmID'], $sanitizedEntries['heureDebut'], $sanitizedEntries['heureFin']
            );
            // en fonction d'où je viens, je redirige
            if (strstr($sanitizedEntries['from'], 'movie')) {
                header('Location: index.php?action=movieShowtimes&filmID=' . $sanitizedEntries['filmID']);
                exit;
            } else {
                header('Location: index.php?action=cinemaShowtimes&cinemaID=' . $sanitizedEntries['cinemaID']);
                exit;
            }
        } else {
            // renvoi à la page d'accueil
            return $app->redirect($request->getBasePath() . '/home');
            
        }
    }

    /**
     * Route pour créer/modifier une séance
     * @param type $filmId Récupéré depuis la route {filmId}
     * @param type $cinemaId
     * @param \Semeformation\Mvc\Cinema_crud\Controllers\Request $request
     * @param \Semeformation\Mvc\Cinema_crud\Controllers\Application $app
     * @return type
     */
    public function editShowtime($filmId, $cinemaId, Request $request = null, Application $app = null) {
        session_start();
        // si l'utilisateur n'est pas connecté ou sinon s'il n'est pas amdinistrateur
        if (!array_key_exists("user", $_SESSION) or $_SESSION['user'] !== 'admin@adm.adm') {
            // renvoi à la page d'accueil
            return $app->redirect($request->getBasePath() . '/home');
           // header('Location:index.php');
        }

        // init. des flags. Etat par défaut => je viens du cinéma et je créé
        $fromCinema = true;
        $fromFilm = false;
        $isItACreation = true;

        // init. des variables du formulaire
        $seanceOld = [
            'dateheureDebutOld' => '',
            'dateheureFinOld' => '',
            'heureFinOld' => ''];

        $seance = null;

        // si l'on est en GET
        if ($request->isMethod('GET')) {
            // on assainie les variables
            $sanitizedEntries = filter_input_array(INPUT_GET, [
                'from' => FILTER_SANITIZE_STRING,
                'heureDebut' => FILTER_SANITIZE_STRING,
                'heureFin' => FILTER_SANITIZE_STRING,
                'version' => FILTER_SANITIZE_STRING]);
            // pour l'instant, on vérifie les données en GET
            if ($sanitizedEntries && isset($cinemaId, $filmId, $sanitizedEntries['from'])) {
                // d'où vient on ?
                $from = $sanitizedEntries['from'];

                // puis on récupère les informations du cinéma en question
                $cinema = $this->seanceDAO->getCinemaDAO()->getCinemaByID($cinemaId);

                // puis on récupère les informations du film en question
                $film = $this->seanceDAO->getFilmDAO()->getMovieByID($filmId);

                // s'il on vient des séances du film
                if (strstr($sanitizedEntries['from'], 'movie')) {
                    $fromCinema = false;
                    // on vient du film
                    $fromFilm = true;
                }

                // ici, on veut savoir si on modifie ou si on ajoute
                if (isset($sanitizedEntries['heureDebut'], $sanitizedEntries['heureFin'], $sanitizedEntries['version'])) {
                    // nous sommes dans le cas d'une modification
                    $isItACreation = false;
                    $seance = new \Semeformation\Mvc\Cinema_crud\Models\Seance();
                    // on récupère les anciennes valeurs (utile pour retrouver la séance avant de la modifier
                    $seanceOld['dateheureDebutOld'] = $sanitizedEntries['heureDebut'];
                    $seanceOld['dateheureFinOld'] = $sanitizedEntries['heureFin'];
                    // dates PHP
                    $dateheureDebut = new DateTime($sanitizedEntries['heureDebut']);
                    $dateheureFin = new DateTime($sanitizedEntries['heureFin']);
                    // découpage en heures
                    $seance->setHeureDebut($dateheureDebut);
                    $seance->setHeureFin($dateheureFin);
                    // on récupère la version
                    $seance->setVersion($sanitizedEntries['version']);
                }
            }
            // sinon, on retourne à l'accueil
            else {
                return $app->redirect($request->getBasePath() . '/home');
            }
            // sinon, on est en POST
        } else if ($request->isMethod('POST')) {
            // on assainie les variables
            //$entries = $this->extractArrayFromPostRequest($request, ['email', 'password']);
            $sanitizedEntries = filter_input_array(INPUT_POST, ['datedebut' => FILTER_SANITIZE_STRING,
                'heuredebut' => FILTER_SANITIZE_STRING,
                'datefin' => FILTER_SANITIZE_STRING,
                'heurefin' => FILTER_SANITIZE_STRING,
                'dateheurefinOld' => FILTER_SANITIZE_STRING,
                'dateheuredebutOld' => FILTER_SANITIZE_STRING,
                'version' => FILTER_SANITIZE_STRING,
                'from' => FILTER_SANITIZE_STRING,
                'modificationInProgress' => FILTER_SANITIZE_STRING]);
            // si toutes les valeurs sont renseignées
            if ($sanitizedEntries && isset($cinemaId, $filmId, $sanitizedEntries['datedebut'], $sanitizedEntries['heuredebut'], $sanitizedEntries['datefin'], $sanitizedEntries['heurefin'], $sanitizedEntries['dateheuredebutOld'], $sanitizedEntries['dateheurefinOld'], $sanitizedEntries['version'], $sanitizedEntries['from'])) {
                // nous sommes en Français
                setlocale(LC_TIME, 'fra_fra');
                // date du jour de projection de la séance
                $datetimeDebut = new DateTime($sanitizedEntries['datedebut'] . ' ' . $sanitizedEntries['heuredebut']);
                $datetimeFin = new DateTime($sanitizedEntries['datefin'] . ' ' . $sanitizedEntries['heurefin']);
                // Est-on dans le cas d'une insertion ?
                if (!isset($sanitizedEntries['modificationInProgress'])) {
                    // j'insère dans la base
                    $resultat = $this->seanceDAO->insertNewShowtime($cinemaId, $filmId, 
                            $datetimeDebut->format("Y-m-d H:i"), 
                            $datetimeFin->format("Y-m-d H:i"), 
                            $sanitizedEntries['version']);
                } else {
                    // c'est une mise à jour
                    $resultat = $this->seanceDAO->updateShowtime($cinemaId, $filmId, 
                            $sanitizedEntries['dateheuredebutOld'], 
                            $sanitizedEntries['dateheurefinOld'], 
                            $datetimeDebut->format("Y-m-d H:i"), 
                            $datetimeFin->format("Y-m-d H:i"), 
                            $sanitizedEntries['version']);
                }
                // en fonction d'où je viens, je redirige
                if (strstr($sanitizedEntries['from'], 'movie')) {
                    // header('Location: index.php?action=movieShowtimes&filmID=' . $sanitizedEntries['filmID']);
                    $app->redirect($request->getBasePath() . '/showtime/movie/' . $filmId);
                } else {
                    //header('Location: index.php?action=cinemaShowtimes&cinemaID=' . $sanitizedEntries['cinemaID']);
                    $app->redirect($request->getBasePath() . '/showtime/cinema/' . $cinemaId);                   
                }
            }
        }
        // sinon, on retourne à l'accueil
        else {
            return $app->redirect($request->getBasePath() . '/home');
        }

        // On génère la vue édition d'une séance
        $vue = new View("EditShowtime");
        // En passant les variables nécessaires à son bon affichage
        $vue->generer($request, [
            'cinema' => $cinema,
            'film' => $film,
            'seance' => $seance,
            'seanceOld' => $seanceOld,
            'from' => $from,
            'isItACreation' => $isItACreation,
            'fromCinema' => $fromCinema,
            'fromFilm' => $fromFilm
        ]);
    }

}
