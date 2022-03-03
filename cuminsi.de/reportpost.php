<?php
    require 'requirements.php';

    if(!isset($_POST['postid'])) {
        header('Location: index.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require 'head.html'; ?>
    <title>Report post</title>
</head>
<body>
    <a href="index.php">Zurück</a>
    <h2>Report Post</h2>
    <form method="post" action="logic.php?action=reportpost">
        <input name="reportedpostId" type="hidden" value=<?php echo $_POST['postid'] ?>>
        <input name="description" type="text" placeholder="description" required>
        <input name="reportpostSubmit" type="submit" value="report">
    </form>
</body>
</html>