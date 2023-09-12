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
$list = new Lists($dbc);
$card = new Card($dbc); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>
        <?= SITE_NAME ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Courgette:400,700">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="css/style2.css">
    <style>
        .active {
            color: yellow;
        }

        /* Style for the "Add a list" button */
        .add-list-btn {
            background-color: #3385b5;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Style for the "Add a list" input field container */
        .add-list-input {
            display: none;
            margin-top: 10px;
        }

        /* Style for the list name input */
        .add-list-input input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 10px;
        }

        /* Style for the "Add" button within the input field */
        .add-list-confirm-btn {
            background-color: #3385b5;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Hover effect for buttons */
        .add-list-btn:hover,
        .add-list-confirm-btn:hover {
            background-color: #4b963b;
        }

        /* Add some margin to the "Add" button */
        .add-list-confirm-btn {
            margin-left: 10px;
        }

        /* Style for the "Add a card" button */
        .add-card-btn {
            color: black;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Style for the "Add a card" input field container */
        .add-card-input {
            display: none;
            margin-top: 10px;
        }

        /* Style for the card name input */
        .add-card-input input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 10px;
        }

        /* Style for the "Add" button within the input field */
        .add-card-confirm-btn {
            background-color: #3385b5;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Hover effect for buttons */
        .add-card-btn:hover,
        .add-card-confirm-btn:hover {
            background-color: #4b963b;
        }

        /* Add some margin to the "Add" button */
        .add-card-confirm-btn {
            margin-left: 10px;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow: auto;
            z-index: 2;
        }

        /* Modal content */
        .modal-content {
            color: #000;
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 60%;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        }

        /* Close button */
        .close {
            color: #888;
            float: right;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #000;
        }

        /* Form styles */
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Radio button styles */
        .visibility-options {
            margin-top: 10px;
        }

        input[type="radio"] {
            margin-right: 10px;
        }

        /* Submit button styles */
        button[type="submit"] {
            background-color: #3385b5;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #4b963b;
        }

        /* Your existing styles here */

        .list-container {
            display: none;
            position: fixed;
            top: 0;
            right: 0;
            width: 300px;
            height: 100%;
            background-color: #f0f0f0;
            box-shadow: -3px 0px 10px rgba(0, 0, 0, 0.2);
            overflow-y: scroll;
            padding: 20px;
        }

        .list {
            margin-bottom: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
        }

        .list h3 {
            margin: 0;
            font-size: 18px;
        }

        .list ul {
            list-style-type: none;
            padding: 0;
        }

        .list li {
            margin: 5px 0;
        }

        /* user menu */
        /* Your existing styles here */

        .user-menu {
            color: #333;
            display: none;
            position: absolute;
            top: 40px;
            right: 0;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .user-menu ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            color: #333;
        }

        .user-menu li {
            padding: 10px 20px;
            font-size: 14px;
            border-bottom: 1px solid #ccc;
        }

        .user-menu li:last-child {
            border-bottom: none;
            font-weight: bold;
            color: #333;
        }

        .user-menu a {
            text-decoration: none;
            color: #333;
        }

        .user-menu a:hover {
            background-color: #f0f0f0;
        }

        .user-menu li:has(a) {
            font-weight: bold;
        }

        .menu-container {
            position: relative;
            display: inline-block;
        }

        /* Style for the button */
        .menu-btn {
            background-color: #3385b5;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        /* Hover effect for the button */
        .menu-btn:hover {
            background-color: #4b963b;
        }

        /* Style for the menu container */
        .menu {
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            display: none;
            z-index: 1;
        }

        /* Style for the menu items */
        .menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu li {
            padding: 10px;
            text-align: center;

        }

        .menu li a {
            text-decoration: none;
            color: #333;
            display: block;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        /* Hover effect for menu items */
        .menu li a:hover {
            background-color: #f0f0f0;

        }

        /* Style for the template category alert */
        #templateCategoryAlert {

            color: black;
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        #templateCategoryAlert p {
            color: black;

            font-size: 16px;
            margin-bottom: 10px;
        }

        #templateCategoryAlert input[type="radio"] {
            margin-right: 5px;
        }

        #templateCategoryAlert button {
            display: block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        #templateCategoryAlert button:hover {
            background-color: #0056b3;
        }
    </style>

</head>

<body>

    <!-- Masthead -->
    <header class="masthead">

        <div class="boards-menu">

            <button class="boards-btn btn"><i class="fab fa-trello boards-btn-icon"></i>Boards</button>

            <div class="board-search">
                <form action="" method="post">
                    <input type="search" id="searchInput" list="boardList" autocomplete="off" name="boardSearch"
                        class="board-search-input" aria-label="Board Search">
                    <i class="fas fa-search search-icon" aria-hidden="true"></i>
                </form>
            </div>
            <datalist id="boardList">
                <?php
                $boards = $board->find([]);
                foreach ($boards as $sBoard) {
                    $boardName = $sBoard['title']; // Corrected variable name here
                    echo "<option value='$boardName'>$boardName</option>";
                }
                ?>
            </datalist>
            <?php
            if (isset($_POST['boardSearch'])) {
                $boardSearch = $board->checkFields($_POST['boardSearch']);
                $boardSearchSlug = $board->create_slug($boardSearch);
                header("Location:board.php?slug=$boardSearchSlug");
                exit();
            }
            ?>



        </div>

        <div class="logo">
            <h1><i class="fab fa-trello logo-icon" aria-hidden="true"></i><a href="index.php">
                    <?= SITE_NAME ?>
                </a></h1>
        </div>

        <div class="user-settings">
            <button id="create-board-button" class="user-settings-btn btn" aria-label="Create">
                <i class="fas fa-plus" aria-hidden="true"></i>
            </button>
            <div id="create-board-modal" class="modal">
                <div class="modal-content">
                    <span class="close" id="close-modal">&times;</span>
                    <h2>Create a New Board</h2>
                    <br>
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
            <button class="user-settings-btn btn" aria-label="Information">
                <i class="fas fa-info-circle" aria-hidden="true"></i>
            </button>

            <button class="user-settings-btn btn" aria-label="Notifications">
                <i class="fas fa-bell" aria-hidden="true"></i>
            </button>

            <button class="user-settings-btn btn" id="user-settings-button" aria-label="User Settings">
                <i class="fas fa-user-circle" aria-hidden="true"></i>
            </button>
            <div class="user-menu" id="user-menu">
                <ul>
                    <li><strong>ACCOUNT</strong></li>
                    <li>
                        <?= $_SESSION['name'] ?>
                    </li>
                    <li>
                        <?= $_SESSION['email'] ?>
                    </li>
                    <li><a href="#">Manage account</a></li>
                    <li><strong>TRELLO</strong></li>
                    <li><a href="#">Profile and visibility</a></li>
                    <li><a href="#">Activity</a></li>
                    <li><a href="#">Cards</a></li>
                    <li><a href="#">Settings</a></li>
                    <li><a href="#">Log out</a></li>
                </ul>
            </div>
        </div>
        <?php
        if (isset($_POST['create'])) {
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
    </header>
    <!-- End of masthead -->

    <!-- Board info bar -->
    <section class="board-info-bar">
        <?php
        $slugBoard = isset($_GET['slug']) ? $_GET['slug'] : null;

        if ($slugBoard) {
            $conditions = ['slug' => $slugBoard];
            $boardInfo = $board->find($conditions);

            if (!empty($boardInfo)) {
                $boardStar = $boardInfo[0]['star'];
                $boardId = $boardInfo[0]['id'];
            } else {
                // Handle the case where the board with the specified slug is not found.
                echo "Board not found";
                exit;
            }
        } else {
            // Handle the case where 'slug' is missing in the URL.
            echo "Slug is missing in the URL";
            exit;
        }



        ?>
        <div class="board-controls">
            <button class="board-title btn">
                <h2>
                    <?= $board->checkFields($boardInfo[0]['title']) ?>
                </h2>
            </button>
            <?php

            if (isset($_POST['star'])) {
                if ($boardStar == 0) {
                    $newValues = array(
                        'star' => 1,
                    );

                    $conditions = array(
                        'slug' => $slugBoard,
                    );

                    $board->update($newValues, $conditions);
                    $boardStar = 1;
                } else {
                    $newValues = array(
                        'star' => 0,
                    );

                    $conditions = array(
                        'slug' => $slugBoard,
                    );

                    $board->update($newValues, $conditions);
                    $boardStar = 0;
                }
            }
            ?>
            <form action="" method="post">
                <button class="star-btn btn" name="star" id="starButton" aria-label="Star Board">
                    <i class="far fa-star <?= $boardStar == 1 ? 'active' : '' ?>" aria-hidden="true"></i>
                </button>

            </form>


            <button class="private-btn btn"><i class="fas fa-briefcase private-btn-icon" aria-hidden="true"></i>
                <?= $board->checkFields($boardInfo[0]['visibility']) ?>

            </button>
        </div>
        <!-- Menu container -->
        <div class="menu-container">
            <button class="menu-btn btn" id="menuBtn">
                <i class="fas fa-ellipsis-h menu-btn-icon" aria-hidden="true"></i>Show Menu
            </button>
            <div class="menu" id="menu">
                <form action="" method="post">
                    <ul>
                        <li>
                            <button type="button" id="publishTemplateBtn">Publish as Template</button>
                        </li>
                        <li><button type="submit" name="background">Change Background</button></li>
                        <li><button type="submit" name="close">Close this Board</button></li>
                    </ul>
                    <div id="templateCategoryAlert" style="display: none;">
                        <p>Choose a category:</p>
                        <input type="radio" name="category" value="Design"> Design<br>
                        <input type="radio" name="category" value="Education"> Education<br>
                        <input type="radio" name="category" value="Business"> Business<br>
                        <input type="radio" name="category" value="Other"> Other<br>
                        <button type="submit" name="categorySelected" id="confirmCategoryBtn">OK</button>
                    </div>
                </form>
            </div>
        </div>

        <?php
        // Check if a category is selected
        if (isset($_POST['categorySelected'])) {
            // The category is selected
            $category = $_POST['category'];
            $template = new Template($dbc);

            // Define the values to be inserted into the template table
            $valuesTem = [
                null,
                $boardInfo[0]['title'],
                $slugBoard,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
                $boardId,
                $boardInfo[0]['user_id'],
                $boardInfo[0]['image'],
                $category
            ];

            // Insert the template data into the database
            $template->addIntoDb($valuesTem);

            // Optionally, you can add a success message or perform other actions here
        }


        if (isset($_POST['close'])) {
            // Handle 'Close this Board' button click
            $board->update(['is_close' => 1], ['slug' => $slugBoard]);
            header('Location: index.php');
            exit();
        }
        ?>




    </section>
    <!-- End of board info bar -->

    <!-- Lists container -->
    <section class="lists-container">
        <?php
        $userId = $_SESSION['user_id'];
        $conditionsList = array(
            'user_id' => $userId,
            'board_id' => $boardId
        );
        $allLists = $list->find($conditionsList);
        foreach ($allLists as $index => $allList) {
            ?>
            <div class="list">

                <h3 class="list-title">
                    <?= $allList['title'] ?>
                </h3>
                <ul class="list-items">
                    <?php
                    $conditionsCard = array(
                        'user_id' => $userId,
                        'board_id' => $boardId,
                        'list_id' => $allList['id']
                    );
                    $allcards = $card->find($conditionsCard);
                    foreach ($allcards as $allCard) {
                        ?>
                        <li>
                            <?= $allCard['title'] ?>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
                <?php
                if (isset($_POST["addCard$index"])) {
                    $cardName = $card->checkFields($_POST['cardName']);
                    $values = [
                        null,
                        $cardName,
                        date('Y-m-d H:i:s'),
                        date('Y-m-d H:i:s'),
                        $boardId,
                        $allList['id'],
                        $userId,
                        0
                    ];
                    $card->addIntoDb($values);

                    // Reload the page to reflect the changes
                    header("Location: board.php?slug=$slugBoard"); // Change to your actual page URL
                    exit();
                }
                ?>
                <div class="add-card-container">
                    <form action="" method="post">
                        <button class="add-card-btn btn">Add a card</button>
                        <div class="add-card-input" style="display: none;">
                            <input type="text" name="cardName" placeholder="Enter card name" required>
                            <button type="submit" name="addCard<?= $index ?>" class="add-card-confirm-btn btn">Add</button>
                        </div>
                    </form>
                </div>


            </div>
            <?php
        }
        ?>

        <div class="add-list-container">
            <form id="addListForm" action="" method="post">
                <button class="add-list-btn btn">Add a list</button>
                <div class="add-list-input" style="display: none;">
                    <input type="text" name="listName" id="listName" placeholder="Enter list name">
                    <button type="submit" name="addList" class="add-list-confirm-btn btn">Add</button>
                </div>
            </form>
        </div>

        <?php
        if (isset($_POST['addList'])) {
            $listName = $list->checkFields($_POST['listName']);
            $slugList = $list->create_slug($listName);
            $values = [
                null,
                $listName,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
                $boardId,
                $userId,
                0
            ];
            $list->addIntoDb($values);

            // Reload the page to reflect the changes
            header("Location: board.php?slug=$slugBoard"); // Change to your actual page URL
            exit();
        }
        ?>

    </section>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const publishTemplateBtn = document.getElementById("publishTemplateBtn");
            const templateCategoryAlert = document.getElementById("templateCategoryAlert");
            const confirmCategoryBtn = document.getElementById("confirmCategoryBtn");

            publishTemplateBtn.addEventListener("click", function (event) {
                event.preventDefault(); // Prevent the default form submission

                // Display the templateCategoryAlert
                templateCategoryAlert.style.display = "block";
            });

            confirmCategoryBtn.addEventListener("click", function () {
                // You can add further logic here to handle category selection if needed

                // Hide the templateCategoryAlert
                templateCategoryAlert.style.display = "none";

                // Submit the form

            });
        });
    </script>



    <!-- End of lists container -->

    <script src="css/script.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const addListContainer = document.querySelector(".add-list-container");
            const addListBtn = document.querySelector(".add-list-btn");
            const addListInput = document.querySelector(".add-list-input");
            const addListForm = document.getElementById("addListForm");

            addListBtn.addEventListener("click", function (e) {
                e.preventDefault(); // Prevent the default form submission behavior
                // Toggle the visibility of the button and input field
                addListBtn.style.display = "none";
                addListInput.style.display = "block";
            });

            const addListConfirmBtn = document.querySelector(".add-list-confirm-btn");

            addListConfirmBtn.addEventListener("click", function () {
                const listNameInput = document.getElementById("listName").value;
                // Here, you can use JavaScript to send the listNameInput to your server via AJAX or any other method to add the list to your database.
                // Once the list is added successfully, you can clear the input and hide it.

                // Toggle the visibility of the button and input field after adding the list
                addListBtn.style.display = "block";
                addListInput.style.display = "none";
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Add a card button click event handler
            const addCardBtns = document.querySelectorAll(".add-card-btn");
            addCardBtns.forEach(function (addCardBtn) {
                addCardBtn.addEventListener("click", function () {
                    const addCardInput = addCardBtn.nextElementSibling;
                    // Toggle the visibility of the input field for the specific list
                    addCardBtn.style.display = "none";
                    addCardInput.style.display = "block";
                });
            });

            // Add card confirm button click event handler
            const addCardConfirmBtns = document.querySelectorAll(".add-card-confirm-btn");
            addCardConfirmBtns.forEach(function (addCardConfirmBtn) {
                addCardConfirmBtn.addEventListener("click", function () {
                    const cardNameInput = addCardConfirmBtn.previousElementSibling.value;
                    // Here, you can use JavaScript to send the cardNameInput to your server via AJAX
                    // or any other method to add the card to your database.
                    // You also need to identify the list ID associated with this card.

                    // After adding the card, you can reload or update the page to reflect the changes.

                    // Toggle the visibility of the button and input field after adding the card
                    const addCardBtn = addCardConfirmBtn.parentElement.querySelector(".add-card-btn");
                    const addCardInput = addCardBtn.nextElementSibling;
                    addCardBtn.style.display = "block";
                    addCardInput.style.display = "none";
                });
            });
        });
    </script>


    <script>
        // JavaScript to handle modal functionality
        var modal = document.getElementById('create-board-modal');
        var closeButton = document.getElementById('close-modal');
        var createBoardButton = document.getElementById('create-board-button');

        createBoardButton.addEventListener('click', function (event) {
            event.preventDefault(); // Prevent the default form submission behavior
            modal.style.display = 'block';
        });

        closeButton.addEventListener('click', function () {
            modal.style.display = 'none';
        });

        window.addEventListener('click', function (event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });

    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const userIcon = document.querySelector(".fa-user-circle");
            const userMenu = document.getElementById("user-menu");

            userIcon.addEventListener("click", function () {
                if (userMenu.style.display === "block") {
                    userMenu.style.display = "none";
                } else {
                    userMenu.style.display = "block";
                }
            });

            // Close the menu when clicking outside of it
            window.addEventListener("click", function (event) {
                if (event.target !== userIcon && event.target !== userMenu) {
                    userMenu.style.display = "none";
                }
            });
        });

    </script>
    <!-- Add this script at the end of your HTML body section -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const menuBtn = document.getElementById("menuBtn");
            const menu = document.getElementById("menu");

            menuBtn.addEventListener("click", function () {
                if (menu.style.display === "none" || menu.style.display === "") {
                    menu.style.display = "block";
                } else {
                    menu.style.display = "none";
                }
            });

            // Close the menu when clicking outside of it
            document.addEventListener("click", function (e) {
                if (e.target !== menuBtn && !menu.contains(e.target)) {
                    menu.style.display = "none";
                }
            });
        });
    </script>
</body>

</html>