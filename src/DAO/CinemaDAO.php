<?php

namespace Semeformation\Mvc\Cinema_crud\DAO;

use Semeformation\Mvc\Cinema_crud\Includes\DAO;
use Semeformation\Mvc\Cinema_crud\Models\Cinema;

/**
 * Description of CinemaDAO
 *
 * @author User
 */
class CinemaDAO extends DAO {

    /**
     * Crée un cinéma à partir d'une ligne de la BDD.
     *
     * @param array $row La ligne de résultat de la BDD.
     * @return Cinema
     */
    protected function buildBusinessObject($row) {
        $cinema = new Cinema();
        $cinema->setCinemaId($row['CINEMAID']);
        $cinema->setDenomination($row['DENOMINATION']);
        $cinema->setAdresse($row['ADRESSE']);
        return $cinema;
    }

    /**
     * Renvoie un objet Cinema
     * @param integer $cinemaID
     * @return Cinema
     */
    public function getCinemaByID($cinemaID) {
        $requete  = "SELECT * FROM cinema WHERE cinemaID = "
                . $cinemaID;
        $resultat = $this->extraire1xN($requete);
        // on crée l'objet métier Cinema
        $cinema   = $this->buildBusinessObject($resultat);
        // on retourne le résultat extrait
        return $cinema;
    }

    /**
     * Renvoie la liste des cinéma d'un film
     * @param integer $filmID
     * @return array
     */
    public function getMovieCinemasByMovieID($filmID) {
        // requête qui nous permet de récupérer la liste des cinémas pour un film donné
        $requete   = "SELECT DISTINCT c.* FROM cinema c"
                . " INNER JOIN seance s ON c.cinemaID = s.cinemaID"
                . " AND s.filmID = " . $filmID;
        // on extrait les résultats
        $resultats = $this->extraireNxN($requete);
        // on extrait les objets métiers des résultats
        return $this->extractObjects($resultats);
    }

    /**
     * Renvoie la liste des cinémas
     * @return array
     */
    public function getCinemasList() {
        $requete   = "SELECT * FROM cinema";
        // on extrait les résultats
        $resultats = $this->extraireNxN($requete);
        // on extrait les objets métiers des résultats
        return $this->extractObjects($resultats);
    }

    /**
     * Renvoie une liste de cinémas qui ne projettent pas le film donné
     * @param integer $filmID
     * @return array
     */
    public function getNonPlannedCinemas($filmID) {
        // requête de récupération des titres et des identifiants des films
        // qui n'ont pas encore été programmés dans ce cinéma
        $requete   = "SELECT c.cinemaID, c.denomination, c.adresse "
                . "FROM cinema c"
                . " WHERE c.cinemaID NOT IN ("
                . "SELECT cinemaID"
                . " FROM seance"
                . " WHERE filmID = :id"
                . ")";
        // extraction des résultats
        $resultats = $this->extraireNxN($requete, ['id' => $filmID], false);
        // retour du résultat
        return $this->extractObjects($resultats);
    }

    /**
     * Insère un nouveau cinéma
     * @param string $denomination
     * @param string $adresse
     */
    public function insertNewCinema($denomination, $adresse) {
        // construction
        $requete = "INSERT INTO cinema (denomination, adresse) VALUES ("
                . ":denomination"
                . ", :adresse)";
        // exécution
        $this->executeQuery($requete,
                [
            'denomination' => $denomination,
            'adresse'      => $adresse]);
        // log
        if ($this->logger) {
            $this->logger->info('Cinema ' . $denomination . ' successfully added.');
        }
    }

    /**
     * Met à jour un cinéma
     * @param integer $cinemaID
     * @param string $denomination
     * @param string $adresse
     */
    public function updateCinema($cinemaID, $denomination, $adresse) {
        // on construit la requête d'insertion
        $requete = "UPDATE cinema SET "
                . "denomination = "
                . "'" . $denomination . "'"
                . ", adresse = "
                . "'" . $adresse . "'"
                . " WHERE cinemaID = "
                . $cinemaID;
        // exécution de la requête
        $this->executeQuery($requete);
    }

    /**
     * Supprime un cinéma
     * @param integer $cinemaID
     */
    public function deleteCinema($cinemaID) {
        $this->executeQuery("DELETE FROM cinema WHERE cinemaID = "
                . $cinemaID);

        if ($this->logger) {
            $this->logger->info('Cinema ' . $cinemaID . ' successfully deleted.');
        }
    }

}
