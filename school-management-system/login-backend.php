<?php
// error_reporting(0); // Uncomment this once you are sure everything works
session_start();
$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    http_response_code(404);
    die();
} else {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        // Ensure this path is correct relative to this file
        include('owner_panel/fetch-data/config.php');

        if ($conn) {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            $sql = "SELECT id, role, password_hash FROM users WHERE email=?";
            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($result) {
                    $row = mysqli_fetch_assoc($result);

                    if ($row) {
                        // Check if typed password matches the hashed password in DB
                        if (password_verify($password, $row['password_hash'])) {
                            // Set Session Variables
                            $_SESSION['uid'] = $row['id'];
                            $_SESSION['role'] = $row['role']; // Crucial for verifyRoleRedirect.php

                            $response['status'] = 'success';
                            $response['role'] = $row['role'];

                            // Define the redirect location based on role
                            if ($row['role'] === 'student') {
                                $response['location'] = 'student_panel/index.php';
                            } elseif ($row['role'] === 'admin' || $row['role'] === 'owner') {
                                $response['location'] = 'owner_panel/index.php';
                            } elseif ($row['role'] === 'teacher') {
                                $response['location'] = 'teacher_panel/index.php';
                            } else {
                                $response['location'] = 'index.php'; // Default fallback
                            }

                        } else {
                            $response['status'] = 'error';
                            $response['message'] = 'Password mismatch for: ' . $email;
                        }
                    } else {
                        $response['status'] = 'error';
                        $response['message'] = 'User not found!';
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Error fetching result';
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error preparing statement';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Database connection error';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Both fields are required';
    }

    // Clear any accidental output before sending JSON
    if (ob_get_length()) ob_clean(); 
    header('Content-Type: application/json');
    echo json_encode($response);
}