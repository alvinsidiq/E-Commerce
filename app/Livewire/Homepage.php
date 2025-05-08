<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\Title;
class Homepage extends Component
{
    #[Title('Homepage-alvinsidiq')]
    
    public function render()
    
    {
        $brands = Brand::where('is_active',1)->get();
        $categories = Category::where('is_active',1)->get();
        //dd($categories);
        return view('livewire.homepage',[
            'brands'=> $brands,
            'categories'=>$categories,
            ]);
    }
}
