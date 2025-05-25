<?php

declare(strict_types=1);

require_once(__DIR__ . '/includes/common.php');

head();
echo '<link rel="stylesheet" href="/css/landing.css">';
drawHeader(); ?>
<main>
  <!-- Hero Section -->
  <section class="hero-section">
    <div class="hero-content">
      <div class="hero-text">
        <h1 class="gradient-text">Find Your Perfect Professional!</h1>
        <p class="hero-subtitle">Connect with talented freelancers and discover the perfect service for your needs</p>
        <div class="hero-actions">
          <button class="cta-button primary"
            onclick="document.querySelector('.services-section').scrollIntoView({behavior: 'smooth'})">
            Explore Services
          </button>
          <button class="cta-button secondary" onclick="window.location.href='/pages/search.php'">
            Search
          </button>
        </div>
      </div>
    </div>
  </section>

  <!-- Services Section -->
  <section class="services-section">
    <div class="section-header">
      <h2>Featured Services</h2>
      <p>Discover the best professionals on our platform</p>
    </div>

    <div class="services-grid">
      <?php drawFeaturedCards() ?>
    </div>
  </section>
</main>

<?php drawFooter(); ?>