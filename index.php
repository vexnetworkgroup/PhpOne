<?php
// Rule34 Website - Single File PHP Application with JSON Storage
// JSON file paths
$posts_file = 'data/posts.json';
$tags_file = 'data/tags.json';
$artists_file = 'data/artists.json';

// Create data directory if it doesn't exist
if (!file_exists('data')) {
    mkdir('data', 0755, true);
}

// Initialize JSON files with sample data if they don't exist
function initializeData() {
    global $posts_file, $tags_file, $artists_file;
    
    if (!file_exists($posts_file)) {
        $sample_posts = [
            [
                'id' => 1,
                'title' => 'Anime Waifu Art',
                'description' => 'Beautiful anime character illustration',
                'image_url' => 'https://via.placeholder.com/800x600/ff6b9d/ffffff?text=Anime+Waifu',
                'thumbnail_url' => 'https://via.placeholder.com/300x200/ff6b9d/ffffff?text=Anime',
                'rating' => 'questionable',
                'category' => 'anime',
                'tags' => ['big_breasts', 'anime', 'waifu'],
                'score' => 15,
                'views' => 120,
                'created_at' => date('Y-m-d H:i:s'),
                'is_secret' => false,
                'uploader' => 'AnimeArt123'
            ],
            [
                'id' => 2,
                'title' => 'Hentai Collection',
                'description' => 'Premium hentai artwork',
                'image_url' => 'https://via.placeholder.com/800x600/ff4444/ffffff?text=Hentai',
                'thumbnail_url' => 'https://via.placeholder.com/300x200/ff4444/ffffff?text=Hentai',
                'rating' => 'explicit',
                'category' => 'hentai',
                'tags' => ['hentai', 'ahegao', 'tentacles'],
                'score' => 25,
                'views' => 250,
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                'is_secret' => false,
                'uploader' => 'HentaiMaster'
            ],
            [
                'id' => 3,
                'title' => 'Furry Art',
                'description' => 'Anthropomorphic character art',
                'image_url' => 'https://via.placeholder.com/800x600/44ff44/ffffff?text=Furry',
                'thumbnail_url' => 'https://via.placeholder.com/300x200/44ff44/ffffff?text=Furry',
                'rating' => 'explicit',
                'category' => 'furry',
                'tags' => ['furry', 'anthro', 'yiff'],
                'score' => 18,
                'views' => 89,
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'is_secret' => false,
                'uploader' => 'FurryFan'
            ],
            [
                'id' => 4,
                'title' => 'VIP Premium Content',
                'description' => 'Exclusive VIP content',
                'image_url' => 'https://via.placeholder.com/800x600/ff9999/ffffff?text=VIP+Secret',
                'thumbnail_url' => 'https://via.placeholder.com/300x200/ff9999/ffffff?text=VIP',
                'rating' => 'explicit',
                'category' => 'hentai',
                'tags' => ['vip', 'premium', 'exclusive'],
                'score' => 35,
                'views' => 45,
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 hours')),
                'is_secret' => true,
                'uploader' => 'VIPUploader'
            ]
        ];
        file_put_contents($posts_file, json_encode($sample_posts, JSON_PRETTY_PRINT));
    }
    
    if (!file_exists($tags_file)) {
        $sample_tags = [
            ['name' => 'big_breasts', 'type' => 'general', 'description' => 'Large chest size', 'post_count' => 15],
            ['name' => 'ahegao', 'type' => 'general', 'description' => 'Facial expression', 'post_count' => 8],
            ['name' => 'tentacles', 'type' => 'general', 'description' => 'Tentacle content', 'post_count' => 12],
            ['name' => 'futanari', 'type' => 'general', 'description' => 'Hermaphrodite characters', 'post_count' => 6],
            ['name' => 'yuri', 'type' => 'general', 'description' => 'Girl on girl', 'post_count' => 9],
            ['name' => 'yaoi', 'type' => 'general', 'description' => 'Boy on boy', 'post_count' => 4],
            ['name' => 'loli', 'type' => 'general', 'description' => 'Young-looking characters', 'post_count' => 7],
            ['name' => 'milf', 'type' => 'general', 'description' => 'Mature women', 'post_count' => 11],
            ['name' => 'monster_girl', 'type' => 'species', 'description' => 'Monster girl characters', 'post_count' => 5],
            ['name' => 'catgirl', 'type' => 'species', 'description' => 'Cat-like characters', 'post_count' => 13],
            ['name' => 'bondage', 'type' => 'fetish', 'description' => 'Restraint content', 'post_count' => 8],
            ['name' => 'bdsm', 'type' => 'fetish', 'description' => 'BDSM content', 'post_count' => 6],
            ['name' => 'shadman', 'type' => 'artist', 'description' => 'Popular R34 artist', 'post_count' => 3],
            ['name' => 'sakimichan', 'type' => 'artist', 'description' => 'Popular anime artist', 'post_count' => 4],
            ['name' => 'naruto', 'type' => 'copyright', 'description' => 'Naruto series', 'post_count' => 8],
            ['name' => 'pokemon', 'type' => 'copyright', 'description' => 'Pokemon series', 'post_count' => 12],
            ['name' => 'overwatch', 'type' => 'copyright', 'description' => 'Overwatch game', 'post_count' => 15]
        ];
        file_put_contents($tags_file, json_encode($sample_tags, JSON_PRETTY_PRINT));
    }
    
    if (!file_exists($artists_file)) {
        $sample_artists = [
            [
                'name' => 'Shadman',
                'description' => 'Popular R34 artist known for controversial content',
                'website' => 'https://shadbase.com',
                'twitter' => '@shadman',
                'pixiv' => '',
                'post_count' => 15
            ],
            [
                'name' => 'Sakimichan',
                'description' => 'Anime and game character artist',
                'website' => 'https://sakimichan.deviantart.com',
                'twitter' => '@sakimichanart',
                'pixiv' => 'sakimichan',
                'post_count' => 12
            ],
            [
                'name' => 'Incase',
                'description' => 'Western and anime style R34 artist',
                'website' => 'https://incaseart.com',
                'twitter' => '@InCaseArt',
                'pixiv' => '',
                'post_count' => 8
            ],
            [
                'name' => 'Zone-tan',
                'description' => 'Flash animation and R34 artist',
                'website' => 'https://zone-archive.com',
                'twitter' => '@z0ne',
                'pixiv' => '',
                'post_count' => 6
            ]
        ];
        file_put_contents($artists_file, json_encode($sample_artists, JSON_PRETTY_PRINT));
    }
}

