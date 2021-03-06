<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2017, iBenchu.org
 * @datetime 2017-02-15 14:41
 */
namespace Notadd\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PageCategory.
 */
class PageCategory extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'title',
        'alias',
        'description',
        'type',
        'background_color',
        'seo_title',
        'seo_keyword',
        'seo_description',
        'background_image',
        'top_image',
        'pagination',
        'enabled',
        'order_id',
        'deleted_at'
    ];

    /**
     * Get category structure list.
     *
     * @return array
     */
    public function structure() {
        $list = $this->newQuery()->where('parent_id', 0)->orderBy('order_id', 'asc')->get();
        $list->transform(function (PageCategory $category) {
            $children = $category->newQuery()->where('parent_id', $category->getAttribute('id'))->orderBy('order_id', 'asc')->get();
            $children->transform(function (PageCategory $category) {
                $children = $category->newQuery()->where('parent_id', $category->getAttribute('id'))->orderBy('order_id', 'asc')->get();
                $category->setAttribute('children', $children);

                return $category;
            });
            $category->setAttribute('children', $children);

            return $category;
        });

        return $list;
    }
}
