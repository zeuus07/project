<?php
session_start();

// Create connection
$connection = new mysqli("localhost", "root", "", "sse");
if (isset($_SESSION['username'])) {
  // Display the username
  echo 'Welcome, ' . htmlspecialchars($_SESSION['username']) . '!';
} else {
  // If the username is not set, display a default message or redirect to the login page
  echo 'Welcome, Guest!';
}
// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
} else {
    
}
// Query to fetch all products from the database
$query = "SELECT * FROM products";
$result = mysqli_query($connection, $query);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}

// Define variables and initialize with empty values
$username = $email = $password = $confirm_password = "";
$username_err = $email_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modal_source']) && $_POST['modal_source'] === 'signup') {

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM sse_users WHERE username = ?";

        if ($stmt = $connection->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before inserting into database
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO sse_users (username, email, password) VALUES (?, ?, ?)";

        if ($stmt = $connection->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sss", $param_username, $param_email, $param_password);

            // Set parameters
            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to login page
                header("location: index.php");
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
  }
    // Close connection
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Check if the request comes from the sign-in modal
  if (isset($_POST['from_modal']) && $_POST['from_modal'] == 'sign_in') {
      // Get the username/email and password from the form
      $username_or_email = $_POST["username_or_email"];
      $password = $_POST["password"];

      // Perform validation (e.g., check if fields are not empty)

      // Authenticate the user (e.g., check credentials against database)
      // Assuming you have a database connection established

      // Check connection (replace with your database connection code)
      $connection = new mysqli("localhost", "root", "", "sse");
      if ($connection->connect_error) {
          die("Connection failed: " . $connection->connect_error);
      }

      // Prepare SQL statement to fetch user with provided username/email
      $sql = "SELECT id, username, email, password FROM sse_users WHERE username = ? OR email = ?";
      if ($stmt = $connection->prepare($sql)) {
          // Bind variables to the prepared statement as parameters
          $stmt->bind_param("ss", $username_or_email, $username_or_email);

          // Attempt to execute the prepared statement
          if ($stmt->execute()) {
              // Store result
              $stmt->store_result();

              // Check if username/email exists
              if ($stmt->num_rows == 1) {
                  // Bind result variables
                  $stmt->bind_result($id, $username, $email, $hashed_password);
                  if ($stmt->fetch()) {
                      // Verify password
                      if (password_verify($password, $hashed_password)) {
                          // Password is correct, store user data in session variables
                          $_SESSION["id"] = $id;
                          $_SESSION["username"] = $username;
                          $_SESSION["email"] = $email;

                          // Redirect user to dashboard or any other page
                          header("location: index.php");
                          exit;
                      } else {
                          // Password is incorrect
                          $error = "Invalid password.";
                      }
                  }
              } else {
                  // Username/email not found
                  $error = "Invalid username/email.";
              }
          } else {
              echo "Oops! Something went wrong. Please try again later.";
          }

          // Close statement
          $stmt->close();
      }

      // Close connection
    
  }
}

?>
<!DOCTYPE html>
<html>

<head>
  <style>
    .shop-image {
    width: 50px; /* Adjust the width as needed */
    height: auto; /* Maintain aspect ratio */
}

    </style>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <link rel="shortcut icon" href="images/sselogo.png" type="">

  <title> Shree Sai Enterprises</title>

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!--owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <!-- nice select  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha512-CruCP+TD3yXzlvvijET8wV5WxxEh5H8P4cmz0RFbKK6FlZ2sYl3AEsKlLPHbniXKSrDdFewhbmBK5skbdsASbQ==" crossorigin="anonymous" />
  <!-- font awesome style -->
  <link href="css/font-awesome.min.css" rel="stylesheet" />

  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />
<!-- jQery -->
    <script src="js/jquery-3.4.1.min.js"></script>
  <script>
    $(function () {
      //this will get the full URL at the address bar
      var url = window.location.href;

      // passes on every "a" tag
      $("#navbarSupportContent a").each(function () {
        // checks if its the same on the address bar
        if (url == (this.href)) {
          $(this).closest("li").addClass("active");
        }
      })

      
    });
  </script>
