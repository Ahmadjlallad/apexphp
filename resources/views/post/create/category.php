<?php
/**
 * @var Categories $categories
 */

use Apex\models\Categories;

?>
<form>
    <label for="website-admin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Search</label>
    <div class="flex">
        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
            <i class="fas fa-search"></i>
        </span>
        <input type="text" id="website-admin"
               class="rounded-none rounded-r-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
               name="category"
               placeholder="Bonnie Green">
    </div>
</form>
<div class="mt-5">
    <form action="/post/create-options" method="post">
        <?php foreach ($categories as $category) { ?>
            <button class="w-full max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 mx-auto">
                <span class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"><?= $category->name ?></span>
                <input type="hidden"
                       name="category_id" value="<?= $category->category_id ?>">
            </button>
        <?php } ?>
    </form>
</div>
