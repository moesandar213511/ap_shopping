<?php 
    require 'config/config.php';

    $imgstmt = $pdo->prepare("SELECT * FROM products WHERE id=".$_GET['id']);
    $imgstmt->execute();
    $imgResult = $imgstmt->fetch(PDO::FETCH_ASSOC);
    unlink("images/".$imgResult['image']);

    $stmt = $pdo->prepare("DELETE FROM products WHERE id=".$_GET['id']);
    $stmt->execute();

    header('Location:index.php');
?>