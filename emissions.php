<?php
$pageTitle = "Émissions";
$pageDescription = "Découvrez toutes nos émissions sportives - Les Futures du Football Congolais, Les Talents du Congo, Parcours des Léopards";

$type = isset($_GET['type']) ? sanitize($_GET['type']) : null;

try {
    if ($type) {
        $stmt = db()->prepare("SELECT e.*, s.nom as sport_name FROM emissions e 
                               LEFT JOIN sports s ON e.sport_id = s.id 
                               WHERE e.type = ? AND e.is_active = 1 ORDER BY e.titre");
        $stmt->execute([$type]);
        $emissions = $stmt->fetchAll();
    } else {
        $emissions = db()->query("SELECT e.*, s.nom as sport_name FROM emissions e 
                                  LEFT JOIN sports s ON e.sport_id = s.id 
                                  WHERE e.is_active = 1 ORDER BY e.is_featured DESC, e.titre")->fetchAll();
    }
} catch(Exception $e) {
    $emissions = [];
}

$types = ['emission', 'documentaire', 'interview', 'reportage', 'magazine', 'direct'];

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="py-16 bg-gradient-to-b from-secondary to-dark">
    <div class="container mx-auto px-4">
        <div class="text-center">
            <span class="inline-block px-4 py-2 bg-primary/20 rounded-full text-primary text-sm font-medium mb-4">PROGRAMMES TV</span>
            <h1 class="font-display font-bold text-4xl md:text-5xl text-white mb-4">Nos Émissions</h1>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                Découvrez toute la programmation sportive de VISION2KINSPORT
            </p>
        </div>
    </div>
</section>

<!-- Filter Tabs -->
<section class="bg-secondary border-b border-white/10 sticky top-20 z-30">
    <div class="container mx-auto px-4">
        <nav class="flex gap-4 overflow-x-auto py-4">
            <a href="emissions.php" 
               class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors <?php echo !$type ? 'bg-primary text-white' : 'bg-white/10 text-gray-400 hover:text-white'; ?>">
                Toutes
            </a>
            <?php foreach ($types as $t): ?>
            <a href="emissions.php?type=<?php echo $t; ?>" 
               class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors <?php echo $type === $t ? 'bg-primary text-white' : 'bg-white/10 text-gray-400 hover:text-white'; ?>">
                <?php echo ucfirst($t); ?>s
            </a>
            <?php endforeach; ?>
        </nav>
    </div>
</section>

<!-- Emissions Grid -->
<section class="py-16 bg-dark">
    <div class="container mx-auto px-4">
        <?php if (empty($emissions)): ?>
        <!-- Default emissions when DB is empty -->
        <?php 
        $defaultEmissions = [
            ['titre' => 'Les Futures du Football Congolais', 'description' => 'Découvrez les jeunes talents qui feront le football congolais de demain. Une émission qui met en lumière les espoirs du football national.', 'type' => 'emission', 'jour_diffusion' => 'Samedi', 'heure_diffusion' => '18:00', 'duree' => 60, 'is_featured' => 1],
            ['titre' => 'Les Talents du Congo', 'description' => 'Portrait des sportifs congolais qui brillent dans leur discipline à travers le pays et à l\'international.', 'type' => 'magazine', 'jour_diffusion' => 'Dimanche', 'heure_diffusion' => '20:00', 'duree' => 45, 'is_featured' => 1],
            ['titre' => 'Parcours des Léopards', 'description' => 'L\'histoire et l\'actualité de l\'équipe nationale de football. Rencontres exclusives avec les joueurs et staff technique.', 'type' => 'documentaire', 'jour_diffusion' => 'Vendredi', 'heure_diffusion' => '21:00', 'duree' => 52, 'is_featured' => 1],
            ['titre' => 'Ring Side', 'description' => 'Magazine dédié à la boxe congolaise et internationale. Combats, interviews et analyses.', 'type' => 'magazine', 'jour_diffusion' => 'Mercredi', 'heure_diffusion' => '19:00', 'duree' => 30, 'is_featured' => 0],
            ['titre' => 'Sport Hebdo', 'description' => 'Le résumé de toute l\'actualité sportive de la semaine en RDC et dans le monde.', 'type' => 'emission', 'jour_diffusion' => 'Dimanche', 'heure_diffusion' => '12:00', 'duree' => 60, 'is_featured' => 1],
            ['titre' => 'Le Grand Débat Sport', 'description' => 'Débats et analyses avec les experts du sport congolais sur les sujets d\'actualité.', 'type' => 'emission', 'jour_diffusion' => 'Mardi', 'heure_diffusion' => '20:00', 'duree' => 90, 'is_featured' => 0],
        ];
        ?>
        
        <!-- Featured Emission -->
        <div class="mb-12">
            <div class="bg-gradient-to-r from-primary/20 to-accent/10 rounded-3xl overflow-hidden border border-white/10">
                <div class="grid lg:grid-cols-2 gap-8 p-8">
                    <div class="aspect-video bg-dark rounded-2xl flex items-center justify-center relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-primary/30 to-transparent rounded-2xl"></div>
                        <div class="relative">
                            <div class="w-24 h-24 bg-primary rounded-full flex items-center justify-center cursor-pointer hover:scale-110 transition-transform">
                                <i class="fas fa-play text-white text-3xl ml-2"></i>
                            </div>
                        </div>
                        <span class="absolute top-4 left-4 px-3 py-1 bg-accent text-dark text-xs font-bold rounded">EN VEDETTE</span>
                    </div>
                    <div class="flex flex-col justify-center">
                        <span class="inline-block px-3 py-1 bg-primary/30 rounded-full text-primary text-sm font-medium mb-4 w-fit">Émission</span>
                        <h2 class="font-display font-bold text-3xl text-white mb-4">Les Futures du Football Congolais</h2>
                        <p class="text-gray-300 mb-6 leading-relaxed">
                            Découvrez les jeunes talents qui feront le football congolais de demain. Une émission qui met en lumière les espoirs du football national.
                        </p>
                        <div class="flex flex-wrap gap-4 text-gray-400 text-sm mb-6">
                            <span><i class="far fa-calendar mr-2"></i>Samedi</span>
                            <span><i class="far fa-clock mr-2"></i>18:00</span>
                            <span><i class="fas fa-stopwatch mr-2"></i>60 min</span>
                        </div>
                        <a href="#" class="inline-flex items-center gap-2 px-6 py-3 btn-primary text-white font-semibold rounded-xl w-fit">
                            <i class="fas fa-play"></i>
                            Regarder
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- All Emissions Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($defaultEmissions as $emission): ?>
            <div class="group bg-secondary rounded-2xl overflow-hidden border border-white/10 card-hover">
                <div class="aspect-video bg-gradient-to-br from-primary/20 to-dark flex items-center justify-center relative">
                    <i class="fas fa-tv text-primary/40 text-5xl"></i>
                    <div class="absolute top-3 left-3 px-2 py-1 bg-primary/80 rounded text-white text-xs font-medium">
                        <?php echo ucfirst($emission['type']); ?>
                    </div>
                    <?php if ($emission['is_featured']): ?>
                    <div class="absolute top-3 right-3 px-2 py-1 bg-accent rounded text-dark text-xs font-bold">
                        <i class="fas fa-star"></i>
                    </div>
                    <?php endif; ?>
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-dark/60">
                        <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center">
                            <i class="fas fa-play text-white text-xl ml-1"></i>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="font-semibold text-white text-lg mb-2 group-hover:text-primary transition-colors">
                        <?php echo $emission['titre']; ?>
                    </h3>
                    <p class="text-gray-400 text-sm mb-4 line-clamp-2"><?php echo $emission['description']; ?></p>
                    <div class="flex items-center justify-between text-gray-500 text-sm">
                        <div class="flex items-center gap-3">
                            <span><i class="far fa-calendar mr-1"></i><?php echo $emission['jour_diffusion']; ?></span>
                            <span><i class="far fa-clock mr-1"></i><?php echo substr($emission['heure_diffusion'], 0, 5); ?></span>
                        </div>
                        <span><?php echo $emission['duree']; ?> min</span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php else: ?>
        
        <!-- Database emissions -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($emissions as $emission): ?>
            <div class="group bg-secondary rounded-2xl overflow-hidden border border-white/10 card-hover">
                <div class="aspect-video bg-gradient-to-br from-primary/20 to-dark flex items-center justify-center relative overflow-hidden">
                    <?php if ($emission['image']): ?>
                        <img src="<?php echo UPLOADS_URL . $emission['image']; ?>" alt="<?php echo $emission['titre']; ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <i class="fas fa-tv text-primary/40 text-5xl"></i>
                    <?php endif; ?>
                    <div class="absolute top-3 left-3 px-2 py-1 bg-primary/80 rounded text-white text-xs font-medium">
                        <?php echo ucfirst($emission['type']); ?>
                    </div>
                    <?php if ($emission['is_featured']): ?>
                    <div class="absolute top-3 right-3 px-2 py-1 bg-accent rounded text-dark text-xs font-bold">
                        <i class="fas fa-star"></i>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="p-6">
                    <h3 class="font-semibold text-white text-lg mb-2 group-hover:text-primary transition-colors">
                        <?php echo $emission['titre']; ?>
                    </h3>
                    <p class="text-gray-400 text-sm mb-4 line-clamp-2"><?php echo $emission['description']; ?></p>
                    <div class="flex items-center justify-between text-gray-500 text-sm">
                        <div class="flex items-center gap-3">
                            <?php if ($emission['jour_diffusion']): ?>
                            <span><i class="far fa-calendar mr-1"></i><?php echo $emission['jour_diffusion']; ?></span>
                            <?php endif; ?>
                            <?php if ($emission['heure_diffusion']): ?>
                            <span><i class="far fa-clock mr-1"></i><?php echo substr($emission['heure_diffusion'], 0, 5); ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if ($emission['duree']): ?>
                        <span><?php echo $emission['duree']; ?> min</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Schedule Section -->
<section class="py-16 bg-secondary">
    <div class="container mx-auto px-4">
        <h2 class="font-display font-bold text-3xl text-white mb-8 text-center">Grille des Programmes</h2>
        
        <div class="bg-dark rounded-2xl border border-white/10 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-primary/20">
                        <tr>
                            <th class="px-6 py-4 text-left text-primary font-semibold">Jour</th>
                            <th class="px-6 py-4 text-left text-primary font-semibold">Heure</th>
                            <th class="px-6 py-4 text-left text-primary font-semibold">Émission</th>
                            <th class="px-6 py-4 text-left text-primary font-semibold">Type</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 text-white">Lundi</td>
                            <td class="px-6 py-4 text-gray-400">20:00</td>
                            <td class="px-6 py-4 text-white">Journal du Sport</td>
                            <td class="px-6 py-4"><span class="px-2 py-1 bg-primary/20 rounded text-primary text-xs">Info</span></td>
                        </tr>
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 text-white">Mardi</td>
                            <td class="px-6 py-4 text-gray-400">20:00</td>
                            <td class="px-6 py-4 text-white">Le Grand Débat Sport</td>
                            <td class="px-6 py-4"><span class="px-2 py-1 bg-blue-500/20 rounded text-blue-400 text-xs">Débat</span></td>
                        </tr>
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 text-white">Mercredi</td>
                            <td class="px-6 py-4 text-gray-400">19:00</td>
                            <td class="px-6 py-4 text-white">Ring Side</td>
                            <td class="px-6 py-4"><span class="px-2 py-1 bg-red-500/20 rounded text-red-400 text-xs">Magazine</span></td>
                        </tr>
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 text-white">Vendredi</td>
                            <td class="px-6 py-4 text-gray-400">21:00</td>
                            <td class="px-6 py-4 text-white">Parcours des Léopards</td>
                            <td class="px-6 py-4"><span class="px-2 py-1 bg-purple-500/20 rounded text-purple-400 text-xs">Documentaire</span></td>
                        </tr>
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 text-white">Samedi</td>
                            <td class="px-6 py-4 text-gray-400">18:00</td>
                            <td class="px-6 py-4 text-white">Les Futures du Football Congolais</td>
                            <td class="px-6 py-4"><span class="px-2 py-1 bg-green-500/20 rounded text-green-400 text-xs">Émission</span></td>
                        </tr>
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 text-white">Dimanche</td>
                            <td class="px-6 py-4 text-gray-400">12:00</td>
                            <td class="px-6 py-4 text-white">Sport Hebdo</td>
                            <td class="px-6 py-4"><span class="px-2 py-1 bg-accent/20 rounded text-accent text-xs">Magazine</span></td>
                        </tr>
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 text-white">Dimanche</td>
                            <td class="px-6 py-4 text-gray-400">20:00</td>
                            <td class="px-6 py-4 text-white">Les Talents du Congo</td>
                            <td class="px-6 py-4"><span class="px-2 py-1 bg-orange-500/20 rounded text-orange-400 text-xs">Portrait</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
