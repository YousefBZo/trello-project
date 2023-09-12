<?php
ob_start();
session_start();
if (!isset($_SESSION['logged'])) {
    header('Location: login.php');
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
$user = new User($dbc);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= SITE_NAME ?>
    </title>

    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Include your custom CSS if needed -->
    <!-- <link rel="stylesheet" href="css/style.css"> -->

    <style>
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

        .search-form {
            display: inline-block;
        }

        .search-input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s ease-in-out;
            width: 200px;
            margin-right: 10px;
        }

        .search-input:focus {
            border-color: #007BFF;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .search-form button[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
        }

        .search-form button[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* CSS for the horizontal navigation bar */
        nav {
            background-color: slategray;
        }

        .nav ul.pipes {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
            /* Vertically center items */
        }

        .nav li {
            margin-right: 20px;
            /* Add spacing between navigation items */
        }

        .nav-link {
            text-decoration: none;
            color: white;
        }

        /* Style the search input */
        .search-input {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
        }

        /* Style the search button (if you have one) */
        .search-form button[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }

        /* Style the search button on hover */
        .search-form button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .user-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }


        .user-info p {
            margin: 10px 0;
            font-size: 18px;
        }

        .card-container {
            background-color: #f5f5f5;
            padding: 10px;
            border: 1px solid #ccc;
            margin: 10px;
            border-radius: 5px;
        }

        /* Style for the card title */
        .card-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <header class="header">
        <?php include_once 'partials/__nav.php'; ?>
    </header>

    <?php
    $userEmail = $_SESSION['email'];
    $users = $user->find(['email' => $userEmail]);
    $userName = $users[0]['name'];
    $userJob = $users[0]['job_name'];
    $userOrg = $users[0]['organization'];
    $userLoc = $users[0]['location'];
    $userBio = $users[0]['bio'];

    ?>
    <!-- User Information Section -->
    <div class="container mt-4">
        <div class="user-info">
            <div class="d-flex align-items-center">

                <div class="user-details ml-3">
                    <p>
                        <?= $userEmail ?>
                    </p>
                    <p>
                        <?= $userName ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header p-2">
            <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link" href="#profile" data-toggle="tab" style="color:black;">Profile
                        and visibility</a></li>
                <li class="nav-item"><a class="nav-link" href="#cards" data-toggle="tab" style="color:black;">
                        Cards</a></li>



            </ul>
        </div><!-- /.card-header -->
        <div class="card-body">
            <div class="tab-content">
                <!-- Activity Tab -->
                <div class="tab-pane" id="profile">
                    <div class="user-profile-form">
                        <h2>Edit Profile</h2>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="fullName">Full Name</label>
                                <input type="text" class="form-control" id="fullName" name="fullName"
                                    placeholder="<?= $userName ?>">
                            </div>
                            <div class="form-group">
                                <label for="jobTitle">Job Title</label>
                                <input type="text" class="form-control" id="jobTitle" name="jobTitle"
                                    placeholder="<?= $userJob ?>">
                            </div>
                            <div class="form-group">
                                <label for="organization">Organization</label>
                                <input type="text" class="form-control" id="organization" name="organization"
                                    placeholder="<?= $userOrg ?>">
                            </div>
                            <div class="form-group">
                                <label for="location">Location</label>
                                <input type="text" class="form-control" id="location" name="location"
                                    placeholder="<?= $userLoc ?>">
                            </div>
                            <div class="form-group">
                                <label for="bio">Bio</label>
                                <textarea class="form-control" id="bio" name="bio" rows="4"
                                    placeholder="<?= $userBio ?>"></textarea>
                            </div>
                            <button type="submit" name="update" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
                <div class="tab-pane" id="cards">
                    <?php
                    $card = new Card($dbc);
                    $allCards = $card->find(['user_id' => $_SESSION['user_id']]);
                    foreach ($allCards as $cards) {
                        ?>
                        <div class="card-title">
                            Card:
                            <?= $cards['title'] ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div><!-- /.tab-content -->
        </div><!-- /.card-body -->
        <?php
        if (isset($_POST['update'])) {
            $name = $user->checkFields($_POST['fullName']);
            $jobTitle = $user->checkFields($_POST['jobTitle']);
            $organization = $user->checkFields($_POST['organization']);
            $location = $user->checkFields($_POST['location']);
            $bioInput = $user->checkFields($_POST['bio']);

            $values = [
                'name' => $name,
                'job_name' => $jobTitle,
                'organization' => $organization,
                'location' => $location,
                'bio' => $bioInput,
            ];

            if ($user->update($values, ['email' => $userEmail])) {
                header("Location:{$_SERVER['PHP_SELF']}");
            }
        }
        $msg->display();
        ?>

    </div>

    <!-- Include jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Include Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Include your custom JavaScript if needed -->
    <!-- <script src="js/custom.js"></script> -->
</body>

</html>