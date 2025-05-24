<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../database/classes/service.php');
require_once(__DIR__ . '/../database/classes/category.php');
require_once(__DIR__ . '/../database/classes/user.php');
require_once(__DIR__ . '/../components/card/card.php');
require_once(__DIR__ . '/../components/pagination/pagination.php');

head();
drawHeader();

echo '<link rel="stylesheet" href="/css/search.css">';

$searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';
$searchMode = isset($_GET['mode']) ? trim($_GET['mode']) : 'services';

$categoryId = null;

if (isset($_GET['category']) && $_GET['category'] !== '') {
    $categoryId = (int)$_GET['category'];

    if ($categoryId <= 0) {
        $categoryId = null;
    }
}

$minPrice = null;

if (isset($_GET['min_price']) && $_GET['min_price'] !== '') {
    $minPrice = (float)$_GET['min_price'];

    if ($minPrice < 0) {
        $minPrice = null;
    }
}

$maxPrice = null;

if (isset($_GET['max_price']) && $_GET['max_price'] !== '') {
    $maxPrice = (float)$_GET['max_price'];

    if ($maxPrice <= 0) {
        $maxPrice = null;
    }
}

$minRating = null;

if (isset($_GET['min_rating']) && $_GET['min_rating'] !== '') {
    $minRating = (float)$_GET['min_rating'];

    if ($minRating < 1 || $minRating > 5) {
        $minRating = null;
    }
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 12;
$offset = ($page - 1) * $perPage;

$categories = Category::getAllCategories();

$results = [];
$totalResults = 0;
$hasActiveFilters = $categoryId !== null || $minPrice !== null || $maxPrice !== null || $minRating !== null;
$onlySellers = isset($_GET['only_sellers']) && $_GET['only_sellers'] === '1';

$results = [];
$totalResults = 0;

if (!empty($searchQuery) || $hasActiveFilters) {
    $query = !empty($searchQuery) ? $searchQuery : '';

    if ($searchMode === 'users') {
        $results = User::searchUsers($query, $onlySellers);
        $totalResults = count($results);
        $results = array_slice($results, $offset, $perPage);
    } else {
        $results = Service::searchServicesWithFilters(
            $query,
            $categoryId,
            $minPrice,
            $maxPrice,
            $minRating
        );
        $totalResults = count($results);
        $results = array_slice($results, $offset, $perPage);
    }
}

$totalPages = ceil($totalResults / $perPage);
?>

<main>
    <?php if (!empty($searchQuery)): ?>
        <h1>Results for: <?= htmlspecialchars($searchQuery) ?></h1>
    <?php elseif ($hasActiveFilters): ?>
        <?php if ($categoryId !== null && $minPrice === null && $maxPrice === null && $minRating === null):
            $categoryName = '';

            foreach ($categories as $cat) {
                if ($cat['id'] == $categoryId) {
                    $categoryName = $cat['name'];
                    break;
                }
            }

            if (!empty($categoryName)): ?>
                <h1><?= htmlspecialchars($categoryName) ?></h1>
            <?php endif; ?>
        <?php else: ?>
            <h1>Filtered Results</h1>
            <?php if ($categoryId !== null):
                $categoryName = '';

                foreach ($categories as $cat) {
                    if ($cat['id'] == $categoryId) {
                        $categoryName = $cat['name'];
                        break;
                    }
                }
            ?>
            <?php endif; ?>
        <?php endif; ?>
    <?php else: ?>
        <h1>Search Results</h1>
    <?php endif; ?>

    <div class="search-page-container">
        <div class="search-filters">
            <h2>Search Mode</h2>
            <div class="search-mode-toggle">
                <a href="<?= !empty($searchQuery) ? "?q=" . urlencode($searchQuery) . "&mode=services" : "?mode=services" ?>"
                    class="search-mode-button <?= $searchMode !== 'users' ? 'active' : '' ?>">Services</a>
                <a href="<?= !empty($searchQuery) ? "?q=" . urlencode($searchQuery) . "&mode=users" : "?mode=users" ?>"
                    class="search-mode-button <?= $searchMode === 'users' ? 'active' : '' ?>">Users</a>
            </div>

            <h2>Filters</h2>
            <form id="filter-form" method="get" action="search.php">
                <?php if (!empty($searchQuery)): ?>
                    <input type="hidden" name="q" value="<?= htmlspecialchars($searchQuery) ?>">
                <?php endif; ?>
                <input type="hidden" name="mode" value="<?= htmlspecialchars($searchMode) ?>">

                <?php if ($searchMode === 'users'): ?>
                    <div class="filter-section">
                        <label for="only-sellers">Show only:</label>
                        <div class="checkbox-option">
                            <input type="checkbox" id="only-sellers" name="only_sellers" value="1" <?= $onlySellers ? 'checked' : '' ?>>
                            <label for="only-sellers">Only show freelancers</label>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="filter-section">
                        <label for="category">Category:</label>
                        <select name="category" id="category">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" <?= isset($categoryId) && $categoryId == $category['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-section">
                        <label>Price Range:</label>
                        <div class="price-inputs">
                            <input type="number" name="min_price" id="min-price" placeholder="Min" min="0" step="0.01"
                                value="<?= $minPrice !== null ? htmlspecialchars((string)$minPrice) : '' ?>">
                            <span>to</span>
                            <input type="number" name="max_price" id="max-price" placeholder="Max" min="0" step="0.01"
                                value="<?= $maxPrice !== null ? htmlspecialchars((string)$maxPrice) : '' ?>">
                        </div>
                    </div>

                    <div class="filter-section">
                        <label for="min-rating">Minimum Rating:</label>
                        <select name="min_rating" id="min-rating">
                            <option value="">Any Rating</option>
                            <option value="5" <?= isset($minRating) && $minRating == 5 ? 'selected' : '' ?>>5 stars</option>
                            <option value="4" <?= isset($minRating) && $minRating == 4 ? 'selected' : '' ?>>4+ stars</option>
                            <option value="3" <?= isset($minRating) && $minRating == 3 ? 'selected' : '' ?>>3+ stars</option>
                            <option value="2" <?= isset($minRating) && $minRating == 2 ? 'selected' : '' ?>>2+ stars</option>
                            <option value="1" <?= isset($minRating) && $minRating == 1 ? 'selected' : '' ?>>1+ stars</option>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="filter-actions">
                    <?php
                    Button::start([
                        'type' => 'submit',
                        'variant' => 'primary',
                    ]);
                    echo '<span>Apply Filters</span>';
                    Button::end();

                    Button::start([
                        'type' => 'button',
                        'variant' => 'text',
                        'class' => 'reset-button',
                        'onClick' => "window.location.href='" .
                            (!empty($searchQuery) ?
                                "search.php?q=" . htmlspecialchars($searchQuery) . "&mode=" . htmlspecialchars($searchMode) :
                                "search.php?mode=" . htmlspecialchars($searchMode)) . "'"
                    ]);
                    echo '<span>Reset Filters</span>';
                    Button::end();
                    ?>
                </div>
            </form>
        </div>

        <?php if (empty($searchQuery) && !$hasActiveFilters): ?>
            <div class="content-area">
                <div class="search-empty-state">
                    <i class="fas fa-search"></i>
                    <h2>Start Searching</h2>
                    <p>Use the search bar above to find <?= $searchMode === 'users' ? 'users' : 'services' ?></p>
                </div>
            </div>
        <?php else: ?>
            <div class="content-area">
                <?php if (empty($results)): ?>
                    <div class="search-empty-state">
                        <i class="fas fa-search"></i>
                        <h2>No Results Found</h2>
                        <?php if (!empty($searchQuery)): ?>
                            <p>We couldn't find any <?= $searchMode === 'users' ? 'users' : 'services' ?> matching your search:
                                <strong><?= htmlspecialchars($searchQuery) ?></strong>
                            </p>
                        <?php else: ?>
                            <p>We couldn't find any <?= $searchMode === 'users' ? 'users' : 'services' ?> matching the selected filters</p>
                        <?php endif; ?>
                        <p>Try changing your filters or using different keywords.</p>
                    </div>
                <?php else: ?>
                    <?php if ($searchMode === 'users'): ?>
                        <?php Card::renderUserGrid($results); ?>
                    <?php else: ?>
                        <?php Card::renderGrid($results, showPrice: false); ?>
                    <?php endif; ?>

                    <?php if ($totalPages > 1): ?>
                        <div class="pagination-container">
                            <?php
                            $additionalParams = [
                                'q' => $searchQuery,
                                'mode' => $searchMode
                            ];

                            if ($searchMode === 'users') {
                                if ($onlySellers) $additionalParams['only_sellers'] = 1;
                            } else {
                                if ($categoryId) $additionalParams['category'] = $categoryId;
                                if ($minPrice) $additionalParams['min_price'] = $minPrice;
                                if ($maxPrice) $additionalParams['max_price'] = $maxPrice;
                                if ($minRating) $additionalParams['min_rating'] = $minRating;
                            }

                            Pagination::render($page, (int)$totalPages, 'page', $additionalParams);
                            ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php drawFooter(); ?>