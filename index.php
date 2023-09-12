<?php
ob_start();
session_start();
if (!isset($_SESSION['logged'])) {
    header('Location:login.php');
    exit();
}

require_once 'include/class_autoloader.inc.php';
require_once 'include/config.inc.php';
require_once 'include/database.inc.php';
require 'include/vendor/plasticbrain/php-flash-messages/src/FlashMessages.php';
$msg = new \Plasticbrain\FlashMessages\FlashMessages();
$db = DbConnect::getInstance();
$dbc = DbConnect::getConnection();
$board = new Board($dbc);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= SITE_NAME ?>
    </title>
    <!-- <link rel="stylesheet" href="css/style.css"> -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            font-weight: 400;
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
            border: 0;
        }

        /* Style for the "Create Board" button */
        .create-button {
            background-color: #007BFF;
            /* Button background color */
            color: #fff;
            /* Text color */
            border: none;
            border-radius: 5px;
            padding: 12px 24px;
            /* Adjust padding for button size */
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease-in-out;
            margin-top: 20px;
            /* Add some spacing from the existing boards */
            float: right;
            /* Position the button to the right */
        }

        /* Hover effect for the button */
        .create-button:hover {
            background-color: #0056b3;
            /* Darker shade on hover */
        }

        /* Style to make the button rounded */
        .create-button {
            border-radius: 30px;
        }


        main {
            border: 5px solid rgba(0, 0, 0, .1);
            box-shadow: 0 8px 12px 0 rgba(0, 0, 0, .1);
            max-width: 1000px;
            margin: auto;
            position: relative;
        }

        header {
            margin: 0;
            padding: 0;
            border: 0;
            display: block;
            text-align: center;
            position: relative;
        }

        /* Style for the modal background overlay */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        /* Style for the modal content */
        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            text-align: center;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        }

        /* Style for the close button */
        .close {
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        /* Style for the close button on hover */
        .close:hover {
            color: #f00;
        }

        /* Style for the modal form elements */
        form {
            text-align: center;
        }

        label {
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
        }

        /* Style for radio buttons and labels */
        .visibility-options {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .visibility-options label {
            margin: 0 10px;
        }

        /* Style the checked radio button */
        .visibility-options input[type="radio"]:checked+label {
            font-weight: bold;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        ul {
            display: inline-block;
            position: relative;
        }

        li {
            display: inline-block;
            height: auto;
            line-height: 50px;
            padding-left: 5px;
            position: relative;
            box-shadow: border-box;
            margin: auto;
        }

        a {
            text-decoration: none;
        }

        .dashboard {
            width: 100%;
            height: auto;
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .card {
            display: flex;
            width: 45%;
            height: 10rem;
        }

        .card a {
            color: #fff;
            font-size: 1.5rem;
            background-size: cover;
            padding: .5rem 0 6rem .5rem;
            margin: auto;
            width: 100%;
            height: auto;
            border-radius: 3px;
            text-align: start;
            box-shadow: 0 8px 12px 0 rgba(0, 0, 0, 2);
        }

        .cardImage {
            opacity: 1;
            -webkit-transition: .3s ease-in-out;
            transition: .3s ease-in-out;
        }

        .cardImage:hover {
            opacity: .6;
            background-image: none;
        }

        .pipes {
            display: inline-block;
            margin: 0;
            padding: 0;
        }

        .pipes li+li::before {
            content: "|";
            color: #fff;
            padding: 0 .3em 0 .1em;
        }

        .nav {
            width: 100%;
            max-width: 768px;
            padding: auto;
            margin: auto;
        }

        .nav li:hover {
            border-radius: 8px;
            background: lightskyblue;
            transition: background .6s;
            opacity: .6;
        }

        .nav-link {
            color: #fff;
            text-decoration: none;
        }

        .sidebar {
            margin: 0;
            padding: 0;
            width: 200px;
            background-color: #fff;
            position: fixed;
            height: 1000px;
            box-shadow: 0 8px 12px 0 rgba(0, 0, 0, .1);
        }

        .sidebar a {
            font-weight: 700;
            display: block;
            color: black;
            text-decoration: none;
            padding: 1rem;
            margin: .5rem 0 0 1rem;
            border-radius: 3px;
            height: auto;
            width: auto;
        }

        .sidebar a.active {
            background-color: #eee;
            color: #444;
        }

        .sidebar a:hover:not(.active) {
            background-color: dimgrey;
            color: whitesmoke;
        }

        .mainContent {
            padding: .1rem 1rem;
            margin-left: 200px;
            height: 1000px;
            position: relative;
        }

        @media (min-width: 1281px) {
            header {
                background-color: slategray;
                height: auto;
            }
        }

        @media (min-width: 1025px) and (max-width: 1280px) {
            header {
                background-color: tan;
                height: auto;
            }
        }

        @media (min-width: 768px) and (max-width: 1024px) {
            header {
                background-color: tomato;
                height: auto;
            }
        }

        @media (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
            header {
                background-color: thistle;
                height: auto;
            }
        }

        @media (min-width: 481px) and (max-width: 767px) {
            header {
                background-color: violet;
                height: auto;
            }

            li {
                display: inline-block;
            }

            .pipes li+li::before {
                display: inline-block;
            }

            .sidebar {
                display: none;
            }

            .mainContent {
                margin-left: 0;
            }

            .dashboard {
                display: column;
            }

            .card {
                max-width: 50%;
            }
        }

        @media (min-width: 320px) and (max-width: 480px) {
            header {
                background-color: sienna;
                height: auto;
                margin: 0;
            }

            li {
                text-align: center;
                width: 50%;
                float: left;
                margin: 0;
                padding: 0;
            }

            .pipes li+li::before {
                display: none;
            }

            .sidebar {
                display: none;
            }

            .mainContent {
                margin-left: 0;
            }

            .dashboard {
                display: column;
            }

            .card {
                width: 100%;
            }
        }

        @media (max-width: 319px) {
            header {
                background-color: darksalmon;
                margin: 0;
            }

            li {
                text-align: center;
                width: 50%;
                float: left;
                margin: 0;
                padding: 0;
            }

            .pipes li+li::before {
                display: none;
            }

            .sidebar {
                display: none;
            }

            .mainContent {
                margin-left: 0;
            }

            .dashboard {
                display: column;
            }

            .card {
                width: 100%;
            }
        }

        /* Style for the entire navigation bar */
        nav {
            background-color: slategray;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
        }

        /* Style for the list items in the navigation */
        .nav ul.pipes {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .nav li {
            margin-right: 10px;
            /* Reduced the margin between list items */
        }

        .nav-link {
            text-decoration: none;
            color: white;
        }

        /* Style the search form */
        .search-form {
            display: flex;
            align-items: center;
        }

        /* Style the search input */
        .search-input {
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
            padding: 5px;
            /* Added padding for the input field */
        }

        /* Style the search button (if you have one) */
        .search-form button[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            /* Added padding for the button */
            cursor: pointer;
        }

        /* Style the search button on hover */
        .search-form button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <header class="header">
        <?php include_once 'partials/__nav.php'; ?>
    </header>

    <!-- Sidebar -->
    <?php include_once 'partials/__sidebar.php'; ?>

    <main class="main" id="home">


        <!-- Page content -->
        <section class="mainContent">

            <div class="dashboard">
                <?php
                $allBoards = $board->find(['is_close' => 0, 'user_id' => $_SESSION['user_id']]);
                foreach ($allBoards as $boards) {

                    ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <div class="card column">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <a href="board.php?slug=<?= $boards['slug'] ?> " class="cardImage"
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    style="background-image: url('<?= $boards['image'] ?>');">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <span class="title">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <?= $boards['title'] ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </a>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <?php
                }
                ?>


            </div>
            <!-- Create Board Button -->
            <button id="create-board-button" class="create-button">Create Board</button>
            <!-- Modal content -->
            <div id="create-board-modal" class="modal">
                <div class="modal-content">
                    <span class="close" id="close-modal">&times;</span>
                    <h2>Create a New Board</h2>
                    <form action="" method="post">
                        <label for="board-name">Board Name:</label>
                        <input type="text" id="board-name" name="name" required>

                        <div class="visibility-options">
                            <label for="public">Public</label>
                            <input type="radio" id="public" name="visibility" value="public" checked>

                            <label for="private">Private</label>
                            <input type="radio" id="private" name="visibility" value="private">
                        </div>

                        <button type="submit" name="create">Create</button>
                    </form>
                </div>
            </div>
            <?php
            if (isset($_POST['create'])) {
                $db = DbConnect::getInstance();
                $dbc = DbConnect::getConnection();
                $board = new Board($dbc);
                $name = $board->checkFields($_POST['name']);
                $visibility = $_POST['visibility']; // Fix variable name here
                $slug = $board->create_slug($name);
                $validation = new Validation();
                $allErrors = [];
                if (
                    !$validation->addRule(new ValidateEmptyFields())
                        ->validate($name)
                ) {
                    $allErrors[] = $validation->getErrorMessages();
                }
                $values = [
                    null,
                    $name,
                    $visibility,
                    date('Y-m-d H:i:s'),
                    date('Y-m-d H:i:s'),
                    $slug,
                    'https://images.unsplash.com/photo-1563311091-24bbd732c899?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=800&q=80',
                    0,
                    $_SESSION['user_id'],
                    0

                ];

                if (empty($allErrors)) {
                    if ($board->addIntoDb($values)) {
                        header("Location: board.php?slug=$slug"); // Use double quotes here
                        exit();
                    }
                } else {
                    $i = 0;
                    foreach ($allErrors as $error) {
                        // Assuming $msg is an initialized message object
                        $msg->error("{$error[$i++]}");
                    }
                }
            }
            ?>
        </section>
    </main>
    <main class="main" id="template">
        <section class="mainContent">
        <?php
        $template = new Template($dbc);
        $businessTem = $template->find(['category' => 'business']);
        $designTem = $template->find(['category' => 'design']);
        $educationTem = $template->find(['category' => 'education']);
        $otherTem = $template->find(['category' => 'other']);
        $user = new User($dbc);
        function generateTemplateSection($templates, $categoryName)
        {
            $i = time();


            global $user, $board;

            if (!empty($templates)) {
                foreach ($templates as $template) {
                    ?>
                                                                                            <div class="card column" style="border: 1px solid #ccc; padding: 10px; margin: 10px;">
                                                                                                <a href="board.php?slug=<?= $template['slug'] ?> " class="cardImage" style="background-image: url('<?= $template['image'] ?>'); display: block; height: 60px; background-size: cover; background-position: center; margin-bottom: 10px;">
                                                                                                    <span class="title" style="font-weight: bold; font-size: 16px; margin-top: 5px;"><?= $template['title'] ?></span>
                                                                                                    <?php
                                                                                                    $username = $user->find(['id' => $template['user_id']]);
                                                                                                    ?>
                                                                                                    <span class="username" style="font-size: 14px;">From: <?= $username[0]['name'] ?></span>
                                                                                                </a>
                                                                                                <form action="" method="post">
                                                                                                    <input type="hidden" name="template_id" value="<?= $template['id'] ?>">
                                                                                                    <input type="hidden" name="temTitle" value="<?= $template['title'] ?>">
                                                                                                    <input type="hidden" name="temSlug" value="<?= $template['slug'] ?>">
                                                                                                    <input type="hidden" name="temCreatedAt" value="<?= $template['created_at'] ?>">
                                                                                                    <input type="hidden" name="temUpdatedAt" value="<?= $template['updated_at'] ?>">
                                                                                                    <input type="hidden" name="temUserID" value="<?= $template['user_id'] ?>">
                                                                                                    <input type="hidden" name="temImage" value="<?= $template['image'] ?>">
                                                                                                    <button type="submit" name="useit" class="use-button" style="background-color: #007bff; color: #fff; border: none; padding: 5px 10px; cursor: pointer;">Use</button>
                                                                                                </form>
                                                                                            </div>
                                                                                            <?php
                }
            } else {
                echo 'No templates';
            }
            if (isset($_POST['useit'])) {
                // Handle form submission for the "Use" button
                $temTitle = $_POST['temTitle'];
                $temSlug = $_POST['temSlug'];
                $temCreatedAt = $_POST['temCreatedAt'];
                $temUpdatedAt = $_POST['temUpdatedAt'];
                $temUserID = $_POST['temUserID'];
                $temImage = $_POST['temImage'];

                // Adjust the slug by appending $i to make it unique
                $temSlug = $temSlug . $i++;
                $valuesBoard = [
                    null,
                    $temTitle,
                    'public',
                    $temCreatedAt,
                    $temUpdatedAt,
                    $temSlug,
                    $temImage,
                    0,
                    $_SESSION['user_id'],
                    0
                ];
                try {
                    $board->addIntoDb($valuesBoard);
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
                header("Location:board.php?slug=$temSlug");
                exit();
            }
        }
        ?>        

<div class="category" id="business-category">
    <h2>Business Category</h2>
    <div class="card-container">
        <?php generateTemplateSection($businessTem, 'business'); ?>
    </div>
</div>

<div class="category" id="design-category">
    <h2>Design Category</h2>
    <div class="card-container">
        <?php generateTemplateSection($designTem, 'design'); ?>
    </div>
</div>

<div class="category" id="education-category">
    <h2>Education Category</h2>
    <div class="card-container">
        <?php generateTemplateSection($educationTem, 'education'); ?>
    </div>
</div>

<div class="category" id="other-category">
    <h2>Other Category</h2>
    <div class="card-container">
        <?php generateTemplateSection($otherTem, 'other'); ?>
    </div>
</div>



    </div>
</div>

    </div>
</div>

    </div>
</div>

                </div>
            </div>
            <!-- Add more categories as needed -->

        </section>
    </main>
    
     
    <script>
        // JavaScript to handle modal functionality
        var modal = document.getElementById('create-board-modal');
        var closeButton = document.getElementById('close-modal');
        var createBoardButton = document.getElementById('create-board-button');

        createBoardButton.onclick = function (event) {
            event.preventDefault(); // Prevent the default form submission behavior
            modal.style.display = 'block';
        }

        closeButton.onclick = function () {
            modal.style.display = 'none';
        }

        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
   <script>
    // JavaScript to handle sidebar link clicks
    var templatesLink = document.getElementById('templates-link');
    var homeLink = document.getElementById('home-link');
    var homeContent = document.getElementById('home');
    var templateContent = document.getElementById('template');
    var templateCategories = document.getElementById('template-categories');
    var categoryTitle = document.getElementById('category-title');
    var categoryTemplates = document.getElementById('category-templates');

    // Initially, show the Home content and hide the Templates content and categories
    homeContent.style.display = 'block';
    templateContent.style.display = 'none';
    templateCategories.style.display = 'none';

    templatesLink.onclick = function (event) {
        event.preventDefault(); // Prevent the default link behavior
        homeContent.style.display = 'none'; // Hide Home content
        templateContent.style.display = 'block'; // Show Templates content
        templateCategories.style.display = 'block'; // Show Template categories
    }

    homeLink.onclick = function (event) {
        event.preventDefault(); // Prevent the default link behavior
        homeContent.style.display = 'block'; // Show Home content
        templateContent.style.display = 'none'; // Hide Templates content
        templateCategories.style.display = 'none'; // Hide Template categories
    }

    // Function to show a specific category in the main content
    

    // Add event listeners for more category links as needed
</script>



</body>
</html>
<?php
ob_end_flush();

?> 