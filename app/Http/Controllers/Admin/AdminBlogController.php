<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogRequest;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\Cat;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminBlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //ブログ一覧画面
    public function index()
    {
        $blogs = Blog::latest('updated_at')->paginate(10);
       
        return view('admin.blogs.index',['blogs' => $blogs]);
    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    // ブログ投稿処理
    public function store(StoreBlogRequest $request)
    {
        //
        $savedImagePath = $request->file('image')->store('blogs','public');
        $blogs = new Blog($request->validated());
        $blogs->image =$savedImagePath;
        $blogs->save();

        return to_route('admin.blogs.index')->with('success','ブログを投稿しました');
    } 
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    //指定したIDのブログ編集画面
    public function edit(Blog $blog)
    {
        //
       
        $categories = Category::all();
        $cats = Cat::all();

        
        return view('admin.blogs.edit',[
            'blog'=> $blog, 
            'categories' => $categories, 
            'cats' => $cats]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        
        $updateData = $request->validated();

        //画像を変更する場合
        if ($request->has('image')) {
            //　変更前の画像を削除
            Storage::disk('public')->delete($blog->image);
            //変更後の画像をアップロード、保存パスを更新対象データにセット
            $updateData['image'] = $request->file('image')->store('blogs','public');
        }
        $blog->category()->associate($updateData['category_id']);
        $blog->update($updateData);
        $blog->cats()->sync($updateData['cats'] ?? []);


        return to_route('admin.blogs.index')->with('success','ブログを更新しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    //指定したIDのブログの削除機能
    public function destroy(Blog $blog)
    {
        //
        $blog->delete();
        Storage::disk('public')->delete($blog->image);

        return to_route('admin.blogs.index')->with('success','ブログを削除しました');

    }
}
