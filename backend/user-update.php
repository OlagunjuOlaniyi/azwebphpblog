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

            <?php 
                if (isset($_POST['update-user'])) {
                    $user_id = $_POST['user_id'];
                    $url = "http://localhost/azweb/backend/user-update.php?user_id=".$user_id;
                    header("Location: {$url}");
                }
            ?>



            <div id="layoutSidenav_content">
                <main>
                    <div class="page-header pb-10 page-header-dark bg-gradient-primary-to-secondary">
                        <div class="container-fluid">
                            <div class="page-header-content">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="edit-3"></i></div>
                                    <span>Updating User</span>
                                </h1>
                            </div>
                        </div>
                    </div>

                    <!--Start Table-->
                    <div class="container-fluid mt-n10">
                        <div class="card mb-4">
                            <div class="card-header">Edit User</div>
                            <div class="card-body">
                                <?php 
                                    if (isset($_POST['update'])) {
                                        $user_name = trim($_POST['user-name']);
                                        $nick_name = trim($_POST['user-nickname']);
                                        //nickname already exist
                                        $sql2 = "SELECT * FROM users WHERE user_nickname = :nickname";
                                        $stmt2 = $pdo->prepare($sql2);
                                        $stmt2->execute([
                                            ':nickname' => $nick_name
                                        ]);
                                        $countNickname = $stmt2->rowCount();
                                        if ($countNickname >= 2) {
                                            $error_nickname_exist = "Nickname already exists!";
                                        }

                                        $user_email = trim($_POST['user-email']);
                                        //email already exist
                                        $sql1 = "SELECT * FROM users WHERE user_email = :email";
                                        $stmt1 = $pdo->prepare($sql1);
                                        $stmt1->execute([
                                            ':email' => $user_email
                                        ]);
                                        $countEmail = $stmt1->rowCount();
                                        if ($countEmail >= 2) {
                                            $error_email_exist = "Email already exists!";
                                        }

                                        if (!isset($error_nickname_exist) || !isset($error_email_exist)) {
                                            $user_role = $_POST['user-role'];
                                            $user_photo = $_FILES['user-photo']['name'];
                                            $user_photo_tmp = $_FILES['user-photo']['tmp_name'];
                                            move_uploaded_file("{$user_photo_tmp}", "./assets/img/{$user_photo}");

                                            if (empty($user_photo)) {
                                                $sql3 = "SELECT * FROM users WHERE user_id = :id";
                                                $stmt3 = $pdo->prepare($sql3);
                                                $stmt3->execute([
                                                    ':id' => $_GET['user_id']
                                                ]);
                                                $user = $stmt3->fetch(PDO::FETCH_ASSOC);
                                                $user_photo = $user['user_photo'];
                                            }
                                            
                                            $sql = "UPDATE users SET user_name = :name, user_nickname = :nickname, user_email = :email, user_photo = :photo, user_role = :role WHERE user_id = :id";
                                            $stmt = $pdo->prepare($sql);
                                            $stmt->execute([
                                                ':name' => $user_name,
                                                ':nickname' => $nick_name,
                                                ':email' => $user_email,
                                                ':photo' => $user_photo,
                                                ':role' => $user_role,
                                                ':id' => $_GET['user_id']
                                            ]);
                                            
                                            header("Location: users.php");
                                        }
                                    }
                                ?>

                                <?php 
                                    if (isset($_GET['user_id'])) {
                                        $user_id = $_GET['user_id'];
                                        $sql = "SELECT * FROM users WHERE user_id = :id";
                                        $stmt = $pdo->prepare($sql);
                                        $stmt->execute([
                                            ':id' => $user_id
                                        ]);
                                        $user = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $user_name = $user['user_name'];
                                        $user_nickname = $user['user_nickname'];
                                        $user_email = $user['user_email'];
                                        $user_role = $user['user_role'];
                                        $user_photo = $user['user_photo'];
                                    }
                                    
                                ?>

                                <form action="user-update.php?user_id=<?php echo $user_id; ?>" method="POST" enctype="multipart/form-data">
                                    <?php 
                                        if (isset($error_email_exist)) {
                                            echo "<p class='alert alert-danger'>{$error_email_exist}</p>";
                                        } elseif (isset($error_nickname_exist)) {
                                            echo "<p class='alert alert-danger'>{$error_nickname_exist}</p>";
                                        }
                                    ?>
                                    <div class="form-group">
                                        <label for="user-name">User Name:</label>
                                        <input value="<?php echo $user_name; ?>" name="user-name" class="form-control" id="user-name" type="text" placeholder="" />
                                    </div>
                                    <div class="form-group">
                                        <label for="user-nickname">User Nickname:</label>
                                        <input value="<?php echo $user_nickname; ?>" name="user-nickname" class="form-control" id="user-nickname" type="text" placeholder="" />
                                    </div>
                                    <div class="form-group">
                                        <label for="user-email">User Email:</label>
                                        <input value="<?php echo $user_email; ?>" name="user-email" class="form-control" id="user-email" type="email" placeholder="" />
                                    </div>
                                    <div class="form-group">
                                        <label for="user-role">Role:</label>
                                        <select name="user-role" class="form-control" id="user-role">
                                            <option value="admin" <?php echo $user_role=='admin'?'selected':''; ?>>Admin</option>
                                            <option value="subscriber" <?php echo $user_role=='subscriber'||'Subscriber'?'selected':''; ?>>Subscriber</option>
                                        </select>
                                        <div class="form-group">
                                        <label for="user-photo">Choose photo:</label>
                                        <input name="user-photo" class="form-control" id="user-photo" type="file" />
                                        <img src="./assets/img/<?php echo $user_photo; ?>" width="50" height="50">
                                    </div>
                                    </div>
                                    <button name="update" class="btn btn-primary mr-2 my-1" type="submit">Update now!</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--End Table-->
                </main>

<?php require_once("./includes/footer.php"); ?>