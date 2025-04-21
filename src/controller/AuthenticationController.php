<?php

namespace Src\Controller;

use Src\Db\Database;
use Src\Middleware\Auth;
use Src\Model\UserModel;

class AuthenticationController extends BaseController
{
    public function create_account()
    {
        if (Auth::user()) redirect("dashboard");

        $error = "";
        $errorMessages = [];

        if (isPost()) {
            $firstname = cleanRequest($_POST['firstname']);
            $lastname = cleanRequest($_POST['lastname']);
            $username = cleanRequest($_POST['username']);
            $password = cleanRequest($_POST['password']);
            $retype_password = cleanRequest($_POST['retype-password']);
            $email = cleanRequest($_POST['email']);

            $isAllInputValid = true;

            // Check required fields
            if (empty($firstname)) {
                $errorMessages[] = "First name cannot be blank!";
                $isAllInputValid = false;
            } elseif (!preg_match('/^[a-zA-Z]+$/', $firstname)) {
                $errorMessages[] = "First name must only contain letters!";
                $isAllInputValid = false;
            }
            
            if (empty($lastname)) {
                $errorMessages[] = "Last name cannot be blank!";
                $isAllInputValid = false;
            } elseif (!preg_match('/^[a-zA-Z]+$/', $lastname)) {
                $errorMessages[] = "Last name must only contain letters!";
                $isAllInputValid = false;
            }
            

            if (empty($username)) {
                $errorMessages[] = "Username cannot be blank!";
                $isAllInputValid = false;
            } elseif (!preg_match('/^[a-zA-Z0-9._-]{3,30}$/', $username)) {
                $errorMessages[] = "Username must be 3–30 characters and use letters, numbers, dots, underscores, or hyphens.";
                $isAllInputValid = false;
            }

            if (empty($email)) {
                $errorMessages[] = "Email cannot be blank!";
                $isAllInputValid = false;
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errorMessages[] = "Invalid email format!";
                $isAllInputValid = false;
            }

            if (empty($password)) {
                $errorMessages[] = "Password cannot be blank!";
                $isAllInputValid = false;
            } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
                $errorMessages[] = "Password must be at least 8 characters and include uppercase, lowercase, number, and special character.";
                $isAllInputValid = false;
            }

            if ($password !== $retype_password) {
                $errorMessages[] = "Password mismatch!";
                $isAllInputValid = false;
            }

            $errors = [];

            foreach ($errorMessages as $msg) {
                if (str_contains($msg, "First name")) $errors['firstname'] = $msg;
                if (str_contains($msg, "Last name")) $errors['lastname'] = $msg;
                if (str_contains($msg, "Username")) $errors['username'] = $msg;
                if (str_contains($msg, "Email")) $errors['email'] = $msg;
                if (str_contains($msg, "Password") && !str_contains($msg, "mismatch")) $errors['password'] = $msg;
                if (str_contains($msg, "mismatch")) $errors['retype-password'] = $msg;
            }
            

            // If all good, insert user
            if ($isAllInputValid) {
                $hashed = password_hash($password, PASSWORD_DEFAULT);

                $user = new UserModel();

                if ($user->insert([
                    'first_name' => $firstname,
                    'last_name' => $lastname,
                    'username' => $username,
                    'email' => $email,
                    'password_hashed' => $hashed
                ])) {
                    Auth::authenticate_user($username, $password);
                    redirect("dashboard");
                } else {
                    $errors = "<p style='color:red;'>Account creation failed. Please try again.</p>";
                }
            }
        }

        $content = [
            "title" => "Create Account",
            "head" => "../src/views/default_head.php",
            "body" => "../src/views/auth/create-account.view.php",
            "errors" => $errors,
        ];

        $static = [
            "css" => ['css/global.css', 'css/theme.css', 'css/auth/auth.css'],
            "js"  => []
        ];

        render_page($content, $static);
    }

    public function login_account()
    {
        if (Auth::user()) redirect("dashboard");

        $error = "";
        $errorMessages = [];

        if (isPost()) {
            $username = cleanRequest($_POST['username']);
            $password = cleanRequest($_POST['password']);
            $isAllInputValid = true;

            if (empty($username)) {
                $errorMessages[] = "Username cannot be blank!";
                $isAllInputValid = false;
            }

            if (empty($password)) {
                $errorMessages[] = "Password cannot be blank!";
                $isAllInputValid = false;
            }

            if ($isAllInputValid) {
                if (Auth::authenticate_user($username, $password)) {
                    redirect("dashboard");
                } else {
                    $errorMessages[] = "Invalid username or password!";
                }
            }

            $error = join("<br>", array_map(fn($msg) => "<p style='color:red;'>$msg</p>", $errorMessages));
        }

        $content = [
            "title" => "Login Account",
            "head" => "../src/views/default_head.php",
            "body" => "../src/views/auth/login.view.php",
            "error" => $error
        ];

        $static = [
            "css" => ['css/global.css', 'css/theme.css', 'css/auth/auth.css'],
            "js"  => []
        ];

        render_page($content, $static);
    }

    public function logout()
    {
        Auth::logout();
        redirect("login_account_get");
    }
}
