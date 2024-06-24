<?php

// Fonction pour ajouter une page d'administration dans le menu
function mon_plugin_add_admin_page() {
    add_menu_page(
        'Mon Plugin',          // Titre de la page
        'Mon Plugin',          // Texte du menu
        'manage_options',      // Capacité requise pour voir la page
        'mon-plugin-admin',    // Identifiant unique de la page
        'mon_plugin_admin_page_callback', // Fonction de rappel pour afficher la page
        'dashicons-admin-generic', // Icône du menu
        85                      // Position dans le menu
    );
}
add_action('admin_menu', 'mon_plugin_add_admin_page');

// Fonction pour enqueuer les styles CSS de l'administration
function mon_plugin_admin_enqueue_styles($hook) {
    // Vérifier que nous sommes sur la page d'administration de notre plugin
    if ($hook != 'toplevel_page_mon-plugin-admin') {
        return;
    }
    wp_enqueue_style('mon-plugin-admin-styles', plugin_dir_url(__FILE__) . '../../assets/css/admin-style.css');
}
add_action('admin_enqueue_scripts', 'mon_plugin_admin_enqueue_styles');

// Fonction de rappel pour afficher la page d'administration
function mon_plugin_admin_page_callback() {
    $result = '';
    $scraped_content = '';
    $publish_message = '';

    // Vérifier si le formulaire de recherche ou de scraping a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recherche d'articles
        if (isset($_POST['search_keyword'])) {
            $keyword = htmlspecialchars($_POST['search_keyword']);
            $date_filter = isset($_POST['date_filter']) ? htmlspecialchars($_POST['date_filter']) : '';
            if (!empty($keyword)) {
                $result = search_articles_by_keyword($keyword, $date_filter); // Appel de la fonction de recherche d'articles
            } else {
                $result = "Veuillez entrer un mot-clé valide.";
            }
        }

        // Scraping
        if (isset($_POST['scrape_url'])) {
            $url = esc_url_raw($_POST['scrape_url']);
            if (!empty($url)) {
                $scraped_content = scrape_url_content($url); // Appel de la fonction de scraping
            } else {
                $scraped_content = "Veuillez entrer une URL valide.";
            }
        }

        // Publication du contenu scrappé
        if (isset($_POST['publish_content']) && !empty($_POST['scraped_content'])) {
            $content_to_publish = sanitize_text_field($_POST['scraped_content']);
            $publish_message = mon_plugin_publish_scraped_content($content_to_publish); // Publier le contenu scrappé
        }
    }

    // Afficher les formulaires et les résultats
    echo '<div class="wrap">';
    echo '<h1>Mon Plugin</h1>';

    // Formulaire de recherche d'articles
    echo '<h2>Recherche d\'articles</h2>';
    echo '<form method="post">';
    echo '<label for="search_keyword">Entrez un mot-clé pour rechercher des articles :</label>';
    echo '<input type="text" id="search_keyword" name="search_keyword" value="" required>';
    echo '<label for="date_filter">Filtrer par date :</label>';
    echo '<select id="date_filter" name="date_filter">';
    echo '<option value="">Aucun filtre</option>';
    echo '<option value="d1">Dernière heure</option>';
    echo '<option value="d1d">Dernières 24 heures</option>';
    echo '<option value="w">Dernière semaine</option>';
    echo '<option value="m">Dernier mois</option>';
    echo '</select>';
    echo '<input type="submit" name="search" value="Rechercher">';
    echo '</form>';

    if (!empty($result)) {
        echo $result;
    }

    echo '<hr>';

    // Formulaire pour scraper une URL
    echo '<h2>Scraping de contenu</h2>';
    echo '<form method="post">';
    echo '<label for="scrape_url">Entrez une URL à scraper :</label>';
    echo '<input type="text" id="scrape_url" name="scrape_url" value="" required>';
    echo '<input type="submit" value="Scraper">';
    echo '</form>';

    if (!empty($scraped_content)) {
        echo '<div style="border: 1px solid #ccc; padding: 10px; width: 100%; max-height: 500px; overflow-y: scroll;">';
        echo $scraped_content;
        echo '</div>';
    }

    // Bouton pour publier le contenu scrappé
    if (!empty($scraped_content)) {
        echo '<form method="post">';
        echo '<input type="hidden" name="scraped_content" value="' . esc_attr($scraped_content) . '">';
        echo '<input type="submit" name="publish_content" value="Publier">';
        echo '</form>';
    }

    if (!empty($publish_message)) {
        echo '<div class="publish-message">';
        echo $publish_message;
        echo '</div>';
    }

    echo '</div>';
}
?>
