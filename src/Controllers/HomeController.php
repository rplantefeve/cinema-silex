<?php

namespace Semeformation\Mvc\Cinema_crud\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Semeformation\Mvc\Cinema_crud\Controllers\Controller;
use Semeformation\Mvc\Cinema_crud\DAO\UtilisateurDAO;
use Semeformation\Mvc\Cinema_crud\Views\View;
use Psr\Log\LoggerInterface;
use Exception;

/**
 * Description of HomeController
 *
 * @author User
 */
class HomeController extends Controller {

    /**
     * L'utilisateur de l'application
     */
    private $utilisateurDAO;

    /**
     * Constructeur de la classe
     */
    public function __construct(LoggerInterface $logger = null) {
        $this->utilisateurDAO = new UtilisateurDAO($logger);
    }

    /*
     * Route Accueil
     */

    public function home(Request $request = null, Application $app = null) {
        session_start();
        // personne d'authentifié à ce niveau
        $loginSuccess = false;

        // variables de contrôle du formulaire
        $areCredentialsOK = true;

        // si l'utilisateur est déjà authentifié
        if (array_key_exists("user", $_SESSION)) {
            $loginSuccess = true;
            // Sinon (pas d'utilisateur authentifié pour l'instant)
        } else {
            // si la méthode POST a été employée
            if ($request->isMethod('POST')) {
                // on "sainifie" les entrées
                $entries = $this->extractArrayFromPostRequest($request, ['email', 'password']);                
                $this->login($request,$app,$entries, $areCredentialsOK);
            }
        }
        // On génère la vue Accueil
        $vue = new View("Home");
        // En passant les variables nécessaires à son bon affichage
        return $vue->generer($request, [
                    'areCredentialsOK' => $areCredentialsOK,
                    'loginSuccess' => $loginSuccess]);
    }

    private function login(Request $request = null, Application $app = null,$entries, &$areCredentialsOK) {        
        try { 
            
            // On vérifie l'existence de l'utilisateur
            $this->utilisateurDAO->verifyUserCredentials($entries['email'], $entries['password']);

            // on enregistre l'utilisateur
            $_SESSION['user'] = $entries['email'];
            $_SESSION['userID'] = $this->utilisateurDAO->getUserIDByEmailAddress($entries['email']);
            // on redirige vers la page d'édition des films préférés
            // redirection vers la liste des préférences de films
            //header("Location: index.php?action=editFavoriteMoviesList");
          return $app->redirect($request->getBasePath() . '/favorite/list');
        } catch (Exception $ex) {
            $areCredentialsOK = false;
            $this->utilisateurDAO->getLogger()->error($ex->getMessage());
        }
    }

    public function createNewUser(Request $request = null, Application $app = null) {
        // variables de contrôles du formulaire de création
        $isFirstNameEmpty = false;
        $isLastNameEmpty = false;
        $isEmailAddressEmpty = false;
        $isUserUnique = true;
        $isPasswordEmpty = false;
        $isPasswordConfirmationEmpty = false;
        $isPasswordValid = true;

        // si la méthode POST est utilisée, cela signifie que le formulaire a été envoyé
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "POST") {
            // si la méthode POST a été employée
           
                $entries = $this->extractArrayFromPostRequest($request, ['firstName',
                'lastName',
                'email',
                'password',
                'passwordConfirmation']);

                
//            // on "sainifie" les entrées
//            $sanitizedEntries = filter_input_array(INPUT_POST, ['firstName' => FILTER_SANITIZE_STRING,
//                'lastName' => FILTER_SANITIZE_STRING,
//                'email' => FILTER_SANITIZE_EMAIL,
//                'password' => FILTER_DEFAULT,
//                'passwordConfirmation' => FILTER_DEFAULT]);

            // si le prénom n'a pas été renseigné
            if ($entries['firstName'] === "") {
                $isFirstNameEmpty = true;
            }

            // si le nom n'a pas été renseigné
            if ($entries['lastName'] === "") {
                $isLastNameEmpty = true;
            }

            // si l'adresse email n'a pas été renseignée
            if ($entries['email'] === "") {
                $isEmailAddressEmpty = true;
            } else {
                // On vérifie l'existence de l'utilisateur
                $userID = $this->utilisateurDAO->getUserIDByEmailAddress($entries['email']);
                // si on a un résultat, cela signifie que cette adresse email existe déjà
                if ($userID) {
                    $isUserUnique = false;
                }
            }
            // si le password n'a pas été renseigné
            if ($entries['password'] === "") {
                $isPasswordEmpty = true;
            }
            // si la confirmation du password n'a pas été renseigné
            if ($entries['passwordConfirmation'] === "") {
                $isPasswordConfirmationEmpty = true;
            }

            // si le mot de passe et sa confirmation sont différents
            if ($entries['password'] !== $entries['passwordConfirmation']) {
                $isPasswordValid = false;
            }

            // si les champs nécessaires ne sont pas vides, que l'utilisateur est unique et que le mot de passe est valide
            if (!$isFirstNameEmpty && !$isLastNameEmpty && !$isEmailAddressEmpty && $isUserUnique && !$isPasswordEmpty && $isPasswordValid) {
                // hash du mot de passe
                $password = password_hash($sanitizedEntries['password'], PASSWORD_DEFAULT);
                // créer l'utilisateur
                $this->utilisateurDAO->createUser($sanitizedEntries['firstName'], $sanitizedEntries['lastName'], $sanitizedEntries['email'], $password);

                session_start();
                // authentifier l'utilisateur
                $_SESSION['user'] = $sanitizedEntries['email'];
                $_SESSION['userID'] = $this->utilisateurDAO->getUserIDByEmailAddress($_SESSION['user']);
                // redirection vers la liste des préférences de films
                header("Location: index.php?action=editFavoriteMoviesList");
                exit;
            }
        }
        // sinon (le formulaire n'a pas été envoyé)
        else {
            // initialisation des variables du formulaire
            $entries['firstName'] = '';
            $entries['lastName'] = '';
            $entries['email'] = '';
        }

        $donnees = [
            'entries' => $entries,
            'isFirstNameEmpty' => $isFirstNameEmpty,
            'isLastNameEmpty' => $isLastNameEmpty,
            'isEmailAddressEmpty' => $isEmailAddressEmpty,
            'isUserUnique' => $isUserUnique,
            'isPasswordEmpty' => $isPasswordEmpty,
            'isPasswordConfirmationEmpty' => $isPasswordConfirmationEmpty,
            'isPasswordValid' => $isPasswordValid];
        // On génère la vue Création d'un utilisateur
        $vue = new View("CreateUser");
        // En passant les variables nécessaires à son bon affichage
        $vue->generer($request, $donnees);
    }

    public function logout() {
        session_start();
        session_destroy();
        return $app->redirect('/home');
    }

    public function error(Request $request = null, Application $app = null,$e) {

        $this->utilisateurDAO->getLogger()->error('Exception : ' . $e->getMessage() . ', File : ' . $e->getFile() . ', Line : ' . $e->getLine() . ', Stack trace : ' . $e->getTraceAsString());
        $vue = new View("Error");
        $vue->generer($request,['messageErreur' => $e->getMessage()]);
    }

}
