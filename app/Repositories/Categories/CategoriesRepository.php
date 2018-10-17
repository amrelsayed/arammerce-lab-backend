<?php
namespace App\Repositories\Categories;

use App\Models\Category\Category;
use Carbon\Carbon;
use Collection;
use HZ\Laravel\Organizer\App\Contracts\RepositoryInterface;
use Model;
use Item;
use RepositoryManager;
use Request;
use Str;

class CategoriesRepository extends RepositoryManager implements RepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    const MODEL = Category::class;

    /**
     * {@inheritDoc}
     */
    const TABLE = 'categories';
    
    /**
     * {@inheritDoc}
     */
    const TABLE_ALIAS = 'c';

    /**
     * {@inheritDoc}
     */
    protected function records(Collection $records): Collection 
    {
        return $records->map(function ($record) {
            return $record;
        });
    }

    /**
     * {@inheritDoc}
     */
    protected function setData(Model $category, Request $request)
    {
        $category->name = $request->name;
        
        $category->parent_id = $request->parent_id;
    } 
    
    /**
     * {@inheritDoc}
     */
    public function get(int $id): \Category
    {
        $category = Category::find($id);

        $info = (object) $category->getAttributes();

        return new Item($info);
    }

    /**
     * {@inheritDoc}
     */
    protected function select()
    {
    } 
    
    /**
     * {@inheritDoc}
     */
    protected function filter() 
    {
    }  
}