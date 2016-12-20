<?php $this->titre = "Ajouter / Modifier un cinéma"; ?>
<h1>Ajouter/Modifier un cinéma</h1>
<form method="POST" name="editCinema" action="<?= $request->getBasePath() . 'cinema/edit/'?>">
    <label>Dénomination :</label>
    <input name="denomination" type="text" value="<?php
    if ($cinema) {
        echo $cinema->getDenomination();
    }
    ?>" required/>
    <label>Adresse :</label>
    <textarea name="adresse" required><?php
        if ($cinema) {
            echo $cinema->getAdresse();
        }
        ?></textarea>
    <br/>
    <input type="hidden" value="<?php
    if ($cinema) {
        echo $cinema->getCinemaId();
    }
    ?>" name="cinemaID"/>
           <?php
           // si c'est une modification, c'est une information dont nous avons besoin
           if (!$isItACreation) {
               ?>
        <input type="hidden" name="modificationInProgress" value="true"/>
        <input type="hidden" name="cinemaID" value="<?= $cinema->getCinemaId();?>"/>
        
        <?php
    }
    ?>
    <input type="submit" name="saveEntry" value="Sauvegarder"/>
</form>
<form method="get" action="<?= $request->getBasePath() . '/home'?>">
    <input type="hidden" name="action" value="cinema/list"/>
    <input type="submit" name="backToList" value="Retour à la liste"/>
</form>