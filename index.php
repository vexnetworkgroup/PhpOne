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
                score INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                is_secret BOOLEAN DEFAULT FALSE
            )
        ");
        
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS tags (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) UNIQUE NOT NULL,
                type ENUM('general', 'artist', 'character', 'copyright', 'meta') DEFAULT 'general',
                post_count INT DEFAULT 0
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
    // Sample posts
    $posts = [
        ['Anime Girl Portrait', 'Beautiful anime character art', 'https://via.placeholder.com/800x600/ff6b9d/ffffff?text=Anime+Art', 'https://via.placeholder.com/300x200/ff6b9d/ffffff?text=Anime', 'safe', 0],
        ['Fantasy Landscape', 'Epic fantasy world scenery', 'https://via.placeholder.com/800x600/4ecdc4/ffffff?text=Fantasy', 'https://via.placeholder.com/300x200/4ecdc4/ffffff?text=Fantasy', 'safe', 0],
        ['Secret Art', 'Hidden masterpiece', 'https://via.placeholder.com/800x600/ff9999/ffffff?text=Secret', 'https://via.placeholder.com/300x200/ff9999/ffffff?text=Secret', 'questionable', 1]
    ];
    
    foreach ($posts as $post) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO posts (title, description, image_url, thumbnail_url, rating, is_secret) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute($post);
    }
    
    // Sample tags
    $tags = [
        ['anime', 'general'],
        ['girl', 'general'],
        ['portrait', 'general'],
        ['fantasy', 'general'],
        ['landscape', 'general'],
        ['artist_sample', 'artist'],
        ['character_sample', 'character']
    ];
    
    foreach ($tags as $tag) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO tags (name, type) VALUES (?, ?)");
        $stmt->execute($tag);
    }
    
    // Sample artists
    $artists = [
        ['SampleArtist', 'A talented digital artist', 'https://example.com'],
        ['FantasyMaster', 'Specializes in fantasy art', 'https://fantasy.com']
    ];
    
    foreach ($artists as $artist) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO artists (name, description, website) VALUES (?, ?, ?)");
        $stmt->execute($artist);
    }
}

// Helper functions
function getPosts($pdo, $limit = 20, $offset = 0, $search = '', $tags = [], $rating = '', $include_secret = false) {
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
            $include_secret = isset($_GET['secret']) && $_GET['secret'] === '1';
            
            $posts = getPosts($pdo, $limit, $offset, $search, $tags, $rating, $include_secret);
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
                $stmt = $pdo->prepare("INSERT INTO posts (title, description, image_url, thumbnail_url, rating, is_secret) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $_POST['title'],
                    $_POST['description'],
                    $_POST['image_url'],
                    $_POST['thumbnail_url'] ?? $_POST['image_url'],
                    $_POST['rating'],
                    isset($_POST['is_secret']) ? 1 : 0
                ]);
                break;
                
            case 'add_tag':
                $stmt = $pdo->prepare("INSERT IGNORE INTO tags (name, type) VALUES (?, ?)");
                $stmt->execute([$_POST['tag_name'], $_POST['tag_type']]);
                break;
                
            case 'add_artist':
                $stmt = $pdo->prepare("INSERT INTO artists (name, description, website) VALUES (?, ?, ?)");
                $stmt->execute([$_POST['artist_name'], $_POST['description'], $_POST['website']]);
                break;
        }
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Get current page data
$page = $_GET['page'] ?? 'home';
$search = $_GET['search'] ?? '';
$tag_filter = $_GET['tag'] ?? '';
$rating_filter = $_GET['rating'] ?? '';
$show_secret = isset($_GET['secret']);

