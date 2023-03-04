<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Http\Requests\BlogRequest;
use Illuminate\Support\Facades\DB;


class BlogController extends Controller
{
    /**
     * ブログ一覧を表示する
     * 
     * @return View
    */
    public function showList()
    {
        $blogs = Blog::all();

        return view('blog.list',
        ['blogs'=>$blogs]);
    }


    /**
     * ブログ詳細を表示する
     * @param int $id
     * @return View
    */
    public function showDetail($id)
    {
        $blog = Blog::find($id);

        //もし該当するデータのIDがなかったら一覧画面に戻る
        if(is_null($blog))
        {
            session()->flash('err_msg', 'データがありません。');
            return redirect(route('blogs'));
        }

        return view('blog.detail',
        ['blog'=>$blog]);
    }

    /**
     * ブログ登録画面を表示する
     * 
     * @return View
    */
    public function showCreate()
    {
        return view('blog.form');
    }

    /**
     * ブログを登録する
     * 
     * @return View
    */
    public function exeStore(BlogRequest $request)
    {
        //ブログのデータを受け取る
        $inputs = $request->all();

        DB::beginTransaction();
        try{
            //ブログの登録
            Blog::create($inputs);
            DB::commit();
        }catch(\Throwable $e){
            DB::rollback();
            abort(500);
            error_log($e);
        }

        session()->flash('err_msg', 'ブログ登録しました');
        return redirect(route('blogs'));
    }

    /**
     * ブログ編集フォームを表示する
     * @param int $id
     * @return View
    */
    public function showEdit($id)
    {
        $blog = Blog::find($id);

        //もし該当するデータのIDがなかったら一覧画面に戻る
        if(is_null($blog))
        {
            session()->flash('err_msg', 'データがありません。');
            return redirect(route('blogs'));
        }

        return view('blog.edit',
        ['blog'=>$blog]);
    }

    /**
     * ブログを編集する
     * 
     * @return View
    */
    public function exeUpdate(BlogRequest $request)
    {
        //ブログのデータを受け取る
        $inputs = $request->all();

        DB::beginTransaction();
        try{
            //ブログの登録
            $blog = Blog::find($inputs['id']);
            $blog->fill([
                'title' => $inputs['title'],
                'content' => $inputs['content']
            ]);
            $blog->save();

            DB::commit();
        }catch(\Throwable $e){
            DB::rollback();
            abort(500);
            error_log($e);
        }

        session()->flash('err_msg', 'ブログ更新しました');
        return redirect(route('blogs'));
    }

    /**
     * ブログを削除する
     * @param int $id
     * @return View
    */
    public function exeDelete($id)
    {
        //もし該当するデータのIDがなかったら一覧画面に戻る
        if(empty($id))
        {
            session()->flash('err_msg', 'データがありません。');
            return redirect(route('blogs'));
        }

        try{
            //ブログを削除
            Blog::destroy($id);
        }catch(\Throwable $e){
            DB::rollback();
            abort(500);
            error_log($e);
        }
        //ブログ削除成功時メッセージ
        session()->flash('err_msg', '削除しました。');
        return redirect(route('blogs'));
    }
};
