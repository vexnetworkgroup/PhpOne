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
                'description' => 'Beautiful anime character illustration with detailed artwork',
                'image_url' => 'https://via.placeholder.com/800x600/ff6b9d/ffffff?text=Anime+Waifu',
                'thumbnail_url' => 'https://via.placeholder.com/300x200/ff6b9d/ffffff?text=Anime',
                'rating' => 'questionable',
                'category' => 'anime',
                'tags' => ['big_breasts', 'anime', 'waifu', 'cute'],
                'score' => 15,
                'views' => 120,
                'created_at' => date('Y-m-d H:i:s'),
                'is_secret' => false,
                'uploader' => 'AnimeArt123'
            ],
            [
                'id' => 2,
                'title' => 'Hentai Collection',
                'description' => 'Premium hentai artwork collection',
                'image_url' => 'https://via.placeholder.com/800x600/ff4444/ffffff?text=Hentai',
                'thumbnail_url' => 'https://via.placeholder.com/300x200/ff4444/ffffff?text=Hentai',
                'rating' => 'explicit',
                'category' => 'hentai',
                'tags' => ['hentai', 'ahegao', 'tentacles', 'explicit'],
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
                'tags' => ['furry', 'anthro', 'yiff', 'wolf'],
                'score' => 18,
                'views' => 89,
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'is_secret' => false,
                'uploader' => 'FurryFan'
            ],
            [
                'id' => 4,
                'title' => 'VIP Premium Content',
                'description' => 'Exclusive VIP content for premium members',
                'image_url' => 'https://via.placeholder.com/800x600/ff9999/ffffff?text=VIP+Secret',
                'thumbnail_url' => 'https://via.placeholder.com/300x200/ff9999/ffffff?text=VIP',
                'rating' => 'explicit',
                'category' => 'hentai',
                'tags' => ['vip', 'premium', 'exclusive', 'special'],
                'score' => 35,
                'views' => 45,
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 hours')),
                'is_secret' => true,
                'uploader' => 'VIPUploader'
            ],
            [
                'id' => 5,
                'title' => 'Western Cartoon Style',
                'description' => 'Western cartoon R34 content',
                'image_url' => 'https://via.placeholder.com/800x600/4444ff/ffffff?text=Western',
                'thumbnail_url' => 'https://via.placeholder.com/300x200/4444ff/ffffff?text=Western',
                'rating' => 'explicit',
                'category' => 'western',
                'tags' => ['western', 'cartoon', 'r34', 'disney'],
                'score' => 12,
                'views' => 78,
                'created_at' => date('Y-m-d H:i:s', strtotime('-4 hours')),
                'is_secret' => false,
                'uploader' => 'WesternArt'
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
        echo json_encode(['success' => true]);
        exit;
    }
}

