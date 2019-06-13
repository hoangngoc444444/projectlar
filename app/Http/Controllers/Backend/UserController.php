<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
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
        $data['users'] = User::paginate(20);

        return view('admin.users.index', $data);
    }


    public function create() {
        return view('admin.users.create');
    }

    public function store(Request $request) {
        $valid = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed'
        ], [
            'name.required' => 'Vui lòng nhập Họ Tên',
            'email.required' => 'Vui lòng nhập Email',
            'email.email' => 'Không đúng định dạng Email',
            'email.unique' => 'Email này đã trùng vui lòng chọn Email khác',
            'password.required' => 'Vui lòng nhập Mật Khẩu',
        ]);
        if ($valid->fails()) {
            return redirect()->back()->withErrors($valid)->withInput();
        } else {
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
            ]);
            return redirect()->route('admin.user.index')->with('message', "Thêm người dùng $user->name thành công");
        }
    }

    public function show($id) {
        $data['user'] = User::find($id);
        if ($data['user'] !== null) {
            return view('admin.users.show', $data);
        }
        return redirect()->route('admin.user.index')->with('error', 'Không tìm thấy người dùng này');
    }

    public function update(Request $request, $id) {
        $valid = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'confirmed'
        ], [
            'name.required' => 'Vui lòng nhập Họ Tên',
            'email.required' => 'Vui lòng nhập Email',
            'email.email' => 'Không đúng định dạng Email',
            'email.unique' => 'Email này đã trùng vui lòng chọn Email khác'
        ]);

        if ($valid->fails()) {
            return redirect()->back()->withErrors($valid)->withInput();
        } else {
            $user = User::find($id);
            if ($user !== null) {
                $user->name = $request->input('name');
                $user->email = $request->input('email');
                if ($request->input('password')) {
                    $user->password = bcrypt($request->input('password'));
                }
                $user->save();
                return redirect()->route('admin.user.index')->with('message', "Cập nhật người dùng $user->name thành công");
            }
            return redirect()->route('admin.user.index')->with('error', 'Không tìm thấy người dùng này');
        }
    }

    public function delete($id) {
        $user = User::find($id);
        if ($user !== null) {
            $user->delete();
            return redirect()->route('admin.user.index')->with('message', "Xóa người dùng $user->name thành công");
        }
        return redirect()->route('admin.user.index')->with('error', 'Không tìm thấy người dùng này');
    }
}
