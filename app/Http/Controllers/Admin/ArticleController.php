<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ParsedownExtra;

class ArticleController extends Controller
{
    public function detail()
    {
        return view('admin.article.detail');
    }

    public function show($id)
    {
        $article = DB::table('articles')->where(['id' => $id])->first();
        $markdown = $article->markdown;
        $content = $article->content;
        return view('admin.article.show', compact('markdown', 'content'));
    }

    public function store(Request $request)
    {
        $data = $request->post();
        $markdownParser = new ParsedownExtra();
        $html = $markdownParser->setBreaksEnabled(true)->text($data['markdown']);
        return $this->successData($data);
    }
}
