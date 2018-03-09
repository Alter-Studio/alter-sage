<?php

namespace App;

use Roots\Sage\Container;
use Roots\Sage\Assets\JsonManifest;
use Roots\Sage\Template\Blade;
use Roots\Sage\Template\BladeProvider;

/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('sage/main.css', asset_path('styles/main.css'), false, null);
    wp_enqueue_script('sage/main.js', asset_path('scripts/main.js'), ['jquery'], null, true);
}, 100);

/**
 * Theme setup
 */
add_action('after_setup_theme', function () {
    /**
     * Enable features from Soil when plugin is activated
     * @link https://roots.io/plugins/soil/
     */
    add_theme_support('soil-clean-up');
    add_theme_support('soil-jquery-cdn');
    add_theme_support('soil-nav-walker');
    add_theme_support('soil-nice-search');
    add_theme_support('soil-relative-urls');

    /**
     * Enable plugins to manage the document title
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Register navigation menus
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage')
    ]);

    /**
     * Enable post thumbnails
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable HTML5 markup support
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

    /**
     * Enable selective refresh for widgets in customizer
     * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#theme-support-in-sidebars
     */
    add_theme_support('customize-selective-refresh-widgets');

    /**
     * Use main stylesheet for visual editor
     * @see resources/assets/styles/layouts/_tinymce.scss
     */
    add_editor_style(asset_path('styles/main.css'));
}, 20);

/**
 * Register sidebars
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ];
    register_sidebar([
        'name'          => __('Primary', 'sage'),
        'id'            => 'sidebar-primary'
    ] + $config);
    register_sidebar([
        'name'          => __('Footer', 'sage'),
        'id'            => 'sidebar-footer'
    ] + $config);
});

/**
 * Updates the `$post` variable on each iteration of the loop.
 * Note: updated value is only available for subsequently loaded views, such as partials
 */
add_action('the_post', function ($post) {
    sage('blade')->share('post', $post);
});

/**
 * Setup Sage options
 */