<style>
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            max-width: 350px;
            background: #F8F9FD;
            background: linear-gradient(0deg, rgb(255, 255, 255) 0%, rgb(244, 247, 251) 100%);
            border-radius: 40px;
            padding: 25px 35px;
            border: 5px solid rgb(255, 255, 255);
            box-shadow: rgba(133, 189, 215, 0.8784313725) 0px 30px 30px -20px;
            margin: 20px auto;
        }

        .heading {
            text-align: center;
            font-weight: 900;
            font-size: 30px;
            color: rgb(16, 137, 211);
        }

        .form .input {
            width: 100%;
            background: white;
            border: none;
            padding: 15px 20px;
            border-radius: 20px;
            margin-top: 15px;
            box-shadow: #cff0ff 0px 10px 10px -5px;
            border-inline: 2px solid transparent;
        }

        .form .input::-moz-placeholder {
            color: rgb(170, 170, 170);
        }

        .form .input::placeholder {
            color: rgb(170, 170, 170);
        }

        .form .input:focus {
            outline: none;
            border-inline: 2px solid #12B1D1;
        }

        .form .forgot-password {
            display: block;
            margin-top: 10px;
            margin-left: 10px;
        }

        .form .forgot-password a {
            font-size: 11px;
            color: #0099ff;
            text-decoration: none;
        }

        .form .login-button {
            display: block;
            width: 100%;
            font-weight: bold;
            background: linear-gradient(45deg, rgb(16, 137, 211) 0%, rgb(18, 177, 209) 100%);
            color: white;
            padding-block: 15px;
            margin: 20px auto;
            border-radius: 20px;
            box-shadow: rgba(133, 189, 215, 0.8784313725) 0px 20px 10px -15px;
            border: none;
            transition: all 0.2s ease-in-out;
        }

        .form .login-button:hover {
            transform: scale(1.03);
            box-shadow: rgba(133, 189, 215, 0.8784313725) 0px 23px 10px -20px;
        }

        .form .login-button:active {
            transform: scale(0.95);
            box-shadow: rgba(133, 189, 215, 0.8784313725) 0px 15px 10px -10px;
        }

        .social-account-container {
            margin-top: 25px;
        }

        .social-account-container .title {
            display: block;
            text-align: center;
            font-size: 10px;
            color: rgb(170, 170, 170);
        }

        .social-account-container .social-accounts {
            width: 100%;
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 5px;
        }

        .social-account-container .social-accounts .social-button {
            background: linear-gradient(45deg, rgb(0, 0, 0) 0%, rgb(112, 112, 112) 100%);
            border: 5px solid white;
            padding: 5px;
            border-radius: 50%;
            width: 40px;
            aspect-ratio: 1;
            display: grid;
            place-content: center;
            box-shadow: rgba(133, 189, 215, 0.8784313725) 0px 12px 10px -8px;
            transition: all 0.2s ease-in-out;
        }

        .social-account-container .social-accounts .social-button .svg {
            fill: white;
            margin: auto;
        }

        .social-account-container .social-accounts .social-button:hover {
            transform: scale(1.2);
        }

        .social-account-container .social-accounts .social-button:active {
            transform: scale(0.9);
        }

        .agreement {
            display: block;
            text-align: center;
            margin-top: 15px;
        }

        .agreement a {
            text-decoration: none;
            color: #0099ff;
            font-size: 9px;
        }

    </style>
</head>

