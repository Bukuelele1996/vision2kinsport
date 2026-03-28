<?php
$pageTitle = "Sports";
$pageDescription = "Découvrez tous les sports couverts par VISION2KINSPORT - Football, Boxe, Judo, Basketball, Tennis, Golf, Rugby";

$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : null;

try {
    if ($slug) {
        $stmt = db()->prepare("SELECT * FROM sports WHERE slug = ? AND is_active = 1");
        $stmt->execute([$slug]);
        $selectedSport = $stmt->fetch();
        
        if ($selectedSport) {
            $pageTitle = $selectedSport['nom'];
            
            // Articles du sport
            $stmt = db()->prepare("SELECT a.*, c.nom as category_name FROM articles a 
                                   LEFT JOIN categories c ON a.category_id = c.id 
                                   WHERE a.sport_id = ? AND a.status = 'published' 
                                   ORDER BY a.published_at DESC LIMIT 9");
            $stmt->execute([$selectedSport['id']]);
            $articles = $stmt->fetchAll();
            
            // Équipes du sport
            $stmt = db()->prepare("SELECT * FROM equipes WHERE sport_id = ? AND is_active = 1");
            $stmt->execute([$selectedSport['id']]);
            $equipes = $stmt->fetchAll();
            
            // Athlètes du sport
            $stmt = db()->prepare("SELECT * FROM athletes WHERE sport_id = ? AND is_active = 1 LIMIT 8");
            $stmt->execute([$selectedSport['id']]);
            $athletes = $stmt->fetchAll();
        }
    }
    
    $sports = db()->query("SELECT * FROM sports WHERE is_active = 1 ORDER BY ordre")->fetchAll();
} catch(Exception $e) {
    $sports = [];
    $selectedSport = null;
}

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="py-16 bg-gradient-to-b from-secondary to-dark">
    <div class="container mx-auto px-4">
        <div class="text-center">
            <span class="inline-block px-4 py-2 bg-primary/20 rounded-full text-primary text-sm font-medium mb-4">
                <?php echo $selectedSport ? strtoupper($selectedSport['nom']) : 'NOS DISCIPLINES'; ?>
            </span>
            <h1 class="font-display font-bold text-4xl md:text-5xl text-white mb-4">
                <?php echo $selectedSport ? $selectedSport['nom'] : 'Sports'; ?>
            </h1>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                <?php echo $selectedSport ? $selectedSport['description'] : 'Découvrez toutes les disciplines sportives que nous couvrons'; ?>
            </p>
        </div>
    </div>
</section>

<?php if (!$selectedSport): ?>
<!-- All Sports Grid -->
<section class="py-16 bg-dark">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php 
            $icons = ['fa-futbol', 'fa-hand-fist', 'fa-user-ninja', 'fa-basketball', 'fa-table-tennis-paddle-ball', 'fa-golf-ball-tee', 'fa-football'];
            $i = 0;
            foreach ($sports as $sport): 
                $icon = $icons[$i % count($icons)];
                $i++;
            ?>
            <a href="sports.php?slug=<?php echo $sport['slug']; ?>" 
               class="group bg-secondary rounded-2xl overflow-hidden border border-white/10 card-hover">
                <div class="aspect-video bg-gradient-to-br from-primary/20 to-dark flex items-center justify-center relative">
                    <?php if ($sport['image']): ?>
                        <img src="<?php echo UPLOADS_URL . $sport['image']; ?>" alt="<?php echo $sport['nom']; ?>" class="w-full h-full object-cover opacity-60">
                        <div class="absolute inset-0 bg-gradient-to-t from-dark via-transparent to-transparent"></div>
                    <?php endif; ?>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-20 h-20 bg-primary/30 group-hover:bg-primary rounded-2xl flex items-center justify-center transition-colors">
                            <i class="fas <?php echo $icon; ?> text-white text-3xl"></i>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="font-display font-bold text-xl text-white mb-2 group-hover:text-primary transition-colors">
                        <?php echo $sport['nom']; ?>
                    </h3>
                    <p class="text-gray-400 text-sm line-clamp-2"><?php echo truncateText($sport['description'], 100); ?></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php else: ?>
<!-- Selected Sport Content -->

<!-- Navigation Tabs -->
<section class="bg-secondary border-b border-white/10 sticky top-20 z-30">
    <div class="container mx-auto px-4">
        <nav class="flex gap-8 overflow-x-auto">
            <a href="#actualites" class="py-4 text-primary font-medium border-b-2 border-primary whitespace-nowrap">Actualités</a>
            <a href="#equipes" class="py-4 text-gray-400 hover:text-white font-medium border-b-2 border-transparent whitespace-nowrap">Équipes</a>
            <a href="#athletes" class="py-4 text-gray-400 hover:text-white font-medium border-b-2 border-transparent whitespace-nowrap">Athlètes</a>
        </nav>
    </div>
</section>

<!-- Articles -->
<section id="actualites" class="py-16 bg-dark">
    <div class="container mx-auto px-4">
        <h2 class="font-display font-bold text-3xl text-white mb-8">Actualités <?php echo $selectedSport['nom']; ?></h2>
        
        <?php if (empty($articles)): ?>
        <div class="text-center py-12 bg-secondary rounded-2xl">
            <i class="fas fa-newspaper text-gray-600 text-5xl mb-4"></i>
            <p class="text-gray-400">Aucun article pour le moment</p>
        </div>
        <?php else: ?>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($articles as $article): ?>
            <article class="group bg-secondary rounded-2xl overflow-hidden border border-white/10 card-hover">
                <div class="aspect-video bg-gradient-to-br from-primary/10 to-secondary flex items-center justify-center">
                    <?php if ($article['image']): ?>
                        <img src="<?php echo UPLOADS_URL . $article['image']; ?>" alt="<?php echo $article['titre']; ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <i class="fas fa-newspaper text-primary/30 text-4xl"></i>
                    <?php endif; ?>
                </div>
                <div class="p-5">
                    <span class="text-gray-500 text-sm"><?php echo formatDate($article['published_at']); ?></span>
                    <h3 class="font-semibold text-white mt-2 group-hover:text-primary transition-colors">
                        <?php echo $article['titre']; ?>
                    </h3>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Équipes -->
<section id="equipes" class="py-16 bg-secondary">
    <div class="container mx-auto px-4">
        <h2 class="font-display font-bold text-3xl text-white mb-8">Équipes de <?php echo $selectedSport['nom']; ?></h2>
        
        <?php if (empty($equipes)): ?>
        <div class="text-center py-12 bg-dark rounded-2xl">
            <i class="fas fa-users text-gray-600 text-5xl mb-4"></i>
            <p class="text-gray-400">Aucune équipe enregistrée</p>
        </div>
        <?php else: ?>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($equipes as $equipe): ?>
            <div class="bg-dark rounded-2xl p-6 text-center border border-white/10 card-hover">
                <div class="w-20 h-20 mx-auto mb-4 bg-white/10 rounded-full flex items-center justify-center">
                    <?php if ($equipe['logo']): ?>
                        <img src="<?php echo UPLOADS_URL . $equipe['logo']; ?>" alt="<?php echo $equipe['nom']; ?>" class="w-16 h-16 object-contain">
                    <?php else: ?>
                        <i class="fas fa-shield-halved text-primary text-3xl"></i>
                    <?php endif; ?>
                </div>
                <h3 class="font-semibold text-white"><?php echo $equipe['nom']; ?></h3>
                <p class="text-gray-400 text-sm"><?php echo $equipe['ville']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Athlètes -->
<section id="athletes" class="py-16 bg-dark">
    <div class="container mx-auto px-4">
        <h2 class="font-display font-bold text-3xl text-white mb-8">Athlètes</h2>
        
        <?php if (empty($athletes)): ?>
        <div class="text-center py-12 bg-secondary rounded-2xl">
            <i class="fas fa-running text-gray-600 text-5xl mb-4"></i>
            <p class="text-gray-400">Aucun athlète enregistré</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <?php foreach ($athletes as $athlete): ?>
            <div class="bg-secondary rounded-2xl overflow-hidden border border-white/10 card-hover">
                <div class="aspect-square bg-gradient-to-br from-primary/20 to-dark flex items-center justify-center">
                    <?php if ($athlete['photo']): ?>
                        <img src="<?php echo UPLOADS_URL . $athlete['photo']; ?>" alt="<?php echo $athlete['prenom'] . ' ' . $athlete['nom']; ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <i class="fas fa-user text-primary/30 text-5xl"></i>
                    <?php endif; ?>
                </div>
                <div class="p-4 text-center">
                    <h3 class="font-semibold text-white"><?php echo $athlete['prenom'] . ' ' . $athlete['nom']; ?></h3>
                    <p class="text-gray-400 text-sm"><?php echo $athlete['poste']; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Back Button -->
<section class="py-8 bg-dark">
    <div class="container mx-auto px-4">
        <a href="sports.php" class="inline-flex items-center gap-2 text-primary hover:text-accent transition-colors">
            <i class="fas fa-arrow-left"></i>
            Retour aux sports
        </a>
    </div>
</section>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
