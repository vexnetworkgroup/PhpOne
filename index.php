<?php
// Rule34 Website - Single File PHP Application
// Database configuration
$db_host = 'localhost';
$db_name = 'rule34_db';
$db_user = 'root';
$db_pass = '';

// Initialize database connection
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Create database if it doesn't exist
    try {
        $pdo = new PDO("mysql:host=$db_host", $db_user, $db_pass);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS $db_name");
        $pdo->exec("USE $db_name");
        
        // Create tables
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS posts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                description TEXT,
                image_url VARCHAR(500) NOT NULL,
                thumbnail_url VARCHAR(500),
                rating ENUM('safe', 'questionable', 'explicit') DEFAULT 'safe',
                category ENUM('anime', 'hentai', 'furry', 'western', 'manga', 'doujin', 'cosplay', 'real') DEFAULT 'anime',
                score INT DEFAULT 0,
                views INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                is_secret BOOLEAN DEFAULT FALSE,
                uploader VARCHAR(100) DEFAULT 'Anonymous'
            )
        ");
        
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS tags (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) UNIQUE NOT NULL,
                type ENUM('general', 'artist', 'character', 'copyright', 'meta', 'species', 'fetish') DEFAULT 'general',
                post_count INT DEFAULT 0,
                description TEXT
            )
        ");
        
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS post_tags (
                post_id INT,
                tag_id INT,
                PRIMARY KEY (post_id, tag_id),
                FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
                FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
            )
        ");
        
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS artists (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) UNIQUE NOT NULL,
                description TEXT,
                website VARCHAR(255),
                twitter VARCHAR(255),
                pixiv VARCHAR(255),
                post_count INT DEFAULT 0
            )
        ");
        
        // Insert sample data
        insertSampleData($pdo);
        
    } catch(PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}

function insertSampleData($pdo) {
    // Sample R18 posts
    $posts = [
        ['Anime Waifu Art', 'Beautiful anime character illustration', 'https://via.placeholder.com/800x600/ff6b9d/ffffff?text=Anime+Waifu', 'https://via.placeholder.com/300x200/ff6b9d/ffffff?text=Anime', 'questionable', 'anime', 0],
        ['Hentai Collection', 'Premium hentai artwork', 'https://via.placeholder.com/800x600/ff4444/ffffff?text=Hentai', 'https://via.placeholder.com/300x200/ff4444/ffffff?text=Hentai', 'explicit', 'hentai', 1],
        ['Furry Art', 'Anthropomorphic character art', 'https://via.placeholder.com/800x600/44ff44/ffffff?text=Furry', 'https://via.placeholder.com/300x200/44ff44/ffffff?text=Furry', 'explicit', 'furry', 0],
        ['Western Style', 'Western cartoon R34', 'https://via.placeholder.com/800x600/4444ff/ffffff?text=Western', 'https://via.placeholder.com/300x200/4444ff/ffffff?text=Western', 'explicit', 'western', 0],
        ['Secret Premium', 'VIP exclusive content', 'https://via.placeholder.com/800x600/ff9999/ffffff?text=Secret', 'https://via.placeholder.com/300x200/ff9999/ffffff?text=Secret', 'explicit', 'hentai', 1]
    ];
    
    foreach ($posts as $post) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO posts (title, description, image_url, thumbnail_url, rating, category, is_secret) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute($post);
    }
    
    // Sample R18 tags
    $tags = [
        ['big_breasts', 'general', 'Large chest size'],
        ['ahegao', 'general', 'Facial expression'],
        ['tentacles', 'general', 'Tentacle content'],
        ['futanari', 'general', 'Hermaphrodite characters'],
        ['yuri', 'general', 'Girl on girl'],
        ['yaoi', 'general', 'Boy on boy'],
        ['loli', 'general', 'Young-looking characters'],
        ['milf', 'general', 'Mature women'],
        ['monster_girl', 'species', 'Monster girl characters'],
        ['catgirl', 'species', 'Cat-like characters'],
        ['bondage', 'fetish', 'Restraint content'],
        ['bdsm', 'fetish', 'BDSM content'],
        ['artist_shadman', 'artist', 'Popular R34 artist'],
        ['artist_sakimichan', 'artist', 'Popular anime artist'],
        ['naruto', 'copyright', 'Naruto series'],
        ['pokemon', 'copyright', 'Pokemon series'],
        ['overwatch', 'copyright', 'Overwatch game']
    ];
    
    foreach ($tags as $tag) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO tags (name, type, description) VALUES (?, ?, ?)");
        $stmt->execute($tag);
    }
    
    // Sample artists
    $artists = [
        ['Shadman', 'Popular R34 artist', 'https://shadbase.com', '@shadman', ''],
        ['Sakimichan', 'Anime and game character artist', 'https://sakimichan.deviantart.com', '@sakimichanart', 'sakimichan'],
        ['Incase', 'Western and anime style artist', 'https://incaseart.com', '@InCaseArt', ''],
        ['Zone-tan', 'Flash animation artist', 'https://zone-archive.com', '@z0ne', '']
    ];
    
    foreach ($artists as $artist) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO artists (name, description, website, twitter, pixiv) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute($artist);
    }
}