<body>
  <!-- Sign In Modal -->
  <div id="signInModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('signInModal')">&times;</span>
        <div class="container">
            <div class="heading">Sign In</div>
            <form action="index.php" method="POST" class="form">
                <!-- Username/Email input -->
                <input type="hidden" name="from_modal" value="sign_in">

                <input required="" class="input" type="text" name="username_or_email" id="username_or_email" placeholder="Username or E-mail">
                <!-- Password input -->
                <input required="" class="input" type="password" name="password" id="password" placeholder="Password">
                <span class="forgot-password"><a href="#">Forgot Password ?</a></span>
                <input class="login-button" type="submit" value="Sign In">
            </form>
            <div class="social-account-container">
                <span class="title">Or Sign in with</span>
                <div class="social-accounts">
                    <button class="social-button google">
                        <svg class="svg" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 488 512">
                            <path d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z"></path>
                        </svg>
                    </button>
                    <button class="social-button apple">
                        <svg class="svg" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 384 512">
                            <path d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141.2 4 184.8 4 273.5q0 39.3 14.4 81.2c12.8 36.7 59 126.7 107.2 125.2 25.2-.6 43-17.9 75.8-17.9 31.8 0 48.3 17.9 76.4 17.9 48.6-.7 90.4-82.5 102.6-119.3-65.2-30.7-61.7-90-61.7-91.9zm-56.6-164.2c27.3-32.4 24.8-61.9 24-72.5-24.1 1.4-52 16.4-67.9 34.9-17.5 19.8-27.8 44.3-25.6 71.9 26.1 2 49.9-11.4 69.5-34.3z"></path>
                        </svg>
                    </button>
                    <button class="social-button twitter">
                        <svg class="svg" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                            <path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <span class="agreement"><a href="#">Learn user licence agreement</a></span>
            <div class="signup-option">
                Don't have an account? <a href="#" onclick="openSignUpModalAndCloseSignIn()">Sign Up</a>
            </div>
        </div>
    </div>
</div>


    <!-- Sign Up Modal -->
    <div id="signUpModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('signUpModal')">&times;</span>
        <div class="container">
            <div class="heading">Sign Up</div>
            <!-- Sign up form -->
            <form action="#" method="POST" class="form">
            <input type="hidden" name="modal_source" value="signup">
                <!-- Username input -->
                <input required="" class="input" type="text" name="username" id="username" placeholder="Username">
                <!-- Email input -->
                <input required="" class="input" type="email" name="email" id="email" placeholder="E-mail">
                <!-- Password input -->
                <input required="" class="input" type="password" name="password" id="password" placeholder="Password">
                <!-- Confirm password input -->
                <input required="" class="input" type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password">
                <!-- Additional sign-up fields can be added here -->
                <input class="login-button" type="submit" value="Sign Up">
            </form>
            <!-- Agreement section -->
            <div class="agreement">
                By signing up, you agree to our <a href="#">Terms and Conditions</a>.
            </div>
        </div>
    </div>
</div>
<!-- Cart Up Modal -->
<div id="shoppingCartModal" class="modal">
  <div >
    <span class="close" onclick="closeModal('shoppingCartModal')">&times;</span>
    <div class="shopping-cart">
      <!-- Title -->
      <div class="title">
        Shopping Bag
      </div>
  
      
    </div>
  </div>
