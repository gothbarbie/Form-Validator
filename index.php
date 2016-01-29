<?php
    require_once '../error-handler/ErrorHandler.php';
    require_once '../validator/Validator.php';
    require_once 'FormValidator.php';

    $errorHandler = new ErrorHandler;
    $errorHandler->addError('Oops, that is not a valid username.', 'username');
    $errorHandler->addError('Oops, that is not an valid email.', 'email');
    $errorHandler->addError('Oops, that is not a valid password.', 'password');

    if (!empty($_POST)) {
        $formValidator = new FormValidator($errorHandler);

        $validation = $formValidator->check($_POST, [
            'username' => [
                'required' => true,
                'maxLength' => 20,
                'minLength' => 5,
                'alphaNumeric' => true
            ],
            'email' => [
                'required' => true,
                'maxLength' => 255,
                'rmail' => true
            ],
            'password' => [
                'required' => true,
                'minLength' => 6
            ],
            'password_again' => [
                'matches' => 'password'
            ]
        ]);

        if ($validation->failed()) {
            echo '<pre>' , print_r($validation->errors()->all()), '</pre>';
        }

    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test Form</title>
</head>
<body>
    <form action="index.php" method="post">
        <div>
            Username: <input type="text" name="username">
        </div>
        <div>
            E-mail: <input type="text" name="email">
        </div>
        <div>
            Password: <input type="password" name="password">
        </div>
        <div>
            Password again: <input type="password" name="password_again">
        </div>
        <div>
            <input type="submit">
        </div>
    </form>
</body>
</html>
