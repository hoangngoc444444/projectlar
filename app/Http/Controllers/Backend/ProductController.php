<?php

namespace App\Http\Controllers\Backend;

use App\Category;
use App\Http\Controllers\Controller;
use App\Product;
use App\Attachment;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
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

    public function index()
    {
        $data['products'] = Product::with('user')->orderBy('feature','desc')->orderBy('id', 'desc')->paginate(20);

        return view('admin.products.index', $data);
    }

    public function create()
    {
        $data['categories'] = Product::orderBy('name', 'asc')->get();
        return view('admin.products.create', $data);
    }

   

    public function store(Request $request)
    {

        $valid = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required|unique:products,code',
            'content' => 'required',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'original_price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'image' => 'image|max:2048',
            'images.*' => 'image|max:2048',
            'category_id' => 'required|exists:categories,id',
        ], [
//            Required
            'name.required' => 'Vui lòng nhập Tên sản phẩm',
            'code.required' => 'Vui lòng nhập Mã sản phẩm',
            'content.required' => 'Vui lòng nhập Nội dung',
            'regular_price.required' => 'Vui lòng nhập Giá thị trường',
            'sale_price.required' => 'Vui lòng nhập Giá bán',
            'original_price.required' => 'Vui lòng nhập Giá gốc',
            'quantity.required' => 'Vui lòng nhập Số lượng',
            'category_id.required' => 'Vui lòng nhập CategoryID',
//            Unique
            'code.unique' => 'Mã sản phẩm đã trùng, vui lòng chọn mã khác',
//            Numeric
            'regular_price.numeric' => 'Vui lòng nhập số',
            'sale_price.numeric' => 'Vui lòng nhập số',
            'original_price.numeric' => 'Vui lòng nhập số',
            'quantity.numeric' => 'Vui lòng nhập số',
//            Min
            'regular_price.min' => 'Vui lòng nhập số và nhỏ nhất cho phép là :min',
            'sale_price.min' => 'Vui lòng nhập số và nhỏ nhất cho phép là :min',
            'original_price.min' => 'Vui lòng nhập số và nhỏ nhất cho phép là :min',
            'quantity.min' => 'Vui lòng nhập số và nhỏ nhất cho phép là :min',
//            Exists
            'category_id.exists' => 'Vui lòng nhập đúng CategoryID',
//              Image
            'image.image' => 'Không đúng chuẩn hình ảnh cho phép',
            'images.*.image' => 'Không đúng chuẩn hình ảnh cho phép',
//            Size
            'image.max' => 'Dung lượng vượt quá giới hạn cho phép là :max KB',
            'images.*.max' => 'Dung lượng vượt quá giới hạn cho phép là :max KB',
        ]);

        if ($valid->fails()) {
            return redirect()->back()->withErrors($valid)->withInput();
        } else {
            //Thêm hình ảnh đại diện
            $imageName = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = $this->save_image($image);
            }
            
           

            // Thêm attribute
            $attributes = null;
            if ($request->has('attributes') && is_array($request->input('attributes')) && count($request->input('attributes')) > 0) {
                $attributes = $request->input('attributes');
                foreach ($attributes as $key => $attribute) {
                    if (!isset($attribute['name'])) {
                        unset($attributes[$key]);
                        continue;
                    }
                    if (!isset($attribute['value'])) {
                        unset($attributes[$key]);
                        continue;
                    }
                }
                $attributes = json_encode($attributes);
            }

            $product = Product::create([
                'name' => $request->input('name'),
                'code' => mb_strtoupper($request->input('code'), 'UTF-8'),
                'content' => $request->input('content'),
                'regular_price' => $request->input('regular_price'),
                'sale_price' => $request->input('sale_price'),
                'original_price' => $request->input('original_price'),
                'quantity' => $request->input('quantity'),
                'image' => $imageName,
                'attributes' => $attributes,
                'user_id' => auth()->id(),
                'category_id' => $request->input('category_id'),
            ]);
            //Thêm thư viện hình ảnh
            if($request->hasFile('images')){
                foreach($request->file('images') as $file){
                    Attachment::create([
                        'mine' => $file->getMimeType(),
                        'type' => 'image',
                        'path' => $this->save_image($file),
                        'product_id' => $product->id
                    ]);
                }
       }
            //Thêm Tag
            if ($request->has('tags') && is_array($request->input('tags')) && count($request->input('tags')) > 0) {
                $tags = $request->input('tags');
                $tagID = [];
                foreach ($tags as $key => $tag):
                    $tag_new = Tag::firstOrCreate([
                        'name' => str_slug($tag),
                    ], [
                        'name' => str_slug($tag),
                        'slug' => str_slug($tag),
                    ]);
                    $tagID[] = $tag_new->id;
                endforeach;
                $product->tags()->sync($tagID);
            }
            return redirect()->route('admin.product.index')->with('message', "Thêm sản phẩm $product->name thành công");
        }
    }

    public function show($id)
    {
        $data['product'] = Product::find($id);
        $data['categories'] = Category::where([
            ['parent', '=', 0],
            ['id', '<>', 1],
        ])->get();
        if ($data['product'] !== null) {
            return view('admin.products.show', $data);
        }
        return redirect()->route('admin.product.index')->with('error', 'Không tìm thấy sản phẩm này');
    }

    public function update(Request $request, $id)
    {
        $valid = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required|unique:products,code,'.$id,
            'content' => 'required',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'original_price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'image' => 'image|max:2048',
            'images.*' => 'image|max:2048',
            'category_id' => 'required|exists:categories,id',
        ], [
//            Required
            'name.required' => 'Vui lòng nhập Tên sản phẩm',
            'code.required' => 'Vui lòng nhập Mã sản phẩm',
            'content.required' => 'Vui lòng nhập Nội dung',
            'regular_price.required' => 'Vui lòng nhập Giá thị trường',
            'sale_price.required' => 'Vui lòng nhập Giá bán',
            'original_price.required' => 'Vui lòng nhập Giá gốc',
            'quantity.required' => 'Vui lòng nhập Số lượng',
            'category_id.required' => 'Vui lòng nhập CategoryID',
//            Unique
            'code.unique' => 'Mã sản phẩm đã trùng, vui lòng chọn mã khác',
//            Numeric
            'regular_price.numeric' => 'Vui lòng nhập số',
            'sale_price.numeric' => 'Vui lòng nhập số',
            'original_price.numeric' => 'Vui lòng nhập số',
            'quantity.numeric' => 'Vui lòng nhập số',
//            Min
            'regular_price.min' => 'Vui lòng nhập số và nhỏ nhất cho phép là :min',
            'sale_price.min' => 'Vui lòng nhập số và nhỏ nhất cho phép là :min',
            'original_price.min' => 'Vui lòng nhập số và nhỏ nhất cho phép là :min',
            'quantity.min' => 'Vui lòng nhập số và nhỏ nhất cho phép là :min',
//            Exists
            'category_id.exists' => 'Vui lòng nhập đúng CategoryID',
//              Image
            'image.image' => 'Không đúng chuẩn hình ảnh cho phép',
            'images.*.image' => 'Không đúng chuẩn hình ảnh cho phép',

//            Size
            'image.max' => 'Dung lượng vượt quá giới hạn cho phép là :max KB',
            'images.*.max' => 'Dung lượng vượt quá giới hạn cho phép là :max KB',
        ]);

        if ($valid->fails()) {
            return redirect()->back()->withErrors($valid)->withInput();
        } else {
            $product = Product::find($id);
            if ($product !== null) {
                //Thêm hình ảnh đại diện
                $imageName = null;
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $this->delete_img($product->image);
                    $imageName = $this->save_image($image);
                    
                }
                      //Thêm thư viện hình ảnh
            if($request->hasFile('images')){
                foreach($product->attachments as $file){
                    $this->delete_img($file->path);
                    $file->delete();
                }
                foreach($request->file('images') as $file){
                        Attachment::create([
                        'mine' => $file->getMimeType(),
                        'type' => 'image',
                        'path' => $this->save_image($file),
                        'product_id' => $product->id
                    ]);
                   
                }
       }
                // Thêm attribute
                $attributes = null;
                if ($request->has('attributes') && is_array($request->input('attributes')) && count($request->input('attributes')) > 0) {
                    $attributes = $request->input('attributes');
                    foreach ($attributes as $key => $attribute) {
                        if (!isset($attribute['name'])) {
                            unset($attributes[$key]);
                            continue;
                        }
                        if (!isset($attribute['value'])) {
                            unset($attributes[$key]);
                            continue;
                        }
                    }
                    $attributes = json_encode($attributes);
                }

                $product->name = $request->input('name');
                $product->code = mb_strtoupper($request->input('code'), 'UTF-8');
                $product->content = $request->input('content');
                $product->regular_price = $request->input('regular_price');
                $product->sale_price = $request->input('sale_price');
                $product->original_price = $request->input('original_price');
                $product->quantity = $request->input('quantity');
                if ($request->hasFile('image')) {
                    $product->image = $imageName;
                }
                $product->attributes = $attributes;
                $product->user_id = auth()->id();
                $product->category_id = $request->input('category_id');
                $product->save();
                //Thêm Tag
                if ($request->has('tags') && is_array($request->input('tags')) && count($request->input('tags')) > 0) {
                    $tags = $request->input('tags');
                    $tagID = [];

                    foreach ($tags as $key => $tag):
                        $tag_new = Tag::firstOrCreate([
                            'name' => $tag,
                        ], [
                            'name' => $tag,
                            'slug' => str_slug($tag),
                        ]);
                        $tagID[] = $tag_new->id;
                    endforeach;
                    $product->tags()->sync($tagID);
                }
                return redirect()->route('admin.product.index')->with('message', "Cập nhật sản phẩm $product->name thành công");
            }
            return redirect()->route('admin.product.index')->with('error', 'Không tìm thấy sản phẩm này');
        }
    }

    
  
    public function delete($id)
    {
        $product = Product::find($id);
        if ($product !== null) {
            $this->delete_img($product->image);
            
            if(count($product->attachments) > 0){
                foreach($product->attachments as $file){
                    $this->delete_img($file->path);
                    $file->delete();
                }
            }
            $product->delete();
            return redirect()->route('admin.product.index')->with('message', "Xóa sản phẩm $product->name thành công");
        }
        return redirect()->route('admin.product.index')->with('error', 'Không tìm thấy sản phẩm này');
    }


