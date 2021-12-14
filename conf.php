<?php
$serverinimi='localhost';
$kasutajanimi='rolanmas';
$parool='123456';
$andmebaasinimi='rolanmas';
$yhendus=new mysqli($serverinimi, $kasutajanimi, $parool, $andmebaasinimi);
$yhendus->set_charset('UTF8');

/* CREATE TABLE konkurss(
    id int PRIMARY KEY AUTO_INCREMENT,
	nimi varchar(50),
    pilt text,
	lisamisaeg datetime,
    punktid int default 0,
    kommentaar text DEFAULT ' ',
    avalik int DEFAULT 1)
*/

?>
