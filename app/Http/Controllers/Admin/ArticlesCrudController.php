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
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation{store as traitStore;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation{update as traitUpdate;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function store(){
        //before store

        //Store
        // $response = $this->traitStore();

        //afterstore
        $categoria= intval($this->crud->getRequest()->request->get('categories'));
        $subcategoria= intval($this->crud->getRequest()->request->get('subcategories')[0]);
        $title=($this->crud->getRequest()->request->get('title'));
        $content=($this->crud->getRequest()->request->get('content'));
        $abstract=($this->crud->getRequest()->request->get('abstract'));
        $article=new Articles;
        $article->title= $title;
        $article->content= $content;
        $article->abstract= $abstract;
        $article->save();
        $articleid= $article->id;

        if($subcategoria!=0){
            DB::table('articles_categories')->insert(['categories_id'=>$categoria,'articles_id'=>$articleid]);
            DB::table('articles_categories')->insert(['categories_id'=>$subcategoria,'articles_id'=>$articleid]);
        }else{
            DB::table('articles_categories')->insert(['categories_id'=>$categoria,'articles_id'=>$articleid]);  
        }
        return redirect(url('/admin/articles'));
    }
    public function update(){
        //before update
        
        //update
        // $response = $this->traitUpdate();

        //after update
		$categoria= intval($this->crud->getRequest()->request->get('categories'));
        $subcategoria= intval($this->crud->getRequest()->request->get('subcategories')[0]);
        $title=($this->crud->getRequest()->request->get('title'));
        $content=($this->crud->getRequest()->request->get('content'));
        $abstract=($this->crud->getRequest()->request->get('abstract'));
        $articleid= ($this->crud->getRequest()->request->get('id'));
        $a=Articles::where('id',$articleid)->updateOrInsert(['title'=>$title,'content'=>$content,'abstract'=>$abstract]);
        

        if($subcategoria!=0){
            DB::table('articles_categories')->where('articles_id',$articleid)->where('categories_id','1')->update(['categories_id'=> $categoria]);
            DB::table('articles_categories')->where('articles_id',$articleid)->where('categories_id','2')->update(['categories_id'=> $subcategoria]);
            
        }else{
            DB::table('articles_categories')->where('articles_id',$articleid)->where('categories_id',1)->update(['categories_id'=> $categoria]);  
        }
        return redirect(url('/admin/articles'));
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
        CRUD::field('categories')->label('Category')->type('relationship')->options(function ($query){return $query->where('type', 'Category')->get();});
        CRUD::field('subcategories')->label('SubCategory')->type('customselect')->options(function ($query){return $query->where('type', 'SubCategory')->get();});
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
        CRUD::field('categories')->label('Category')->type('relationship')->options(function ($query){return $query->where('type', 'Category')->get();});
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