public function save_image($image)
{
    if (file_exists(public_path('uploads'))) {
        $folderName = date('Y-m');
        $fileNameWithTimestamp = md5($image->getClientOriginalName() . time());
        $fileName = $fileNameWithTimestamp . '.' . $image->getClientOriginalExtension();
      
        if (!file_exists(public_path('uploads/' . $folderName))) {
            mkdir(public_path('uploads/' . $folderName), 0755);
        }
//                    Di chuyển file vào folder Uploads
        $imageName = "$folderName/$fileName";
        $image->move(public_path('uploads/' . $folderName), $fileName);

        //  Tạo hình ảnh theo tỉ lệ giao diện
        
        $createImages = function($suffix = '_thumb',$width = 250,$height = 170) use($fileNameWithTimestamp,$image,$folderName,$fileName) {
            $thumbnailFileName = $fileNameWithTimestamp . $suffix . '.' . $image->getClientOriginalExtension();
            Image::make(public_path("uploads/$folderName/$fileName"))
           ->resize($width, $height)
           ->save(public_path("uploads/$folderName/$thumbnailFileName"));
        };
        $createImages();
      
        $createImages('_450x337',450,337);
    
        $createImages('_80x80',80,80);
       
        
        return $imageName;
    }
}
public function delete_img($path){
    if (!is_dir(public_path('uploads/' . $path)) && file_exists(public_path('uploads/' . $path))) {
        unlink(public_path('uploads/' . $path));
        
        $deleteAll = function ($array_size) use($path) {
            foreach($array_size as $size){
                if (!is_dir(public_path('uploads/' . get_thumbnail($path, $size))) && file_exists(public_path('uploads/' . get_thumbnail($path, $size)))) {
                    unlink(public_path('uploads/' . get_thumbnail($path, $size)));
                }
            }
          
        };
       
        $deleteAll(['_thumb','_450x337','_80x80']);
       
    }
}
//set feature

public function setFeature($id){
    $product = Product::find($id);
    if($product !== null){
        $product->feature = !$product->feature;
        $product->save();
    return redirect()->route('admin.product.index')->with('message', "Set sản phẩm $product->name nổi bật thành công");
    }else{
        return redirect()->route('admin.product.index')->with('error', 'Không tìm thấy sản phẩm này');
    }
}




}