</div>


  <div class="hero_area">
    <div class="bg-box">
      <img src="images/mainbg.jpg" alt="">
    </div>
    <!-- header section strats -->
    <header class="header_section">
      <div class="container">
        <nav class="navbar navbar-expand-lg custom_nav-container ">
          <a class="navbar-brand" href="index.html">
            <span>
              Shree Sai Enterprises
            </span>
          </a>

          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class=""> </span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav  mx-auto ">
              <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
              </li>
              <li class="nav-item">
              <a class="nav-link" href="javascript:void(0)" onclick="showProducts()">Products</a>

              </li>
              <li class="nav-item">
              <a class="nav-link" href="#" id="aboutLink">About</a>
              </li>
              <li class="nav-item">
              <a class="nav-link" href="#" id="contactLink">Contact</a>
              </li>
            </ul>
            <div class="user_option">
            <a href="#" class="user_link" onclick="openModal('signInModal')">Login</a>
                <i class="fa fa-user" aria-hidden="true"></i>
              </a>
              <a href="shopping_cart.php" class="cart_link">View Cart</a>


                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 456.029 456.029" style="enable-background:new 0 0 456.029 456.029;" xml:space="preserve">
                  <g>
                    <g>
                      <path d="M345.6,338.862c-29.184,0-53.248,23.552-53.248,53.248c0,29.184,23.552,53.248,53.248,53.248
                   c29.184,0,53.248-23.552,53.248-53.248C398.336,362.926,374.784,338.862,345.6,338.862z" />
                    </g>
                  </g>
                  <g>
                    <g>
                      <path d="M439.296,84.91c-1.024,0-2.56-0.512-4.096-0.512H112.64l-5.12-34.304C104.448,27.566,84.992,10.67,61.952,10.67H20.48
                   C9.216,10.67,0,19.886,0,31.15c0,11.264,9.216,20.48,20.48,20.48h41.472c2.56,0,4.608,2.048,5.12,4.608l31.744,216.064
                   c4.096,27.136,27.648,47.616,55.296,47.616h212.992c26.624,0,49.664-18.944,55.296-45.056l33.28-166.4
                   C457.728,97.71,450.56,86.958,439.296,84.91z" />
                    </g>
                  </g>
                  <g>
                    <g>
                      <path d="M215.04,389.55c-1.024-28.16-24.576-50.688-52.736-50.688c-29.696,1.536-52.224,26.112-51.2,55.296
                   c1.024,28.16,24.064,50.688,52.224,50.688h1.024C193.536,443.31,216.576,418.734,215.04,389.55z" />
                    </g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                </svg>
              </a>
              <form class="form-inline">
                
              </form>
              <a href="admin.php" >
                Admin
              </a>
            </div>
          </div>
        </nav>
      </div>
    </header>
    <!-- end header section -->
    <!-- slider section -->
    <section class="slider_section ">
      <div id="customCarousel1" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="container ">
              <div class="row">
                <div class="col-md-7 col-lg-6 ">
                  <div class="detail-box">
                    <h1>
                      Miniral Water
                    </h1>
                    <p>
                      Mineral Water is definitely one of the healthiest kinds of bottled water and <br> drinking it can enhance your well-being. This water is rich in minerals such as magnesium, calcium and iron. It is free of preservatives or any type of added chemicals. Those who are planning to lose weight should switch to mineral water as it does not contain harmful Composition present in soda and cold drinks. 
                    </p>
                    <div class="btn-box">
    <a href="#" class="btn1" id="orderNowButton">Order Now</a>
</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="carousel-item ">
            <div class="container ">
              <div class="row">
                <div class="col-md-7 col-lg-6 ">
                  <div class="detail-box">
                    <h1>
                      Soft Drinks
                    </h1>
                    <p>
                     We provide best Soft Drinks which are chemical free and safe for drinking
                    </p>
                    <div class="btn-box">
    <a href="#" class="btn1" id="orderNowButton">Order Now</a>
</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="container ">
              <div class="row">
                <div class="col-md-7 col-lg-6 ">
                  <div class="detail-box">
                    <h1>
                      Sodas
                    </h1>
                    <p>
                      This carbonated water with soda relaxes your stomach and relaxes the body <br>from gastric uneasiness. This refreshing drink has a perfect blend soda, <br> which makes it more rejuvenating. A perfect drink to enjoy in summers.
                    </p>
                    <div class="btn-box">
    <a href="#" class="btn1" id="orderNowButton">Order Now</a>
