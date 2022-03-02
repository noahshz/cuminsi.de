<h2>create post</h2>
<?php
    displayMessageOrError();
?>
<form action="logic.php?action=createpost" method="post" enctype="multipart/form-data">
    <input name="title" type="text" placeholder="title" required>
    <input name="link" type="text" placeholder="link" required>
    <input name="thumbnail" type="file" required>
    <input name="createpostSubmit" type="submit" value="create">
</form>