<?php
$pageTitle = "Contact";
$pageDescription = "Contactez VISION2KINSPORT - Votre chaîne TV sportive";

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = sanitize($_POST['nom'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $telephone = sanitize($_POST['telephone'] ?? '');
    $sujet = sanitize($_POST['sujet'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    
    // Validation
    if (empty($nom)) $errors[] = "Le nom est requis";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide";
    if (empty($sujet)) $errors[] = "Le sujet est requis";
    if (empty($message)) $errors[] = "Le message est requis";
    
    if (empty($errors)) {
        try {
            $stmt = db()->prepare("INSERT INTO contacts (nom, email, telephone, sujet, message, ip_address) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nom, $email, $telephone, $sujet, $message, $_SERVER['REMOTE_ADDR']]);
            $success = true;
            $nom = $email = $telephone = $sujet = $message = '';
        } catch(Exception $e) {
            $errors[] = "Une erreur est survenue. Veuillez réessayer.";
        }
    }
}

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="py-16 bg-gradient-to-b from-secondary to-dark">
    <div class="container mx-auto px-4">
        <div class="text-center">
            <span class="inline-block px-4 py-2 bg-primary/20 rounded-full text-primary text-sm font-medium mb-4">CONTACT</span>
            <h1 class="font-display font-bold text-4xl md:text-5xl text-white mb-4">Contactez-nous</h1>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                Une question, une suggestion ou une demande de partenariat ? Notre équipe est à votre écoute.
            </p>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-16 bg-dark">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-3 gap-12">
            <!-- Contact Info -->
            <div class="lg:col-span-1 space-y-8">
                <div>
                    <h2 class="font-display font-bold text-2xl text-white mb-6">Informations</h2>
                    <p class="text-gray-400 leading-relaxed">
                        VISION2KINSPORT est la chaîne TV 100% dédiée au sport congolais. Rejoignez-nous pour vivre votre passion du sport.
                    </p>
                </div>
                
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-primary/20 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-primary"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white mb-1">Adresse</h3>
                            <p class="text-gray-400"><?php echo SITE_ADDRESS; ?></p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-primary/20 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-envelope text-primary"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white mb-1">Email</h3>
                            <a href="mailto:<?php echo SITE_EMAIL; ?>" class="text-gray-400 hover:text-primary transition-colors">
                                <?php echo SITE_EMAIL; ?>
                            </a>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-primary/20 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-phone text-primary"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white mb-1">Téléphone</h3>
                            <a href="tel:<?php echo SITE_PHONE; ?>" class="text-gray-400 hover:text-primary transition-colors">
                                <?php echo SITE_PHONE; ?>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Social Links -->
                <div>
                    <h3 class="font-semibold text-white mb-4">Suivez-nous</h3>
                    <div class="flex gap-3">
                        <a href="#" class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-all">
                            <i class="fab fa-facebook-f text-lg"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-all">
                            <i class="fab fa-twitter text-lg"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-all">
                            <i class="fab fa-instagram text-lg"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-all">
                            <i class="fab fa-youtube text-lg"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-secondary rounded-2xl p-8 border border-white/10">
                    <h2 class="font-display font-bold text-2xl text-white mb-6">Envoyez-nous un message</h2>
                    
                    <?php if ($success): ?>
                    <div class="mb-6 p-4 bg-green-500/20 border border-green-500/30 rounded-lg text-green-400">
                        <i class="fas fa-check-circle mr-2"></i>
                        Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($errors)): ?>
                    <div class="mb-6 p-4 bg-red-500/20 border border-red-500/30 rounded-lg text-red-400">
                        <ul class="list-disc list-inside">
                            <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="nom" class="block text-white font-medium mb-2">Nom complet *</label>
                                <input type="text" id="nom" name="nom" value="<?php echo $nom ?? ''; ?>" required
                                       class="w-full px-4 py-3 bg-dark border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-primary focus:outline-none transition-colors"
                                       placeholder="Votre nom">
                            </div>
                            <div>
                                <label for="email" class="block text-white font-medium mb-2">Email *</label>
                                <input type="email" id="email" name="email" value="<?php echo $email ?? ''; ?>" required
                                       class="w-full px-4 py-3 bg-dark border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-primary focus:outline-none transition-colors"
                                       placeholder="votre@email.com">
                            </div>
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="telephone" class="block text-white font-medium mb-2">Téléphone</label>
                                <input type="tel" id="telephone" name="telephone" value="<?php echo $telephone ?? ''; ?>"
                                       class="w-full px-4 py-3 bg-dark border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-primary focus:outline-none transition-colors"
                                       placeholder="+243 ...">
                            </div>
                            <div>
                                <label for="sujet" class="block text-white font-medium mb-2">Sujet *</label>
                                <select id="sujet" name="sujet" required
                                        class="w-full px-4 py-3 bg-dark border border-white/10 rounded-xl text-white focus:border-primary focus:outline-none transition-colors">
                                    <option value="">Choisir un sujet</option>
                                    <option value="Information générale" <?php echo ($sujet ?? '') === 'Information générale' ? 'selected' : ''; ?>>Information générale</option>
                                    <option value="Partenariat" <?php echo ($sujet ?? '') === 'Partenariat' ? 'selected' : ''; ?>>Partenariat</option>
                                    <option value="Publicité" <?php echo ($sujet ?? '') === 'Publicité' ? 'selected' : ''; ?>>Publicité</option>
                                    <option value="Presse" <?php echo ($sujet ?? '') === 'Presse' ? 'selected' : ''; ?>>Presse / Accréditation</option>
                                    <option value="Technique" <?php echo ($sujet ?? '') === 'Technique' ? 'selected' : ''; ?>>Support technique</option>
                                    <option value="Autre" <?php echo ($sujet ?? '') === 'Autre' ? 'selected' : ''; ?>>Autre</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label for="message" class="block text-white font-medium mb-2">Message *</label>
                            <textarea id="message" name="message" rows="6" required
                                      class="w-full px-4 py-3 bg-dark border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-primary focus:outline-none transition-colors resize-none"
                                      placeholder="Votre message..."><?php echo $message ?? ''; ?></textarea>
                        </div>
                        
                        <button type="submit" class="w-full py-4 btn-primary text-white font-bold rounded-xl flex items-center justify-center gap-2">
                            <i class="fas fa-paper-plane"></i>
                            Envoyer le message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section (placeholder) -->
<section class="bg-secondary">
    <div class="h-96 bg-gradient-to-br from-primary/10 to-dark flex items-center justify-center border-t border-white/10">
        <div class="text-center">
            <i class="fas fa-map-marked-alt text-primary/30 text-6xl mb-4"></i>
            <p class="text-gray-400">Carte interactive - Kinshasa, RDC</p>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
