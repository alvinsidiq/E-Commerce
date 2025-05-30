<?php
use App\Livewire\Homepage;
use App\Livewire\CategoriesPage;
use App\Livewire\ProductsPage;
use App\Livewire\CartPage;
use App\Livewire\ProductDetailPage;
use App\Livewire\CheckoutPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\MyOrderDetailPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ForgetPasswordPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\SuccessPage;
use App\Livewire\CancelPage;
use Illuminate\Http\Request;


Route::get('/',Homepage::class)->name('home');
Route::middleware('auth')->group(function () {
    Route::get('/cart', CartPage::class)->name('cart');
    Route::get('/checkout', CheckoutPage::class)->name('checkout');
    Route::get('/my-orders', MyOrdersPage::class)->name('my-orders');
    Route::get ('/my-orders/{id}', MyOrderDetailPage::class);
    Route::post('/logout', function () {
        Auth::logout();
        return redirect()->route('home');
    })->name('logout');
});
Route::middleware('guest')->group(function () {
    Route::get('/login', LoginPage::class)->name('login');
    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    })->name('login.post');

    Route::get('/register', RegisterPage::class)->name('register');
    // Tambahkan rute untuk forgot password dan reset password nanti
    Route::get('/forget-password',ForgetPasswordPage::class);
    Route::get('/reset-password',ResetPasswordPage::class);
});
Route::get('/success', SuccessPage::class);
Route::get('/cancel', CancelPage::class);
// Route::get('/login',LoginPage::class);
// Route::get('/register',RegisterPage::class)->name('register');
// Route::get('/forget-password',ForgetPasswordPage::class);
// Route::get('/reset-password',ResetPasswordPage::class);

Route::get('/categories',CategoriesPage::class);
Route::get('/products',ProductsPage::class)->name('products');
// Route::get('/cart',CartPage::class)->name('cart');
Route::get('/product/{slug}',ProductDetailPage :: class)->name('product.detail');
// Route::get('/checkout', CheckoutPage :: class);
// Route::get('/my-orders', MyOrdersPage :: class);
// Route::get ('/my-orders/{id}', MyOrderDetailPage::class);

// use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