add_action('after_setup_theme', function () {
    /**
     * Add JsonManifest to Sage container
     */
    sage()->singleton('sage.assets', function () {
        return new JsonManifest(config('assets.manifest'), config('assets.uri'));
    });

    /**
     * Add Blade to Sage container
     */
    sage()->singleton('sage.blade', function (Container $app) {
        $cachePath = config('view.compiled');
        if (!file_exists($cachePath)) {
            wp_mkdir_p($cachePath);
        }
        (new BladeProvider($app))->register();
        return new Blade($app['view']);
    });

    /**
     * Create @asset() Blade directive
     */
    sage('blade')->compiler()->directive('asset', function ($asset) {
        return "<?= " . __NAMESPACE__ . "\\asset_path({$asset}); ?>";
    });

    /**
     * Advanced Custom Fields Blade directives
     */

    /**
     * Create @getLayout() Blade directive
     */
    sage('blade')->compiler()->directive('ifLayout', function ($expression) {
        $expression = strtr($expression, array('(' => '', ')' => ''));
        $output = "<?php if( get_row_layout() == $expression ): ?>";
        return $output;
    });

    /**
     * Create @elseLayout() Blade directive
     */
    sage('blade')->compiler()->directive('elseLayout', function ($expression) {
        $expression = strtr($expression, array('(' => '', ')' => ''));
        $output = "<?php elseif( get_row_layout() == $expression ): ?>";
        return $output;
    });

    /**
     * Create @endLayout Blade directive
     */
    sage('blade')->compiler()->directive('endLayout', function () {
        return "<?php endif; ?>";
    });


    /**
     * Create @fields() Blade directive
     */
    sage('blade')->compiler()->directive('fields', function ($expression) {
        $expression = strtr($expression, array('(' => '', ')' => ''));
        $output = "<?php if (have_rows($expression)) : ?>";
        $output .= "<?php while (have_rows($expression)) : ?>";
        $output .= "<?php the_row(); ?>";
        return $output;
    });

    /**
     * Create @endFields Blade directive
     */
    sage('blade')->compiler()->directive('endFields', function () {
        return "<?php endwhile; endif; ?>";
    });

    /**
     * Create @field() Blade directive
     */
    sage('blade')->compiler()->directive('field', function ($expression) {
         $expression = strtr($expression, array('(' => '', ')' => ''));
         return "<?php the_field($expression); ?>";
    });

    /**
     * Create @getField() Blade directive
     */
    sage('blade')->compiler()->directive('getField', function ($expression) {
        $expression = strtr($expression, array('(' => '', ')' => ''));
        return "<?php get_field($expression); ?>";
    });

    /**
     * Create @hasField() Blade directive
     */
    sage('blade')->compiler()->directive('hasField', function ($expression) {
        $expression = strtr($expression, array('(' => '', ')' => ''));
        return "<?php if (get_field($expression)) : ?>";
    });

    /**
     * Create @endField Blade directive
     */
    sage('blade')->compiler()->directive('endField', function () {
        return "<?php endif; ?>";
    });

    /**
     * Create @sub() Blade directive
     */
    sage('blade')->compiler()->directive('sub', function ($expression) {
        $expression = strtr($expression, array('(' => '', ')' => ''));
        return "<?php the_sub_field($expression); ?>";
    });

    /**
     * Create @getSub() Blade directive
     */
    sage('blade')->compiler()->directive('getSub', function ($expression) {
        $expression = strtr($expression, array('(' => '', ')' => ''));
        return "<?php get_sub_field($expression); ?>";
    });

    /**
     * Create @hasSub() Blade directive
     */
    sage('blade')->compiler()->directive('hasSub', function ($expression) {
        $expression = strtr($expression, array('(' => '', ')' => ''));
        return "<?php if (get_sub_field($expression)) : ?>";
    });

    /**
     * Create @endSub Blade directive
     */
    sage('blade')->compiler()->directive('endSub', function () {
        return "<?php endif; ?>";
    });


    /**
     * Create @reponsiveImage() Blade directive
     */
    sage('blade')->compiler()->directive('reponsiveImage', function ($expression) {
        $expression = strtr($expression, array('(' => '', ')' => ''));
        //Can probably auto generate src set?
        //Will need to revist in the future
        //Currently using small, medium and large
        //var_dump($expression['sizes']);
        $output = '<div class="img--responsive" style="padding-bottom:';
        $output .= "<?php echo get_field($expression)['sizes']['large-ratio'] ?>%";
        $output .= " >';";
        $output .= '<img class="lazyload" data-sizes="auto" data-src="';
        $output .= "<?php echo get_field($expression)['sizes']['large']; ?>";
        $output .= '" data-srcset="';
        $output .= "<?php echo get_field($expression)['sizes']['small']; ?> 800w,";
        $output .= "<?php echo get_field($expression)['sizes']['medium']; ?> 1600w,";
        $output .= "<?php echo get_field($expression)['sizes']['large']; ?> 1920w,";
        $output .= '" />';
        $output .= '</div>';
        return $output;
    });

    /**
     * Create @img() Blade directives
     */
    sage('blade')->compiler()->directive('recImg', function ($expression) {
        $expression = strtr($expression, array('(' => '', ')' => ''));
        $output = '<div class="ratio--rec">';
        $output .= '<img class="lazyload" data-sizes="auto" data-src="';
        $output .= "<?php echo get_field($expression)['sizes']['rec-medium']; ?>";
        $output .= '" data-srcset="';
        $output .= "<?php echo get_field($expression)['sizes']['rec-small']; ?> 800w,";
        $output .= "<?php echo get_field($expression)['sizes']['rec-medium']; ?> 1600w,";
        $output .= "<?php echo get_field($expression)['sizes']['rec-large']; ?> 1920w,";
        $output .= '" />';
        $output .= '</div>';
        return $output;
    });

    sage('blade')->compiler()->directive('squImg', function ($expression) {
        $expression = strtr($expression, array('(' => '', ')' => ''));
        $output = '<div class="ratio--squ">';
        $output .= '<img class="lazyload" data-sizes="(min-width: 768px) 50vw, 100vw" data-src="';
        $output .= "<?php echo get_field($expression)['sizes']['squ-medium']; ?>";
        $output .= '" data-srcset="';
        $output .= "<?php echo get_field($expression)['sizes']['squ-small']; ?> 800w,";
        $output .= "<?php echo get_field($expression)['sizes']['squ-medium']; ?> 1600w,";
        $output .= "<?php echo get_field($expression)['sizes']['squ-large']; ?> 1920w,";
        $output .= '" />';
        $output .= '</div>';
        return $output;
    });

    sage('blade')->compiler()->directive('widImg', function ($expression) {
        $expression = strtr($expression, array('(' => '', ')' => ''));
        $output = '<div class="ratio--wid">';
        $output .= '<img class="lazyload" data-sizes="auto" data-src="';
        $output .= "<?php echo get_field($expression)['sizes']['wid-medium']; ?>";
        $output .= '" data-srcset="';
        $output .= "<?php echo get_field($expression)['sizes']['wid-small']; ?> 800w,";
        $output .= "<?php echo get_field($expression)['sizes']['wid-medium']; ?> 1600w,";
        $output .= "<?php echo get_field($expression)['sizes']['wid-large']; ?> 1920w,";
        $output .= '" />';
        $output .= '</div>';
        return $output;
    });

    sage('blade')->compiler()->directive('recSub', function ($expression) {
        $expression = strtr($expression, array('(' => '', ')' => ''));
        $output = '<div class="ratio--rec">';
        $output .= '<img class="lazyload" data-sizes="auto" data-src="';
        $output .= "<?php echo get_field($expression)['sizes']['rec-medium']; ?>";
        $output .= '" data-srcset="';
        $output .= "<?php echo get_sub_field($expression)['sizes']['rec-small']; ?> 800w,";
        $output .= "<?php echo get_sub_field($expression)['sizes']['rec-medium']; ?> 1600w,";
        $output .= "<?php echo get_sub_field($expression)['sizes']['rec-large']; ?> 1920w,";
        $output .= '" />';
        $output .= '</div>';
        return $output;
    });

    sage('blade')->compiler()->directive('squSub', function ($expression) {
        $expression = strtr($expression, array('(' => '', ')' => ''));
        $output = '<div class="ratio--squ">';
        $output .= '<img class="lazyload" data-sizes="(min-width: 768px) 50vw, 100vw" data-src="';
        $output .= "<?php echo get_sub_field($expression)['sizes']['squ-medium']; ?>";
        $output .= '" data-srcset="';
        $output .= "<?php echo get_sub_field($expression)['sizes']['squ-small']; ?> 800w,";
        $output .= "<?php echo get_sub_field($expression)['sizes']['squ-medium']; ?> 1600w,";
        $output .= "<?php echo get_sub_field($expression)['sizes']['squ-large']; ?> 1920w,";
        $output .= '" />';
        $output .= '</div>';
        return $output;
    });

    sage('blade')->compiler()->directive('widSub', function ($expression) {
        $expression = strtr($expression, array('(' => '', ')' => ''));
        $output = '<div class="ratio--wid">';
        $output .= '<img class="lazyload" data-sizes="auto" data-src="';
        $output .= "<?php echo get_sub_field($expression)['sizes']['wid-medium']; ?>";
        $output .= '" data-srcset="';
        $output .= "<?php echo get_sub_field($expression)['sizes']['wid-small']; ?> 800w,";
        $output .= "<?php echo get_sub_field($expression)['sizes']['wid-medium']; ?> 1600w,";
        $output .= "<?php echo get_sub_field($expression)['sizes']['wid-large']; ?> 1920w,";
        $output .= '" />';
        $output .= '</div>';
        return $output;
    });

    sage('blade')->compiler()->directive('miniSub', function ($expression) {
        $expression = strtr($expression, array('(' => '', ')' => ''));
        $output = '<div class="ratio--rec ratio--mini">';
        $output .= '<img class="lazyload" data-src="';
        $output .= "<?php echo get_sub_field($expression)['sizes']['rec-small']; ?>";
        $output .= '">';
        $output .= '</div>';
        return $output;
    });

    sage('blade')->compiler()->directive('thumbImg', function ($expression) {
        $expression = strtr($expression, array('(' => '', ')' => ''));
        $output = '<div class="ratio--squ">';
        $output .= '<img class="lazyload" data-src="';
        $output .= "<?php echo get_field($expression)['sizes']['squ-small']; ?>";
        $output .= '">';
        $output .= '</div>';
        return $output;
    });

    sage('blade')->compiler()->directive('thumbSub', function ($expression) {
        $expression = strtr($expression, array('(' => '', ')' => ''));
        $output = '<div class="ratio--squ">';
        $output .= '<img class="lazyload" data-src="';
        $output .= "<?php echo get_sub_field($expression)['sizes']['squ-small']; ?>";
        $output .= '">';
        $output .= '</div>';
        return $output;
    });

    sage('blade')->compiler()->directive('squTax', function ($expression) {
        $term = get_queried_object();
        $expression = strtr($expression, array('(' => '', ')' => ''));
        $output = '<div class="ratio--squ">';
        $output .= '<img class="lazyload" data-sizes="(min-width: 768px) 50vw, 100vw" data-src="';
        $output .= "<?php echo get_field($expression, $term)['sizes']['squ-medium']; ?>";
        $output .= '" data-srcset="';
        $output .= "<?php echo get_field($expression, $term)['sizes']['squ-small']; ?> 800w,";
        $output .= "<?php echo get_field($expression, $term)['sizes']['squ-medium']; ?> 1600w,";
        $output .= "<?php echo get_field($expression, $term)['sizes']['squ-large']; ?> 1920w,";
        $output .= '" />';
        $output .= '</div>';
        return $output;
    });

    /**
     * Create @theGallery() Blade directive
     */
    sage('blade')->compiler()->directive('theGallery', function ($expression) {
        $expression = strtr($expression, array('(' => '', ')' => ''));
        $output = "<?php if(get_field($expression)): ?>";
        $output .= "<?php foreach( get_field($expression) ";
        $output .= 'as $image ):?>';
        $output .= '<div class="ratio--rec">';
        $output .= '<img class="lazyload" data-sizes="auto" data-src="';
        $output .= '<?php echo $image["sizes"]["product-image"]; ?>';
        $output .= '" data-srcset="';
        $output .= '<?php echo $image["sizes"]["rec-small"]; ?> 800w,';
        $output .= '<?php echo $image["sizes"]["product-image"]; ?> 1000w,';
        $output .= '" />';
        $output .= '</div>';
        $output .= '<?php endforeach; ?>';
        $output .= '<?php endif; ?>';
        return $output;
    });
    // Started creating a 'trans' directive, not done yet
    sage('blade')->compiler()->directive('trans', function ($expression) {
        $expression = strtr($expression, array('(' => '', ')' => ''));
        $output = "<?php if( have_rows('trans_terms' , 'option') ):";
        $output .= "while( have_rows('trans_terms', 'option') ): the_row(); ?>";
        $output .= "<?php echo the_sub_field($expression); ?>";
        $output .= "<?php endwhile; endif; ?>";
        return $output;
    });
    // SVG Directive
    sage('blade')->compiler()->directive('icon', function ($expression) {
        $expression = preg_replace('/(\'|&#0*39;)/', '', $expression);
        $output = '<svg viewBox="0 0 100 100" class="icon icon--' . $expression . '">';
        $output .= '<use xlink:href="#'.$expression.'"></use>';
        $output .= '</svg>';
        return $output;
    });
});
