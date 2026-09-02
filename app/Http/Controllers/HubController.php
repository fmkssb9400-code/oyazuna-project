<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class HubController extends Controller
{
    /**
     * カテゴリ別ハブページの設定。
     * 掲載企業数が一定以上あるカテゴリのみ追加すること（薄いページ防止）。
     */
    protected array $categories = [
        'window-cleaning' => [
            'key' => 'window',
            'label' => '窓ガラス清掃',
            'h1' => '窓ガラス清掃に対応する高所ロープ作業業者一覧',
            'lead' => '高層ビル・マンション・商業施設の窓ガラス清掃に対応する、無足場工法（ロープアクセス・ゴンドラ等）の専門業者を掲載しています。足場を組まずに施工できるため、営業中の建物でも短工期・低コストで依頼しやすいのが特長です。',
            'meta_description' => '窓ガラス清掃に対応する高所ロープ作業の専門業者一覧。足場不要のロープアクセス・ゴンドラ工法に対応した業者を口コミ・実績で比較できます。',
            'faq' => [
                ['q' => '窓ガラス清掃はロープアクセスとゴンドラどちらが安いですか？', 'a' => '足場が不要な分どちらも一般的な足場工法より費用を抑えやすい傾向がありますが、建物の高さ・形状・設置環境によって最適な工法は変わります。複数社に現地調査を依頼して見積もりを比較することをおすすめします。'],
                ['q' => '定期清掃と単発清掃どちらも依頼できますか？', 'a' => 'はい。掲載業者の多くは単発の清掃だけでなく、月次・年次などの定期契約にも対応しています。各社の詳細ページで対応内容をご確認ください。'],
                ['q' => '営業中のビルでも清掃を依頼できますか？', 'a' => 'ロープアクセス・ゴンドラ工法は足場設置が不要なため、営業を止めずに施工できるケースが多いです。夜間・早朝対応の可否は業者ごとに異なるため、見積もり時にご確認ください。'],
            ],
        ],
        'wall-repair' => [
            'key' => 'repair',
            'label' => '外壁補修',
            'h1' => '外壁補修に対応する高所ロープ作業業者一覧',
            'lead' => 'ひび割れ・タイル浮き・シーリング劣化など、外壁の部分補修に対応する無足場工法の専門業者を掲載しています。全面的な足場を組まずにピンポイントで補修できるため、大規模修繕の前段階の応急処置や、範囲が限定的な補修に向いています。',
            'meta_description' => '外壁補修に対応する高所ロープ作業の専門業者一覧。ひび割れ・タイル浮き・シーリング劣化などの部分補修を、足場不要のロープアクセス工法で依頼できる業者を比較できます。',
            'faq' => [
                ['q' => '外壁補修だけを部分的に依頼できますか？', 'a' => 'はい。全面的な外壁塗装ではなく、ひび割れやタイル浮きなど気になる箇所だけの部分補修に対応する業者を掲載しています。'],
                ['q' => '補修前の調査だけ依頼することはできますか？', 'a' => '外壁調査（劣化診断）のみの依頼に対応している業者もあります。補修が必要かどうか判断がつかない場合は、まず調査から相談すると安心です。'],
                ['q' => '足場を組む場合と比べて費用は安くなりますか？', 'a' => '補修範囲が限定的な場合、足場の設置・解体費用がかからない分、総額を抑えられるケースが多いです。ただし建物の規模や補修範囲によって差があるため、見積もりでの比較をおすすめします。'],
            ],
        ],
    ];

    public function category(Request $request, string $slug)
    {
        abort_unless(isset($this->categories[$slug]), 404);

        $config = $this->categories[$slug];

        $companies = Company::published()
            ->whereJsonContains('service_categories', $config['key'])
            ->withCount('reviews')
            ->withAvg('reviews as average_rating', 'total_score')
            ->orderByDesc('recommend_score')
            ->paginate(12)
            ->withQueryString();

        return view('hub.category', [
            'config' => $config,
            'slug' => $slug,
            'companies' => $companies,
            'otherHubs' => collect($this->categories)->except($slug),
        ]);
    }
}
