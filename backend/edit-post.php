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
                                    <span>Try Updating Post</span>
                                </h1>
                            </div>
                        </div>
                    </div>

                    <?php 
                        if (isset($_GET['post_id'])) {
                            $post_id = $_GET['post_id'];
                            $sql = "SELECT * FROM posts WHERE post_id = :id";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([
                                ':id' => $post_id
                            ]);
                            $post = $stmt->fetch(PDO::FETCH_ASSOC);
                            $post_title = $post['post_title'];
                            $post_status = $post['post_status'];
                            $post_category_id = $post['post_category_id'];
                            $post_image = $post['post_image'];
                            $post_details = $post['post_detail'];
                            $post_tags = $post['post_tags'];
                        }
                    ?>

                    <!--Start Table-->
                    <div class="container-fluid mt-n10">
                        <div class="card mb-4">
                            <div class="card-header">Update Post</div>
                            <div class="card-body">
                                <?php 
                                    if (isset($_POST['update-post'])) {
                                        $post_id = $_GET['post_id'];
                                        $post_title = trim($_POST['post-title']);
                                        $post_status = $_POST['post-status'];
                                        $post_cat_id = $_POST['post-category'];
                                        $post_image = $_FILES['post-image']['name'];
                                        $post_image_tmp = $_FILES['post-image']['tmp_name'];
                                        move_uploaded_file("{$post_image_tmp}", "../img/{$post_image}");
                                        $post_details = trim($_POST['post-details']);
                                        $post_tags = trim($_POST['post-tags']);

                                        //post author
                                        if (isset($_COOKIE['_uid_'])) {
                                            $user_id = base64_decode($_COOKIE['_uid_']);
                                        } elseif (isset($_SESSION['user_id'])) {
                                            $user_id = $_SESSION['user_id'];
                                        } else {
                                            $user_id = -1;
                                        }

                                        $sql1 = "SELECT * FROM users WHERE user_id = :id";
                                        $stmt1 = $pdo->prepare($sql1);
                                        $stmt1->execute([
                                            ':id' => $user_id
                                        ]);
                                        $user = $stmt1->fetch(PDO::FETCH_ASSOC);
                                        $post_author = $user['user_name'];

                                        // empty photo
                                        if (empty($post_image)) {
                                            $sql2 = "SELECT * FROM posts WHERE post_id = :id";
                                            $stmt2 = $pdo->prepare($sql2);
                                            $stmt2->execute([
                                                ':id' => $post_id
                                            ]);
                                            $post = $stmt2->fetch(PDO::FETCH_ASSOC);
                                            $post_image = $post['post_image'];
                                        }

                                        $sql = "UPDATE posts SET post_id = :id, post_title = :title, post_detail = :details, post_category_id = :cat_id, post_image = :image, post_date = :date, post_status = :status, post_author = :author, post_tags = :tags WHERE post_id = :id";
                                        $stmt = $pdo->prepare($sql);
                                        $stmt->execute([
                                            ':title' => $post_title,
                                            ':details' => $post_details,
                                            ':cat_id' => $post_cat_id,
                                            ':image' => $post_image,
                                            ':date' => date('M n, Y') . ' at ' . date('h:i, A'),
                                            ':status' => $post_status,
                                            ':author' => $post_author,
                                            ':tags' => $post_tags,
                                            ':id' => $post_id
                                        ]);
                                        header("Location: all-post.php");
                                    }
                                ?>
                                <form action="edit-post.php?post_id=<?php echo $_GET['post_id']; ?>" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="post-title">Post Title:</label>
                                        <input name="post-title" value="<?php echo $post_title; ?>" class="form-control" id="post-title" type="text" placeholder="Post title ..." />
                                    </div>
                                    <div class="form-group">
                                        <label for="post-status">Post Status:</label>
                                        <select name="post-status" class="form-control" id="post-status">
                                            <option value="Published" <?php echo $post_status=='Published'?'selected':''; ?>>Published</option>
                                            <option value="Draft" <?php echo $post_status=='Draft'?'selected':''; ?>>Draft</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="select-category">Select Category:</label>
                                        <select name="post-category" class="form-control" id="select-category">
                                            <?php 
                                                $sql = "SELECT * FROM categories";
                                                $stmt = $pdo->prepare($sql);
                                                $stmt->execute();
                                                while ($cat = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    $category_id = $cat['category_id'];
                                                    $category_name = $cat['category_name']; ?>
                                                    <option value="<?php echo $category_id; ?>" <?php echo $category_id==$post_category_id?'selected':''; ?>><?php echo $category_name; ?></option>
                                                <?php }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="post-title">Choose photo:</label>
                                        <input name="post-image" class="form-control" id="post-title" type="file" />
                                        <img src="../img/<?php echo $post_image; ?>" width="50" height="50">
                                    </div>

                                    <div class="form-group">
                                        <label for="post-content">Post Details:</label>
                                        <textarea name="post-details" class="form-control" placeholder="Type here..." id="post-content" rows="9"><?php echo $post_details; ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="post-tags">Post Tags:</label>
                                        <textarea name="post-tags" class="form-control" placeholder="Tags..." id="post-tags" rows="3"><?php echo $post_tags; ?></textarea>
                                    </div>
                                    <button name="update-post" class="btn btn-primary mr-2 my-1" type="submit">Submit now</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--End Table-->

                </main>

<?php require_once("./includes/footer.php"); ?>

