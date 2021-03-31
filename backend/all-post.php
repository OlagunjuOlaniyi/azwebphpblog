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
                                    <div class="page-header-icon"><i data-feather="layout"></i></div>
                                    <span>All Posts</span>
                                </h1>
                            </div>
                        </div>
                    </div>

                    <!--Start Table-->
                    <div class="container-fluid mt-n10">
                        <div class="card mb-4">
                            <div class="card-header">All Posts</div>
                            <div class="card-body">
                                <div class="datatable table-responsive">
                                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Title</th>
                                                <th>Status</th>
                                                <th>Category</th>
                                                <th>Author</th>
                                                <th>Image</th>
                                                <th>Date</th>
                                                <th>Details</th>
                                                <th>Tags</th>
                                                <th>Comments</th>
                                                <th>Views</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>ID</th>
                                                <th>Title</th>
                                                <th>Status</th>
                                                <th>Category</th>
                                                <th>Author</th>
                                                <th>Image</th>
                                                <th>Date</th>
                                                <th>Details</th>
                                                <th>Tags</th>
                                                <th>Comments</th>
                                                <th>Views</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                            </tr>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php 
                                                $sql = "SELECT * FROM posts";
                                                $stmt = $pdo->prepare($sql);
                                                $stmt->execute();
                                                
                                                while ($post = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    $post_id = $post['post_id'];
                                                    $post_title = substr($post['post_title'], 0, 20);
                                                    $post_detail = substr($post['post_detail'], 0, 10);

                                                    $post_category_id = $post['post_category_id'];
                                                    $sql1 = "SELECT * FROM categories WHERE category_id = :id";
                                                    $stmt1 = $pdo->prepare($sql1);
                                                    $stmt1->execute([
                                                        ':id' => $post_category_id
                                                    ]);
                                                    $category = $stmt1->fetch(PDO::FETCH_ASSOC);
                                                    $category_id = $category['category_id'];
                                                    $category_name = $category['category_name'];

                                                    $post_image = $post['post_image'];
                                                    $post_date = $post['post_date'];
                                                    $post_status = $post['post_status'];
                                                    $post_author = $post['post_author'];
                                                    $post_views = $post['post_views'];
                                                    $post_comment_count = $post['post_comment_count'];
                                                    $post_tags = substr($post['post_tags'], 0, 10); 
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $post_id; ?></td>
                                                        <td>
                                                            <a href="../single.php?post_id=<?php echo $post_id; ?>" target="_blank"><?php echo $post_title; ?></a>
                                                        </td>
                                                        <td>
                                                            <div class="badge badge-<?php echo $post_status=='Published'?'success':'warning'; ?>"><?php echo $post_status; ?></div>
                                                        </td>
                                                        <td><?php echo $category_name; ?></td>
                                                        <td><?php echo $post_author; ?></td>
                                                        <td><img src="../img/<?php echo $post_image; ?>" width="50" height="50"></td>
                                                        <td><?php echo $post_date; ?></td>
                                                        <td><?php echo $post_detail; ?></td>
                                                        <td><?php echo $post_tags; ?></td>
                                                        <td>
                                                            <a href="comments.php"><?php echo $post_comment_count; ?></a>
                                                        </td>
                                                        <td><?php echo $post_views; ?></td>
                                                        <td>
                                                            <?php 
                                                                if (isset($_POST['edit-post'])) {
                                                                    $post_id = $_POST['post-id'];
                                                                    $url = 'http://localhost/azweb/backend/edit-post.php?post_id='.$post_id;
                                                                    header("Location: {$url}");
                                                                }
                                                            ?>
                                                            <form action="all-post.php" method="POST">
                                                                <input type="hidden" name="post-id" value="<?php echo $post_id; ?>">
                                                                <button name="edit-post" class="btn btn-blue btn-icon" type="submit"><i data-feather="edit"></i></button>
                                                            </form>
                                                        </td>
                                                        <td>
                                                            <?php 
                                                                if (isset($_POST['delete'])) {
                                                                    $post_id = $_POST['post-id'];
                                                                    $sql = "DELETE FROM posts WHERE post_id = :id";
                                                                    $stmt = $pdo->prepare($sql);
                                                                    $stmt->execute([
                                                                        ':id' => $post_id
                                                                    ]);
                                                                    header("Location: all-post.php");
                                                                }
                                                            ?>
                                                            <form action="all-post.php" method="POST">
                                                                <input type="hidden" name="post-id" value="<?php echo $post_id; ?>" />
                                                                <button name="delete" title="Remove this post?" class="btn btn-red btn-icon" type="submit"><i data-feather="trash-2"></i></button>
                                                            </form>
                                                        </td>
                                                    </tr>  
                                                    <?php }

                                            ?>
                                                 
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End Table-->

                </main>

 <?php require_once("./includes/footer.php"); ?>