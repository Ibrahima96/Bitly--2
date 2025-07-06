 <?php
//$pdo = new PDO("mysql:host=localhost;dbname=bitly;charset=utf8", "root", ""); 

$host = getenv("localhost");
$dbname = getenv("bitly");
$user = getenv("root");
$pass = getenv(" ");

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>