// Helper functions for JSON operations
function loadJSON($file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        return json_decode($content, true) ?: [];
    }
    return [];
}

function saveJSON($file, $data) {
    return file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

function getNextId($data) {
    if (empty($data)) return 1;
    $ids = array_column($data, 'id');
    return max($ids) + 1;
}

function getPosts($limit = 20, $offset = 0, $search = '', $tags = [], $rating = '', $category = '', $include_secret = false) {
    global $posts_file;
    $posts = loadJSON($posts_file);
    
    // Filter posts
    $filtered = array_filter($posts, function($post) use ($search, $tags, $rating, $category, $include_secret) {
        // Secret filter
        if (!$include_secret && $post['is_secret']) {
            return false;
        }
        
        // Search filter
        if ($search && stripos($post['title'], $search) === false && stripos($post['description'], $search) === false) {
            return false;
        }
        
        // Rating filter
        if ($rating && $post['rating'] !== $rating) {
            return false;
        }
        
        // Category filter
        if ($category && $post['category'] !== $category) {
            return false;
        }
        
        // Tags filter
        if (!empty($tags)) {
            $postTags = $post['tags'] ?? [];
            foreach ($tags as $tag) {
                if (!in_array($tag, $postTags)) {
                    return false;
                }
            }
        }
        
        return true;
    });
    
    // Sort by created_at desc
    usort($filtered, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });
    
    // Apply pagination
    return array_slice($filtered, $offset, $limit);
}

