<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArticleController extends Controller
{
    public function index(): ArticleCollection
    {
        $articles = Article::all();

        return new ArticleCollection($articles);
    }

    public function show(Article $article): ArticleResource
    {
        $article = Article::query()
            ->findOrFail($article->id)
            ->first()
            ->load('user');

        return new ArticleResource($article);
    }

    public function store(Request $request): ArticleResource
    {
        $article = Article::query()
            ->create($request->all());

        return new ArticleResource($article);
    }

    public function update(Request $request, Article $article): ArticleResource
    {
        $article->update($request->all());

        return new ArticleResource($article);
    }

    public function destroy(Article $article): JsonResponse
    {
        $article->delete();

        return response()->json(['message' => 'Article deleted successfully!'], Response::HTTP_OK);
    }


    public function count(): JsonResponse
    {
        $articleCount = Article::query()
            ->count();

        return response()->json([$articleCount]);
    }

    public function max(): JsonResponse
    {
        $max = Article::query()
            ->max('min_to_read');

        return response()->json([$max]);
    }

    public function min(): JsonResponse
    {
        $min = Article::query()
            ->min('min_to_read');

        return response()->json([$min]);
    }

    public function median(): JsonResponse
    {
        $article = Article::query()
            ->pluck('min_to_read')
            ->median();

        return response()->json([$article]);
    }

    // which users has most articles!
    public function mode(): JsonResponse
    {
        $article = Article::query()
            ->where('is_published', true)
            ->pluck('user_id')
            ->mode();

        return response()->json([$article]);
    }

    public function random(): ArticleResource
    {
        $article = Article::query()
            ->inRandomOrder()
            ->with('user')
            ->first();

        return new ArticleResource($article);
    }

    public function countBy(): JsonResponse
    {
        $userIds = Article::query()
            ->pluck('user_id')
            ->countBy();

        return response()->json([$userIds]);
    }

    public function sum(): JsonResponse
    {
        $sum = Article::query()
            ->sum('min_to_read');

        return response()->json([$sum]);
    }

    public function where(): ArticleCollection
    {
        $articles = Article::query()
            ->where('is_published', true)
            ->get();

        return new ArticleCollection($articles);
    }

    // Use simple where clauses for straightforward conditions.
    public function whereMulti(): ArticleCollection
    {
        $articles = Article::with('user')
            ->where('is_published', true)
            ->where('min_to_read', '>', 90)
            ->get();

        return new ArticleCollection($articles);
    }

    /**
     * Use closures with where when you need to group conditions together,
     * apply dynamic conditions, or build more complex queries.
     **/
    public function whereClosure(): ArticleCollection
    {
        $articles = Article::with('user')
            ->where(function ($query) {
                $query->where('is_published', true)
                    ->where('min_to_read', '>', 90);
            })
            ->orWhere(function ($query) {
                $query->where('is_published', true)
                    ->where('min_to_read', '<', 10);
            })->get();

        return new ArticleCollection($articles);
    }

    public function whereStrict(): JsonResponse
    {
        $collection = collect([
            ['name' => 'MJ', 'is_published' => true],
            ['name' => 'AS', 'is_published' => 1],
            ['name' => 'MD', 'is_published' => '1'],
        ]);

        $filtered = $collection->whereStrict('is_published', true);

        return response()->json([$filtered]);
    }

    public function whereBetween(): ArticleCollection
    {
        $articles = Article::with('user')
            ->whereBetween('user_id', [1, 10])
            ->get();

        return new ArticleCollection($articles);
    }

    public function whereNull(): ArticleCollection
    {
        Article::query()
            ->where('id', '<', 10)
            ->update(['excerpt' => null]);

        $articles = Article::query()
            ->whereNull('excerpt')
            ->get();

        return new ArticleCollection($articles);
    }

    public function whereDate(): ArticleCollection
    {
        Article::query()
            ->where('id', '<', 10)
            ->update([
                'created_at' => now()
                    ->subYear()
                    ->subMonth()
                    ->subDay()
                    ->subHour()
                    ->subMinute()
                    ->subSecond()
            ]);

        $articles = Article::query()
            ->whereDate('created_at', now()
                ->subYear()
                ->subMonth()
                ->subDay()
                ->subHour()
                ->subMinute()
                ->subSecond()
            )
            ->get();

        return new ArticleCollection($articles);
    }

    public function whereDay(): ArticleCollection
    {
        Article::query()
            ->where('id', '<', 10)
            ->update(['updated_at' => '2024-8-29']);

        $articles = Article::query()
            ->whereDate('updated_at', 29)
            ->get();

        return new ArticleCollection($articles);
    }

    public function whereMonth(): ArticleCollection
    {
        Article::query()
            ->where('id', '<', 10)
            ->update(['created_at' => '2024-07-29']);

        $articles = Article::query()
            ->whereMonth('created_at', 7)
            ->get();

        return new ArticleCollection($articles);
    }

    public function whereYear(): ArticleCollection
    {
        Article::query()
            ->where('id', '<', 10)
            ->update(['created_at' => '2003-8-29']);

        $articles = Article::query()
            ->whereYear('created_at', 2003)
            ->get();

        return new ArticleCollection($articles);
    }

    public function whereTime(): ArticleCollection
    {
        Article::query()
            ->where('id', '<', 10)
            ->update(['updated_at' => '2024-8-29 09:00:00']);

        $articles = Article::query()
            ->whereTime('updated_at', '09:00:00')
            ->get();

        return new ArticleCollection($articles);
    }

    public function filter(): JsonResponse
    {
        $collection = collect([
            10, 9, 8, 7, '', null, false, [], 2, 12, 'string!', 0
        ]);

        $collection = $collection->filter();

        return response()->json([$collection]);
    }

    public function filterClosure(): JsonResponse
    {
        $collection = collect([
            10, 9, 8, 7, '', null, false, [], 2, 12, 'string!', 0
        ]);

        $collection = $collection->filter(function ($value, $key) {
            return $key >= 3 && $value <= 5 && $value > 0;
        });

        return response()->json([$collection]);
    }

    // opposite of filter!
    public function reject(): ArticleCollection
    {
        Article::query()
            ->where('id', '<', 10)
            ->update(['excerpt' => null]);

        $articles = Article::all();

        $rejected = $articles->reject(function ($article) {
            return empty($article->excerpt);
        });

        return new ArticleCollection($rejected);
    }

    public function rejectClosure(): JsonResponse
    {
        $collection = collect([
            10, 9, 8, 7, '', null, false, [], 2, 12, 'string!', 0
        ]);

        $collection = $collection->reject(function ($value) {
            return empty($value);
        });

        return response()->json([$collection]);
    }

    public function contains(): JsonResponse
    {
        $collection = collect([
            10, 9, 8, 7, '', null, false, [], 2, 12, 'string!', 0
        ]);

        return response()->json([$collection->contains(12)]);
    }

    public function except(): JsonResponse
    {
        $collection = collect([
            'name' => 'MJ',
            'age' => 21,
            'city' => 'LA',
            'country' => 'USA',
            'university' => 'California Institute of Technology'
        ]);

        return response()->json([$collection->except('age')]);
    }

    public function only(): JsonResponse
    {
        $collection = collect([
            'name' => 'MJ',
            'age' => 21,
            'city' => 'LA',
            'country' => 'USA',
            'university' => 'California Institute of Technology'
        ]);

        return response()->json([$collection->only('university')]);
    }

    public function select(): JsonResponse
    {
        $articles = Article::query()
            ->where('id', '<', 10)
            ->select('id', 'user_id')
            ->get();

        return response()->json([$articles]);
    }

    public function map(): JsonResponse
    {
        $articles = Article::with('user')
            ->get()
            ->map(function ($article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'user_name' => $article->user->name,
                    'user_email' => $article->user->email
                ];
            });

        return response()->json([$articles]);
    }

    public function mapWithKey(): JsonResponse
    {
        $articles = Article::with('user')
            ->get()
            ->mapWithKeys(function ($article) {
                return [
                    $article->id => [
                        $article->title,
                        $article->user->name,
                        $article->user->email
                    ]
                ];
            });

        return response()->json([$articles]);
    }

    public function pluck(): JsonResponse
    {
        $articlesTitle = Article::query()
            ->pluck('title');

        return response()->json([$articlesTitle]);
    }

    public function keyBy(): JsonResponse
    {
        $articlesById = Article::all()
            ->keyBy('id');

        return response()->json([$articlesById]);
    }

    public function push(): JsonResponse
    {
        $collection = collect([
            'PHP', 'JavaScript', 'Java', 'Python'
        ]);

        return response()->json([$collection->push('Go')]);
    }

    public function put(): JsonResponse
    {
        $collection = collect([
            'PHP', 'JavaScript', 'Java', 'Python'
        ]);

        $collection->put('language', 'C#');

        return response()->json([$collection->put('language', 'Go')]);
    }

    public function pop(): JsonResponse
    {
        $collection = collect([
            'PHP', 'JavaScript', 'Java', 'Python', 'C#'
        ]);

        $collection->pop();

        return response()->json([$collection]);
    }

    public function forget(): JsonResponse
    {
        $collection = collect([
            'PHP', 'JavaScript', 'Java', 'Python', 'C#', 'Go'
        ]);

        return response()->json([$collection->forget(4)]);
    }

    public function shift(): JsonResponse
    {
        $collection = collect([
            'C#', 'PHP', 'JavaScript', 'Java', 'Python'
        ]);

        $collection->shift();

        return response()->json([$collection]);
    }

    public function concat(): JsonResponse
    {
        $mainCollection = collect([
            'PHP', 'JavaScript', 'Java'
        ]);

        $secondaryCollection = collect([
            'Python',
            'Go',
        ]);

        $combined = $mainCollection->concat($secondaryCollection);

        return response()->json([$combined]);
    }

    public function zip(): JsonResponse
    {
        $language = collect([
            'PHP', 'JavaScript', 'Java', 'Python', 'Go'
        ]);

        $framework = collect([
            'Laravel', 'Node.js', 'Spring', 'Django'
        ]);

        $zipped = $language->zip($framework);

        return response()->json([$zipped]);
    }

    public function collapse(): JsonResponse
    {
        $orders = collect([
            [
                'id' => 1,
                'items' => [
                    ['name' => 'Thing', 'price' => 15],
                    ['name' => 'Doodad', 'price' => 20]
                ],
            ],
            [
                'id' => 2,
                'items' => [
                    ['name' => 'Widget', 'price' => 10],
                    ['name' => 'Gizmo', 'price' => 5]
                ],
            ]
        ]);

        $collapse = $orders->pluck('items')->collapse();

        return response()->json([$collapse]);
    }

    public function split(): JsonResponse
    {
        $posts = collect([
            ['title' => 'Post 01', 'body' => 'This is `Post 01` body!'],
            ['title' => 'Post 02', 'body' => 'This is `Post 02` body!'],
            ['title' => 'Post 03', 'body' => 'This is `Post 03` body!'],
            ['title' => 'Post 04', 'body' => 'This is `Post 04` body!'],
            ['title' => 'Post 05', 'body' => 'This is `Post 05` body!'],
            ['title' => 'Post 06', 'body' => 'This is `Post 06` body!'],
            ['title' => 'Post 07', 'body' => 'This is `Post 07` body!'],
            ['title' => 'Post 08', 'body' => 'This is `Post 08` body!'],
            ['title' => 'Post 09', 'body' => 'This is `Post 09` body!'],
            ['title' => 'Post 10', 'body' => 'This is `Post 10` body!'],
        ]);

        $chunks = $posts->split(3);

        return response()->json([$chunks]);
    }

    public function sort(): JsonResponse
    {
        $collection = collect([
            3, 1, 4, 1, 5, 9, 2, 6, 5, 3, 5, 1, 4, 9
        ]);

        $sorted = $collection->sort()->values();

        return response()->json([$sorted]);
    }

    public function sortDesc(): JsonResponse
    {
        $collection = collect([
            3, 1, 4, 1, 5, 9, 2, 6, 5, 3, 5, 1, 4, 9
        ]);

        $sorted = $collection->sortDesc()->values();

        return response()->json([$sorted]);
    }

    public function sortBy(): JsonResponse
    {
        $collection = collect([
            ['name' => 'Dary', 'age' => 27],
            ['name' => 'John', 'age' => 30],
            ['name' => 'MJ', 'age' => 21],
            ['name' => 'Jane', 'age' => 25],
            ['name' => 'MM', 'age' => 20],
        ]);

        $sorted = $collection->sortBy('age')->values();

        return response()->json([$sorted]);
    }

    public function sortByDesc(): JsonResponse
    {
        $collection = collect([
            ['name' => 'Dary', 'age' => 27],
            ['name' => 'John', 'age' => 30],
            ['name' => 'MJ', 'age' => 21],
            ['name' => 'Jane', 'age' => 25],
            ['name' => 'MM', 'age' => 20],
        ]);

        $sorted = $collection->sortByDesc('age')->values();

        return response()->json([$sorted]);
    }

    public function sortKeys(): JsonResponse
    {
        $collection = collect([
            ['name' => 'Dary', 'age' => 27],
            ['name' => 'John', 'age' => 30],
            ['name' => 'MJ', 'age' => 21],
            ['name' => 'Jane', 'age' => 25],
            ['name' => 'MM', 'age' => 20],
        ]);

        $sorted = $collection->sortKeys();

        return response()->json([$sorted]);
    }

    public function sortKeysDesc(): JsonResponse
    {
        $collection = collect([
            ['name' => 'Dary', 'age' => 27],
            ['name' => 'John', 'age' => 30],
            ['name' => 'MJ', 'age' => 21],
            ['name' => 'Jane', 'age' => 25],
            ['name' => 'MM', 'age' => 20],
        ]);

        $sorted = $collection->sortKeysDesc();

        return response()->json([$sorted]);
    }
}
