<?php

namespace App\Livewire;


use App\Helpers\CartManagement;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsPage extends Component
{
    
    use WithPagination;
    #[Title('Products-page')]
    #[Url]
    public $selectedCategories = [];
    #[Url]
    public $selectedBrands =[];
    #[Url]
    public $featured = '';
    #[Url] 
    public $onSale='';
    #[Url]
    public $priceRange = 300000;


    public function addToCart($product_id)
    {
        $total_count = CartManagement::addItemToCart($product_id);

        // Update komponen Navbar
        $this->dispatch('update-cart-count', total_count: $total_count)
             ->to(\App\Livewire\Partials\Navbar::class);

        // Kirim event untuk tampilkan SweetAlert
        $this->dispatch('product-added');
    }


    public function render()
    
    {
        $productQuery = Product::query()->where('is_active',1);
        if(!empty($this->selectedCategories)){
            //untuk filter kategori
            $productQuery ->whereIn('category_id', $this->selectedCategories);
        }
        if(!empty($this->selectedBrands)){
            // untuk filter brands
            $productQuery->whereIn('brand_id',$this->selectedBrands);
        }
        if($this->featured){
            $productQuery->where('is_featured',1);
        }

        if($this->onSale){
            $productQuery->where('on_sale',1);
        }
        if ($this->priceRange) {
            $productQuery->whereBetween('price', [0, $this->priceRange]);
        }

        $categories= Category:: where('is_active',1)->select('id','name','slug')->get();
        $brands=Brand :: where('is_active',1)->select('id','name','slug')->get();
        return view('livewire.products-page',
        [
            "products" => $productQuery->paginate(6),
            'categories'=>$categories,
            "brands"=>$brands,
        ]);
    }

    // public function addToCart($product_id)
    // {
    //     //dd($product_id);
    //     CartManagement :: addItemToCart($product_id);
    //     $this ->dispatch ('cartUpdated',count(CartManagement :: getCartItemsFromCookie()));

    //     $total_count = CartManagement :: addItemToCart($product_id);
    //     $this->dispatch('update-cart-count', total_count: $total_count)->to(\App\Livewire\Partials\Navbar::class);

    // }

    
}
