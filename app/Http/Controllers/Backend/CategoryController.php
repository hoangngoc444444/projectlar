<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
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
        $data['categories'] = Category::orderBy('order', 'asc')->paginate(20);

        return view('admin.categories.index', $data);
    }


    public function create() {
        $data['categories'] = Category::where([
            ['parent', '=', 0],
            ['id', '<>', 1],
        ])->get();
        return view('admin.categories.create', $data);
    }

    public function store(Request $request) {
        $valid = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name',
            'parent' => 'required'
        ], [
            'name.required' => 'Vui lòng nhập Tên chuyên mục',
            'name.unique' => 'Chuyên mục đã trùng, vui lòng chọn tên khác',
            'parent.required' => 'Vui lòng nhập Parent ID',
            'parent.exists' => 'Parent ID không hợp lệ'
        ]);

        $valid->sometimes('parent', 'exists:categories,id', function($input) {
            return $input->parent !== "0";
        });
        if ($valid->fails()) {
            return redirect()->back()->withErrors($valid)->withInput();
        } else {
            $order = 0;
            if ($request->input('order')) {
                $order = $request->input('order');
            }
            $category = Category::create([
                'name' => $request->input('name'),
                'slug' => str_slug($request->input('name')),
                'parent' => $request->input('parent'),
                'order' => $order
            ]);
            return redirect()->route('admin.category.index')->with('message', "Thêm chuyên mục $category->name thành công");
        }
    }

    public function show($id) {
        $data['category'] = Category::find($id);
        $data['categories'] = Category::where([
            ['parent','=', 0],
            ['id','<>', 1],
        ])->get();
        if ($data['category'] !== null) {
            return view('admin.categories.show', $data);
        }
        return redirect()->route('admin.category.index')->with('error', 'Không tìm thấy chuyên mục này');
    }

    public function update(Request $request, $id) {
        $valid = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name,' . $id,
            'parent' => 'required'
        ], [
            'name.required' => 'Vui lòng nhập Tên chuyên mục',
            'name.unique' => 'Chuyên mục đã trùng, vui lòng chọn tên khác',
            'parent.required' => 'Vui lòng nhập Parent ID',
            'parent.exists' => 'Parent ID không hợp lệ'
        ]);

        if ($valid->fails()) {
            return redirect()->back()->withErrors($valid)->withInput();
        } else {
            $category = Category::find($id);
            if ($category !== null) {
                $order = 0;
                if ($request->input('order')) {
                    $order = $request->input('order');
                }
                $category->name = $request->input('name');
                $category->parent = $request->input('parent');
                $category->order = $order;
                $category->save();
                return redirect()->route('admin.category.index')->with('message', "Cập nhật chuyên mục $category->name thành công");
            }
            return redirect()->route('admin.category.index')->with('error', 'Không tìm thấy chuyên mục này');
        }
    }

    public function delete($id) {
        $category = Category::find($id);
        if ($category !== null) {
            $category->delete();
            return redirect()->route('admin.category.index')->with('message', "Xóa chuyên mục $category->name thành công");
        }
        return redirect()->route('admin.category.index')->with('error', 'Không tìm thấy chuyên mục này');
    }
}
