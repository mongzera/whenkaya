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

        $errors = [
            'firstname' => '',
            'lastname' => '',
            'username' => '',
            'email' => '',
            'password' => '',
            'retype_password' => ''
        ];

        if (isPost()) {
            $firstname = cleanRequest($_POST['firstname']);
            $lastname = cleanRequest($_POST['lastname']);
            $username = cleanRequest($_POST['username']);
            $password = cleanRequest($_POST['password']);
            $retype_password = cleanRequest($_POST['retype-password']);
            $email = cleanRequest($_POST['email']);

            $isAllInputValid = true;

            // Validate inputs one by one and break on first error
            if (empty($firstname)) {
                $errors['firstname'] = "First name cannot be blank!";
                $isAllInputValid = false;
            } elseif (!preg_match('/^[a-zA-Z]+$/', $firstname)) {
                $errors['firstname'] = "First name must only contain letters!";
                $isAllInputValid = false;
            }

            if ($isAllInputValid && empty($lastname)) {
                $errors['lastname'] = "Last name cannot be blank!";
                $isAllInputValid = false;
            } elseif ($isAllInputValid && !preg_match('/^[a-zA-Z]+$/', $lastname)) {
                $errors['lastname'] = "Last name must only contain letters!";
                $isAllInputValid = false;
            }

            if ($isAllInputValid && empty($username)) {
                $errors['username'] = "Username cannot be blank!";
                $isAllInputValid = false;
            } elseif ($isAllInputValid && !preg_match('/^[a-zA-Z0-9._-]{3,30}$/', $username)) {
                $errors['username'] = "Username must use letters, numbers,<br>dots, underscores, or hyphens.";
                $isAllInputValid = false;
            }

            if ($isAllInputValid && empty($email)) {
                $errors['email'] = "Email cannot be blank!";
                $isAllInputValid = false;
            } elseif ($isAllInputValid && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Invalid email format!";
                $isAllInputValid = false;
            }

            if ($isAllInputValid && empty($password)) {
                $errors['password'] = "Password cannot be blank!";
                $isAllInputValid = false;
            } elseif ($isAllInputValid && !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
                $errors['password'] = "Password must be at least 8 characters<br>and include uppercase, lowercase,<br>number, and special character.";
                $isAllInputValid = false;
            }

            if ($isAllInputValid && $password !== $retype_password) {
                $errors['retype_password'] = "Password mismatch!";
                $isAllInputValid = false;
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
                    $errors['general'] = "Account creation failed. Please try again.";
                }
            }
        }

        $content = [
            "title" => "Create Account",
            "head" => "../src/views/default_head.php",
            "body" => "../src/views/auth/create-account.view.php",
            "errors" => $errors,
            "old" => $_POST ?? [] // Pass old input values back to form
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

        $errors = [
            'username' => '',
            'password' => '',
            'general' => ''
        ];

        if (isPost()) {
            $username = cleanRequest($_POST['username']);
            $password = cleanRequest($_POST['password']);
            $isAllInputValid = true;

            if (empty($username)) {
                $errors['username'] = "Username cannot be blank!";
                $isAllInputValid = false;
            }

            if ($isAllInputValid && empty($password)) {
                $errors['password'] = "Password cannot be blank!";
                $isAllInputValid = false;
            }

            if ($isAllInputValid) {
                if (Auth::authenticate_user($username, $password)) {
                    redirect("dashboard");
                } else {
                    $errors['general'] = "Invalid username or password!";
                }
            }
        }

        $content = [
            "title" => "Login Account",
            "head" => "../src/views/default_head.php",
            "body" => "../src/views/auth/login.view.php",
            "errors" => $errors,
            "old" => $_POST ?? []
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