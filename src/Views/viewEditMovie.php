<?php $this->titre = "Ajouter / Modifier un film"; ?>
<h1>Ajouter/Modifier un film</h1>
<?php
    if ($film) : ?>
<form method="POST" name="editCinema" action="<?= $request->getBasePath() . '/movie/edit/'.$film->getFilmId(); ?>">
    <?php else : ?>
<form method="POST" name="editCinema" action="<?= $request->getBasePath() . '/movie/add'; ?>">
    <?php endif; ?>
    <label>Titre :</label>
    <input name="titre" type="text" value="<?php
    if ($film): echo $film->getTitre();
    endif;
    ?>" required/>
    <label>Titre original :</label>
    <input name="titreOriginal" type="text" value="<?php
    if ($film): echo $film->getTitreOriginal();
    endif;
    ?>" />
    <br/>
    <input type="hidden" value="<?php  
    if ($film) : echo $film->getFilmId();  
    endif;  
    ?>" name="filmID"/>
           <?php
           // si c'est une modification, c'est une information dont nous avons besoin
           if (!$isItACreation) {
               ?>
        <input type="hidden" name="modificationInProgress" value="true"/>
        <?php
    }
    ?>
    <input type="submit" name="saveEntry" value="Sauvegarder"/>
</form>
<form action="<?= $request->getBasePath() . '/movie/list' ?>" method="get">

    <input type="submit" name="backToList" value="Retour à la liste"/>
</form>