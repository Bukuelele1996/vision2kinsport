<?php
$pageTitle = "Actualités";
$pageDescription = "Toutes les actualités du sport congolais - Football, Boxe, Judo, Basketball et plus";

$category = isset($_GET['category']) ? sanitize($_GET['category']) : null;
$sport = isset($_GET['sport']) ? sanitize($_GET['sport']) : null;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 9;
$offset = ($page - 1) * $perPage;

try {
    $whereClause = "WHERE a.status = 'published'";
    $params = [];
    
    if ($category) {
        $whereClause .= " AND c.slug = ?";
        $params[] = $category;
    }
    
    if ($sport) {
        $whereClause .= " AND s.slug = ?";
        $params[] = $sport;
    }
    
    // Count total
    $countQuery = "SELECT COUNT(*) FROM articles a 
                   LEFT JOIN categories c ON a.category_id = c.id 
                   LEFT JOIN sports s ON a.sport_id = s.id $whereClause";
    $stmt = db()->prepare($countQuery);
    $stmt->execute($params);
    $totalArticles = $stmt->fetchColumn();
    $totalPages = ceil($totalArticles / $perPage);
    
    // Get articles
    $query = "SELECT a.*, c.nom as category_name, c.slug as category_slug, 
              s.nom as sport_name, u.prenom as author_prenom, u.nom as author_nom
              FROM articles a 
              LEFT JOIN categories c ON a.category_id = c.id 
              LEFT JOIN sports s ON a.sport_id = s.id 
              LEFT JOIN users u ON a.author_id = u.id 
              $whereClause 
              ORDER BY a.is_featured DESC, a.published_at DESC 
              LIMIT $perPage OFFSET $offset";
    $stmt = db()->prepare($query);
    $stmt->execute($params);
    $articles = $stmt->fetchAll();
    
    $categories = db()->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY ordre")->fetchAll();
    $sports = db()->query("SELECT * FROM sports WHERE is_active = 1 ORDER BY ordre")->fetchAll();
    
} catch(Exception $e) {
    $articles = [];
    $categories = [];
    $sports = [];
    $totalPages = 1;
}

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="py-16 bg-gradient-to-b from-secondary to-dark">
    <div class="container mx-auto px-4">
        <div class="text-center">
            <span class="inline-block px-4 py-2 bg-primary/20 rounded-full text-primary text-sm font-medium mb-4">ACTUALITÉS</span>
            <h1 class="font-display font-bold text-4xl md:text-5xl text-white mb-4">Dernières Infos Sport</h1>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                Suivez toute l'actualité du sport congolais en temps réel
            </p>
        </div>
    </div>
</section>

<!-- Filters -->
<section class="bg-secondary border-b border-white/10 py-4">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap gap-4 items-center">
            <!-- Category Filter -->
            <div class="flex gap-2 flex-wrap">
                <a href="actualites.php" 
                   class="px-4 py-2 rounded-full text-sm font-medium transition-colors <?php echo !$category ? 'bg-primary text-white' : 'bg-white/10 text-gray-400 hover:text-white'; ?>">
                    Toutes
                </a>
                <?php foreach ($categories as $cat): ?>
                <a href="actualites.php?category=<?php echo $cat['slug']; ?>" 
                   class="px-4 py-2 rounded-full text-sm font-medium transition-colors <?php echo $category === $cat['slug'] ? 'bg-primary text-white' : 'bg-white/10 text-gray-400 hover:text-white'; ?>">
                    <?php echo $cat['nom']; ?>
                </a>
                <?php endforeach; ?>
            </div>
            
            <!-- Sport Filter -->
            <select onchange="window.location.href='actualites.php?sport='+this.value" 
                    class="px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm focus:border-primary focus:outline-none">
                <option value="">Tous les sports</option>
                <?php foreach ($sports as $s): ?>
                <option value="<?php echo $s['slug']; ?>" <?php echo $sport === $s['slug'] ? 'selected' : ''; ?>>
                    <?php echo $s['nom']; ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</section>

