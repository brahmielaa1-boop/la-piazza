
<?php
// ═══════════════════════════════════════
//   LA PIAZZA — menu.php
//   Page menu complète avec PHP + MySQL
// ═══════════════════════════════════════

require_once 'db.php';

// Récupérer toutes les catégories
$categories = $pdo->query("SELECT * FROM categories ORDER BY id ASC")->fetchAll();

// Récupérer les plats (filtrer par catégorie si demandé)
$cat_filter = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;

if ($cat_filter > 0) {
    $stmt = $pdo->prepare("SELECT * FROM plats WHERE categorie_id = ? ORDER BY nom ASC");
    $stmt->execute([$cat_filter]);
} else {
    $stmt = $pdo->query("SELECT p.*, c.nom AS categorie FROM plats p JOIN categories c ON p.categorie_id = c.id ORDER BY c.id, p.nom ASC");
}

$plats = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Menu – La Piazza</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Cormorant+Garamond:wght@300;400;500&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="styl.css"/>
  <style>
    .menu-page { padding: 8rem 5% 5rem; max-width: 1200px; margin: 0 auto; }
    .menu-page-title { text-align: center; margin-bottom: 3rem; }

    /* Filter tabs */
    .filter-tabs {
      display: flex;
      justify-content: center;
      gap: 1rem;
      flex-wrap: wrap;
      margin-bottom: 3rem;
    }

    .filter-tab {
      font-family: 'Montserrat', sans-serif;
      font-size: 0.7rem;
      font-weight: 600;
      letter-spacing: 2px;
      text-transform: uppercase;
      padding: 0.6rem 1.5rem;
      border: 1px solid #e0d4c0;
      text-decoration: none;
      color: var(--brown);
      transition: all 0.3s;
    }

    .filter-tab:hover,
    .filter-tab.active {
      background: var(--gold);
      border-color: var(--gold);
      color: var(--dark);
    }

    /* Plats grid */
    .plats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 2rem;
    }

    .plat-card {
      background: #fff;
      border: 1px solid #e0d4c0;
      padding: 1.5rem;
      transition: box-shadow 0.3s, transform 0.3s;
    }

    .plat-card:hover {
      box-shadow: 0 8px 30px rgba(61,43,31,0.1);
      transform: translateY(-3px);
    }

    .plat-cat {
      font-family: 'Montserrat', sans-serif;
      font-size: 0.6rem;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: var(--red);
      margin-bottom: 0.5rem;
    }

    .plat-card h3 {
      font-family: 'Playfair Display', serif;
      font-size: 1.4rem;
      color: var(--dark);
      margin-bottom: 0.5rem;
    }

    .plat-card p {
      font-size: 1rem;
      color: var(--light-brown);
      line-height: 1.7;
      margin-bottom: 1rem;
    }

    .plat-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .plat-price {
      font-family: 'Montserrat', sans-serif;
      font-size: 1rem;
      font-weight: 600;
      color: var(--gold);
    }

    .plat-badge {
      font-family: 'Montserrat', sans-serif;
      font-size: 0.6rem;
      letter-spacing: 2px;
      text-transform: uppercase;
      padding: 0.3rem 0.8rem;
      border: 1px solid #e0d4c0;
      color: var(--light-brown);
    }

    .no-plats {
      text-align: center;
      font-size: 1.2rem;
      color: var(--light-brown);
      padding: 3rem;
      grid-column: 1/-1;
    }

    .back-home {
      text-align: center;
      margin-top: 3rem;
    }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav id="navbar">
  <div class="logo">La <span>Piazza</span></div>
  <ul>
    <li><a href="index.html#story">Notre Histoire</a></li>
    <li><a href="index.html#specialties">Spécialités</a></li>
    <li><a href="index.html#reservation" class="nav-btn">Réserver</a></li>
  </ul>
</nav>

<!-- MENU PAGE -->
<div class="menu-page">
  <div class="menu-page-title">
    <p class="section-label">La Piazza</p>
    <h1 class="section-title">Notre <em>Menu Complet</em></h1>
    <div class="gold-line center-line"></div>
  </div>

  <!-- FILTER TABS (catégories) -->
  <div class="filter-tabs">
    <a href="menu.php" class="filter-tab <?= $cat_filter === 0 ? 'active' : '' ?>">Tout</a>
    <?php foreach ($categories as $cat): ?>
      <a href="menu.php?cat=<?= $cat['id'] ?>"
         class="filter-tab <?= $cat_filter === (int)$cat['id'] ? 'active' : '' ?>">
        <?= htmlspecialchars($cat['nom']) ?>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- PLATS GRID -->
  <div class="plats-grid">
    <?php if (empty($plats)): ?>
      <p class="no-plats">Aucun plat trouvé dans cette catégorie.</p>
    <?php else: ?>
      <?php foreach ($plats as $plat): ?>
        <div class="plat-card">
          <?php if (!empty($plat['categorie'])): ?>
            <p class="plat-cat"><?= htmlspecialchars($plat['categorie']) ?></p>
          <?php endif; ?>
          <h3><?= htmlspecialchars($plat['nom']) ?></h3>
          <p><?= htmlspecialchars($plat['description']) ?></p>
          <div class="plat-footer">
            <span class="plat-price"><?= number_format($plat['prix'], 2) ?> TND</span>
            <?php if (!empty($plat['tag'])): ?>
              <span class="plat-badge"><?= htmlspecialchars($plat['tag']) ?></span>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <div class="back-home">
    <a href="index.html" class="btn-primary">← Retour à l'accueil</a>
  </div>
</div>

<script src="script.js"></script>
</body>
</html> 