function getTags($type = '') {
    global $tags_file;
    $tags = loadJSON($tags_file);
    
    if ($type) {
        $tags = array_filter($tags, function($tag) use ($type) {
            return $tag['type'] === $type;
        });
    }
    
    // Sort by post_count desc, then name asc
    usort($tags, function($a, $b) {
        if ($a['post_count'] === $b['post_count']) {
            return strcmp($a['name'], $b['name']);
        }
        return $b['post_count'] - $a['post_count'];
    });
    
    return $tags;
}

function getArtists() {
    global $artists_file;
    $artists = loadJSON($artists_file);
    
    // Sort by post_count desc, then name asc
    usort($artists, function($a, $b) {
        if ($a['post_count'] === $b['post_count']) {
            return strcmp($a['name'], $b['name']);
        }
        return $b['post_count'] - $a['post_count'];
    });
    
    return $artists;
}

function addPost($data) {
    global $posts_file;
    $posts = loadJSON($posts_file);
    
    $post = [
        'id' => getNextId($posts),
        'title' => $data['title'],
        'description' => $data['description'] ?? '',
        'image_url' => $data['image_url'],
        'thumbnail_url' => $data['thumbnail_url'] ?? $data['image_url'],
        'rating' => $data['rating'],
        'category' => $data['category'],
        'tags' => isset($data['tags']) ? array_map('trim', explode(',', $data['tags'])) : [],
        'score' => 0,
        'views' => 0,
        'created_at' => date('Y-m-d H:i:s'),
        'is_secret' => isset($data['is_secret']),
        'uploader' => $data['uploader'] ?? 'Anonymous'
    ];
    
    $posts[] = $post;
    saveJSON($posts_file, $posts);
    
    // Update tag counts
    updateTagCounts($post['tags']);
    
    return $post['id'];
}

function addTag($name, $type, $description = '') {
    global $tags_file;
    $tags = loadJSON($tags_file);
    
    // Check if tag already exists
    foreach ($tags as $tag) {
        if ($tag['name'] === $name) {
            return false;
        }
    }
    
    $tags[] = [
        'name' => $name,
        'type' => $type,
        'description' => $description,
        'post_count' => 0
    ];
    
    return saveJSON($tags_file, $tags);
}

function addArtist($data) {
    global $artists_file;
    $artists = loadJSON($artists_file);
    
    $artists[] = [
        'name' => $data['name'],
        'description' => $data['description'] ?? '',
        'website' => $data['website'] ?? '',
        'twitter' => $data['twitter'] ?? '',
        'pixiv' => $data['pixiv'] ?? '',
        'post_count' => 0
    ];
    
    return saveJSON($artists_file, $artists);
}

