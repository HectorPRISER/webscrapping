<?php
// Fonction pour scraper le contenu d'une URL
function scrape_url_content($url) {
    // Initialiser une session cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    // Vérifier les erreurs cURL
    if (curl_errno($ch)) {
        $error_message = curl_error($ch);
        curl_close($ch);
        return "Erreur cURL: $error_message";
    }

    curl_close($ch);

    // Charger le contenu HTML avec simple_html_dom
    $html = str_get_html($response);
    if (!$html) {
        return "Erreur lors de l'analyse du contenu HTML.";
    }

    // Extraire le texte et les images
    $content = '';

    // Extraire les textes
    foreach ($html->find('p') as $element) {
        $content .= '<p>' . $element->plaintext . '</p>';
    }

    // Extraire les images (affichées comme texte ici)
    foreach ($html->find('img') as $element) {
        $content .= '<p>Image: ' . $element->src . '</p>';
    }

    // Nettoyer et retourner le contenu
    return $content ? $content : "Aucun contenu trouvé.";
}
?>
