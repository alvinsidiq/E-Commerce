<?php
use App\Livewire\Homepage;
use App\Livewire\CategoriesPage;
use App\livewire\ProductsPage;
use App\livewire\CartPage;
use App\livewire\ProductDetailPage;
use App\livewire\CheckoutPage;
use App\livewire\MyOrdersPage;
use App\livewire\MyOrderDetailPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ForgetPasswordPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\SuccessPage;
use App\Livewire\CancelPage;

Route::get('/success', SuccessPage::class);
Route::get('/cancel', CancelPage::class);
Route::get('/login',LoginPage::class);
Route::get('/register',RegisterPage::class);
Route::get('/forget-password',ForgetPasswordPage::class);
Route::get('/reset-password',ResetPasswordPage::class);
Route::get('/',Homepage::class);
Route::get('/categories',CategoriesPage::class);
Route::get('/products',ProductsPage::class);
Route::get('/cart',CartPage::class);
Route::get('/product/{id}',ProductDetailPage :: class);
Route::get('/checkout', CheckoutPage :: class);
Route::get('/my-orders', MyOrdersPage :: class);
Route::get ('/my-orders/{id}', MyOrderDetailPage::class);

// use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