$posts = getPosts($pdo, 20, 0, $search, $tag_filter ? [$tag_filter] : [], $rating_filter, $show_secret);
$tags = getTags($pdo);
$artists = getArtists($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rule34 Gallery</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(45deg, #ff6b9d, #4ecdc4);
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
            background: linear-gradient(45deg, #ff6b9d, #4ecdc4);
            color: white;
            font-weight: 500;
            transition: transform 0.3s ease;
        }
        
        .nav a:hover {
            transform: translateY(-2px);
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
            border-color: #4ecdc4;
        }
        
        .search-btn {
            padding: 15px 30px;
            background: linear-gradient(45deg, #ff6b9d, #4ecdc4);
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
        }
        
        .post-card:hover {
            transform: translateY(-5px);
        }
        
        .post-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
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
            background: linear-gradient(45deg, #ff6b9d, #4ecdc4);
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
            border-color: #4ecdc4;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
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
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="header-content">
                <div class="logo">Rule34 Gallery</div>
                <nav class="nav">
                    <a href="?page=home">Home</a>
                    <a href="?page=tags">Tags</a>
                    <a href="?page=artists">Artists</a>
                    <a href="?page=admin">Admin</a>
                    <a href="?page=api">API</a>
                    <a href="?secret=1">🔒 Secret</a>
                </nav>
            </div>
        </header>

        <?php if ($page === 'home'): ?>
            <div class="search-section">
                <form class="search-form" method="GET">
                    <input type="text" name="search" class="search-input" placeholder="Search posts..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="search-btn">Search</button>
                </form>
                
                <div class="filters">
                    <select name="rating" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Ratings</option>
                        <option value="safe" <?= $rating_filter === 'safe' ? 'selected' : '' ?>>Safe</option>
                        <option value="questionable" <?= $rating_filter === 'questionable' ? 'selected' : '' ?>>Questionable</option>
                        <option value="explicit" <?= $rating_filter === 'explicit' ? 'selected' : '' ?>>Explicit</option>
                    </select>
                    
                    <label class="checkbox-group">
                        <input type="checkbox" name="secret" <?= $show_secret ? 'checked' : '' ?> onchange="this.form.submit()">
                        Include Secret Posts
                    </label>
                </div>
            </div>

            <div class="gallery">
                <?php foreach ($posts as $post): ?>
                    <div class="post-card">
                        <img src="<?= htmlspecialchars($post['thumbnail_url']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="post-image">
                        <div class="post-content">
                            <h3 class="post-title"><?= htmlspecialchars($post['title']) ?></h3>
                            <p class="post-description"><?= htmlspecialchars($post['description']) ?></p>
                            <div class="post-meta">
                                <span class="rating-badge rating-<?= $post['rating'] ?>"><?= $post['rating'] ?></span>
                                <?php if ($post['is_secret']): ?>
                                    <span class="secret-badge">Secret</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php elseif ($page === 'tags'): ?>
            <div class="sidebar">
                <h3>All Tags</h3>
                <div class="tag-cloud">
                    <?php foreach ($tags as $tag): ?>
                        <a href="?tag=<?= urlencode($tag['name']) ?>" class="tag tag-<?= $tag['type'] ?>">
                            <?= htmlspecialchars($tag['name']) ?> (<?= $tag['post_count'] ?>)
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

        <?php elseif ($page === 'artists'): ?>
            <div class="sidebar">
                <h3>Artists</h3>
                <?php foreach ($artists as $artist): ?>
                    <div style="margin-bottom: 20px; padding: 15px; background: #f9f9f9; border-radius: 8px;">
                        <h4><?= htmlspecialchars($artist['name']) ?></h4>
                        <p><?= htmlspecialchars($artist['description']) ?></p>
                        <?php if ($artist['website']): ?>
                            <a href="<?= htmlspecialchars($artist['website']) ?>" target="_blank">Website</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php elseif ($page === 'admin'): ?>
            <div class="admin-panel">
                <h3>Add New Post</h3>
                <form method="POST">
                    <input type="hidden" name="action" value="add_post">
                    <div class="form-group">
                        <label>Title:</label>
                        <input type="text" name="title" required>
                    </div>
                    <div class="form-group">
                        <label>Description:</label>
                        <textarea name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Image URL:</label>
                        <input type="url" name="image_url" required>
                    </div>
                    <div class="form-group">
                        <label>Thumbnail URL:</label>
                        <input type="url" name="thumbnail_url">
                    </div>
                    <div class="form-group">
                        <label>Rating:</label>
                        <select name="rating">
                            <option value="safe">Safe</option>
                            <option value="questionable">Questionable</option>
                            <option value="explicit">Explicit</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="checkbox-group">
                            <input type="checkbox" name="is_secret">
                            Secret Post
                        </label>
                    </div>
                    <button type="submit" class="search-btn">Add Post</button>
                </form>
            </div>

            <div class="admin-panel">
                <h3>Add New Tag</h3>
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
                        </select>
                    </div>
                    <button type="submit" class="search-btn">Add Tag</button>
                </form>
            </div>

            <div class="admin-panel">
                <h3>Add New Artist</h3>
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
                    <div class="form-group">
                        <label>Website:</label>
                        <input type="url" name="website">
                    </div>
                    <button type="submit" class="search-btn">Add Artist</button>
                </form>
            </div>

        <?php elseif ($page === 'api'): ?>
            <div class="api-section">
                <h3>API Documentation</h3>
                <p>All API endpoints support CORS and return JSON data.</p>
                
                <h4>Available Endpoints:</h4>
                
                <div class="api-endpoint">
                    <strong>GET /?api=posts</strong><br>
                    Parameters: limit, offset, search, tags, rating, secret<br>
                    Example: <code>/?api=posts&limit=10&search=anime&rating=safe</code>
                </div>
                
                <div class="api-endpoint">
                    <strong>GET /?api=tags</strong><br>
                    Parameters: type<br>
                    Example: <code>/?api=tags&type=artist</code>
                </div>
                
                <div class="api-endpoint">
                    <strong>GET /?api=artists</strong><br>
                    Returns all artists<br>
                    Example: <code>/?api=artists</code>
                </div>
                
                <div class="api-endpoint">
                    <strong>GET /?api=post</strong><br>
                    Parameters: id<br>
                    Example: <code>/?api=post&id=1</code>
                </div>
                
                <h4>Test API:</h4>
                <button onclick="testAPI()" class="search-btn">Test Posts API</button>
                <div id="api-result" style="margin-top: 20px; padding: 15px; background: #f5f5f5; border-radius: 8px; display: none;">
                    <pre id="api-output"></pre>
                </div>
            </div>
        <?php endif; ?>
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
        
        // Auto-submit search form on filter change
        document.querySelectorAll('.filter-select').forEach(select => {
            select.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });
    </script>
</body>
</html>