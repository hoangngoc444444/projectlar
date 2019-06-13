<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Product;
use App\Comment;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**Trang chủ
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['products'] = Product::with('category')->orderBy('id','desc')->take(8)->get();
        $data['features'] = Product::with('category')->where('feature',1)->orderBy('id','desc')->take(8)->get();
       $recent_products_id = $request->cookie('recent_products') ? $request->cookie('recent_products') :[];
       $data['recent_products'] = Product::whereIn('id',$recent_products_id)->with('category')->orderBy('id')->take(10)->get();
        return view('frontend.default.index',$data);
    }
        // Trang hiển thị sản phẩm chi tiết
    public function show(Request $request, $slug, $id){
        $data['product']= Product::find($id);
        $recent_products = is_array($request->cookie('recent_products'))  ? $request->cookie('recent_products') :[];
        if(!in_array($data['product']->id,$recent_products)){
            array_unshift($recent_products,$data['product']->id);
            if(count($recent_products) > 10){
                array_splice($recent_products,0,9);
            }
        }
        $cookie = cookie('recent_products',$recent_products,1440);

        return response()->view('frontend.default.single-product',$data)->cookie($cookie);
    }
    public function comment(Request $request, $id){
        $valid = Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email',
            'content'=>'required',
            'score'=>'required|between:1,5',
            
        ],[
            'name.required' => 'Tên không để trống',
            'email.required' => 'Email không để trống',
            'email.email' => 'Email sai',
            'score.between' => 'Đánh giá sai',
            'content.required' => 'Nội dung không để trống',
            'score.required' => 'Đánh giá không để trống'
        ]);

        if($valid->fails()){
            return redirect()->back()->withErrors($valid)->withInput();
        }else{
            $comment = Comment::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'ratings' => $request->input('score'),
                'content' => $request->input('content'),
                'product_id' => $id
            ]);
            return redirect()->back()->with('message', "Thêm bình luận đánh giá thành công");
        }

    }
}
