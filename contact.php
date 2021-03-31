<?php $current_page = "Contact"; ?>
<?php require_once("./includes/header.php") ?>

    <body>
        <div id="layoutDefault">
            <div id="layoutDefault_content">
                <main>
                    
                    <nav class="navbar navbar-marketing navbar-expand-lg bg-white navbar-light">
                        <div class="container">
                            <a class="navbar-brand text-dark" href="index.php">TechBarik</a><button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><img src="./img/menu.png" style="height:20px;width:25px" /><i data-feather="menu"></i></button>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav ml-auto mr-lg-5">
                                    <li class="nav-item">
                                        <a class="nav-link" href="index.php">Home </a>
                                    </li>
                                    <li class="nav-item dropdown no-caret">
                                        <a class="nav-link active" href="contact.php">Contact</a>
                                    </li>
                                    <li class="nav-item dropdown no-caret">
                                        <a class="nav-link" href="about.php">About</a>
                                    </li>
                                </ul>
                                <?php 
                                    $curr_page = basename(__FILE__);
                                    require_once('./includes/registration.php');
                                ?>
                            </div>
                        </div>
                    </nav>

                    <header class="page-header page-header-dark bg-gradient-primary-to-secondary">
                        <div class="page-header-content pt-10">
                            <div class="container text-center">
                                <div class="row justify-content-center">
                                    <div class="col-lg-8">
                                        <h1 class="page-header-title mb-3">Contact Us</h1>
                                        <p class="page-header-text">We will back to you in a week!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="svg-border-rounded text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 144.54 17.34" preserveAspectRatio="none" fill="currentColor"><path d="M144.54,17.34H0V0H144.54ZM0,0S32.36,17.34,72.27,17.34,144.54,0,144.54,0" /></svg>
                        </div>
                    </header>
                    
                    <section class="bg-white py-10">
                        <div class="container">

                            <?php 
                                if(isset($_SESSION['login']) || isset($_COOKIE['_uid_']) || isset($_COOKIE['_uiid_'])) { ?>

                                    <?php 
                                        if (isset($_POST['send'])) {
                                            $ms_username = $_POST['user-name'];
                                            $ms_email = $_POST['user-email'];
                                            $ms_detail = trim($_POST['ms-detail']);

                                            $sql = "INSERT INTO messages (ms_username, ms_email, ms_detail, ms_date, ms_status) VALUES (:name, :email, :detail, :date, :status)";
                                            $stmt = $pdo->prepare($sql);
                                            $stmt->execute([
                                                ':name' => $ms_username,
                                                ':email' => $ms_email,
                                                ':detail' => $ms_detail,
                                                ':date' => date('M n, Y') . ' at ' . date('h:i, A'),
                                                ':status' => 'pending'
                                            ]);
                                            echo "<p class='alert alert-success'>Message successfully sent!</p>";
                                        }
                                    ?>
                                    <form action="contact.php" method="POST">
                                        <?php 
                                            if (isset($_COOKIE['_uid_'])) {
                                                $user_id = base64_decode($_COOKIE['_uid_']);
                                            } elseif (isset($_SESSION['user_id'])) {
                                                $user_id = $_SESSION['user_id'];
                                            } else {
                                                $user_id = -1;
                                            }

                                            $sql = "SELECT * FROM users WHERE user_id = :id";
                                            $stmt = $pdo->prepare($sql);
                                            $stmt->execute([
                                                ':id' => $user_id
                                            ]);
                                            $user = $stmt->fetch(PDO::FETCH_ASSOC);
                                            $user_name = $user['user_name'];
                                            $user_email = $user['user_email'];
                                        ?>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label class="text-dark" for="inputName">Full name</label>
                                                <input name="user-name" value="<?php echo $user_name; ?>" readonly="true" class="form-control py-4" id="inputName" type="text" placeholder="Full name" />
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="text-dark" for="inputEmail">Email</label>
                                                <input name="user-email" value="<?php echo $user_email; ?>" readonly="true" class="form-control py-4" id="inputEmail" type="email" placeholder="name@example.com" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-dark" for="inputMessage">Message</label>
                                            <textarea name="ms-detail" class="form-control py-3" id="inputMessage" type="text" placeholder="Enter your message..." rows="4" required="true"></textarea>
                                        </div>
                                        <div class="text-center">
                                            <button name="send" class="btn btn-primary btn-marketing mt-4" type="submit">Submit Request</button>
                                        </div>
                                    </form>
                                <?php } else {
                                    echo "<a href='backend/signin.php'>Please sign in to send a message!</a>";
                                }
                            ?>
                            
                            

                        </div>

                        <div class="svg-border-rounded text-dark">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 144.54 17.34" preserveAspectRatio="none" fill="currentColor"><path d="M144.54,17.34H0V0H144.54ZM0,0S32.36,17.34,72.27,17.34,144.54,0,144.54,0" /></svg>
                        </div>
                    </section>
                </main>
            </div>
            <div id="layoutDefault_footer">
                <footer class="footer pt-4 pb-4 mt-auto bg-dark footer-dark">
                    <div class="container">
                        <hr class="my-1" />
                        <div class="row align-items-center">
                            <div class="col-md-6 small">Copyright &#xA9; Your Website 2020</div>
                            <div class="col-md-6 text-md-right small">
                                <a href="privacy-policy.php">Privacy Policy</a>
                                &#xB7;
                                <a href="terms-conditions.php">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

<?php require_once("./includes/footer.php") ?>