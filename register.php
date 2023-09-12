<?php
ob_start();
session_start();
if (isset($_SESSION['logged'])) {
    header('Location:index.php');
    exit();
}
require_once 'include/class_autoloader.inc.php';
require_once 'include/config.inc.php';
require_once 'include/database.inc.php';
require 'include/vendor/plasticbrain/php-flash-messages/src/FlashMessages.php';
$msg = new \Plasticbrain\FlashMessages\FlashMessages();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Registration Form</title>
</head>

<body>
    <section class="vh-100" style="background-color: #eee;">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-12 col-xl-11">
                    <div class="card text-black" style="border-radius: 25px;">
                        <div class="card-body p-md-5">
                            <div class="row justify-content-center">
                                <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                                    <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign up</p>

                                    <form class="mx-1 mx-md-4" action="" method="post">
                                        <?php
                                        if (isset($_POST['register'])) {
                                            $db = DbConnect::getInstance();
                                            $dbc = DbConnect::getConnection();
                                            $user = new User($dbc);
                                            $name = $user->checkFields($_POST['name']);
                                            $email = $user->checkFields($_POST['email']);
                                            $password = $user->checkFields($_POST['password']);
                                            $confirmPassword = $user->checkFields($_POST['confirmPassword']);
                                            $validation = new Validation();
                                            if (
                                                !$validation->addRule(new ValidateEmptyFields())
                                                    ->validate($name)
                                            ) {
                                                $allErrors[] = $validation->getErrorMessages();
                                            }
                                            if (
                                                !$validation->addRule(new ValidateEmptyFields())
                                                    // ->addRule(new ValidateEmail())
                                                    ->validate($email)
                                            ) {
                                                $allErrors[] = $validation->getErrorMessages();

                                            }
                                            if (
                                                !$validation->addRule(new ValidateEmptyFields())
                                                    ->addRule(new ValidateMinimum(6))
                                                    ->addRule(new ValidateMaximum(20))
                                                    ->addRule(
                                                        new ValidatePassword(
                                                            $password,
                                                            $confirmPassword
                                                        )
                                                    )
                                                    ->validate($password)
                                            ) {
                                                $allErrors[] = $validation->getErrorMessages();

                                            }
                                            if (empty($allErrors)) {
                                                $hashPassword = password_hash($password, PASSWORD_DEFAULT);
                                                $values = [
                                                    null,
                                                    // id
                                                    $name,
                                                    // name
                                                    $email,
                                                    // email
                                                    $hashPassword,
                                                    // password
                                                    0,
                                                    // is_admin (assuming 0 is not an admin)
                                                    0,
                                                    // is_banned
                                                    1,
                                                    // is_active (assuming 1 is active)
                                                    date('Y-m-d H:i:s'),
                                                    // created_at (you might want to set this to the current timestamp)
                                                    date('Y-m-d H:i:s'),
                                                    // updated_at
                                                    $_SERVER['REMOTE_ADDR']
                                                    ,
                                                    // ip_address
                                                    "",
                                                    // bio
                                                    ""
                                                ];
                                                if ($user->addIntoDb($values)) {
                                                    header('Location:login.php');
                                                    exit();
                                                }


                                            } else {
                                                $i = 0;
                                                foreach ($allErrors as $error) {
                                                    $msg->error("{$error[$i++]}");
                                                }
                                            }

                                        }
                                        $msg->display();
                                        ?>

                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                            <div class="form-outline flex-fill mb-0">
                                                <input type="text" name="name" id="form3Example1c"
                                                    class="form-control" />
                                                <label class="form-label" for="form3Example1c">Your Name</label>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                                            <div class="form-outline flex-fill mb-0">
                                                <input type="email" name="email" id="form3Example3c"
                                                    class="form-control" />
                                                <label class="form-label" for="form3Example3c">Your Email</label>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                            <div class="form-outline flex-fill mb-0">
                                                <input type="password" id="form3Example4c" name="password"
                                                    class="form-control" />
                                                <label class="form-label" for="form3Example4c">Password</label>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                                            <div class="form-outline flex-fill mb-0">
                                                <input type="password" id="form3Example4cd" name="confirmPassword"
                                                    class="form-control" />
                                                <label class="form-label" for="form3Example4cd">Repeat your
                                                    password</label>
                                            </div>
                                        </div>

                                        <div class="g-recaptcha ml-3" data-sitekey="<?= SITE_KEY ?>"></div>
                                        <br>

                                        <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                            <button type="submit" class="btn btn-primary btn-lg"
                                                name="register">Register</button>
                                        </div>

                                    </form>

                                </div>
                                <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">

                                    <img src="images/draw1.webp" class="img-fluid" alt="Sample image">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        function submitForm() {
            const name = document.getElementById('formName').value;
            // Retrieve other form field values similarly
            console.log('Name:', name);
            // Perform further actions (e.g., validation, AJAX submission)
        }
    </script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</body>

</html>
<?PHP ob_end_flush(); ?>