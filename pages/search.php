<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../database/classes/service.php');
require_once(__DIR__ . '/../components/card/card.php');
require_once(__DIR__ . '/../components/pagination/pagination.php');

head();
drawHeader();

// Get search query from URL
$searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 12; // Number of results per page
$offset = ($page - 1) * $perPage;

// Get search results if a query was provided
$results = [];
$totalResults = 0;

if (!empty($searchQuery)) {
    $results = Service::searchServices($searchQuery);
    $totalResults = count($results);

    // Handle pagination manually since we don't have a paginated search method
    $results = array_slice($results, $offset, $perPage);
}

$totalPages = ceil($totalResults / $perPage);
?>

<main>
    <div class="search-page-container">
        <div class="search-header">
            <h1>Search Results</h1>
            <?php if (!empty($searchQuery)): ?>
                <p>Showing results for: <strong><?= htmlspecialchars($searchQuery) ?></strong></p>
                <p>Found <?= $totalResults ?> result<?= $totalResults !== 1 ? 's' : '' ?></p>
            <?php endif; ?>
        </div>

        <?php if (empty($searchQuery)): ?>
            <div class="search-empty-state">
                <i class="fas fa-search"></i>
                <h2>Start Searching</h2>
                <p>Use the search bar above to find services</p>
            </div>
        <?php elseif (empty($results)): ?>
            <div class="search-empty-state">
                <i class="fas fa-search"></i>
                <h2>No Results Found</h2>
                <p>We couldn't find any services matching your search: <strong><?= htmlspecialchars($searchQuery) ?></strong></p>
                <p>Try using different keywords or check for spelling errors.</p>
            </div>
        <?php else: ?>
            <div class="card-grid">
                <?php foreach ($results as $service): ?>
                    <?php Card::render($service); ?>
                <?php endforeach; ?>
            </div>

            <?php if ($totalPages > 1): ?>
                <div class="pagination-container">
                    <?php
                    // Add the search query to pagination
                    $additionalParams = ['q' => $searchQuery];
                    Pagination::render($page, (int)$totalPages, 'page', $additionalParams);
                    ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</main>

<?php drawFooter(); ?>

<style>
    .search-page-container {
        width: 90%;
        margin: 2rem auto;
    }

    .search-header {
        margin-bottom: 2rem;
    }

    .search-header h1 {
        margin-bottom: 0.5rem;
    }

    .search-empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        background-color: white;
        border-radius: 8px;
        padding: 3rem;
        box-shadow: 0 1px 8px 0 var(--shadow);
        margin: 2rem auto;
    }

    .search-empty-state i {
        font-size: 3rem;
        color: var(--primary);
        margin-bottom: 1rem;
    }

    .search-empty-state h2 {
        margin-bottom: 1rem;
    }

    .pagination-container {
        margin-top: 2rem;
    }
</style>