<!-- Articles Grid -->
<section class="py-16 bg-dark">
    <div class="container mx-auto px-4">
        <?php if (empty($articles)): ?>
        
        <!-- Placeholder articles when DB is empty -->
        <?php 
        $placeholderArticles = [
            ['titre' => 'Les Léopards se préparent pour la CAN 2025', 'extrait' => 'L\'équipe nationale de football intensifie ses préparations en vue de la prochaine Coupe d\'Afrique des Nations.', 'category' => 'Actualités', 'date' => date('Y-m-d'), 'featured' => 1],
            ['titre' => 'TP Mazembe remporte le derby congolais', 'extrait' => 'Victoire éclatante du TP Mazembe face à l\'AS V.Club dans un match au sommet de la Linafoot.', 'category' => 'Actualités', 'date' => date('Y-m-d', strtotime('-1 day')), 'featured' => 0],
            ['titre' => 'Junior Kabananga rejoint un club européen', 'extrait' => 'L\'attaquant congolais signe un contrat de 3 ans avec un club de première division européenne.', 'category' => 'Transferts', 'date' => date('Y-m-d', strtotime('-2 days')), 'featured' => 0],
            ['titre' => 'Championnat national de boxe ce week-end', 'extrait' => 'Les meilleurs boxeurs du pays s\'affrontent pour le titre national dans plusieurs catégories de poids.', 'category' => 'Actualités', 'date' => date('Y-m-d', strtotime('-3 days')), 'featured' => 0],
            ['titre' => 'Interview exclusive avec le sélectionneur', 'extrait' => 'Le coach des Léopards nous parle de sa stratégie et de ses ambitions pour l\'équipe nationale.', 'category' => 'Interviews', 'date' => date('Y-m-d', strtotime('-4 days')), 'featured' => 0],
            ['titre' => 'Le basket congolais en pleine expansion', 'extrait' => 'Analyse du développement du basketball en RDC avec les nouveaux investissements et infrastructures.', 'category' => 'Analyses', 'date' => date('Y-m-d', strtotime('-5 days')), 'featured' => 0],
        ];
        ?>
        
        <!-- Featured Article -->
        <div class="mb-12">
            <article class="group bg-gradient-to-r from-primary/20 to-accent/10 rounded-3xl overflow-hidden border border-white/10">
                <div class="grid lg:grid-cols-2 gap-0">
                    <div class="aspect-video lg:aspect-auto bg-gradient-to-br from-primary/30 to-dark flex items-center justify-center relative min-h-[300px]">
                        <i class="fas fa-newspaper text-primary/30 text-6xl"></i>
                        <span class="absolute top-4 left-4 px-3 py-1 bg-red-600 rounded text-white text-xs font-bold animate-pulse">
                            BREAKING
                        </span>
                    </div>
                    <div class="p-8 flex flex-col justify-center">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-3 py-1 bg-primary/30 rounded-full text-primary text-sm font-medium">
                                <?php echo $placeholderArticles[0]['category']; ?>
                            </span>
                            <span class="text-gray-400 text-sm"><?php echo formatDate($placeholderArticles[0]['date']); ?></span>
                        </div>
                        <h2 class="font-display font-bold text-2xl lg:text-3xl text-white mb-4 group-hover:text-primary transition-colors">
                            <?php echo $placeholderArticles[0]['titre']; ?>
                        </h2>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            <?php echo $placeholderArticles[0]['extrait']; ?>
                        </p>
                        <a href="#" class="inline-flex items-center gap-2 text-primary hover:text-accent font-semibold transition-colors">
                            Lire l'article complet <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </article>
        </div>
        
        <!-- Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach (array_slice($placeholderArticles, 1) as $article): ?>
            <article class="group bg-secondary rounded-2xl overflow-hidden border border-white/10 card-hover">
                <div class="aspect-video bg-gradient-to-br from-primary/10 to-secondary flex items-center justify-center">
                    <i class="fas fa-newspaper text-primary/30 text-4xl"></i>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="px-2 py-1 bg-primary/20 rounded text-primary text-xs font-medium">
                            <?php echo $article['category']; ?>
                        </span>
                        <span class="text-gray-500 text-sm"><?php echo formatDate($article['date']); ?></span>
                    </div>
                    <h3 class="font-semibold text-white text-lg mb-3 group-hover:text-primary transition-colors line-clamp-2">
                        <?php echo $article['titre']; ?>
                    </h3>
                    <p class="text-gray-400 text-sm line-clamp-2"><?php echo $article['extrait']; ?></p>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        
        <?php else: ?>
        
        <!-- Database Articles -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($articles as $article): ?>
            <article class="group bg-secondary rounded-2xl overflow-hidden border border-white/10 card-hover">
                <div class="aspect-video bg-gradient-to-br from-primary/10 to-secondary flex items-center justify-center relative overflow-hidden">
                    <?php if ($article['image']): ?>
                        <img src="<?php echo UPLOADS_URL . $article['image']; ?>" alt="<?php echo $article['titre']; ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <?php else: ?>
                        <i class="fas fa-newspaper text-primary/30 text-4xl"></i>
                    <?php endif; ?>
                    <?php if ($article['is_breaking']): ?>
                    <span class="absolute top-3 left-3 px-2 py-1 bg-red-600 rounded text-white text-xs font-bold animate-pulse">BREAKING</span>
                    <?php endif; ?>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="px-2 py-1 bg-primary/20 rounded text-primary text-xs font-medium">
                            <?php echo $article['category_name'] ?: 'Actualités'; ?>
                        </span>
                        <span class="text-gray-500 text-sm"><?php echo formatDate($article['published_at']); ?></span>
                    </div>
                    <h3 class="font-semibold text-white text-lg mb-3 group-hover:text-primary transition-colors line-clamp-2">
                        <a href="article.php?slug=<?php echo $article['slug']; ?>">
                            <?php echo $article['titre']; ?>
                        </a>
                    </h3>
                    <p class="text-gray-400 text-sm line-clamp-2"><?php echo truncateText($article['extrait'], 120); ?></p>
                    <?php if ($article['author_prenom']): ?>
                    <div class="mt-4 pt-4 border-t border-white/10 flex items-center gap-2 text-gray-500 text-sm">
                        <i class="fas fa-user-circle"></i>
                        <span><?php echo $article['author_prenom'] . ' ' . $article['author_nom']; ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="flex justify-center gap-2 mt-12">
            <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?><?php echo $category ? '&category='.$category : ''; ?><?php echo $sport ? '&sport='.$sport : ''; ?>" 
               class="px-4 py-2 bg-white/10 rounded-lg text-white hover:bg-primary transition-colors">
                <i class="fas fa-chevron-left"></i>
            </a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?><?php echo $category ? '&category='.$category : ''; ?><?php echo $sport ? '&sport='.$sport : ''; ?>" 
               class="px-4 py-2 rounded-lg transition-colors <?php echo $i === $page ? 'bg-primary text-white' : 'bg-white/10 text-white hover:bg-primary'; ?>">
                <?php echo $i; ?>
            </a>
            <?php endfor; ?>
            
            <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo $page + 1; ?><?php echo $category ? '&category='.$category : ''; ?><?php echo $sport ? '&sport='.$sport : ''; ?>" 
               class="px-4 py-2 bg-white/10 rounded-lg text-white hover:bg-primary transition-colors">
                <i class="fas fa-chevron-right"></i>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