</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="container">
          <ol class="carousel-indicators">
            <li data-target="#customCarousel1" data-slide-to="0" class="active"></li>
            <li data-target="#customCarousel1" data-slide-to="1"></li>
            <li data-target="#customCarousel1" data-slide-to="2"></li>
          </ol>
        </div>
      </div>

    </section>
    <!-- end slider section -->
  </div>

  <!-- offer section -->

  <section class="offer_section layout_padding-bottom">
    <div class="offer_container">
      <div class="container ">
        <div class="row">
          <div class="col-md-6  ">
            <div class="box ">
              <div class="img-box">
                <img src="images/o2.jpg" alt="">
              </div>
              <div class="detail-box">
                <h5>
                  Thirsty Thursdays
                </h5>
                <h6>
                  <span>20%</span> Off
                </h6>
                <a href="">
                  Order Now <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 456.029 456.029" style="enable-background:new 0 0 456.029 456.029;" xml:space="preserve">
                    <g>
                      <g>
                        <path d="M345.6,338.862c-29.184,0-53.248,23.552-53.248,53.248c0,29.184,23.552,53.248,53.248,53.248
                     c29.184,0,53.248-23.552,53.248-53.248C398.336,362.926,374.784,338.862,345.6,338.862z" />
                      </g>
                    </g>
                    <g>
                      <g>
                        <path d="M439.296,84.91c-1.024,0-2.56-0.512-4.096-0.512H112.64l-5.12-34.304C104.448,27.566,84.992,10.67,61.952,10.67H20.48
                     C9.216,10.67,0,19.886,0,31.15c0,11.264,9.216,20.48,20.48,20.48h41.472c2.56,0,4.608,2.048,5.12,4.608l31.744,216.064
                     c4.096,27.136,27.648,47.616,55.296,47.616h212.992c26.624,0,49.664-18.944,55.296-45.056l33.28-166.4
                     C457.728,97.71,450.56,86.958,439.296,84.91z" />
                      </g>
                    </g>
                    <g>
                      <g>
                        <path d="M215.04,389.55c-1.024-28.16-24.576-50.688-52.736-50.688c-29.696,1.536-52.224,26.112-51.2,55.296
                     c1.024,28.16,24.064,50.688,52.224,50.688h1.024C193.536,443.31,216.576,418.734,215.04,389.55z" />
                      </g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                  </svg>
                </a>
              </div>
            </div>
          </div>
          <div class="col-md-6  ">
            <div class="box ">
              <div class="img-box">
                <img src="images/o1.jpg" alt="">
              </div>
              <div class="detail-box">
                <h5>
                  Soft Offers
                </h5>
                <h6>
                  <span>15%</span> Off
                </h6>
                <a href="">
                  Order Now <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 456.029 456.029" style="enable-background:new 0 0 456.029 456.029;" xml:space="preserve">
                    <g>
                      <g>
                        <path d="M345.6,338.862c-29.184,0-53.248,23.552-53.248,53.248c0,29.184,23.552,53.248,53.248,53.248
                     c29.184,0,53.248-23.552,53.248-53.248C398.336,362.926,374.784,338.862,345.6,338.862z" />
                      </g>
                    </g>
                    <g>
                      <g>
                        <path d="M439.296,84.91c-1.024,0-2.56-0.512-4.096-0.512H112.64l-5.12-34.304C104.448,27.566,84.992,10.67,61.952,10.67H20.48
                     C9.216,10.67,0,19.886,0,31.15c0,11.264,9.216,20.48,20.48,20.48h41.472c2.56,0,4.608,2.048,5.12,4.608l31.744,216.064
                     c4.096,27.136,27.648,47.616,55.296,47.616h212.992c26.624,0,49.664-18.944,55.296-45.056l33.28-166.4
                     C457.728,97.71,450.56,86.958,439.296,84.91z" />
                      </g>
                    </g>
                    <g>
                      <g>
                        <path d="M215.04,389.55c-1.024-28.16-24.576-50.688-52.736-50.688c-29.696,1.536-52.224,26.112-51.2,55.296
                     c1.024,28.16,24.064,50.688,52.224,50.688h1.024C193.536,443.31,216.576,418.734,215.04,389.55z" />
                      </g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end offer section -->

  <!-- food section -->

  <section id="products_section" class="food_section layout_padding-bottom">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>
          Our Products
        </h2>
      </div>

      <ul class="filters_menu">
  <li class="active" data-filter="*">All</li>
  <li data-filter=".MineralWater">Mineral Water</li>
  <li data-filter=".SoftDrinks">Soft Drinks</li>
  <li data-filter=".EnergyDrinks">Energy Drinks</li>
  <li data-filter=".Soda">Soda</li>
</ul>

