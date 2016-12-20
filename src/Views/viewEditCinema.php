<?php $this->titre = "Ajouter / Modifier un cinéma"; ?>
<h1>Ajouter/Modifier un cinéma</h1>
<?php
    if ($cinema) : ?>
<form method="POST" name="editCinema" action="<?= $request->getBasePath() . '/cinema/edit/'.$cinema->getCinemaId(); ?>">
    <?php else : ?>
<form method="POST" name="editCinema" action="<?= $request->getBasePath() . '/cinema/add'; ?>">
    <?php endif; ?>
    
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
<form method="get" action="<?= $request->getBasePath() . '/cinema/list'?>">
    
    <input type="submit" name="backToList" value="Retour à la liste"/>
</form>