<?php
require_once ('conf.php');
global $yhendus;

// punktide nulliks UPDATE
if(isset($_REQUEST['punkt'])){
    $kask=$yhendus->prepare("UPDATE konkurss SET punktid=0 WHERE id=?");
    $kask->bind_param("i", $_REQUEST['punkt']);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}

// nimi lisamine konkurssi
if(!empty($_REQUEST['nimi'])){
    $kask=$yhendus->prepare("
INSERT INTO konkurss (nimi, pilt, lisamisaeg)
VALUES (?, ?, NOW())");
    $kask->bind_param("ss", $_REQUEST['nimi'], $_REQUEST['pilt']);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}

// nimi näitamine avalik=1 UPDATE
if(isset($_REQUEST['avamine'])){
    $kask=$yhendus->prepare("UPDATE konkurss SET avalik=1 WHERE id=?");
    $kask->bind_param("i", $_REQUEST['avamine']);
    $kask->execute();
}

// nimi näitamine avalik=0 UPDATE
if(isset($_REQUEST['peitmine'])){
    $kask=$yhendus->prepare("UPDATE konkurss SET avalik=0 WHERE id=?");
    $kask->bind_param("i", $_REQUEST['peitmine']);
    $kask->execute();
}

// kustutamine
if(isset($_REQUEST['kustuta'])){
    $kask=$yhendus->prepare("DELETE FROM konkurss WHERE id=?");
    $kask->bind_param("i", $_REQUEST['kustuta']);
    $kask->execute();
}

// uue kommentaari lisamine
if(isset($_REQUEST['uus_komment'])){
    $kask=$yhendus->prepare("UPDATE konkurss SET kommentaar=CONCAT(kommentaar, ?) WHERE id=?");
    $kommentLisa=$_REQUEST['komment']."\n";
    $kask->bind_param("si", $kommentLisa,$_REQUEST['uus_komment']);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}

if(isset($_REQUEST['kommentaar'])){
    $kask=$yhendus->prepare("UPDATE konkurss SET kommentaar='' WHERE id=?");
    $kask->bind_param("i", $_REQUEST['kommentaar']);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}

?>
<!Doctype html>
<html lang="et">
<head>
    <link rel="stylesheet" href="style.css">
    <title>Fotokonkurss</title>
</head>

<body>

<div class="topnav">
<nav>
    <a href="haldus.php">Administreerimise leht</a>
    <a href="konkurss.php">Kasutaja leht</a>
</nav>
</div>

<h1>Fotokonkurssi halduseleht</h1>

<?php
// tabeli konkurss sisu näitamine
$kask=$yhendus->prepare("SELECT id, nimi, pilt, lisamisaeg, punktid, avalik, kommentaar FROM konkurss");
$kask->bind_result($id, $nimi, $pilt, $aeg, $punktid, $avalik, $kommentaar);
$kask->execute();
echo "<table><tr> <td></td> <td></td> <td></td> <td>Nimi</td> <td>Pilt</td> <td>Lisamisaeg</td> <td>Punktid</td> <td>Kustuta punktid</td>
 <td>Kommentaarid</td> <td>Kustuta kommentaar</td></tr>";

while($kask->fetch()){
    $delete='"Kas olete kindel, et kustutate?"';
    echo "<tr>";
    echo "<td><a href='?kustuta=$id' onclick='return confirm($delete)'>Kustuta</a></td>";

    // Peida-näita
    $avatekst="Ava";
    $param="avamine";
    $seisund="Peidetud";
    if($avalik==1){
        $avatekst="Peida";
        $param="peitmine";
        $seisund="Avatud";
    }

    echo "<td>$seisund</td>";
    echo "<td><a href='?$param=$id'>$avatekst</a></td>";

    echo "<td>$nimi</td>";
    echo "<td><img src='$pilt' alt='pilt'></td>";
    echo "<td>$aeg</td>";
    echo "<td>$punktid</td>";
    echo "<td><a href='?punkt=$id'>Punktid nulliks</a></td>";
    echo "<td>".nl2br($kommentaar)."</td>";


    echo "<td><a href='?kommentaar=$id'>Kommentaar nulliks</a></td>";

    echo "</tr>";
}
echo "<table>";
?>

<h2>Uue pilti lisamine konkurssi</h2>
<form action="?">
    <input type="text" name="nimi" placeholder="uus nimi">
    <br>
    <textarea name="pilt">pildi linki aadress</textarea>
    <br>
    <input type="submit" value="Lisa">
</form>
</body>
</html>