<div class="filters-content">
    <div class="row grid">
        <?php
        // Loop through each row in the result set and display product information
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <div class="col-sm-6 col-lg-4 all <?php echo str_replace(' ', '', $row['category']); ?>">

                <div class="box">
                    <div>
                        <div class="img-box">
                            <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                        </div>
                        <div class="detail-box">
                            <h5>
                                <?php echo $row['name']; ?>
                            </h5>
                            <p>
                                <?php echo $row['description']; ?>
                            </p>
                            <div class="options">
                                <h6>
                                    ₹<?php echo $row['price']; ?>
                                </h6>
                                <!-- Shop image with onclick event -->
                                <div class="shop-image-container" onclick="addToCart(<?php echo $row['id']; ?>)">
    <img src="images/shop.png" alt="Shop Image" class="shop-image">
</div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</div>

<?php
// Close the database connection
mysqli_close($connection);
?>
      <div class="btn-box">
        <a href="">
          View More
        </a>
      </div>
    </div>
      </div>
      </div>
  </section>
  

  <!-- end food section -->

  <!-- about section -->

  <section id="about_section" class="about_section">
    <div class="container  ">

      <div class="row">
        <div class="col-md-6 ">
          <div class="img-box">
            <img src="images/about-img.png" alt="">
          </div>
        </div>
        <div class="col-md-6">
          <div class="detail-box">
            <div class="heading_container">
              <h2>
                Shree Sai Enterprises
              </h2>
            </div>
            <p>
              A water delivery service SHREE SAI ENTERPRISES serves the purpose of providing convenient and reliable access to clean and safe drinking water as well as soft drinks. The specific objective and benefit of such a service can vary depending on the target market and the unique feature offered by the service provider. User can order water bottles, mini bottles and cold drinks in bulk or individual easily from anywhere and anytime.
            </p>
            <a href="">
              Read More
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end about section -->

  <!-- book section -->
  <section class="book_section layout_padding" id="contactSection">
    <div class="container">
        <div class="heading_container">
            <h2>Contact Us</h2>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form_container">
                <form id="contactForm">

                        <div>
                            <input type="text" class="form-control" name="name" placeholder="Your Name" required />
                        </div>
                        <div>
                            <input type="text" class="form-control" name="phone_number" placeholder="Phone Number" required />
                        </div>
                        <div>
                            <input type="email" class="form-control" name="email" placeholder="Your Email" required />
                        </div>
                        <div>
                            <input type="text" class="form-control" name="message" placeholder="Your Message" required>
                        </div>
                        <div class="btn_box">
                            <button type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>

   


        
        <div class="col-md-6">
        <div class="map_container">
        <iframe
            width="600"
            height="450"
            style="border:0"
            loading="lazy"
            allowfullscreen
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d235.79501403696509!2d73.11843195828382!3d18.98795943471957!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7e992fbca2cab%3A0xd97cf9f33058a6cc!2sShree%20Sai%20Enterprises%20Mineral%20Water!5e0!3m2!1sen!2sin!4v1713203848029!5m2!1sen!2sin"
        ></iframe>
    </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end book section -->

  <!-- client section -->

  <section class="client_section layout_padding-bottom">
    <div class="container">
      <div class="heading_container heading_center psudo_white_primary mb_45">
        <h2>
          What Says Our Customers
        </h2>
      </div>
      <div class="carousel-wrap row ">
        <div class="owl-carousel client_owl-carousel">
          <div class="item">
            <div class="box">
              <div class="detail-box">
                <p>
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam
                </p>
                <h6>
                  Moana Michell
                </h6>
                <p>
                  magna aliqua
                </p>
              </div>
              <div class="img-box">
                <img src="images/client1.jpg" alt="" class="box-img">
              </div>
            </div>
          </div>
          <div class="item">
            <div class="box">
              <div class="detail-box">
                <p>
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam
                </p>
                <h6>
                  Mike Hamell
                </h6>
                <p>
                  magna aliqua
                </p>
              </div>
              <div class="img-box">
                <img src="images/client2.jpg" alt="" class="box-img">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end client section -->

  <!-- footer section -->
  <footer class="footer_section">
    <div class="container">
      <div class="row">
        <div class="col-md-4 footer-col">
          <div class="footer_contact">
            <h4>
              Contact Us
            </h4>
            <div class="contact_link_box">
              <a href="">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                <span>
                  Location
                </span>
              </a>
              <a href="">
                <i class="fa fa-phone" aria-hidden="true"></i>
                <span>
                  Call +01 1234567890
                </span>
              </a>
              <a href="">
                <i class="fa fa-envelope" aria-hidden="true"></i>
                <span>
                  demo@gmail.com
                </span>
              </a>
            </div>
          </div>
        </div>
        <div class="col-md-4 footer-col">
          <div class="footer_detail">
            <a href="" class="footer-logo">
              Follow Us
            </a>
            <p>
              Follow us on our social media accouts to contact us and stay updated.
            </p>
            <div class="footer_social">
              <a href="">
                <i class="fa fa-facebook" aria-hidden="true"></i>
              </a>
              <a href="">
                <i class="fa fa-twitter" aria-hidden="true"></i>
              </a>
              <a href="">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
              </a>
              <a href="">
                <i class="fa fa-instagram" aria-hidden="true"></i>
              </a>
              <a href="">
                <i class="fa fa-pinterest" aria-hidden="true"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="col-md-4 footer-col">
          <h4>
            Opening Hours
          </h4>
          <p>
            Everyday
          </p>
          <p>
            10.00 Am -10.00 Pm
          </p>
        </div>
      </div>
      <div class="footer-info">
        <p>
          &copy; <span id="displayYear"></span> All Rights Reserved By
          <a href="https://html.design/">ShreeSaiEnterprises</a>
        </p>
      </div>
    </div>
  </footer>
  <!-- footer section -->

  <!-- jQery -->
  <!-- <script src="js/jquery-3.4.1.min.js"></script> -->
  <!-- popper js -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
  </script>
  <!-- bootstrap js -->
  <script src="js/bootstrap.js"></script>
  <!-- owl slider -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
  </script>
  <!-- isotope js -->
  <script src="https://unpkg.com/isotope-layout@3.0.4/dist/isotope.pkgd.min.js"></script>
  <!-- nice select -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"></script>
  <!-- custom js -->
  <script src="js/custom.js"></script>
  <!-- Google Map -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap">
    
  </script>
    <script>
        function initMap() {
        // Set the center coordinates for the map
        const center = { lat: -25.344, lng: 131.031 };

        // Create a new map inside the div element with id="map"
        map = new google.maps.Map(document.getElementById("map"), {
          center: center,
          zoom: 4,
        });

        // Add a marker at the specified coordinates
        const marker = new google.maps.Marker({
          position: center,
          map: map,
          title: "Shree Sai Enterprises",
        });
      }
    </script>
  <!-- End Google Map -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js"></script>
  <script>
  $(document).ready(function(){
    // Initialize Isotope
    var $grid = $('.grid').isotope({
      itemSelector: '.grid-item',
      layoutMode: 'fitRows'
    });

    // Filter items on button click
    $('.filters_menu').on( 'click', 'li', function() {
      var filterValue = $(this).attr('data-filter');
      $grid.isotope({ filter: filterValue });
      $('.filters_menu li').removeClass('active');
      $(this).addClass('active');
    });
  });
