<?php
// Fonction de recherche d'articles
function search_articles_by_keyword($keyword, $date_filter = '') {
    // Vérifier d'abord si les résultats sont en cache
    $cache_key = 'search_results_' . md5($keyword . $date_filter);
    $cached_results = get_transient($cache_key);

    if ($cached_results !== false) {
        return $cached_results; // Retourner les résultats mis en cache
    }

    // Clé API et ID du moteur de recherche personnalisé
    $api_key = 'AIzaSyCdNGWXDb3Vua0wxBktByiBKootLvUuftI'; // Remplacez par votre clé API Google
    $search_engine_id = 'f4d8a5cab2b8741cb'; // Remplacez par l'ID de votre moteur de recherche personnalisé

    // Paramètres de requête de l'API Google Custom Search
    $query_params = [
        'key' => $api_key,
        'cx' => $search_engine_id,
        'q' => $keyword,
        'siteSearch' => 'news.google.com', // Limiter la recherche aux actualités Google
    ];

    // Ajouter les paramètres de filtrage de date s'ils sont définis
    if (!empty($date_filter)) {
        $query_params['dateRestrict'] = $date_filter;
    }

    // URL de l'API Google Custom Search
    $url = 'https://www.googleapis.com/customsearch/v1?' . http_build_query($query_params);

    // Initialisation de cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['User-Agent: MonWebScraper/1.0']);

    // Exécution de la requête cURL
    $response = curl_exec($ch);

    // Gestion des erreurs cURL
    if (curl_errno($ch)) {
        $error_message = curl_error($ch);
        error_log("Erreur cURL: $error_message");
        curl_close($ch);
        return "Message d'erreur: $error_message";
    }

    curl_close($ch);

    // Analyse de la réponse JSON
    $data = json_decode($response, true);

    // Vérification des erreurs de réponse
    if ($data === null || isset($data['error'])) {
        $error_message = isset($data['error']['message']) ? $data['error']['message'] : "Erreur lors de l'analyse de la réponse.";
        error_log("Erreur lors de la récupération des articles: $error_message");
        return "Erreur lors de la récupération des articles: $error_message";
    }

    // Formatage des résultats
    $articles = $data['items'];
    $output = "<h2>Résultats de recherche pour : " . htmlspecialchars($keyword) . "</h2>";
    $output .= "<ul>";
    foreach ($articles as $article) {
        $title = htmlspecialchars($article['title']);
        $url = htmlspecialchars($article['link']);
        $output .= "<li><a href='$url' target='_blank'>$title</a></li>";
    }
    $output .= "</ul>";

    // Stocker les résultats en cache pour une heure (3600 secondes)
    set_transient($cache_key, $output, 3600);

    return $output;
}
?>
