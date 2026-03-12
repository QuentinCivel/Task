<h1>Mes ToDoes</h1>

<section>
    <h2><?php echo $modeEdition ? "Modifier la ToDo" : "Ajouter une ToDo"; ?></h2>
    
    <form method="post">
        <input type="hidden" name="id_task_to_update" value="<?php echo $formIdTask; ?>">
        
        <label for="nameTask">Titre : </label>
        <input id="nameTask" type="text" name="nameTask" value="<?php echo $formName; ?>">
        
        <label for="contentTask">Contenu : </label>
        <input id="contentTask" type="text" name="contentTask" value="<?php echo $formContent; ?>">
        
        <label for="dateTask">Date : </label>
        <input id="dateTask" type="date" name="dateTask" value="<?php echo $formDate; ?>">
        
        <fieldset>
            <legend>Les Categories</legend>
            <?php echo $checkboxCategories ?>
        </fieldset>
        
        <input type="submit" name="<?php echo $btnAction; ?>" value="<?php echo $btnText; ?>">
    </form>
    
    <?php if($modeEdition): ?>
        <a href="/Mes_taches">Annuler la modification</a> <?php endif; ?>
    
    <p><?php echo $message ?></p>
</section>

<section>
    <h2>Liste de Mes ToDoes</h2>
    <?php echo $todoList ?>
</section>