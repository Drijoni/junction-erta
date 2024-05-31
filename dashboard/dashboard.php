<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ErtaFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-200">
    <?php include_once 'components/header.php'; ?>
    <div class="flex flex-row">
        <?php include_once 'components/sidebar.php'; ?>

        <?php include_once 'content/departaments.php'; ?>
    </div>
    
</body>
<style>
*{
      font-family: "DM Sans", sans-serif;
}
</style>
</html>
