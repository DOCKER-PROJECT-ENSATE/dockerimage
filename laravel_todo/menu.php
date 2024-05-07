<?php
try {
    // Connexion à la base de données
    $BDD = new PDO('mysql:host=laravel_mysql:3306;dbname=clientBanque', 'root', 'root');
    
    // Vérification des données du formulaire de versement
    if(isset($_POST['montantV'], $_POST['idcliV'])) {
        // Récupération des données du formulaire
        $montant = $_POST['montantV'];
        $idClient = $_POST['idcliV'];

        // Mise à jour du solde du client dans la base de données
        $stmt = $BDD->prepare("UPDATE client SET solde = solde + ? WHERE idClient = ?");
        $stmt->execute([$montant, $idClient]);

        // Redirection vers la page principale après le versement
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }

    // Vérification des données du formulaire de retrait
    if(isset($_POST['montantR'], $_POST['idcliR'])) {
        // Récupération des données du formulaire
        $montant = $_POST['montantR'];
        $idClient = $_POST['idcliR'];

        // Vérification du solde suffisant pour effectuer le retrait
        $stmt = $BDD->prepare("SELECT solde FROM client WHERE idClient = ?");
        $stmt->execute([$idClient]);
        $solde = $stmt->fetchColumn();

        if($solde >= $montant) {
            // Mise à jour du solde du client dans la base de données
            $stmt = $BDD->prepare("UPDATE client SET solde = solde - ? WHERE idClient = ?");
            $stmt->execute([$montant, $idClient]);

            // Redirection vers la page principale après le retrait
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            // Redirection vers la page principale avec un message d'erreur
            header("Location: ".$_SERVER['PHP_SELF']."?error=solde_insuffisant");
            exit();
        }
    }

    
?>
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
<div class="container">
    <h1>Ce header marque qu'on a pu faire la CI et qu'on peut désormais exécuter les services en utilisant simplement le compose</h1>
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
                <?php
                $conn = new PDO('mysql:host=laravel_mysql:3306;dbname=clientBanque', 'root', 'root');
                $listClients = $conn->query('SELECT * FROM client');
                foreach ($listClients as $client) {
                    echo "<tr>
                        <td>{$client['idClient']}</td>
                        <td>{$client['nom']}</td>
                        <td>{$client['prenom']}</td>
                        <td>{$client['solde']}</td>
                        <td>MAX</td>
                        <td>
                            <button class='optionBtn editClientBtn' data-id='{$client['idClient']}'>
                                <img class='optionIcon' src='css/image/edit-button.svg' alt='edit'>
                            </button>
                            <button class='optionBtn'>
                                <img class='optionIcon' src='css/image/delete-button.svg' alt='delete'>
                            </button>
                        </td>
                        <td>
                            <button class='optionBtn verserBtn' data-id='{$client['idClient']}'>
                                Verser
                            </button>
                            <button class='optionBtn retirerBtn' data-id='{$client['idClient']}'>
                                Retirer
                            </button>
                        </td>
                    </tr>";
                }

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clientId'], $_POST['nom'], $_POST['prenom'])) {
                    $stmt = $conn->prepare("UPDATE client SET nom = ?, prenom = ? WHERE idClient = ?");
                    $stmt->bindParam(1, $_POST['nom']);
                    $stmt->bindParam(2, $_POST['prenom']);
                    $stmt->bindParam(3, $_POST['clientId']);
                    $stmt->execute();
                    echo json_encode(['message' => 'Update successful']);
                    exit;
                }
                ?>
                
            </tbody>
        </table>
    </fieldset>
    
    <fieldset class="data">
        <legend>Donnée générale</legend>
        <?php
      
        $totalSolde = $BDD->query('SELECT SUM(solde) AS total FROM client')->fetchColumn();
        $minSolde = $BDD->query('SELECT MIN(solde) AS min FROM client')->fetchColumn();
        $maxSolde = $BDD->query('SELECT MAX(solde) AS max FROM client')->fetchColumn();
        ?>
        <form id="data">
            <span>
                <label for="soldeTotal">Solde total :</label>
                <input type="text" id="total" name="total" value="<?php echo $totalSolde; ?>" disabled>
            </span>
            <span>
                <label for="min">Solde minimal :</label>
                <input type="text" id="min" name="min" value="<?php echo $minSolde; ?>" disabled>
            </span>
            <span>
                <label for="max">Solde maximal :</label>
                <input type="text" id="max" name="max" value="<?php echo $maxSolde; ?>" disabled>
            </span>                         
        </form> 
    </fieldset>
        
    <div id="formOverlayV" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);">
        <div id="formContainer" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px;">
            <h2>Formulaire de versement</h2>
            <form id="verserForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <label for="montantV">Montant à verser :</label>
                <input type="number" id="montantV" name="montantV">
                <input type="hidden" id="idcliV" name="idcliV">
                <button type="submit">Valider</button>
                <button type="button" onclick="closeOverlay('formOverlayV')">Annuler</button>
            </form>
        </div>
    </div>

    <div id="formOverlayR" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);">
        <div id="formContainer" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px;">
            <h2>Formulaire de retrait</h2>
            <form id="retraitForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <label for="montantR">Montant à retirer :</label>
                <input type="number" id="montantR" name="montantR">
                <input type="hidden" id="idcliR" name="idcliR">
                <button type="submit">Valider</button>
                <button type="button" onclick="closeOverlay('formOverlayR')">Annuler</button>
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
                <button type="button" onclick="closeOverlay('formOverlayModifier')">Annuler</button>
            </div>
        </div>

    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const verserButtons = document.querySelectorAll('.verserBtn');
        const retirerButtons = document.querySelectorAll('.retirerBtn');
        
        verserButtons.forEach(button => {
            button.addEventListener('click', function() {
                const clientId = this.dataset.id;
                document.getElementById('idcliV').value = clientId;
                document.getElementById('formOverlayV').style.display = 'block';
            });
        });

        retirerButtons.forEach(button => {
            button.addEventListener('click', function() {
                const clientId = this.dataset.id;
                document.getElementById('idcliR').value = clientId;
                document.getElementById('formOverlayR').style.display = 'block';
            });
        });
    });

    function closeOverlay(overlayId) {
        document.getElementById(overlayId).style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.editClientBtn');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const clientId = this.dataset.id;
                const parentRow = this.closest('tr');
                const cells = parentRow.querySelectorAll('td');
                if (!this.classList.contains('saveBtn')) {
                    convertToInput(cells[1], 'nom', clientId);
                    convertToInput(cells[2], 'prenom', clientId);
                    this.innerHTML = '<img class="optionIcon" src="css/image/save-icon.svg" alt="save">';
                    this.classList.add('saveBtn');
                } else {
                    saveChanges(clientId, parentRow);
                    this.innerHTML = '<img class="optionIcon" src="css/image/edit-button.svg" alt="edit">';
                    this.classList.remove('saveBtn');
                }
            });
        });
    });

        function convertToInput(cell, name, clientId) {
            const currentValue = cell.textContent;
            cell.innerHTML = `<input type='text' name='${name}' value='${currentValue}'>`;
        }

        function saveChanges(clientId, row) {
            let inputs = row.querySelectorAll('input');
            let data = { clientId: clientId };
            inputs.forEach(input => {
                data[input.name] = input.value;
                const value = input.value;
                    const td = input.parentElement;
                    td.innerHTML = value;
            });

            fetch('', { // Submit to the same file
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams(data)
            })
            .then(response => response.json())
            // .then(data => {
            //     // alert(data.message);
            //     inputs.forEach(input => {  // Convert inputs back to text
            //         const value = input.value;
            //         const td = input.parentElement;
            //         td.innerHTML = value;
            //     });
            // })
            .catch(error => {
                console.error('Error:', error);
                // alert('Error: ' + error);
            });
        }
    </script>
</div>
</body>
</html>
<?php
} catch (Exception $erreur) {
    echo 'erreur:' . $erreur->getMessage();
}
?>
