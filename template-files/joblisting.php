<?php
/**
 * Template Name: Job Listing Page
 */
get_header();
?>
<div class="wrap">
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            <h1 class="entry-title">
                <?php
                the_title();
                ?>
            </h1>
            <div>

                <form action="" method="post">
                    <div>
                        <span>
                            <?php
                            if (sanitize_text_field($_POST['btnsub'])) {
                                $taxonomy = 'department';
                                $terms = get_terms('department');
                                echo "<select style='margin:0;' id='mytrem' name='dropdept'>";
                                echo "<option value='0'>All department</option>";
                                foreach ($terms as $term) {
                                    $term_link = get_term_link($term, 'department');
                                    if (is_wp_error($term_link))
                                        continue;
                                    echo '<option  value=' . $term->term_id . ' >' . ucwords($term->name) . '</option>';
                                }
                                echo '</select>';
                            }else {
                                $taxonomy = 'department';
                                $terms = get_terms('department');
                                echo "<select style='margin:0;' id='mytrem' name='dropdept'>";
                                echo "<option value='0'>All department</option>";
                                foreach ($terms as $term) {
                                    echo "<pre>";
                                    print_r($term);
                                    echo "</pre>";
                                    $term_link = get_term_link($term, 'department');
                                    if (is_wp_error($term_link))
                                        continue;
                                    echo '<option  value=' . $term->term_id . ' >' . ucwords($term->name) . '</option>';
                                }
                                echo '</select>';
                            }
                            ?>

                        </span>
                        <span>
                            <input type="submit" name="btnsub" value="Fiter Jobs"/>
                        </span>
                    </div>
                </form>
                <?php
                global $post, $wp_query;
                if ((isset($_POST['btnsub'])) && $_POST['dropdept'] != '0') {
                    $myquery['tax_query'] = array(
                        array(
                            'taxonomy' => 'department',
                            'terms' => $_POST['dropdept'],
                            'field' => 'id',
                    ));
                    ?>
                    <script type="text/javascript">
                        document.getElementById('mytrem').value = "<?php echo $_POST['dropdept'] ?>";
                    </script>
                    <?php
                    $items = query_posts($myquery);
                } else {
                    $items = query_posts('post_type=job');
                }
                $count = count($items);
                echo "<div class='info-error'><br/>" . __('Job result found ', 'wp-jobs') . $count . "</div>";
                ?>

                <table>
                    <th></th>
                    <th><strong><?php _e('Job Title', 'wp-jobs'); ?></strong></th>
                    <th><strong><?php _e('Department', 'wp-jobs'); ?></strong></th>
                    <th><strong><?php _e('Designation', 'wp-jobs'); ?></strong></th>
                    <th><strong><?php _e('Location', 'wp-jobs'); ?></strong></th>
                    <th><strong><?php _e('Salary', 'wp-jobs'); ?></strong></th>
                    <?php
                    foreach ($items as $item) {
                        setup_postdata($item);
                        ?>
                        <tr>
                            <td><i class="icon-star"></i></td>
                            <td><?php echo ucwords($item->post_title); ?></td>
                            <td><?php
                                $terms_as_text = strip_tags(get_the_term_list($item->ID, 'department', '', ', ', ''));
                                echo $terms_as_text;
                                ?></td>
                            <td><?php echo get_post_meta($item->ID, 'wp_jobs_designation', true); ?></td>
                            <td><?php echo get_post_meta($item->ID, 'wp_jobs_location', true); ?></td>
                            <td><?php echo get_post_meta($item->ID, 'wp_jobs_salary', true); ?></td>
                            <td><a href="<?php echo get_permalink($item->ID)
                                ?>" >Learn More</a></td>
                            <td><a href="<?php echo get_permalink($item->ID) ?>" title="Apply for <?php echo $item->post_title ?>" ><i class="icon-check"></i></a></td>
                        </tr>

                    <?php } ?>
                </table>

            </div>
        </main>
        <?php get_sidebar(); ?>
    </div><!-- #content -->

</div><!-- #primary -->

<?php get_footer(); ?>