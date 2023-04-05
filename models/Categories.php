<?php

namespace Apex\models;

use Apex\src\Database\Query\HasMany;
use Apex\src\Model\Model;

/**
 * @property string category_id
 * @property string name
 * @property string option_id
 * @property \Carbon\Carbon|null used_from
 * @property \Carbon\Carbon|null created_at
 * @property \Carbon\Carbon|null updated_at
 * @property Options[] options
 * @property CategoryOption[] categoryOptions
 */
class Categories extends Model
{
    public function addOptionToCategory(Options $option): CategoryOption
    {
        $optionCategory = CategoryOption::create(['option_id' => $option->option_id, 'category_id' => $this->category_id]);
        $optionCategory->save();
        return $optionCategory;
    }

    public function options()
    {
        return $this->belongsToMany(Options::class, CategoryOption::class, ['category_id', 'category_id'], ['option_id', 'option_id']);
    }

    public function categoryOptions(): HasMany
    {
        return $this->hasMany(CategoryOption::class, 'category_id', 'category_id');
    }

    public function groupedOptions(): array
    {
        $groups = [];
        foreach ($this->options as $option) {
            $groups[$option->option_type][] = $option;

        }
        return $groups;
    }
}