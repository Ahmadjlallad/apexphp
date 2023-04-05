<?php
/**
 * @var Categories $categories
 * @var array $options
 */

use Apex\models\Categories;

?>
<div>
    <h2 class="mx-auto"><?= $categories->name ?></h2>
    <?php foreach ($options as $option_type => $option) { ?>
        <label for="<?= $option_type ?>" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select an <?= $option_type ?></label>
        <select name="<?= $option_type ?>" id="<?= $option_type ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <?php foreach ($option as $op) { ?>
                <option value="<?= $op->option_id ?>" style="color: <?= $op->option_value ?>"><?= $op->option_name ?></option>
            <?php } ?>
        </select>
    <?php } ?>
</div>
