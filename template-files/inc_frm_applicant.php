<h3><?php _e('Applicant User', 'wp-jobs'); ?></h3>
<table class="form-table">
    <tr>
        <td> <?php _e('Job Title', 'wp-jobs'); ?></td>
        <td><label class="regular-text"><?php echo apply_filters('the_content', get_the_author_meta('user_job_title', $user->ID)); ?>
            </label></td>
    </tr>
    <tr>
        <td> <?php _e('First Name', 'wp-jobs'); ?></td>
        <td><input type="text" class="regular-text" value="<?php echo esc_attr(get_the_author_meta('user_fname', $user->ID)); ?>"/></td>
    </tr>
    <tr>
        <td> <?php _e('Last Name', 'wp-jobs'); ?></td>
        <td><input type="text" class="regular-text" value="<?php echo esc_attr(get_the_author_meta('user_lname', $user->ID)); ?>"/></td>
    </tr>
    <tr>
        <td><?php _e('User Email', 'wp-jobs'); ?></td>
        <td>
            <label class="regular-text" ><?php echo esc_attr(get_the_author_meta('user_email', $user->ID)); ?></label></td>
    </tr>
    <tr>
        <td><?php _e('Phone Number', 'wp-jobs'); ?> </td>
        <td><input type="text" class="regular-text" value="<?php echo esc_attr(get_the_author_meta('user_phone', $user->ID)); ?>"/></td>
    </tr>
    <tr>
        <td><?php _e('Download CV', 'wp-jobs'); ?></td>
        <td>
            <?php
            $userCv = get_the_author_meta('user_cv_link', $user->ID);
            if (!empty($userCv)) {
                ?>
                <a download href=" <?php echo esc_attr(get_the_author_meta('user_cv_link', $user->ID)); ?>"><?php _e('Download CV', 'wp-jobs'); ?></a></td>
            <?php
        } else {
            echo "No CV ";
        }
        ?>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
</table>