function updateTagCounts($postTags) {
    global $tags_file;
    $tags = loadJSON($tags_file);
    
    // Create new tags if they don't exist
    foreach ($postTags as $tagName) {
        $found = false;
        foreach ($tags as &$tag) {
            if ($tag['name'] === $tagName) {
                $tag['post_count']++;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $tags[] = [
                'name' => $tagName,
                'type' => 'general',
                'description' => '',
                'post_count' => 1
            ];
        }
    }
    
    saveJSON($tags_file, $tags);
}

function incrementViews($postId) {
    global $posts_file;
    $posts = loadJSON($posts_file);
    
    foreach ($posts as &$post) {
        if ($post['id'] == $postId) {
            $post['views']++;
            break;
        }
    }
    
    saveJSON($posts_file, $posts);
}

// Initialize data
initializeData();

// API endpoints
if (isset($_GET['api'])) {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type');
    
    $endpoint = $_GET['api'];
    
    switch ($endpoint) {
        case 'posts':
            $limit = min(100, intval($_GET['limit'] ?? 20));
            $offset = intval($_GET['offset'] ?? 0);
            $search = $_GET['search'] ?? '';
            $tags = isset($_GET['tags']) ? explode(',', $_GET['tags']) : [];
            $rating = $_GET['rating'] ?? '';
            $category = $_GET['category'] ?? '';
            $include_secret = isset($_GET['secret']) && $_GET['secret'] === '1';
            
            $posts = getPosts($limit, $offset, $search, $tags, $rating, $category, $include_secret);
            echo json_encode(['posts' => $posts, 'count' => count($posts)]);
            exit;
            
        case 'tags':
            $type = $_GET['type'] ?? '';
            $tags = getTags($type);
            echo json_encode(['tags' => $tags]);
            exit;
            
        case 'artists':
            $artists = getArtists();
            echo json_encode(['artists' => $artists]);
            exit;
            
        case 'post':
            if (isset($_GET['id'])) {
                $posts = loadJSON($posts_file);
                $post = null;
                foreach ($posts as $p) {
                    if ($p['id'] == $_GET['id']) {
                        $post = $p;
                        break;
                    }
                }
                
                if ($post) {
                    incrementViews($_GET['id']);
                    echo json_encode(['post' => $post]);
                } else {
                    echo json_encode(['error' => 'Post not found']);
                }
            }
            exit;
            
        default:
            echo json_encode(['error' => 'Invalid API endpoint']);
            exit;
    }
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_post':
                addPost($_POST);
                break;
                
            case 'add_tag':
                addTag($_POST['tag_name'], $_POST['tag_type'], $_POST['tag_description'] ?? '');
                break;
                
            case 'add_artist':
                addArtist($_POST);
                break;
        }
        header('Location: ' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
        exit;
    }
}

// Get current page data
$page = $_GET['page'] ?? 'home';
$search = $_GET['search'] ?? '';
$tag_filter = $_GET['tag'] ?? '';
$rating_filter = $_GET['rating'] ?? '';
$category_filter = $_GET['category'] ?? '';
$show_secret = isset($_GET['secret']);

