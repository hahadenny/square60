<?php

namespace App\Repositories;

use App\Article;

class ArticleRepo
{

    public function getArticles()
    {
        return Article::all();
    }

    public function getArticle($id)
    {
        return Article::find($id);
    }

    public function createArticle($request)
    {
        if($request->has('id')) {
            $article = $this->getArticle($request->id);
        }else{
            $article = new Article();
        }

        return $article->fill($request->except('_token'))->save();
    }

    public function articlesList()
    {
        return Article::paginate(env('SEARCH_RESULTS_PER_PAGE'));
    }

    public function articleDelete($id)
    {
        $article = $this->getArticle($id);

        $article->delete();

        return $article;
    }
}