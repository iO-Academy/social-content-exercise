<?php
require_once 'src/functions.php';
$db = getDbConnection();
$posts = getPosts($db);
$posts = getReplies($db, $posts);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Social Content Exercise</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>
<body>

<main class="container">
    <div class="row">
        <div class="col-12">
            <h1>Social content exercise</h1>
            <p>Replace the below content with the data from the supplied DB.</p>
        </div>
        <div class="col-12">

            <?php echo outputPosts($posts); ?>

        </div>
    </div>
</main>

</body>
</html>