// Helper functions
function getPosts($pdo, $limit = 20, $offset = 0, $search = '', $tags = [], $rating = '', $category = '', $include_secret = false) {
    $sql = "SELECT p.*, GROUP_CONCAT(t.name) as tags FROM posts p 
            LEFT JOIN post_tags pt ON p.id = pt.post_id 
            LEFT JOIN tags t ON pt.tag_id = t.id 
            WHERE 1=1";
    $params = [];
    
    if (!$include_secret) {
        $sql .= " AND p.is_secret = 0";
    }
    
    if ($search) {
        $sql .= " AND (p.title LIKE ? OR p.description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    if ($rating) {
        $sql .= " AND p.rating = ?";
        $params[] = $rating;
    }
    
    if ($category) {
        $sql .= " AND p.category = ?";
        $params[] = $category;
    }
    
    if (!empty($tags)) {
        $placeholders = str_repeat('?,', count($tags) - 1) . '?';
        $sql .= " AND p.id IN (
            SELECT pt.post_id FROM post_tags pt 
            JOIN tags t ON pt.tag_id = t.id 
            WHERE t.name IN ($placeholders)
        )";
        $params = array_merge($params, $tags);
    }
    
    $sql .= " GROUP BY p.id ORDER BY p.created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTags($pdo, $type = '') {
    $sql = "SELECT * FROM tags";
    $params = [];
    
    if ($type) {
        $sql .= " WHERE type = ?";
        $params[] = $type;
    }
    
    $sql .= " ORDER BY post_count DESC, name ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getArtists($pdo) {
    $stmt = $pdo->query("SELECT * FROM artists ORDER BY post_count DESC, name ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

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
            
            $posts = getPosts($pdo, $limit, $offset, $search, $tags, $rating, $category, $include_secret);
            echo json_encode(['posts' => $posts, 'count' => count($posts)]);
            exit;
            
        case 'tags':
            $type = $_GET['type'] ?? '';
            $tags = getTags($pdo, $type);
            echo json_encode(['tags' => $tags]);
            exit;
            
        case 'artists':
            $artists = getArtists($pdo);
            echo json_encode(['artists' => $artists]);
            exit;
            
        case 'post':
            if (isset($_GET['id'])) {
                $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                $post = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Increment view count
                $pdo->prepare("UPDATE posts SET views = views + 1 WHERE id = ?")->execute([$_GET['id']]);
                
                echo json_encode(['post' => $post]);
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
                $stmt = $pdo->prepare("INSERT INTO posts (title, description, image_url, thumbnail_url, rating, category, is_secret, uploader) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $_POST['title'],
                    $_POST['description'],
                    $_POST['image_url'],
                    $_POST['thumbnail_url'] ?? $_POST['image_url'],
                    $_POST['rating'],
                    $_POST['category'],
                    isset($_POST['is_secret']) ? 1 : 0,
                    $_POST['uploader'] ?? 'Anonymous'
                ]);
                
                $post_id = $pdo->lastInsertId();
                
                // Add tags if provided
                if (!empty($_POST['tags'])) {
                    $tag_names = array_map('trim', explode(',', $_POST['tags']));
                    foreach ($tag_names as $tag_name) {
                        if (!empty($tag_name)) {
                            // Insert tag if doesn't exist
                            $stmt = $pdo->prepare("INSERT IGNORE INTO tags (name, type) VALUES (?, 'general')");
                            $stmt->execute([$tag_name]);
                            
                            // Get tag ID
                            $stmt = $pdo->prepare("SELECT id FROM tags WHERE name = ?");
                            $stmt->execute([$tag_name]);
                            $tag_id = $stmt->fetchColumn();
                            
                            // Link post to tag
                            $stmt = $pdo->prepare("INSERT IGNORE INTO post_tags (post_id, tag_id) VALUES (?, ?)");
                            $stmt->execute([$post_id, $tag_id]);
                        }
                    }
                }
                break;
                
            case 'add_tag':
                $stmt = $pdo->prepare("INSERT IGNORE INTO tags (name, type, description) VALUES (?, ?, ?)");
                $stmt->execute([$_POST['tag_name'], $_POST['tag_type'], $_POST['tag_description'] ?? '']);
                break;
                
            case 'add_artist':
                $stmt = $pdo->prepare("INSERT INTO artists (name, description, website, twitter, pixiv) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([
                    $_POST['artist_name'], 
                    $_POST['description'], 
                    $_POST['website'],
                    $_POST['twitter'] ?? '',
                    $_POST['pixiv'] ?? ''
                ]);
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

$posts = getPosts($pdo, 20, 0, $search, $tag_filter ? [$tag_filter] : [], $rating_filter, $category_filter, $show_secret);
$tags = getTags($pdo);
$artists = getArtists($pdo);
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
                            <div class="post-meta">
                                <div>
                                    <span class="rating-badge rating-<?= $post['rating'] ?>"><?= $post['rating'] ?></span>
                                    <span class="category-badge"><?= $post['category'] ?></span>
                                    <?php if ($post['is_secret']): ?>
                                        <span class="secret-badge">VIP</span>
                                    <?php endif; ?>
                                </div>
                                <small>👁️ <?= $post['views'] ?> | ⭐ <?= $post['score'] ?></small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php elseif ($page === 'upload'): ?>
            <div class="upload-section">
                <h2>📤 Upload R18 Content</h2>
                <p style="color: #666; margin-bottom: 20px;">Share your favorite R34 content with the community. All content must comply with site rules.</p>
                
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
                <p style="color: #666; margin-bottom: 20px;">Administrative functions for site management.</p>
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
                        <input type="text" name="artist_name" required>
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
                <p>All API endpoints support CORS and return JSON data. Perfect for building R34 apps!</p>
                
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