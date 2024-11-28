<?php
function shortcode_summary_cuvee($attrs)
{
    extract(shortcode_atts(
        array(
            'id' => null
        ),
        $attrs
    ));

    ob_start(); ?>
    <div class='cpt-cuvee-summary g__radius'>
        <a href='<?php echo esc_html(get_permalink($id)); ?>'>
            <div class="txt__group">
                <h3 class="has-light-color has-medium-font-size"><?php echo esc_html(get_the_title($id)); ?></h3>
                <?php
                $appellations = get_the_terms($id, 'appellation');

                if ($appellations && !is_wp_error($appellations)) {
                    $appellation_names = array();
                    foreach ($appellations as $appellation) {
                        $appellation_names[] = '<span class="appellation has-light-color has-small-font-size">' . esc_html($appellation->name) . '</span>';
                    }
                    echo implode(', ', $appellation_names);
                }
                ?>
            </div>

            <figure>
                <?php echo get_the_post_thumbnail($id, 'large'); ?>
            </figure>
        </a>
    </div>
<?php
    return ob_get_clean();
}
add_shortcode('summary_cuvee', 'shortcode_summary_cuvee');
