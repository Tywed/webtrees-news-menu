<?php

use Fisharebest\Webtrees\I18N;

/**
 * @var string $module_name
 * @var Tree   $tree
 * @var int    $current
 * @var int    $limit
 * @var int    $totalArticles
 * @var Category|null $current_category
 */

$current       = max(1, (int)($current ?? 1));
$limit         = max(1, (int)($limit ?? 5));
$totalArticles = max(0, (int)($totalArticles ?? 0));
$totalPages    = (int)ceil($totalArticles / $limit);

if ($totalPages <= 1) {
    return;
}

$category_id = isset($current_category) ? $current_category->getCategoryId() : null;

$page_url = static function (int $page) use ($module_name, $tree, $limit, $category_id): string {
    $params = [
        'module' => $module_name,
        'action' => $category_id === null ? 'Page' : 'Category',
        'tree'   => $tree->name(),
        'page'   => $page,
        'limit'  => $limit,
    ];

    if ($category_id !== null) {
        $params['category_id'] = $category_id;
    }

    return route('module', $params);
};

?>
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <?php if ($current > 1) : ?>
            <li class="page-item">
                <a class="page-link" href="<?php echo e($page_url($current - 1)) ?>"><?php echo I18N::translate('previous') ?></a>
            </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
            <?php if ($i === $current) : ?>
                <li class="page-item active" aria-current="page">
                    <span class="page-link"><?php echo $i ?></span>
                </li>
            <?php else : ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo e($page_url($i)) ?>"><?php echo $i ?></a>
                </li>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($current < $totalPages) : ?>
            <li class="page-item">
                <a class="page-link" href="<?php echo e($page_url($current + 1)) ?>"><?php echo I18N::translate('next') ?></a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