</script>

</body>

</html>
<script>
    function showProducts() {
        // Get a reference to the products section
        var productsSection = document.getElementById('products_section');

        // Scroll to the products section
        productsSection.scrollIntoView({ behavior: 'smooth' });
    }
</script>
<script>
    function showAbout() {
        // Get a reference to the about section
        var aboutSection = document.getElementById('about_section');

        // Scroll to the about section
        aboutSection.scrollIntoView({ behavior: 'smooth' });
    }
</script>
<script>
        // Function to open modal
        function openModal(modalId) {
            var modal = document.getElementById(modalId);
            modal.style.display = "block";
        }

        // Function to close modal
        function closeModal(modalId) {
            var modal = document.getElementById(modalId);
            modal.style.display = "none";
        }

        // Close the modal when clicking outside of it
        window.onclick = function(event) {
            var modals = document.getElementsByClassName('modal');
            for (var i = 0; i < modals.length; i++) {
                var modal = modals[i];
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        }
        function openSignUpModalAndCloseSignIn() {
            closeModal('signInModal');
            openModal('signUpModal');
        }
    </script>
    <script>
      $('.minus-btn').on('click', function(e) {
    e.preventDefault();
    var $this = $(this);
    var $input = $this.closest('div').find('input');
    var value = parseInt($input.val());
 
    if (value &amp;amp;gt; 1) {
        value = value - 1;
    } else {
        value = 0;
    }
 
  $input.val(value);
 
});
 
$('.plus-btn').on('click', function(e) {
    e.preventDefault();
    var $this = $(this);
    var $input = $this.closest('div').find('input');
    var value = parseInt($input.val());
 
    if (value &amp;amp;lt; 100) {
        value = value + 1;
    } else {
        value =100;
    }
 
    $input.val(value);
});
      </script>
<script>
function addToCart(productId) {
    // Check if the user is logged in
    var loggedIn = <?php echo isset($_SESSION['id']) ? 'true' : 'false'; ?>;
    
    // If user is not logged in, open the sign-in modal
    if (!loggedIn) {
        openSignInModal();
        return; // Stop further execution
    }
    
    // Send an AJAX request to add the product to the cart
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "add_to_cart.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Handle the response
            var response = xhr.responseText;
            if (response === "success") {
                // Display success message
                alert("Product added to cart successfully!");
            } else {
                // Display error message
                alert("Product added to cart successfully!");
            }
        }
    };
    // Send the product ID in the request body
    xhr.send("product_id=" + productId);
}

