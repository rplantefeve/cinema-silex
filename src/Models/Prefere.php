<?php

namespace Semeformation\Mvc\Cinema_crud\Models;

use Semeformation\Mvc\Cinema_crud\Models\Film;
use Semeformation\Mvc\Cinema_crud\Models\Utilisateur;

/**
 * Description of Prefere
 *
 * @author User
 */
class Prefere {

    private $utilisateur;
    private $film;
    private $commentaire;

    public function getUtilisateur() {
        return $this->utilisateur;
    }

    public function getFilm() {
        return $this->film;
    }

    public function setUtilisateur(Utilisateur $utilisateur) {
        $this->utilisateur = $utilisateur;
    }

    public function setFilm(Film $film) {
        $this->film = $film;
    }

    public function getCommentaire() {
        return $this->commentaire;
    }

    public function setCommentaire($commentaire) {
        $this->commentaire = $commentaire;
    }

}
