<?php

namespace App\Http\Controllers;

use App\Repositories\ArticleRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticlesController extends Controller
{
    private $article;

    public function __construct(ArticleRepo $article)
    {
        $this->article = $article;
    }

    public function index(Request $request){

        $request->user()->authorizeRoles(['Admin']);

        $newsList = $this->article->getArticles();

        return view('news', compact('newsList'));
    }

    public function singleArticle(Request $request, $id){

        $request->user()->authorizeRoles(['Admin']);

        $news = $this->article->getArticle($id);

        return view('singleNews', compact('news'));
    }

    public function newArticle(Request $request){

        $request->user()->authorizeRoles(['Admin']);

        return view('newArticle');
    }

    public function addArticle(Request $request){

        $request->user()->authorizeRoles(['Admin']);

            $validator = Validator::make($request->all(), [
                'title' => 'required|min:3',
                'text' => 'required|min:5',
            ]);

            if ($validator->fails()) {
                return redirect('/article')
                    ->withErrors($validator)
                    ->withInput();
            }else{

                if($this->article->createArticle($request)){
                    return redirect('/articles')
                        ->with('status', 'Article save successfully');
                }else{
                    return redirect('/articles')
                        ->with('status', 'Can not save article, try again please');
                }
            }
    }

    public function articleList(Request $request){

        $request->user()->authorizeRoles(['Admin']);

       $dataList = $this->article->articlesList();

       return view('articles',compact('dataList'));
    }

    public function editArticle(Request $request){

        $request->user()->authorizeRoles(['Admin']);

        $article = $this->article->getArticle($request->id);

        return view('newArticle', compact('article'));

    }

    public function deleteArticle(Request $request){

        $request->user()->authorizeRoles(['Admin']);

        if ($this->article->articleDelete($request->id)){
            return redirect('/articles')->with('status', 'Article deleted');
        }else{
            return redirect('/articles')->with('status', 'Can not delete article, please try again');
        }
    }

}
