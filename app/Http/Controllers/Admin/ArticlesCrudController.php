<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ArticlesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Articles;
use App\Models\Categories;
use Illuminate\Support\Facades\DB;

/**
 * Class ArticlesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ArticlesCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as traitUpdate;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function store()
    {
        //before store

        //Store
        $response = $this->traitStore();

        //afterstore
        $cat= $this->crud->getRequest()->request->get('Cat');
        $sub= $this->crud->getRequest()->request->get('Sub');
        $catsub=[$cat,$sub];
        $this->data["entry"]->categories()->attach($catsub);

        return $response;
    }
    public function update()
    {
        //before update

        //update
        $response = $this->traitUpdate();

        //after update
        $cat= $this->crud->getRequest()->request->get('Cat');
        $sub= $this->crud->getRequest()->request->get('Sub');
        $catsub=[$cat,$sub];
        if($sub == null){
            $this->data["entry"]->categories()->sync($cat);
        } else {
            $this->data["entry"]->categories()->sync($catsub);
        }
        return $response;
    }
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Articles::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/articles');
        CRUD::setEntityNameStrings('article', 'articles');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('title');
        CRUD::column('slug');
        CRUD::column('categories');
        CRUD::column('abstract');
        CRUD::column('content');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */

    protected function setupCreateOperation()
    {
        CRUD::setValidation(ArticlesRequest::class);

        CRUD::field('title');
        $categories = Categories::where('type','Category')->pluck('name', 'id')->all();
        CRUD::addField([
            'name'        => 'Cat',
            'label'       => "Categories",
            'type'        => 'select_from_array',
            'options'     => $categories,
            'allows_null' => false,
            'default'     => 'one',
        ]);
        $subcategories = Categories::where('type','SubCategory')->pluck('name', 'id')->all();
        CRUD::addField([
            'name'        => 'Sub',
            'label'       => "Subcategories",
            'type'        => 'select_from_array',
            'options'     => $subcategories,
            'allows_null' => true,
            'default'     => 'one',
        ]);
        CRUD::field('abstract');
        CRUD::field('content');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
        CRUD::field('title');
        $categories = Categories::where('type','Category')->pluck('name', 'id')->all();
        CRUD::addField([
            'name'        => 'Cat',
            'label'       => "Categories",
            'type'        => 'select_from_array',
            'options'     => $categories,
            'allows_null' => false,
            'default'     => 'one',
        ]);
        $subcategories = Categories::where('type','SubCategory')->pluck('name', 'id')->all();
        CRUD::addField([
            'name'        => 'Sub',
            'label'       => "Subcategories",
            'type'        => 'select_from_array',
            'options'     => $subcategories,
            'allows_null' => true,
            'default'     => 'one',
        ]);
        CRUD::field('abstract');
        CRUD::field('content');
    }
    protected function setupShowOperation()
    {
        CRUD::column('id');
        CRUD::column('title');
        CRUD::column('slug');
        CRUD::column('categories');
        CRUD::column('abstract');
        CRUD::column('content');
    }
}
