<?php
/*
 * Plugin Name:       Mon Plugin
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       WebScraping
 * Version:           0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            LucasMarteau, HectorMorlaix
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       my-basics-plugin
 * Domain Path:       /languages
 */

// Inclusion des fichiers du plugin
require_once plugin_dir_path(__FILE__) . 'includes/admin/admin-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/search/search.php';
require_once plugin_dir_path(__FILE__) . 'includes/scraping/scraper.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin/publish-post.php';

// Inclusion des dépendances
require_once plugin_dir_path(__FILE__) . 'simple_html_dom.php';

// Activation du plugin
function mon_plugin_activate() {
    // Code à exécuter lors de l'activation du plugin
}
register_activation_hook(__FILE__, 'mon_plugin_activate');

// Désactivation du plugin
function mon_plugin_deactivate() {
    // Code à exécuter lors de la désactivation du plugin
}
register_deactivation_hook(__FILE__, 'mon_plugin_deactivate');
?>
