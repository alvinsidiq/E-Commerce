<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsPage extends Component
{
    use WithPagination;
    #[Title('Products-page')]

    public function render()
    
    {
        $productQuery = Product::query()->where('is_active',1);
        $categories= Category:: where('is_active',1)->select('id','name','slug')->get();
        $brands=Brand :: where('is_active',1)->select('id','name','slug')->get();
        return view('livewire.products-page',
        [
            "products" => $productQuery->paginate(6),
            'categories'=>$categories,
            "brands"=>$brands,
        ]);
    }
}