// Get current page data
$posts = getPosts(20, 0, '', [], '', '', false);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            min-height: 100vh;
            color: #333;
            overflow-x: hidden;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 20px 0;
            margin-bottom: 30px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideDown 0.6s ease-out;
        }
        
        @keyframes slideDown {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes fadeInUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .logo {
            font-size: 2.8em;
            font-weight: bold;
            background: linear-gradient(45deg, #ff1744, #e91e63, #9c27b0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 30px rgba(255, 23, 68, 0.5);
            animation: pulse 3s infinite;
        }
        
        .nav {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .nav-btn {
            text-decoration: none;
            color: white;
            padding: 12px 24px;
            border-radius: 30px;
            background: linear-gradient(45deg, #ff1744, #e91e63);
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            font-size: 14px;
            position: relative;
            overflow: hidden;
        }
        
        .nav-btn:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .nav-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(255, 23, 68, 0.4);
        }
        
        .nav-btn:hover:before {
            left: 100%;
        }
        
        .nav-btn.active {
            background: linear-gradient(45deg, #9c27b0, #673ab7);
            box-shadow: 0 8px 20px rgba(156, 39, 176, 0.4);
        }
        
        .age-warning {
            background: linear-gradient(45deg, #ff5722, #ff1744);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
            animation: fadeInUp 0.6s ease-out 0.2s both;
            box-shadow: 0 10px 25px rgba(255, 87, 34, 0.3);
        }
        
        .page-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 40px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeInUp 0.6s ease-out 0.4s both;
            display: none;
        }
        
        .page-section.active {
            display: block;
        }
        
        .search-form {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .search-input {
            padding: 18px 25px;
            border: 3px solid #e0e0e0;
            border-radius: 30px;
            font-size: 16px;
            outline: none;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }
        
        .search-input:focus {
            border-color: #e91e63;
            box-shadow: 0 0 20px rgba(233, 30, 99, 0.3);
            transform: translateY(-2px);
        }
        
        .search-btn {
            padding: 18px 35px;
            background: linear-gradient(45deg, #ff1744, #e91e63);
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .search-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(255, 23, 68, 0.4);
        }
        
        .filters {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
        }
        
        .filter-select {
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            background: white;
            outline: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .filter-select:focus {
            border-color: #e91e63;
            box-shadow: 0 0 15px rgba(233, 30, 99, 0.2);
        }
        
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .post-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .post-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        }
        
        .post-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .post-card:hover .post-image {
            transform: scale(1.1);
        }
        
        .post-content {
            padding: 25px;
        }
        
        .post-title {
            font-size: 1.3em;
            font-weight: 700;
            margin-bottom: 12px;
            color: #333;
            line-height: 1.3;
        }
        
        .post-description {
            color: #666;
            margin-bottom: 15px;
            line-height: 1.6;
        }
        
        .post-tags {
            margin: 15px 0;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .post-tag {
            background: linear-gradient(45deg, #e0e0e0, #f5f5f5);
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.85em;
            color: #555;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .post-tag:hover {
            background: linear-gradient(45deg, #ff1744, #e91e63);
            color: white;
            transform: scale(1.1);
        }
        
        .post-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .rating-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .rating-safe { background: linear-gradient(45deg, #4caf50, #66bb6a); color: white; }
        .rating-questionable { background: linear-gradient(45deg, #ff9800, #ffb74d); color: white; }
        .rating-explicit { background: linear-gradient(45deg, #f44336, #ef5350); color: white; }
        
        .category-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            text-transform: uppercase;
            background: linear-gradient(45deg, #2196f3, #42a5f5);
            color: white;
        }
        
        .secret-badge {
            background: linear-gradient(45deg, #9c27b0, #ba68c8);
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
        }
        
        .tag-cloud {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }
        
        .tag {
            padding: 12px 20px;
            background: linear-gradient(45deg, #ff1744, #e91e63);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-size: 0.95em;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .tag:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 25px rgba(255, 23, 68, 0.4);
        }
        
        .tag-artist { background: linear-gradient(45deg, #e91e63, #9c27b0); }
        .tag-character { background: linear-gradient(45deg, #2196f3, #3f51b5); }
        .tag-copyright { background: linear-gradient(45deg, #ff5722, #ff9800); }
        .tag-fetish { background: linear-gradient(45deg, #795548, #5d4037); }
        .tag-species { background: linear-gradient(45deg, #4caf50, #388e3c); }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            outline: none;
            transition: all 0.3s ease;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.9);
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #e91e63;
            box-shadow: 0 0 20px rgba(233, 30, 99, 0.2);
            transform: translateY(-2px);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }
        
        .tags-input {
            border: 3px dashed #e0e0e0;
            padding: 20px;
            border-radius: 15px;
            background: rgba(249, 249, 249, 0.8);
            transition: all 0.3s ease;
        }
        
        .tags-input:hover {
            border-color: #e91e63;
            background: rgba(233, 30, 99, 0.05);
        }
        
        .popular-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 15px;
        }
        
        .popular-tag {
            background: linear-gradient(45deg, #e0e0e0, #f5f5f5);
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.85em;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }
        
        .popular-tag:hover {
            background: linear-gradient(45deg, #e91e63, #ff1744);
            color: white;
            transform: scale(1.1);
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.95);
            backdrop-filter: blur(10px);
        }
        
        .modal-content {
            margin: auto;
            display: block;
            width: 85%;
            max-width: 800px;
            max-height: 85%;
            object-fit: contain;
            margin-top: 5%;
            border-radius: 15px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        }
        
        .close {
            position: absolute;
            top: 20px;
            right: 40px;
            color: #f1f1f1;
            font-size: 50px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .close:hover {
            color: #ff1744;
            transform: scale(1.2);
        }
        
        .stats-info {
            background: linear-gradient(45deg, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.05));
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }
        
        .artist-card {
            background: rgba(249, 249, 249, 0.8);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .artist-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.9);
        }
        
        .artist-links {
            margin-top: 15px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .artist-link {
            padding: 8px 16px;
            background: linear-gradient(45deg, #2196f3, #42a5f5);
            color: white;
            text-decoration: none;
            border-radius: 20px;
            font-size: 0.9em;
            transition: all 0.3s ease;
        }
        
        .artist-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(33, 150, 243, 0.3);
        }
        
        .loading {
            text-align: center;
            padding: 40px;
            font-size: 1.2em;
            color: #666;
        }
        
        .success-message {
            background: linear-gradient(45deg, #4caf50, #66bb6a);
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
            animation: fadeInUp 0.5s ease-out;
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
            
            .nav {
                justify-content: center;
            }
            
            .logo {
                font-size: 2.2em;
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
                    <button class="nav-btn active" onclick="showPage('home')">🏠 Home</button>
                    <button class="nav-btn" onclick="showPage('upload')">📤 Upload</button>
                    <button class="nav-btn" onclick="showPage('tags')">🏷️ Tags</button>
                    <button class="nav-btn" onclick="showPage('artists')">👨‍🎨 Artists</button>
                    <button class="nav-btn" onclick="showPage('admin')">⚙️ Admin</button>
                    <button class="nav-btn" onclick="showPage('api')">🔌 API</button>
                    <button class="nav-btn" onclick="toggleSecret()">🔒 VIP</button>
                </nav>
            </div>
        </header>

        <!-- Home Page -->
        <div id="home-page" class="page-section active">
            <div class="stats-info">
                📊 Total Posts: <span id="total-posts"><?= count(loadJSON($posts_file)) ?></span> | 
                🏷️ Tags: <?= count($tags) ?> | 
                👨‍🎨 Artists: <?= count($artists) ?> | 
                💾 Storage: JSON Files
            </div>
            
            <div style="margin-bottom: 30px;">
                <div class="search-form">
                    <input type="text" id="search-input" class="search-input" placeholder="Search R34 content..." onkeyup="searchPosts()">
                    <button class="search-btn" onclick="searchPosts()">🔍 Search</button>
                </div>
                
                <div class="filters">
                    <select id="rating-filter" class="filter-select" onchange="filterPosts()">
                        <option value="">All Ratings</option>
                        <option value="safe">Safe</option>
                        <option value="questionable">Questionable</option>
                        <option value="explicit">Explicit</option>
                    </select>
                    
                    <select id="category-filter" class="filter-select" onchange="filterPosts()">
                        <option value="">All Categories</option>
                        <option value="anime">Anime</option>
                        <option value="hentai">Hentai</option>
                        <option value="furry">Furry</option>
                        <option value="western">Western</option>
                        <option value="manga">Manga</option>
                        <option value="doujin">Doujin</option>
                        <option value="cosplay">Cosplay</option>
                        <option value="real">Real</option>
                    </select>
                    
                    <label style="display: flex; align-items: center; gap: 10px; color: #333; font-weight: 600;">
                        <input type="checkbox" id="secret-filter" onchange="filterPosts()">
                        🔒 VIP Content
                    </label>
                </div>
            </div>

            <div id="posts-gallery" class="gallery">
                <!-- Posts will be loaded here -->
            </div>
        </div>

        <!-- Upload Page -->
        <div id="upload-page" class="page-section">
            <h2 style="margin-bottom: 20px; text-align: center; color: #333;">📤 Upload R18 Content</h2>
            <p style="color: #666; margin-bottom: 30px; text-align: center;">Share your favorite R34 content with the community. All data stored in JSON files.</p>
            
            <form id="upload-form" onsubmit="uploadPost(event)">
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
                    <label style="display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" name="is_secret">
                        🔒 VIP Content (Premium users only)
                    </label>
                </div>
                
                <button type="submit" class="search-btn" style="width: 100%; padding: 20px; font-size: 18px;">
                    📤 Upload Content
                </button>
            </form>
        </div>

        <!-- Tags Page -->
        <div id="tags-page" class="page-section">
            <h2 style="margin-bottom: 30px; text-align: center; color: #333;">🏷️ All Tags</h2>
            <div id="tags-container" class="tag-cloud">
                <!-- Tags will be loaded here -->
            </div>
        </div>

        <!-- Artists Page -->
        <div id="artists-page" class="page-section">
            <h2 style="margin-bottom: 30px; text-align: center; color: #333;">👨‍🎨 Featured Artists</h2>
            <div id="artists-container">
                <!-- Artists will be loaded here -->
            </div>
        </div>

        <!-- Admin Page -->
        <div id="admin-page" class="page-section">
            <h2 style="margin-bottom: 20px; text-align: center; color: #333;">⚙️ Admin Panel</h2>
            <p style="color: #666; margin-bottom: 30px; text-align: center;">Administrative functions for site management. All data stored in JSON files.</p>
            
            <div class="stats-info">
                📁 Data Files: posts.json (<span id="admin-posts-count"><?= count(loadJSON($posts_file)) ?></span> posts) | 
                tags.json (<?= count($tags) ?> tags) | 
                artists.json (<?= count($artists) ?> artists)
            </div>

            <div style="display: grid; gap: 30px;">
                <div>
                    <h3 style="margin-bottom: 20px;">🏷️ Add New Tag</h3>
                    <form id="tag-form" onsubmit="addNewTag(event)">
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

                <div>
                    <h3 style="margin-bottom: 20px;">👨‍🎨 Add New Artist</h3>
                    <form id="artist-form" onsubmit="addNewArtist(event)">
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
            </div>
        </div>

        <!-- API Page -->
        <div id="api-page" class="page-section">
            <h2 style="margin-bottom: 20px; text-align: center; color: #333;">🔌 API Documentation</h2>
            <p style="color: #666; margin-bottom: 30px; text-align: center;">All API endpoints support CORS and return JSON data. Perfect for building R34 apps! Data stored in JSON files.</p>
            
            <h4 style="margin-bottom: 20px;">Available Endpoints:</h4>
            
            <div style="display: grid; gap: 20px; margin-bottom: 30px;">
                <div style="background: #f5f5f5; padding: 20px; border-radius: 10px; font-family: monospace;">
                    <strong>GET /?api=posts</strong><br>
                    Parameters: limit, offset, search, tags, rating, category, secret<br>
                    Example: <code>/?api=posts&limit=10&search=anime&rating=explicit&category=hentai</code>
                </div>
                
                <div style="background: #f5f5f5; padding: 20px; border-radius: 10px; font-family: monospace;">
                    <strong>GET /?api=tags</strong><br>
                    Parameters: type<br>
                    Example: <code>/?api=tags&type=fetish</code>
                </div>
                
                <div style="background: #f5f5f5; padding: 20px; border-radius: 10px; font-family: monospace;">
                    <strong>GET /?api=artists</strong><br>
                    Returns all artists with social links<br>
                    Example: <code>/?api=artists</code>
                </div>
                
                <div style="background: #f5f5f5; padding: 20px; border-radius: 10px; font-family: monospace;">
                    <strong>GET /?api=post</strong><br>
                    Parameters: id<br>
                    Example: <code>/?api=post&id=1</code>
                </div>
            </div>
            
            <h4 style="margin-bottom: 15px;">Test API:</h4>
            <button onclick="testAPI()" class="search-btn">🧪 Test Posts API</button>
            <div id="api-result" style="margin-top: 20px; padding: 20px; background: #f5f5f5; border-radius: 10px; display: none;">
                <pre id="api-output"></pre>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>

    <script>
        let currentPosts = <?= json_encode($posts) ?>;
        let allTags = <?= json_encode($tags) ?>;
        let allArtists = <?= json_encode($artists) ?>;
        let showSecret = false;

        // Page Navigation
        function showPage(pageId) {
            // Hide all pages
            document.querySelectorAll('.page-section').forEach(page => {
                page.classList.remove('active');
            });
            
            // Remove active class from all nav buttons
            document.querySelectorAll('.nav-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected page
            document.getElementById(pageId + '-page').classList.add('active');
            
            // Add active class to clicked button
            event.target.classList.add('active');
            
            // Load page content
            if (pageId === 'home') {
                loadPosts();
            } else if (pageId === 'tags') {
                loadTags();
            } else if (pageId === 'artists') {
                loadArtists();
            }
        }

        // Load Posts
        function loadPosts() {
            const gallery = document.getElementById('posts-gallery');
            gallery.innerHTML = '<div class="loading">Loading posts...</div>';
            
            setTimeout(() => {
                gallery.innerHTML = '';
                currentPosts.forEach(post => {
                    const postCard = createPostCard(post);
                    gallery.appendChild(postCard);
                });
            }, 300);
        }

        // Create Post Card
        function createPostCard(post) {
            const card = document.createElement('div');
            card.className = 'post-card';
            card.style.animation = 'fadeInUp 0.6s ease-out';
            
            card.innerHTML = `
                <img src="${post.thumbnail_url}" alt="${post.title}" class="post-image" onclick="openModal('${post.image_url}')">
                <div class="post-content">
                    <h3 class="post-title">${post.title}</h3>
                    <p class="post-description">${post.description}</p>
                    <div class="post-tags">
                        ${post.tags.map(tag => `<span class="post-tag">${tag}</span>`).join('')}
                    </div>
                    <div class="post-meta">
                        <div>
                            <span class="rating-badge rating-${post.rating}">${post.rating}</span>
                            <span class="category-badge">${post.category}</span>
                            ${post.is_secret ? '<span class="secret-badge">VIP</span>' : ''}
                        </div>
                        <small>👁️ ${post.views} | ⭐ ${post.score} | 👤 ${post.uploader}</small>
                    </div>
                </div>
            `;
            
            return card;
        }

        // Load Tags
        function loadTags() {
            const container = document.getElementById('tags-container');
            container.innerHTML = '';
            
            allTags.forEach(tag => {
                const tagElement = document.createElement('span');
                tagElement.className = `tag tag-${tag.type}`;
                tagElement.innerHTML = `${tag.name} (${tag.post_count})`;
                tagElement.title = tag.description;
                tagElement.onclick = () => filterByTag(tag.name);
                container.appendChild(tagElement);
            });
        }

        // Load Artists
        function loadArtists() {
            const container = document.getElementById('artists-container');
            container.innerHTML = '';
            
            allArtists.forEach(artist => {
                const artistCard = document.createElement('div');
                artistCard.className = 'artist-card';
                
                let links = '';
                if (artist.website) links += `<a href="${artist.website}" target="_blank" class="artist-link">🌐 Website</a>`;
                if (artist.twitter) links += `<a href="https://twitter.com/${artist.twitter}" target="_blank" class="artist-link">🐦 Twitter</a>`;
                if (artist.pixiv) links += `<a href="https://pixiv.net/users/${artist.pixiv}" target="_blank" class="artist-link">🎨 Pixiv</a>`;
                
                artistCard.innerHTML = `
                    <h4>${artist.name}</h4>
                    <p>${artist.description}</p>
                    <div class="artist-links">${links}</div>
                    <small>📊 Posts: ${artist.post_count}</small>
                `;
                
                container.appendChild(artistCard);
            });
        }

        // Search and Filter Functions
        function searchPosts() {
            filterPosts();
        }

        function filterPosts() {
            const search = document.getElementById('search-input').value.toLowerCase();
            const rating = document.getElementById('rating-filter').value;
            const category = document.getElementById('category-filter').value;
            const includeSecret = document.getElementById('secret-filter').checked;
            
            const params = new URLSearchParams({
                limit: 20,
                search: search,
                rating: rating,
                category: category,
                secret: includeSecret ? '1' : '0'
            });
            
            fetch(`?api=posts&${params}`)
                .then(response => response.json())
                .then(data => {
                    currentPosts = data.posts;
                    loadPosts();
                });
        }

        function filterByTag(tagName) {
            showPage('home');
            document.getElementById('search-input').value = tagName;
            searchPosts();
        }

        function toggleSecret() {
            showSecret = !showSecret;
            document.getElementById('secret-filter').checked = showSecret;
            filterPosts();
        }

        // Upload Functions
        function uploadPost(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            formData.append('action', 'add_post');
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage('Post uploaded successfully!');
                    event.target.reset();
                    // Refresh posts count
                    updatePostsCount();
                }
            });
        }

        function addNewTag(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            formData.append('action', 'add_tag');
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage('Tag added successfully!');
                    event.target.reset();
                }
            });
        }

        function addNewArtist(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            formData.append('action', 'add_artist');
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage('Artist added successfully!');
                    event.target.reset();
                }
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

        // Modal Functions
        function openModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            modal.style.display = 'block';
            modalImg.src = imageSrc;
        }

        document.querySelector('.close').onclick = function() {
            document.getElementById('imageModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('imageModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        // API Test
        function testAPI() {
            fetch('?api=posts&limit=5')
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

        // Utility Functions
        function showSuccessMessage(message) {
            const successDiv = document.createElement('div');
            successDiv.className = 'success-message';
            successDiv.textContent = message;
            
            const container = document.querySelector('.page-section.active');
            container.insertBefore(successDiv, container.firstChild);
            
            setTimeout(() => {
                successDiv.remove();
            }, 3000);
        }

        function updatePostsCount() {
            fetch('?api=posts&limit=1000')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total-posts').textContent = data.count;
                    document.getElementById('admin-posts-count').textContent = data.count;
                });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadPosts();
        });
    </script>
</body>
</html>