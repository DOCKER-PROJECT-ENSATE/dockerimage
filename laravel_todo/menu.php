<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css"/> 
    <title>banque</title>
</head>
<body>
    <script src="/app.js"></script>
    
    <div class="container">
        
        <h3>HCPBank<img src="css/image/bankLogo.png" alt="logo"></h3>
        
        <fieldset class="NewClient" style="width: fit-content;">
            <legend>Ajouter un nouvel utilisateur</legend>
            <form id="formUtilisateur">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" required><br><br>
                
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" required><br><br>
                
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required><br><br>
                
                <button type="submit">Ajouter utilisateur</button>
            </form> 
        </fieldset>
        
        <fieldset id="tableau">
            <legend>Liste des utilisateurs</legend>
            <form id="rechercher">
                <label for="Rnom">Nom/Prenom/Email :</label>
                <input type="text" id="Rnom" name="Rnom" required>      
            </form> <br>
            <table id="resultsTable" width="100%">
                <thead>
                    <tr>
                        <th>NumCompte</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Solde</th>
                        <th>Observation</th>
                        <th>Actions</th>
                        <th>Options</th>                                           
                    </tr>
                </thead>
                <tbody>
                    <!-- Les résultats seront insérés ici -->
                    <?php
                        try{
                            $BDD=new PDO('mysql:host=laravel_mysql:3306;dbname=clientBanque','root', 'root');
                            $liste= $BDD->query('SELECT * FROM client');
                            while ($donnee = $liste->fetch()){
                    ?>
                            <tr> 
                                <td> <?php echo ($donnee['idClient']);?> </td>
                                <td> <?php echo ($donnee['nom']);?> </td>
                                <td> <?php echo ($donnee['prenom']);?> </td>
                                <td> <?php echo ($donnee['solde']);?> </td>
                                <td> MAX </td>  
                                <td>
                                    <button class="optionBtn"><img class="optionIcon" src="css/image/edit-button.svg" alt="edit"></button>
                                    
                                    <button class="optionBtn"><img class="optionIcon" src="css/image/delete-button.svg" alt="delete"></button>
                                </td>
                            </tr>
                    <?php
                            }
                        }
                        catch (Exception $erreur)
                        {
                            echo'erreur:' . $erreur->getMessage();
                        }
                    ?>

                    
                </tbody>
            </table>
        </fieldset>
        <fieldset class="data">
            <legend>Donnée generale</legend>
            <form id="data">
                <span>
                    <label for="soldeTotal">Solde total :</label>
                    <input type="text" id="total" name="total" disabled>
                </span>
                <span>
                    <label for="min">solde minimale :</label>
                    <input type="text" id="min" name="min" disabled>
                </span>
                <span>
                    <label for="max">Solde maximale :</label>
                    <input type="text" id="max" name="max" disabled> 
                </span>                          
            </form> 
        </fieldset>
        
        <div id="formOverlayV" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);">
            <div id="formContainer" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px;">
                <h2>Formulaire de versement</h2>
                <form id="verserForm">
                    <label for="montantV">Montant à verser :</label>
                    <input type="number" id="montantV" name="montantV">
                    <input type="hidden" id="idcliV" name="idcliV" >
                    <button>Valider</button>
                    <button>Annuler</button>
                </form>
            </div>
        </div>
        
        <div id="formOverlayR" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);">
            <div id="formContainer" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px;">
                <h2>Formulaire de retrait</h2>
                <form id="retraitForm">
                    <label for="montantR">Montant à retirer :</label>
                    <input type="number" id="montantR" name="montantR">
                    <input type="hidden" id="idcliR" name="idcliR" >
                    <button >Valider</button>
                    <button >Annuler</button>
                </form>
            </div>
        </div>
        
        <div id="formOverlayModifier" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);">
            <div id="formContainer" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px;">
                <h2>Modifier le client</h2>
                
                <label for="nomM">Nom :</label>
                <input type="text" id="nomM" name="nomM" value=""><br><br>
                <label for="prenomM">Prénom :</label>
                <input type="text" id="prenomM" name="prenomM" value=""><br><br>
                <label for="emailM">Email :</label>
                <input type="email" id="emailM" name="emailM" value=""><br><br>
                <input type="hidden" id="idcliM" name="idcliM" value="">
                <button>Valider</button>
                <button>Annuler</button>
            </div>
        </div>
        
    </div>
    
</body>
</html>
