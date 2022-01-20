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
        Article::create([
            'title' => '欢迎来到我的博客',
            'cover_img' => 'http://oss.shayvmo.cn/blog/upload/6d86cdc9b338679bce51a944bc3c716066.jpg',
            'author' => '沙屿沫',
            'markdown' => $data['markdown'],
            'content' => $html,
            'article_url' => '',
            'status' => 0,
        ]);
        return $this->successData($data);
    }
}
