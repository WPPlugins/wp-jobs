<?php
/**
 * Template Name: Applicationsform
 */
get_header();
?>
<div class="wrap">
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            <?php
            global $post;
            $post_data = get_post($post->ID);
            ?>
            <div>
                <h1 class="entry-title">
                    <?php
                    $title = $post_data->post_title;
                    echo ucwords($title);
                    ?>
                </h1>
                <div id="msg"></div>
                <div id="jbst">
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="well well-small">
                                <h2><?php _e('Job Detail', 'wp-jobs'); ?></h2>
                                <p><?php _e('Designation', 'wp-jobs'); ?> : <?php echo get_post_meta($post_data->ID, 'wp_jobs_designation', true); ?><br />
                                    <?php _e('Location', 'wp-jobs'); ?> : <?php echo get_post_meta($post_data->ID, 'wp_jobs_location', true); ?><br />
                                    <?php _e('Salary', 'wp-jobs'); ?> : <?php echo get_post_meta($post_data->ID, 'wp_jobs_salary', true); ?></p>
                            </div>

                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12 ulstyle">
                            <div class="well well-small">
                                <h2><?php _e('Description', 'wp-jobs'); ?></h2>
                                <p><?php echo apply_filters('the_content', $post_data->post_content); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="well well-small ulstyle">
                                <h2><?php _e('Qualification / Requirements', 'wp-jobs'); ?></h2>
                                <p><?php
                                    #echo apply_filters('the_content', get_post_meta($post_data->ID, 'wpjobseditorqualification', true));

                                    if (null != get_post_meta($post_data->ID, 'wpjobseditorqualification', true)) {
                                        $wpj_req_text = get_post_meta($post_data->ID, 'wpjobseditorqualification', true);
                                        echo apply_filters('get_the_content', $wpj_req_text);
                                    }
                                    ?></p>

                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="well well-small" >

                                <h2><?php _e('Apply for this job', 'wp-jobs'); ?></h2>
                                <form method="post" id="frmInquiry" action="" enctype="multipart/form-data">

                                    <label><?php _e('First name', 'wp-jobs'); ?> <span style="color:red;">*</span></label>
                                    <input id="user_fname" type="text" value="<?php
                                    if (!empty($_POST['user_fname'])) {
                                        echo $_POST['user_fname'];
                                    } else {

                                    }
                                    ?>" name="user_fname"/>
                                    <label><?php _e('Last name', 'wp-jobs'); ?></label>
                                    <input type="text" id="user_lname" value="<?php
                                    if (!empty($_POST['user_lname'])) {
                                        echo $_POST['user_lname'];
                                    } else {

                                    }
                                    ?>" name="user_lname"/>

                                    <label><?php _e('Phone Number', 'wp-jobs'); ?><span style="color:red;">*</span></label>
                                    <input type="text" value="<?php
                                    if (!empty($_POST['user_phn'])) {
                                        echo $_POST['user_phn'];
                                    } else {

                                    }
                                    ?>" name="user_phn" id="user_phn"/>

                                    <label><?php _e('Email Address', 'wp-jobs'); ?> <span style="color:red;">*</span></label>
                                    <input type="email" value="<?php
                                    if (!empty($_POST['user_email'])) {
                                        echo $_POST['user_email'];
                                    } else {

                                    }
                                    ?>" name="user_email" id="user_email"/>

                                    <label><?php _e('Attach Resume', 'wp-jobs'); ?> <span style="color:red;">*</span></label>
                                    <input type="file" name="myfile" id="myfile">
                                    <span class="help-block">( .doc,.docx,.pdf )</span>
                                    <input type="submit"  name="btnsubform" id="btnsubform" class="btn" value="<?php _e('Submit Application', 'wp-jobs'); ?>" />

                                </form>
                                <?php
                                if (isset($_POST['btnsubform'])) {

                                    $fname = $_POST['user_fname'];
                                    $lname = $_POST['user_lname'];
                                    $email = $_POST['user_email'];
                                    $phone = $_POST['user_phn'];

                                    if (!empty($_POST['user_phn'])) {
                                        if (!empty($_POST['user_fname'])) {
                                            global $wpdb;
                                            $allowed = array('pdf', 'doc', 'docx', 'txt');
                                            $filename = $_FILES['myfile']['name'];
                                            $ext = pathinfo($filename, PATHINFO_EXTENSION);

                                            //this is extention if strart
                                            if (!in_array($ext, $allowed)) {
                                                ?>
                                                <script type="text/javascript">
                                                    var msgbox = document.getElementById('msg');
                                                    msgbox.innerHTML = "<div class='alert alert-error'><a class='close' data-dismiss='alert'>×</a><strong>Error! </strong>File type or file empty </div>";
                                                </script>
                                                <?php
                                            } else {

                                                //this chek for checking email for appling job start
                                                $tbl = $wpdb->prefix;
                                                $emailchkqry = "select * from " . $tbl . "app_user_info where app_job_id='" . $title . "' and app_email='" . $_POST['user_email'] . "'";

                                                $checkEmail = $wpdb->get_results($emailchkqry);
                                                $userreccount = count($checkEmail);
                                                if ($userreccount > 0) {
                                                    ?>
                                                    <script type="text/javascript">
                                                        var msgbox = document.getElementById('msg');
                                                        msgbox.innerHTML = "<div class='alert alert-info'><a class='close' data-dismiss='alert'>×</a><?php _e('You have already applied for ', 'wp-jobs'); ?> <?php echo $title; ?></div>";
                                                    </script>

                                                    <?php
                                                } else {
                                                    if (!function_exists('wp_handle_upload')) {
                                                        require_once( ABSPATH . 'wp-admin/includes/file.php');
                                                    }
                                                    $uploadedfile = $_FILES['myfile'];
                                                    $upload_overrides = array('test_form' => false);
                                                    $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
                                                    if ($movefile) {
                                                        $wp_filetype = $movefile['type'];
                                                        $filename = $movefile['file'];
                                                        $wp_upload_dir = wp_upload_dir();
                                                        $attachment = array(
                                                            'guid' => $wp_upload_dir['url'] . '/' . basename($filename),
                                                            'post_mime_type' => $wp_filetype,
                                                            'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
                                                            'post_content' => ''
                                                        );
                                                        $attach_id = wp_insert_attachment($attachment, $filename);
                                                        if ($attach_id) {
                                                            //this is an insert user section start
                                                            $tbl = $wpdb->prefix;

                                                            $wpdb->insert($tbl . 'app_user_info', array(
                                                                'app_name' => $_POST['user_fname'] . "-" . $_POST['user_lname'],
                                                                'app_job_id' => $post_data->ID,
                                                                'app_email' => $_POST['user_email'],
                                                                'app_phn' => $_POST['user_phn'],
                                                                'app_cv' => $wp_upload_dir['url'] . '/' . basename($filename),
                                                                    ), array(
                                                                '%s', '%s', '%s', '%s', '%s'
                                                                    )
                                                            );
                                                            //this is an insert user section end
                                                            //This is Email Section	Start


                                                            $usrheadersrpt[] = "Content-type: text/html";
                                                            $usrheadersrpt[] = 'From: <' . get_option("wpjobs_send_mail") . '>';
                                                            $adm_messagerpt = "<table><tr><td colspan='4'><h3>Thank you " . $_POST['user_fname'] . "</h3></td></tr>
  <tr><td colspan='4'>Applying for the post of " . $title . "</td></tr></table>";
                                                            $adm_messagerpt = "Thank you " . $_POST['user_fname'] . "-" . $_POST['user_lname'] . " for applying for the post of " . $title;

                                                            wp_mail($_POST['user_email'], "Job submission confirmation", $adm_messagerpt, $usrheadersrpt);
                                                            //This is Email Section	End
                                                            ?>
                                                            <script type="text/javascript">
                                                                var msgbox = document.getElementById('msg');
                                                                msgbox.innerHTML = "<div class='alert alert-info'><a class='close' data-dismiss='alert'>×</a>Thank You <?php echo ucwords($_POST['user_fname']); ?> For Applying For The Job <?php echo ucwords($title); ?></div>";

                                                                document.getElementById('user_fname').value = ""
                                                                document.getElementById('user_lname').value = "";
                                                                document.getElementById('user_email').value = "";
                                                                document.getElementById('user_phn').value = "";

                                                            </script>
                                                            <?php
                                                        } else {
                                                            echo "attach !";
                                                        }
                                                    } else {
                                                        echo "move";
                                                    }
                                                }
                                                //this chek for checking email for appling job end
                                            }
                                        } else {
                                            ?>
                                            <script type="text/javascript">
                                                var msgbox = document.getElementById('msg');
                                                msgbox.innerHTML = "<div class='alert alert-error'><a class='close' data-dismiss='alert'>×</a>Enter User Name </div>";
                                            </script>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <script type="text/javascript">
                                            var msgbox = document.getElementById('msg');
                                            msgbox.innerHTML = "<div class='alert alert-error'><a class='close' data-dismiss='alert'>×</a>Enter Phone Number </div>";
                                        </script>
                                        <?php
                                    }
                                    //here extension check end
                                }
                                ?>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
    </div>
</main>
<?php get_sidebar(); ?>
</div><!-- #content -->

</div><!-- #primary -->

<?php get_footer(); ?>