$posts = getPosts(20, 0, $search, $tag_filter ? [$tag_filter] : [], $rating_filter, $category_filter, $show_secret);
$tags = getTags();
$artists = getArtists();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rule34 Gallery - Premium R18 Content</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #2d1b69 0%, #11998e 100%);
            min-height: 100vh;
            color: #333;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 20px 0;
            margin-bottom: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .logo {
            font-size: 2.5em;
            font-weight: bold;
            background: linear-gradient(45deg, #ff1744, #e91e63);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .nav {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .nav a {
            text-decoration: none;
            color: #333;
            padding: 10px 20px;
            border-radius: 25px;
            background: linear-gradient(45deg, #ff1744, #e91e63);
            color: white;
            font-weight: 500;
            transition: transform 0.3s ease;
        }
        
        .nav a:hover {
            transform: translateY(-2px);
        }
        
        .age-warning {
            background: linear-gradient(45deg, #ff5722, #ff1744);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }
        
        .search-section {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .search-form {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .search-input {
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s ease;
        }
        
        .search-input:focus {
            border-color: #e91e63;
        }
        
        .search-btn {
            padding: 15px 30px;
            background: linear-gradient(45deg, #ff1744, #e91e63);
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        
        .search-btn:hover {
            transform: translateY(-2px);
        }
        
        .filters {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .filter-select {
            padding: 10px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 20px;
            background: white;
            outline: none;
        }
        
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .post-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            position: relative;
        }
        
        .post-card:hover {
            transform: translateY(-5px);
        }
        
        .post-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            cursor: pointer;
        }
        
        .post-content {
            padding: 20px;
        }
        
        .post-title {
            font-size: 1.2em;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }
        
        .post-description {
            color: #666;
            margin-bottom: 15px;
            line-height: 1.5;
        }
        
        .post-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .rating-badge {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .rating-safe { background: #4caf50; color: white; }
        .rating-questionable { background: #ff9800; color: white; }
        .rating-explicit { background: #f44336; color: white; }
        
        .category-badge {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: 500;
            text-transform: uppercase;
            background: #2196f3;
            color: white;
        }
        
        .secret-badge {
            background: #9c27b0;
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: 500;
        }
        
        .sidebar {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar h3 {
            margin-bottom: 20px;
            color: #333;
            font-size: 1.3em;
        }
        
        .tag-cloud {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .tag {
            padding: 8px 15px;
            background: linear-gradient(45deg, #ff1744, #e91e63);
            color: white;
            text-decoration: none;
            border-radius: 20px;
            font-size: 0.9em;
            transition: transform 0.3s ease;
        }
        
        .tag:hover {
            transform: scale(1.05);
        }
        
        .tag-artist { background: linear-gradient(45deg, #e91e63, #9c27b0); }
        .tag-character { background: linear-gradient(45deg, #2196f3, #3f51b5); }
        .tag-copyright { background: linear-gradient(45deg, #ff5722, #ff9800); }
        .tag-fetish { background: linear-gradient(45deg, #795548, #5d4037); }
        .tag-species { background: linear-gradient(45deg, #4caf50, #388e3c); }
        
        .admin-panel {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            outline: none;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #e91e63;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .upload-section {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .upload-form {
            display: grid;
            gap: 20px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .tags-input {
            border: 2px dashed #e0e0e0;
            padding: 15px;
            border-radius: 8px;
            background: #f9f9f9;
        }
        
        .popular-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 10px;
        }
        
        .popular-tag {
            background: #e0e0e0;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .popular-tag:hover {
            background: #e91e63;
            color: white;
        }
        
        .api-section {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .api-endpoint {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-family: monospace;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.9);
        }
        
        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            max-height: 80%;
            object-fit: contain;
            margin-top: 5%;
        }
        
        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .stats-info {
            background: rgba(0, 0, 0, 0.1);
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            .search-form {
                grid-template-columns: 1fr;
            }
            
            .gallery {
                grid-template-columns: 1fr;
            }
            
            .filters {
                justify-content: center;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="age-warning">
            ⚠️ WARNING: This site contains adult content (18+). By continuing, you confirm you are of legal age.
        </div>
        
        <header>
            <div class="header-content">
                <div class="logo">Rule34 Gallery</div>
                <nav class="nav">
                    <a href="?page=home">🏠 Home</a>
                    <a href="?page=upload">📤 Upload</a>
                    <a href="?page=tags">🏷️ Tags</a>
                    <a href="?page=artists">👨‍🎨 Artists</a>
                    <a href="?page=admin">⚙️ Admin</a>
                    <a href="?page=api">🔌 API</a>
                    <a href="?secret=1">🔒 VIP</a>
                </nav>
            </div>
        </header>

        <?php if ($page === 'home'): ?>
            <div class="stats-info">
                📊 Total Posts: <?= count(loadJSON($posts_file)) ?> | 🏷️ Tags: <?= count($tags) ?> | 👨‍🎨 Artists: <?= count($artists) ?> | 💾 Storage: JSON Files
            </div>
            
            <div class="search-section">
                <form class="search-form" method="GET">
                    <input type="text" name="search" class="search-input" placeholder="Search R34 content..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="search-btn">🔍 Search</button>
                </form>
                
                <div class="filters">
                    <select name="rating" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Ratings</option>
                        <option value="safe" <?= $rating_filter === 'safe' ? 'selected' : '' ?>>Safe</option>
                        <option value="questionable" <?= $rating_filter === 'questionable' ? 'selected' : '' ?>>Questionable</option>
                        <option value="explicit" <?= $rating_filter === 'explicit' ? 'selected' : '' ?>>Explicit</option>
                    </select>
                    
                    <select name="category" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <option value="anime" <?= $category_filter === 'anime' ? 'selected' : '' ?>>Anime</option>
                        <option value="hentai" <?= $category_filter === 'hentai' ? 'selected' : '' ?>>Hentai</option>
                        <option value="furry" <?= $category_filter === 'furry' ? 'selected' : '' ?>>Furry</option>
                        <option value="western" <?= $category_filter === 'western' ? 'selected' : '' ?>>Western</option>
                        <option value="manga" <?= $category_filter === 'manga' ? 'selected' : '' ?>>Manga</option>
                        <option value="doujin" <?= $category_filter === 'doujin' ? 'selected' : '' ?>>Doujin</option>
                        <option value="cosplay" <?= $category_filter === 'cosplay' ? 'selected' : '' ?>>Cosplay</option>
                        <option value="real" <?= $category_filter === 'real' ? 'selected' : '' ?>>Real</option>
                    </select>
                    
                    <label class="checkbox-group">
                        <input type="checkbox" name="secret" <?= $show_secret ? 'checked' : '' ?> onchange="this.form.submit()">
                        🔒 VIP Content
                    </label>
                </div>
            </div>

            <div class="gallery">
                <?php foreach ($posts as $post): ?>
                    <div class="post-card">
                        <img src="<?= htmlspecialchars($post['thumbnail_url']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="post-image" onclick="openModal('<?= htmlspecialchars($post['image_url']) ?>')">
                        <div class="post-content">
                            <h3 class="post-title"><?= htmlspecialchars($post['title']) ?></h3>
                            <p class="post-description"><?= htmlspecialchars($post['description']) ?></p>
                            <div style="margin: 10px 0;">
                                <?php foreach ($post['tags'] as $tag): ?>
                                    <span style="background: #e0e0e0; padding: 2px 6px; border-radius: 10px; font-size: 0.8em; margin-right: 5px;"><?= htmlspecialchars($tag) ?></span>
                                <?php endforeach; ?>
                            </div>
                            <div class="post-meta">
                                <div>
                                    <span class="rating-badge rating-<?= $post['rating'] ?>"><?= $post['rating'] ?></span>
                                    <span class="category-badge"><?= $post['category'] ?></span>
                                    <?php if ($post['is_secret']): ?>
                                        <span class="secret-badge">VIP</span>
                                    <?php endif; ?>
                                </div>
                                <small>👁️ <?= $post['views'] ?> | ⭐ <?= $post['score'] ?> | 👤 <?= htmlspecialchars($post['uploader']) ?></small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php elseif ($page === 'upload'): ?>
            <div class="upload-section">
                <h2>📤 Upload R18 Content</h2>
                <p style="color: #666; margin-bottom: 20px;">Share your favorite R34 content with the community. All data stored in JSON files.</p>
                
                <form method="POST" class="upload-form">
                    <input type="hidden" name="action" value="add_post">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>🏷️ Title *</label>
                            <input type="text" name="title" required placeholder="Enter a descriptive title">
                        </div>
                        <div class="form-group">
                            <label>👤 Uploader Name</label>
                            <input type="text" name="uploader" placeholder="Anonymous" value="Anonymous">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>📝 Description</label>
                        <textarea name="description" rows="3" placeholder="Describe the content..."></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>🖼️ Image URL *</label>
                            <input type="url" name="image_url" required placeholder="https://example.com/image.jpg">
                        </div>
                        <div class="form-group">
                            <label>🖼️ Thumbnail URL</label>
                            <input type="url" name="thumbnail_url" placeholder="Leave empty to use main image">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>🔞 Rating *</label>
                            <select name="rating" required>
                                <option value="">Select Rating</option>
                                <option value="safe">Safe - No explicit content</option>
                                <option value="questionable">Questionable - Suggestive content</option>
                                <option value="explicit">Explicit - Adult content</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>📂 Category *</label>
                            <select name="category" required>
                                <option value="">Select Category</option>
                                <option value="anime">Anime - Japanese animation style</option>
                                <option value="hentai">Hentai - Explicit anime/manga</option>
                                <option value="furry">Furry - Anthropomorphic characters</option>
                                <option value="western">Western - Western cartoon style</option>
                                <option value="manga">Manga - Japanese comic style</option>
                                <option value="doujin">Doujin - Fan-made manga</option>
                                <option value="cosplay">Cosplay - Costume play</option>
                                <option value="real">Real - Real photography</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>🏷️ Tags (comma-separated)</label>
                        <div class="tags-input">
                            <input type="text" name="tags" placeholder="big_breasts, ahegao, tentacles, etc..." style="border: none; background: transparent; width: 100%; outline: none;">
                            <small style="color: #666;">Popular tags:</small>
                            <div class="popular-tags">
                                <span class="popular-tag" onclick="addTag('big_breasts')">big_breasts</span>
                                <span class="popular-tag" onclick="addTag('ahegao')">ahegao</span>
                                <span class="popular-tag" onclick="addTag('tentacles')">tentacles</span>
                                <span class="popular-tag" onclick="addTag('futanari')">futanari</span>
                                <span class="popular-tag" onclick="addTag('yuri')">yuri</span>
                                <span class="popular-tag" onclick="addTag('yaoi')">yaoi</span>
                                <span class="popular-tag" onclick="addTag('loli')">loli</span>
                                <span class="popular-tag" onclick="addTag('milf')">milf</span>
                                <span class="popular-tag" onclick="addTag('monster_girl')">monster_girl</span>
                                <span class="popular-tag" onclick="addTag('catgirl')">catgirl</span>
                                <span class="popular-tag" onclick="addTag('bondage')">bondage</span>
                                <span class="popular-tag" onclick="addTag('bdsm')">bdsm</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="checkbox-group">
                            <input type="checkbox" name="is_secret">
                            🔒 VIP Content (Premium users only)
                        </label>
                    </div>
                    
                    <button type="submit" class="search-btn" style="width: 100%; padding: 20px;">
                        📤 Upload Content
                    </button>
                </form>
            </div>

        <?php elseif ($page === 'tags'): ?>
            <div class="sidebar">
                <h3>🏷️ All Tags</h3>
                <div class="tag-cloud">
                    <?php foreach ($tags as $tag): ?>
                        <a href="?tag=<?= urlencode($tag['name']) ?>" class="tag tag-<?= $tag['type'] ?>" title="<?= htmlspecialchars($tag['description']) ?>">
                            <?= htmlspecialchars($tag['name']) ?> (<?= $tag['post_count'] ?>)
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

        <?php elseif ($page === 'artists'): ?>
            <div class="sidebar">
                <h3>👨‍🎨 Featured Artists</h3>
                <?php foreach ($artists as $artist): ?>
                    <div style="margin-bottom: 20px; padding: 15px; background: #f9f9f9; border-radius: 8px;">
                        <h4><?= htmlspecialchars($artist['name']) ?></h4>
                        <p><?= htmlspecialchars($artist['description']) ?></p>
                        <div style="margin-top: 10px;">
                            <?php if ($artist['website']): ?>
                                <a href="<?= htmlspecialchars($artist['website']) ?>" target="_blank" style="margin-right: 10px;">🌐 Website</a>
                            <?php endif; ?>
                            <?php if ($artist['twitter']): ?>
                                <a href="https://twitter.com/<?= htmlspecialchars($artist['twitter']) ?>" target="_blank" style="margin-right: 10px;">🐦 Twitter</a>
                            <?php endif; ?>
                            <?php if ($artist['pixiv']): ?>
                                <a href="https://pixiv.net/users/<?= htmlspecialchars($artist['pixiv']) ?>" target="_blank">🎨 Pixiv</a>
                            <?php endif; ?>
                        </div>
                        <small>📊 Posts: <?= $artist['post_count'] ?></small>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php elseif ($page === 'admin'): ?>
            <div class="admin-panel">
                <h3>⚙️ Admin Panel</h3>
                <p style="color: #666; margin-bottom: 20px;">Administrative functions for site management. All data stored in JSON files.</p>
                
                <div class="stats-info">
                    📁 Data Files: posts.json (<?= count(loadJSON($posts_file)) ?> posts) | tags.json (<?= count($tags) ?> tags) | artists.json (<?= count($artists) ?> artists)
                </div>
            </div>

            <div class="admin-panel">
                <h3>🏷️ Add New Tag</h3>
                <form method="POST">
                    <input type="hidden" name="action" value="add_tag">
                    <div class="form-group">
                        <label>Tag Name:</label>
                        <input type="text" name="tag_name" required>
                    </div>
                    <div class="form-group">
                        <label>Tag Type:</label>
                        <select name="tag_type">
                            <option value="general">General</option>
                            <option value="artist">Artist</option>
                            <option value="character">Character</option>
                            <option value="copyright">Copyright</option>
                            <option value="meta">Meta</option>
                            <option value="species">Species</option>
                            <option value="fetish">Fetish</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Description:</label>
                        <textarea name="tag_description" rows="2"></textarea>
                    </div>
                    <button type="submit" class="search-btn">Add Tag</button>
                </form>
            </div>

            <div class="admin-panel">
                <h3>👨‍🎨 Add New Artist</h3>
                <form method="POST">
                    <input type="hidden" name="action" value="add_artist">
                    <div class="form-group">
                        <label>Artist Name:</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Description:</label>
                        <textarea name="description" rows="3"></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Website:</label>
                            <input type="url" name="website">
                        </div>
                        <div class="form-group">
                            <label>Twitter (@username):</label>
                            <input type="text" name="twitter">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Pixiv ID:</label>
                        <input type="text" name="pixiv">
                    </div>
                    <button type="submit" class="search-btn">Add Artist</button>
                </form>
            </div>

        <?php elseif ($page === 'api'): ?>
            <div class="api-section">
                <h3>🔌 API Documentation</h3>
                <p>All API endpoints support CORS and return JSON data. Perfect for building R34 apps! Data stored in JSON files.</p>
                
                <h4>Available Endpoints:</h4>
                
                <div class="api-endpoint">
                    <strong>GET /?api=posts</strong><br>
                    Parameters: limit, offset, search, tags, rating, category, secret<br>
                    Example: <code>/?api=posts&limit=10&search=anime&rating=explicit&category=hentai</code>
                </div>
                
                <div class="api-endpoint">
                    <strong>GET /?api=tags</strong><br>
                    Parameters: type<br>
                    Example: <code>/?api=tags&type=fetish</code>
                </div>
                
                <div class="api-endpoint">
                    <strong>GET /?api=artists</strong><br>
                    Returns all artists with social links<br>
                    Example: <code>/?api=artists</code>
                </div>
                
                <div class="api-endpoint">
                    <strong>GET /?api=post</strong><br>
                    Parameters: id<br>
                    Example: <code>/?api=post&id=1</code>
                </div>
                
                <h4>Test API:</h4>
                <button onclick="testAPI()" class="search-btn">🧪 Test Posts API</button>
                <div id="api-result" style="margin-top: 20px; padding: 15px; background: #f5f5f5; border-radius: 8px; display: none;">
                    <pre id="api-output"></pre>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>

    <script>
        function testAPI() {
            fetch('/?api=posts&limit=5')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('api-result').style.display = 'block';
                    document.getElementById('api-output').textContent = JSON.stringify(data, null, 2);
                })
                .catch(error => {
                    document.getElementById('api-result').style.display = 'block';
                    document.getElementById('api-output').textContent = 'Error: ' + error.message;
                });
        }
        
        function addTag(tagName) {
            const tagsInput = document.querySelector('input[name="tags"]');
            const currentTags = tagsInput.value;
            if (currentTags && !currentTags.endsWith(', ')) {
                tagsInput.value += ', ';
            }
            tagsInput.value += tagName;
        }
        
        function openModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            modal.style.display = 'block';
            modalImg.src = imageSrc;
        }
        
        // Close modal when clicking X or outside image
        document.querySelector('.close').onclick = function() {
            document.getElementById('imageModal').style.display = 'none';
        }
        
        window.onclick = function(event) {
            const modal = document.getElementById('imageModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
        
        // Auto-submit search form on filter change
        document.querySelectorAll('.filter-select').forEach(select => {
            select.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });
    </script>
</body>
</html>