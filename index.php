<?php

// seconde partie 2

// if (!empty($_GET['q'])) {
// 	// je le metes sur une variable
// 	$raccourcie = htmlspecialchars($_GET['q']);
// 	// mais est-ce que sa exist ?
// 	require_once './src/connexion.php';
// 	$requete = $pdo->prepare("SELECT COUNT(*) AS nombre FROM links WHERE  short = ?");
// 	$requete->execute([$raccourcie]);

// 	while ($result = $requete->fetch()) {
// 		if ($result['nombre'] != 1) {
// 			header('Location: /?error=true&message=Adresse+url+non+reconnue');
// 			exit();
// 		}
// 	}

// 	//Redirection car tout va bien 
// 	$requete = $pdo->prepare("SELECT * FROM links WHERE  short = ?");
// 	$requete->execute([$raccourcie]);

// 	while ($results = $requete->fetch(PDO::FETCH_OBJ)) {
// 		header("Location:$results->url");
// 		exit();
// 	}
// }

if (!empty($_GET['q'])) {
	$raccourcie = htmlspecialchars($_GET['q']);
	require_once './src/connexion.php';

	$requete = $pdo->prepare("SELECT url FROM links WHERE short = ?");
	$requete->execute([$raccourcie]);
	$result = $requete->fetch(PDO::FETCH_OBJ);

	if (!$result) {
		header('Location: /?error=true&message=Adresse+url+non+reconnue');
		exit();
	}

	header("Location: {$result->url}");
	exit();
}

//premier partie 1
if (!empty($_POST['url'])) {

	// Nettoyage de l'URL
	$url = trim($_POST['url']);

	// Vérification du format
	if (!filter_var($url, FILTER_VALIDATE_URL)) {
		header('Location: /?error=true&message=Adresse+url+non+valide');
		exit();
	}

	// Génération d’un code court simple (ex: 6 caractères aléatoires)
	$racourcie = substr(md5(uniqid()), 0, 6);

	// Vérification doublon (si l’URL existe déjà dans la BDD)
	require_once './src/connexion.php';

	/*
    $requete = $pdo->prepare("SELECT short FROM links WHERE url = ?");
    $requete->execute([$url]);
    $resultat = $requete->fetch(PDO::FETCH_OBJ);

    if ($resultat) {
        header("Location: /?error=true&message=Adresse+déjà+raccourcie&short=" . $resultat->short);
        exit();
    }
	*/

	// Insertion en BDD
	$requete = $pdo->prepare("INSERT INTO links (url, short) VALUES (?, ?)");
	$requete->execute([$url, $racourcie]);

	// Redirection avec le lien raccourci
	header("Location: /?short=$racourcie");
	exit();
}
?>


<html>

<head>
	<meta charset="utf-8">
	<title>BITLY - Raccourcissez vos urls</title>
	<link rel="stylesheet" href="design/default.css">
	<link rel="icon" type="image/png" href="assets/favicon.png">
</head>

<body>

	<!-- PRESENTATION -->
	<section id="main">

		<!-- CONTAINER -->
		<div class="container">

			<!-- EN-TETE -->
			<?php require_once("./src/header.php"); ?>

			<!-- PROPOSITION -->
			<h1>Une url longue ? Raccourcissez-là ?</h1>
			<h2>Largement meilleur et plus court que les autres.</h2>

			<!-- FORM -->
			<form method="post" action="index.php">
				<input type="url" name="url" placeholder="Collez un lien à raccourcir">
				<input type="submit" value="Raccourcir">
			</form>

			<?php if (isset($_GET['error']) && isset($_GET['message'])) { ?>

				<div class="center">
					<div id="result">
						<b><?php echo htmlspecialchars($_GET['message']); ?></b>
					</div>
				</div>

			<?php } else if (isset($_GET['short'])) { ?>

				<div class="center">
					<div id="result">
						<b>URL RACCOURCIE : </b>
						http://localhost:3000/?q=<?php echo htmlspecialchars($_GET['short']); ?>
					</div>
				</div>

			<?php } ?>

		</div>

	</section>

	<!-- MARQUES -->
	<section id="brands">

		<!-- CONTAINER -->
		<div class="container">
			<h3>Ces marques nous font confiance</h3>
			<img src="assets/1.png" alt="1" class="picture">
			<img src="assets/2.png" alt="2" class="picture">
			<img src="assets/3.png" alt="3" class="picture">
			<img src="assets/4.png" alt="4" class="picture">
		</div>

	</section>

	<!-- PIED DE PAGE -->
	<?php require_once("./src/footer.php"); ?>

</body>

</html>