// JavaScript function to open sign-in modal
function openSignInModal() {
    // Replace this with your code to open the sign-in modal
    // Example:
    document.getElementById("signInModal").style.display = "block";
}
</script>

</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js"></script>
  <script>
  $(document).ready(function(){
    // Initialize Isotope
    var $grid = $('.grid').isotope({
      itemSelector: '.grid-item',
      layoutMode: 'fitRows'
    });

    // Filter items on button click
    $('.filters_menu').on( 'click', 'li', function() {
      var filterValue = $(this).attr('data-filter');
      $grid.isotope({ filter: filterValue });
      $('.filters_menu li').removeClass('active');
      $(this).addClass('active');
    });
  });
</script>
 <!-- Include jQuery library -->
<script>
$(document).ready(function(){
    $("#contactForm").submit(function(event){
        event.preventDefault(); // Prevent the default form submission
        
        var formData = $(this).serialize();
        
        $.ajax({
            type: "POST",
            url: "submit_form.php",
            data: formData,
            success: function(response){
                alert(response);
                $('#contactForm')[0].reset(); // Reset only the contact form
            }
        });
    });
});

$(document).ready(function(){
    $("#contactLink").click(function(event){
        event.preventDefault(); // Prevent the default link behavior
        
        // Show the contact section
        $("#contactSection").show();
        
        // Scroll to the contact section
        $('html, body').animate({
            scrollTop: $("#contactSection").offset().top
        }, 1000); // Adjust the duration as needed
    });
});
$(document).ready(function(){
    $("#aboutLink").click(function(event){
        event.preventDefault(); // Prevent the default link behavior
        
        // Show the about section
        $("#about_section").show();
        
        // Scroll to the about section
        $('html, body').animate({
            scrollTop: $("#about_section").offset().top
        }, 1000); // Adjust the duration as needed
    });
});
$(document).ready(function(){
    $("#orderNowButton").click(function(event){
        event.preventDefault(); // Prevent the default link behavior
        
        // Show the products section
        $("#products_section").show();
        
        // Scroll to the products section
        $('html, body').animate({
            scrollTop: $("#products_section").offset().top
        }, 1000); // Adjust the duration as needed
    });
});


</script>
