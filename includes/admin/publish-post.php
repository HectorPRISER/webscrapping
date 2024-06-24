<?php
// Fonction pour publier le contenu scrappé en tant que post WordPress
function mon_plugin_publish_scraped_content($content) {
    // Vérifier les capacités de l'utilisateur
    if (!current_user_can('publish_posts')) {
        return 'Permission refusée.';
    }

    // Remplacer le texte "Image: [url]" par des balises d'images réelles
    $content_with_images = preg_replace('/Image: (https?:\/\/[^\s]+)/', '<img src="$1" alt="Image">', $content);

    // Créer un nouvel article WordPress avec le contenu scrappé
    $new_post = array(
        'post_title'    => wp_strip_all_tags('Contenu scrappé'),
        'post_content'  => $content_with_images,
        'post_status'   => 'draft', // Publier directement 'publish', 'draft' pour brouillon
        'post_author'   => get_current_user_id(),
    );

    // Insérer le post dans la base de données
    $post_id = wp_insert_post($new_post);

    if (is_wp_error($post_id)) {
        return 'Erreur lors de la publication de l\'article.';
    }

    return 'Le contenu scrappé a été publié avec succès en tant qu\'article.';
}
?>
