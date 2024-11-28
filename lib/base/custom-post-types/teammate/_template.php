<?php
// Shortcode pour afficher un teammate
function shortcode_summary_teammate($attrs)
{
    extract(shortcode_atts(
        array(
            'id' => null
        ),
        $attrs
    ));

    $job = get_post_meta($id, 'teammate_job', true);
	$social_link = get_post_meta($id, 'social_link', true);
    ob_start(); ?>
    <div class='o-teammate-summary'>
        <a target='_blank' href='<?php echo esc_url(get_permalink($id)); ?>'>
            <h3><?php echo esc_html(get_the_title($id)); ?></h3>
            <p><?php echo esc_html($job); ?></p>
			<?php if (!empty($social_link)) : ?>
                <p><a href="<?php echo esc_url($social_link); ?>">Social Link</a></p>
            <?php endif; ?>
            <figure>
                <?php echo get_the_post_thumbnail($id, [260, 260]); ?>
            </figure>
        </a>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('summary_teammate', 'shortcode_summary_teammate');
