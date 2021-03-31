<?php require_once("./includes/header.php"); ?>

    <body class="nav-fixed">
        <?php require_once('./includes/top-navbar.php'); ?>
        

        <!--Side Nav-->
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <?php 
                    $curr_page = basename(__FILE__);
                    require_once('./includes/left-sidebar.php'); 
                ?>
            </div>


            <div id="layoutSidenav_content">
                <main>
                    <div class="page-header pb-10 page-header-dark bg-gradient-primary-to-secondary">
                        <div class="container-fluid">
                            <div class="page-header-content">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="edit-3"></i></div>
                                    <span>Create New User</span>
                                </h1>
                            </div>
                        </div>
                    </div>

                    <!--Start Table-->
                    <div class="container-fluid mt-n10">
                        <div class="card mb-4">
                            <div class="card-header">Create New User</div>
                            <div class="card-body">
                                <?php 
                                    if (isset($_POST['create-user'])) {
                                        $user_name = trim($_POST['user_name']);
                                        $nick_name = trim($_POST['nick_name']);
                                        //nickname already exist
                                        $sql2 = "SELECT * FROM users WHERE user_nickname = :nickname";
                                        $stmt2 = $pdo->prepare($sql2);
                                        $stmt2->execute([
                                            ':nickname' => $nick_name
                                        ]);
                                        $countNickname = $stmt2->rowCount();
                                        if ($countNickname != 0) {
                                            $error_nickname_exist = "Nickname already exists!";
                                        }

                                        $user_email = trim($_POST['user_email']);
                                        //email already exist
                                        $sql1 = "SELECT * FROM users WHERE user_email = :email";
                                        $stmt1 = $pdo->prepare($sql1);
                                        $stmt1->execute([
                                            ':email' => $user_email
                                        ]);
                                        $countEmail = $stmt1->rowCount();
                                        if ($countEmail != 0) {
                                            $error_email_exist = "Email already exists!";
                                        }

                                        if (!isset($error_nickname_exist) || !isset($error_email_exist)) {
                                            $user_password = trim($_POST['user_password']);
                                            $hash = password_hash($user_password, PASSWORD_BCRYPT, ['cost'=>10]);

                                            $user_role = $_POST['user_role'];
                                            $user_photo = $_FILES['user_photo']['name'];
                                            $user_photo_tmp = $_FILES['user_photo']['tmp_name'];
                                            move_uploaded_file("{$user_photo_tmp}", "./assets/img/{$user_photo}");

                                            $sql = "INSERT INTO users (user_name, user_nickname, user_email, user_password, user_photo, registered_on, user_role) VALUES (:name, :nickname, :email, :password, :photo, :register, :role)";
                                            $stmt = $pdo->prepare($sql);
                                            $stmt->execute([
                                                ':name' => $user_name,
                                                ':nickname' => $nick_name,
                                                ':email' => $user_email,
                                                ':password' => $hash,
                                                ':photo' => $user_photo,
                                                ':register' => date('M n, Y') . ' at ' . date('h:i, A'),
                                                ':role' => $user_role
                                            ]);
                                            header("Location: users.php");
                                        }

                                        
                                    }
                                ?>
                                <form action="new-user.php" method="POST" enctype="multipart/form-data">
                                    <?php 
                                        if (isset($error_email_exist)) {
                                            echo "<p class='alert alert-danger'>{$error_email_exist}</p>";
                                        } elseif (isset($error_nickname_exist)) {
                                            echo "<p class='alert alert-danger'>{$error_nickname_exist}</p>";
                                        }
                                    ?>
                                    <div class="form-group">
                                        <label for="user-name">User Name:</label>
                                        <input name="user_name" class="form-control" id="user-name" type="text" placeholder="User Name..." required="true" />
                                    </div>
                                    <div class="form-group">
                                        <label for="nick-name">Nickname:</label>
                                        <input name="nick_name" class="form-control" id="nick-name" type="text" placeholder="Nickname..." required="true" />
                                    </div>
                                    <div class="form-group">
                                        <label for="user-email">User Email:</label>
                                        <input name="user_email" class="form-control" id="user-email" type="email" placeholder="User Email..." required="true" />
                                    </div>
                                    <div class="form-group">
                                        <label for="user-password">User Password:</label>
                                        <input name="user_password" class="form-control" id="user-password" type="password" placeholder="Password..." required="true" />
                                    </div>
                                    <div class="form-group">
                                        <label for="user-role">Role:</label>
                                        <select name="user_role" class="form-control" id="user-role" required="true">
                                            <option value="admin">Admin</option>
                                            <option value="subscriber">Subscriber</option>
                                        </select>
                                        <div class="form-group">
                                        <label for="post-title">Choose photo:</label>
                                        <input name="user_photo" class="form-control" id="post-title" type="file" required="true" />
                                    </div>
                                    </div>
                                    <button name="create-user" class="btn btn-primary mr-2 my-1" type="submit">Create now!</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--End Table-->
                </main>

<?php require_once("./includes/footer.php"); ?>