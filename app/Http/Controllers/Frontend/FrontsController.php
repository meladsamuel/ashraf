<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class FrontsController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    // protected function authenticated() {
    //     if (Auth::user()->role_as == '1')  //1 = admin login
    //      {
    //         return redirect('dashboard')->with('status', 'welcome to your dashboard');
    //     }
    //     elseif(Auth::user()->role_as == '0')   //0 = normal or default user login
    //     {
    //         $name = Auth::user()->name;
    //          return redirect('frontend.index')->with('status', ' welcome in E pharmacy ', ['name'=>$name]);
    //     }
    // }

    public function index()
    {
       
        return view('frontend.index');
        // return view('home');
    }

   public function category(){

        $categories = Category::where('status', '0')->get();
      return view('frontend.categories', compact('categories'));
   }

   public function viewcategory($slug){
       
       if (Category::where('slug', $slug)->exists()) {
          
          $category = Category::where('slug', $slug)->first();
          $products = Product::where('cate_id', $category->id)->where('status', '0')->get();
          return view('frontend.products.index', compact('category', 'products'));
       } else{
        return redirect('/')->with('status', 'Slug doesnot exisits');
       }
   }

       public function viewproduct($cate_slug, $prod_slug){
             
        if (Category::where('slug', $cate_slug)->exists()) {
          if(Product::where('slug', $prod_slug )->exists()){
             $products = Product::where('slug', $prod_slug)->first();
            // $products = Product::find($prod_id);
            $ratings = Rating::where('prod_id',  $products->id)->get();
            $rating_sum = Rating::where('prod_id',  $products->id)->sum('stars_rated');
            $user_rating = Rating::where('prod_id',  $products->id)->where('user_id', Auth::id())->first();
            $reviews = Review::where('prod_id', $products->id)->get();
            if($ratings->count() > 0){
                $rating_value = $rating_sum/$ratings->count();
            }else{
                $rating_value = 0;
            }
            // $products = Product::find($prod_id);
    return view('frontend.products.view', compact('products','ratings', 'rating_value', 'user_rating', 'reviews'));
          }
          else{
             return redirect('/')->with('status', 'the link was broken');
          }
         }
          else{
             return redirect('/')->with('status', 'no such category found');
          }
   }

   public function productlistAjax(){
    $products = Product::select('name')->where('status', '0')->get();
    $data = [];

    foreach($products as $item) {
         $data[] = $item['name'];
    }
    return $data;
   }

   public function searchProduct(Request $request){
     $search_product = $request->product_name;
     if($search_product != '')
     {
       $product = Product::where("name", "LIKE", "%$search_product%")->first();
       if($product)
       {
        return redirect('categories/'.$product->category->slug.'/'.$product->slug);
       }
       else{
        return redirect()->back()->with("status", "No Medicine matched your search");
       }
     }
     else{
        return redirect()->back();
   }
}
}
