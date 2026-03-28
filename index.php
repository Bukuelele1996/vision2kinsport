<?php
$pageTitle = "Accueil";
$pageDescription = "VISION2KINSPORT - La chaîne TV dédiée au sport congolais. Football, Boxe, Judo, Basket, Tennis, Golf, Rugby.";

// Récupérer les données
try {
    $sports = db()->query("SELECT * FROM sports WHERE is_active = 1 ORDER BY ordre")->fetchAll();
    $emissions = db()->query("SELECT * FROM emissions WHERE is_active = 1 AND is_featured = 1 LIMIT 4")->fetchAll();
    $articles = db()->query("SELECT a.*, c.nom as category_name, u.prenom as author_name 
                             FROM articles a 
                             LEFT JOIN categories c ON a.category_id = c.id 
                             LEFT JOIN users u ON a.author_id = u.id 
                             WHERE a.status = 'published' 
                             ORDER BY a.published_at DESC LIMIT 6")->fetchAll();
    $evenements = db()->query("SELECT e.*, s.nom as sport_name 
                               FROM evenements e 
                               LEFT JOIN sports s ON e.sport_id = s.id 
                               WHERE e.date_debut >= NOW() 
                               ORDER BY e.date_debut ASC LIMIT 4")->fetchAll();
} catch(Exception $e) {
    $sports = [];
    $emissions = [];
    $articles = [];
    $evenements = [];
}

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="relative min-h-[90vh] flex items-center overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-gradient-to-br from-dark via-secondary to-dark"></div>
    <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23FF4500\' fill-opacity=\'0.1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    
    <!-- Animated Shapes -->
    <div class="absolute top-20 right-10 w-72 h-72 bg-primary/20 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute bottom-20 left-10 w-96 h-96 bg-accent/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary/20 rounded-full border border-primary/30">
                    <span class="w-2 h-2 bg-primary rounded-full animate-pulse"></span>
                    <span class="text-primary text-sm font-medium">EN DIRECT - Sport Congolais</span>
                </div>
                
                <h1 class="font-display font-black text-5xl md:text-6xl lg:text-7xl text-white leading-tight">
                    VISION<span class="text-gradient">2K</span><br>INSPORT
                </h1>
                
                <p class="text-xl text-gray-300 leading-relaxed max-w-xl">
                    La chaîne TV 100% dédiée au sport congolais. Suivez la <strong class="text-white">Linafoot</strong>, 
                    les <strong class="text-white">Léopards</strong>, la <strong class="text-white">Boxe</strong>, 
                    le <strong class="text-white">Judo</strong> et bien plus encore !
                </p>
                
                <div class="flex flex-wrap gap-4">
                    <a href="direct.php" class="inline-flex items-center gap-3 px-8 py-4 btn-primary text-white font-bold rounded-xl">
                        <i class="fas fa-play"></i>
                        Regarder en Direct
                    </a>
                    <a href="emissions.php" class="inline-flex items-center gap-3 px-8 py-4 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl border border-white/20 transition-all">
                        <i class="fas fa-tv"></i>
                        Nos Émissions
                    </a>
                </div>
                
                <!-- Stats -->
                <div class="grid grid-cols-3 gap-8 pt-8 border-t border-white/10">
                    <div>
                        <div class="font-display font-bold text-3xl text-white">7</div>
                        <div class="text-gray-400 text-sm">Sports couverts</div>
                    </div>
                    <div>
                        <div class="font-display font-bold text-3xl text-white">24/7</div>
                        <div class="text-gray-400 text-sm">Diffusion</div>
                    </div>
                    <div>
                        <div class="font-display font-bold text-3xl text-white">100%</div>
                        <div class="text-gray-400 text-sm">Congolais</div>
                    </div>
                </div>
            </div>
            
            <!-- Hero Image/Video Player -->
            <div class="relative">
                <div class="relative bg-gradient-to-br from-secondary to-dark rounded-3xl overflow-hidden border border-white/10 shadow-2xl">
                    <div class="aspect-video flex items-center justify-center">
                        <div class="text-center p-8">
                            <div class="w-24 h-24 mx-auto mb-6 gradient-primary rounded-full flex items-center justify-center">
                                <i class="fas fa-play text-white text-3xl ml-2"></i>
                            </div>
                            <h3 class="font-display font-bold text-2xl text-white mb-2">Sport en Direct</h3>
                            <p class="text-gray-400">Cliquez pour regarder</p>
                        </div>
                    </div>
                    
                    <!-- Live Badge -->
                    <div class="absolute top-4 left-4 flex items-center gap-2 px-3 py-1 bg-red-600 rounded-full">
                        <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                        <span class="text-white text-xs font-bold">LIVE</span>
                    </div>
                </div>
                
                <!-- Floating Cards -->
                <div class="absolute -bottom-6 -left-6 bg-secondary p-4 rounded-2xl border border-white/10 shadow-xl hidden md:block">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-primary/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-futbol text-primary text-xl"></i>
                        </div>
                        <div>
                            <div class="text-white font-semibold">Linafoot</div>
                            <div class="text-gray-400 text-sm">Saison en cours</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sports Section -->
<section class="py-20 bg-gradient-to-b from-dark to-secondary">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-2 bg-primary/20 rounded-full text-primary text-sm font-medium mb-4">NOS DISCIPLINES</span>
            <h2 class="font-display font-bold text-4xl md:text-5xl text-white mb-4">Les Sports que Nous Couvrons</h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">De la Linafoot aux arts martiaux, découvrez toute la richesse du sport congolais</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-4">
            <?php 
            $icons = ['fa-futbol', 'fa-hand-fist', 'fa-user-ninja', 'fa-basketball', 'fa-table-tennis-paddle-ball', 'fa-golf-ball-tee', 'fa-football'];
            $i = 0;
            foreach ($sports as $sport): 
                $icon = $icons[$i % count($icons)];
                $i++;
            ?>
            <a href="sports.php?slug=<?php echo $sport['slug']; ?>" 
               class="group bg-white/5 hover:bg-primary/20 border border-white/10 hover:border-primary/50 rounded-2xl p-6 text-center transition-all duration-300 card-hover">
                <div class="w-16 h-16 mx-auto mb-4 bg-primary/20 group-hover:bg-primary rounded-2xl flex items-center justify-center transition-colors">
                    <i class="fas <?php echo $icon; ?> text-primary group-hover:text-white text-2xl transition-colors"></i>
                </div>
                <h3 class="font-semibold text-white group-hover:text-primary transition-colors"><?php echo $sport['nom']; ?></h3>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Emissions Section -->
<section class="py-20 bg-secondary">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-12">
            <div>
                <span class="inline-block px-4 py-2 bg-primary/20 rounded-full text-primary text-sm font-medium mb-4">PROGRAMMES</span>
                <h2 class="font-display font-bold text-4xl text-white">Nos Émissions Phares</h2>
            </div>
            <a href="emissions.php" class="inline-flex items-center gap-2 text-primary hover:text-accent font-semibold transition-colors">
                Voir toutes les émissions <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php if (empty($emissions)): ?>
                <!-- Default emissions if DB is empty -->
                <?php 
                $defaultEmissions = [
                    ['titre' => 'Les Futures du Football Congolais', 'type' => 'emission', 'jour_diffusion' => 'Samedi', 'heure_diffusion' => '18:00'],
                    ['titre' => 'Les Talents du Congo', 'type' => 'magazine', 'jour_diffusion' => 'Dimanche', 'heure_diffusion' => '20:00'],
                    ['titre' => 'Parcours des Léopards', 'type' => 'documentaire', 'jour_diffusion' => 'Vendredi', 'heure_diffusion' => '21:00'],
                    ['titre' => 'Sport Hebdo', 'type' => 'emission', 'jour_diffusion' => 'Dimanche', 'heure_diffusion' => '12:00'],
                ];
                foreach ($defaultEmissions as $emission):
                ?>
                <div class="group bg-dark rounded-2xl overflow-hidden border border-white/10 card-hover">
                    <div class="aspect-video bg-gradient-to-br from-primary/20 to-accent/10 flex items-center justify-center relative">
                        <i class="fas fa-tv text-primary/50 text-5xl"></i>
                        <div class="absolute top-3 left-3 px-2 py-1 bg-primary/80 rounded text-white text-xs font-medium">
                            <?php echo ucfirst($emission['type']); ?>
                        </div>
                    </div>
                    <div class="p-5">
                        <h3 class="font-semibold text-white text-lg mb-2 group-hover:text-primary transition-colors">
                            <?php echo $emission['titre']; ?>
                        </h3>
                        <div class="flex items-center gap-3 text-gray-400 text-sm">
                            <span><i class="far fa-calendar mr-1"></i> <?php echo $emission['jour_diffusion']; ?></span>
                            <span><i class="far fa-clock mr-1"></i> <?php echo substr($emission['heure_diffusion'], 0, 5); ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <?php foreach ($emissions as $emission): ?>
                <div class="group bg-dark rounded-2xl overflow-hidden border border-white/10 card-hover">
                    <div class="aspect-video bg-gradient-to-br from-primary/20 to-accent/10 flex items-center justify-center relative overflow-hidden">
                        <?php if ($emission['image']): ?>
                            <img src="<?php echo UPLOADS_URL . $emission['image']; ?>" alt="<?php echo $emission['titre']; ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i class="fas fa-tv text-primary/50 text-5xl"></i>
                        <?php endif; ?>
                        <div class="absolute top-3 left-3 px-2 py-1 bg-primary/80 rounded text-white text-xs font-medium">
                            <?php echo ucfirst($emission['type']); ?>
                        </div>
                    </div>
                    <div class="p-5">
                        <h3 class="font-semibold text-white text-lg mb-2 group-hover:text-primary transition-colors">
                            <?php echo $emission['titre']; ?>
                        </h3>
                        <div class="flex items-center gap-3 text-gray-400 text-sm">
                            <span><i class="far fa-calendar mr-1"></i> <?php echo $emission['jour_diffusion']; ?></span>
                            <span><i class="far fa-clock mr-1"></i> <?php echo substr($emission['heure_diffusion'], 0, 5); ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Latest News Section -->
<section class="py-20 bg-dark">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-12">
            <div>
                <span class="inline-block px-4 py-2 bg-primary/20 rounded-full text-primary text-sm font-medium mb-4">ACTUALITÉS</span>
                <h2 class="font-display font-bold text-4xl text-white">Dernières Infos Sport</h2>
            </div>
            <a href="actualites.php" class="inline-flex items-center gap-2 text-primary hover:text-accent font-semibold transition-colors">
                Toutes les actualités <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (empty($articles)): ?>
                <!-- Default articles placeholder -->
                <?php for ($j = 0; $j < 3; $j++): ?>
                <article class="group bg-secondary rounded-2xl overflow-hidden border border-white/10 card-hover">
                    <div class="aspect-video bg-gradient-to-br from-primary/10 to-secondary flex items-center justify-center">
                        <i class="fas fa-newspaper text-primary/30 text-5xl"></i>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="px-2 py-1 bg-primary/20 rounded text-primary text-xs font-medium">Actualités</span>
                            <span class="text-gray-500 text-sm"><?php echo date('d/m/Y'); ?></span>
                        </div>
                        <h3 class="font-semibold text-white text-lg mb-3 group-hover:text-primary transition-colors line-clamp-2">
                            Article à venir - Restez connectés
                        </h3>
                        <p class="text-gray-400 text-sm line-clamp-2">Les dernières actualités sportives seront bientôt disponibles.</p>
                    </div>
                </article>
                <?php endfor; ?>
            <?php else: ?>
                <?php foreach ($articles as $article): ?>
                <article class="group bg-secondary rounded-2xl overflow-hidden border border-white/10 card-hover">
                    <div class="aspect-video bg-gradient-to-br from-primary/10 to-secondary flex items-center justify-center overflow-hidden">
                        <?php if ($article['image']): ?>
                            <img src="<?php echo UPLOADS_URL . $article['image']; ?>" alt="<?php echo $article['titre']; ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <?php else: ?>
                            <i class="fas fa-newspaper text-primary/30 text-5xl"></i>
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
                            <?php echo $article['titre']; ?>
                        </h3>
                        <p class="text-gray-400 text-sm line-clamp-2"><?php echo truncateText($article['extrait'], 100); ?></p>
                    </div>
                </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Upcoming Events Section -->
<section class="py-20 bg-gradient-to-b from-secondary to-dark">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="inline-block px-4 py-2 bg-primary/20 rounded-full text-primary text-sm font-medium mb-4">CALENDRIER</span>
            <h2 class="font-display font-bold text-4xl text-white mb-4">Événements à Venir</h2>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php if (empty($evenements)): ?>
                <!-- Placeholder events -->
                <?php 
                $placeholderEvents = [
                    ['titre' => 'Linafoot - Journée 15', 'lieu' => 'Stade des Martyrs', 'date' => '+3 days'],
                    ['titre' => 'Championnat de Boxe', 'lieu' => 'Kinshasa Arena', 'date' => '+7 days'],
                    ['titre' => 'Match Léopards', 'lieu' => 'Stade TP Mazembe', 'date' => '+10 days'],
                    ['titre' => 'Tournoi de Judo', 'lieu' => 'Palais des Sports', 'date' => '+14 days'],
                ];
                foreach ($placeholderEvents as $event):
                ?>
                <div class="bg-dark rounded-2xl p-6 border border-white/10 card-hover">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 bg-accent/20 rounded-full text-accent text-xs font-medium">À venir</span>
                        <span class="text-gray-400 text-sm"><i class="far fa-clock mr-1"></i> <?php echo date('d M', strtotime($event['date'])); ?></span>
                    </div>
                    <h3 class="font-semibold text-white text-lg mb-2"><?php echo $event['titre']; ?></h3>
                    <p class="text-gray-400 text-sm flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-primary"></i>
                        <?php echo $event['lieu']; ?>
                    </p>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <?php foreach ($evenements as $event): ?>
                <div class="bg-dark rounded-2xl p-6 border border-white/10 card-hover">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 bg-accent/20 rounded-full text-accent text-xs font-medium">
                            <?php echo $event['sport_name'] ?: 'Sport'; ?>
                        </span>
                        <span class="text-gray-400 text-sm">
                            <i class="far fa-clock mr-1"></i> <?php echo formatDate($event['date_debut'], 'd M'); ?>
                        </span>
                    </div>
                    <h3 class="font-semibold text-white text-lg mb-2"><?php echo $event['titre']; ?></h3>
                    <p class="text-gray-400 text-sm flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-primary"></i>
                        <?php echo $event['lieu']; ?>
                    </p>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 relative overflow-hidden">
    <div class="absolute inset-0 gradient-primary opacity-90"></div>
    <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    
    <div class="container mx-auto px-4 relative z-10 text-center">
        <h2 class="font-display font-bold text-4xl md:text-5xl text-white mb-6">
            Ne Manquez Aucun Match !
        </h2>
        <p class="text-white/80 text-xl max-w-2xl mx-auto mb-8">
            Abonnez-vous à notre newsletter pour recevoir les dernières actualités, résultats et exclusivités du sport congolais.
        </p>
        <form action="newsletter.php" method="POST" class="flex flex-col sm:flex-row gap-4 max-w-xl mx-auto">
            <input type="email" name="email" placeholder="Votre adresse email" required
                   class="flex-1 px-6 py-4 rounded-xl bg-white/20 border border-white/30 text-white placeholder-white/60 focus:bg-white/30 focus:outline-none transition-colors">
            <button type="submit" class="px-8 py-4 bg-dark text-white font-bold rounded-xl hover:bg-secondary transition-colors">
                S'abonner
            </button>
        </form>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
