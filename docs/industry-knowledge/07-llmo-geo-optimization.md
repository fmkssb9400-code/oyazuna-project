# LLMO / GEO（生成AI検索最適化）2026年版 — Google以外のAIエンジン横断リサーチ

> 作成日: 2026-10-06。Web検索による調査（学術論文1本＋SEO業界メディア多数）の要約。
> **このファイルは`05-google-seo-policy-2026.md`（Google公式方針）の続編**で、ChatGPT・Perplexity等Google以外のAI検索エンジンの動向と、GEO（Generative Engine Optimization）の学術研究を扱う。
> 表記ルール: 「学術」＝査読付き研究論文。「公式」＝各社の公式ドキュメント・経営陣発言で確認済み。「業者ブログ」＝SEO/GEO専業ベンダーの発信（具体的な%数値の多くはここ発で、営業目的のバイアスがかかりやすい。ベンダー間で数値が大きく割れている指標は「傾向の参考」程度に留めること）。[[feedback_no_speculation_in_content]]の方針に準拠。

---

## 1. 結論サマリー（oyazunaへの示唆）

1. **`llms.txt`は依然として不要**。Google・OpenAI・Anthropic・Perplexity・Metaいずれも、消費者向け検索・チャット機能のランキング/引用シグナルとして使っていると公式確認した例はゼロ（§3）。実装コストは低いが優先度は最低のまま。
2. **ChatGPT Search対策として、Bing Webmaster Toolsへの登録が未実施なら価値がある**。ChatGPT SearchはBing検索インデックスへの依存度が高いとされる（§4）。**oyazunaは現状Bing Webmaster Toolsに未登録と確認**（2026-10-06、ブラウザで未サインイン状態を確認）。登録自体は無料・低コストなので優先度を上げてよい。
3. **「バックリンクよりブランド言及（mentions）」という、従来SEOと異なる力学がGEOでは強調されている**（§6）。oyazuna掲載企業や業界内での言及を増やす施策（業界団体サイトへの掲載、口コミプラットフォームでの一貫した情報、SNS言及等）は、リンク獲得より優先度を上げる価値があるかもしれない。
4. **「結論ファースト」「直接回答形式」のコンテンツ構造は、既存のGoogle公式方針とも、Perplexity個別の実証傾向とも整合する**（§5）。新規の対応ではなく、今の記事作成方針の延長で良い。
5. **「地名×工法×建物種別」のような同一トピッククラスター内での網羅的な露出**は、Perplexityの実証研究で最も強い予測因子とされる「関連プロンプト群での可視性スコア」と整合的（§5）。oyazunaが[[oyazuna_hub_vs_article_cannibalization]]等で進めてきたハブページ拡充の方向性は、GEOの観点からも理にかなっている。

---

## 2. GEOという分野の立ち位置（学術研究）【学術】

GEO（Generative Engine Optimization）という用語は、Princeton大学・Georgia Tech・Allen Institute for AI・IIT Delhiの研究チームが2023年に提唱し、2024年にACM SIGKDDで発表した論文 *"GEO: Generative Engine Optimization"*（Aggarwal et al., arXiv:2311.09735）が起点。10,000クエリのベンチマークで9種類のコンテンツ改善手法をテストし、**統計データ・引用・専門家の発言の追加で生成AI回答内での可視性が最大40%向上**という結果を報告した。

**重要な留保（2026年の批判的サーベイより）**: 2026年7月公開の *"Optimizing Visibility in Generative Engines: A Critical Survey"*（arXiv:2607.14035、45本の先行研究をレビュー）は、GEOを「単一のランキングタスクではなく、検索起動・クロール・索引化・検索・再ランキング・引用・事実の吸収・再現性・ユーザー行動まで含む確率的で部分観測可能なパイプライン」と位置づけ、個々のベンダー調査の数値を「普遍的なランキング要因」として鵜呑みにしないよう警告している。oyazunaの情報整理でもこの立場を踏襲し、具体的な%数値は「傾向」として扱い、断定しない。

**GEO研究で統計的に確認された傾向（Princeton論文ベース）**:
- 統計データ・数値の追加: 可視性+40%程度（最も効果が大きいとされる施策）
- 引用・専門家の発言の追加: 同様に高い効果
- キーワードの過剰な繰り返し（500字あたり同一フレーズ2回超）: 可視性**-10%**（Googleが公式に明言する「AI向けキーワード最適化は不要」という方針と整合）
- 流暢さ・読みやすさ: プラス方向だが他の施策より効果は小さい

---

## 3. llms.txt の決着【複数ソースで再確認】

`05-google-seo-policy-2026.md`でのGoogle公式見解（不要）に加え、他社も含めて改めて調査。

| 企業 | 公式見解 |
|---|---|
| Google | 「Google Searchは無視する」と明言済み（Gary Illyes, 2025年7月／John Mueller、過去のkeywordsメタタグと同種の失敗と比較） |
| OpenAI | 自社のAgents SDK・Agentic Commerce Protocol向けには`llms.txt`を公開しているが、ChatGPT Searchのランキング/引用シグナルとして使うとは公式に確認していない |
| Anthropic | 自社ドキュメント（Writing for Agents）で`llms.txt`を推奨しているが、これは**開発者向けのAIエージェント（Claude Codeのようなコーディングエージェント等）がドキュメントサイトを効率よく読むための用途**であり、一般消費者向けの検索・チャット機能の話ではない |
| Perplexity | `llms.txt`のコンテンツを独自に参照している観測例はあるが、標準的な検索ランキングシグナルとして確認されたわけではない |
| Meta / Mistral | 公式見解なし |

**定量データ**: SE Rankingの30万ドメイン調査で採用率10.13%。別の調査（Limy）では5億件超のAI bot通信のうち`llms.txt`への直接アクセスはわずか408件（統計的に無視できる水準）。94,000件のAI引用を分析した調査（ALLMO.ai）でも`llms.txt`経由の引用は0.002%未満。

**oyazunaへの示唆**: oyazunaはAPI・開発者向けドキュメントサイトではなく、一般消費者・BtoB発注担当者向けの比較サイトなので、Anthropic/OpenAIが言う「エージェント向け用途」にも該当しない。引き続き`llms.txt`は作らなくてよい。

---

## 4. ChatGPT Search の仕組みと対策【業者ブログ（複数ソースで傾向が一致）】

- OpenAIは2種類のクローラーを使い分けている: `GPTBot`（学習データ収集用）と`OAI-SearchBot`（リアルタイム検索用、"ChatGPT-User"と表記されることもある）。**`robots.txt`で`OAI-SearchBot`をブロックすると、ChatGPT Searchの検索結果から完全に除外される**。oyazunaの`robots.txt`は全クローラーに全面公開済み（`Disallow:`なし）なので、ここは問題なし。
- **ChatGPT Searchはリアルタイム検索の実装に相当部分Bingのインデックスを利用している**とされ、ある分析ではChatGPT検索結果とBing検索結果の重複率が73%という報告がある。**Bing Webmaster Toolsへのサイト登録・サイトマップ送信が、ChatGPT Search対策として独立した価値を持つ**とする複数ソースが一致。
- Googleの検索順位とChatGPTの引用には**重複率が約12%しかない**という報告があり（調査元による）、「Googleで上位＝ChatGPTでも引用される」とは限らない。
- 1つの回答に引用されるソースは平均4.7〜4.9件程度、特定ドメインが4%以上のシェアを持つことはない（＝引用は非常に分散している）、という分析がある。
- サーバーサイドレンダリングのページはJS重量級SPAより引用されやすい（2.4倍という報告）とされる。**oyazunaはLaravel/Bladeのサーバーサイドレンダリングなので、この点は元々有利な構造**。

**oyazunaへの示唆（優先順位付き）**:
1. **Bing Webmaster Toolsへの登録（未実施、要対応）** — 無料・低コストで、ChatGPT Search経由の露出に直接関わる可能性がある基礎的な抜け漏れ。
2. robots.txtは現状維持でよい（既にOAI-SearchBot等をブロックしていない）。
3. 個別の技術的な引用率向上施策（ページ速度等）は、Googleの通常SEOで既に取り組んでいる範囲でカバーされる。

---

## 5. Perplexity の仕組みと対策【業者ブログ（複数ソースで傾向が一致）】

- Perplexityは固定インデックスを持たず、クエリごとにリアルタイムでWeb検索・再ランキング・引用を行う。Google Search Consoleに相当する公式計測ツールは無く、**手動でクエリをテストして自社の露出を確認するしかない**のが実務上の課題（複数の業者ブログが共通してこの手法を推奨）。
- **Reddit等のコミュニティサイトからの引用比率が高い**（一調査では上位引用の46.7%がReddit）。「他サイトでの言及の蓄積（コンセンサス）」をPerplexityが重視しているという分析が多い。
- バックリンク数の直接効果は小さいという分析が複数ある（引用された85,000〜94,000件のURL分析で、大半が被リンク50未満だったとする報告）。**従来SEOの「被リンクを集める」発想とは異なる力学**。
- Q&A形式・直接回答形式のコンテンツ構造は、Top3入りする確率が平均31%に対し55%という報告がある（1調査のみ、要注意）。
- **最も相関が強いとされる因子は「関連プロンプト群にまたがる可視性スコア」**（1つのクエリだけでなく、同一トピッククラスターの複数クエリで継続的に引用されること）。これがいったん確立されると「好循環（flywheel）」が生まれ、新しいクエリでも自動的に引用されやすくなる、という分析がある。
- 更新頻度（鮮度）への感応度がGoogleのAI Overviewsより強いという報告が複数ある。

**oyazunaへの示唆**:
- Perplexity単体への個別最適化は優先度を上げすぎない（oyazunaの規模・業種ではPerplexity経由のトラフィック自体がまだ小さいと推測される。実測する手段が無いため「推測」に留める）。
- ただし「関連プロンプト群での一貫した露出」という考え方は、既存のoyazunaの「地名×工法×建物種別」のハブページ網羅戦略とすでに方向性が一致しており、**今の路線を続けることがそのままGEO対策にもなっている**と整理できる。
- 記事・guideページの冒頭に結論ファーストの直接回答パラグラフを置く構成は、既に一部のページで実践済み（[[docs/industry-knowledge/06-writing-techniques.md]]の構成方針と整合）。

---

## 6. 「ブランド言及（Mentions）」がバックリンクより重要という力学【業者ブログ（大規模調査、ただし単一ソース）】

Ahrefsの75,000ブランド調査（2025年8月）によれば、**ブランド言及（mentions）とAI可視性の相関（0.664）は、被リンクとの相関（0.218）の約3倍**という報告がある。Muck Rackの調査（2025年12月）では、AI引用の82%が「アーンドメディア（第三者による自発的な言及）」由来で、広告・自社コンテンツ発の引用はわずか6%という報告もある。

メカニズムとしての説明: LLMは「ハイパーリンクのグラフ構造」ではなく「生のテキスト」で学習されているため、独立した複数のソース（業界フォーラム・比較記事・レビューサイト・ニュース等）で一貫してブランド名が言及されているほど、「信頼できる既知のエンティティ」として認識されやすい、という理屈。

**oyazunaへの示唆（推測を含む、裏取りは今後必要）**:
- oyazuna自体のブランド名が、第三者サイト（業界メディア・掲載企業のHP・SNS・比較記事等）でどれだけ言及されているかは未計測。計測手段としてはGoogleアラート設定や、定期的な「oyazuna」でのWeb検索が考えられる。
- 掲載企業に「oyazunaに掲載されています」という文言・リンクを自社HPに置いてもらう依頼は、被リンクというより「言及」の増加策として捉え直すと優先度が上がる可能性がある。
- oyazunaの各都道府県ページ・ハブページで企業情報を具体的に扱っていること自体が、間接的に「この企業＝oyazunaで比較できる」という連想をAIに学習させる土壌になっているかもしれない（推測）。

---

## 7. oyazunaの既存方針との整合性チェック

| 既存方針 | GEO観点での評価 |
|---|---|
| 「地名×工法×建物種別」のハブページ網羅戦略 | ◎ Perplexityの「関連プロンプト群での可視性」と整合。今の路線を継続してよい |
| 一次情報・独自データの重視（[[feedback_no_speculation_in_content]]） | ◎ GoogleのE-E-A-T方針・GEO学術研究（統計データの効果）の両方と整合 |
| ロングテール・検索意図の具体性重視（[[feedback_longtail_volume_judgment]]） | ◎ Perplexityの直接回答形式・結論ファースト構成と相性が良い |
| 専門解説シリーズ（ボリューム非依存方針） | △ GEOの観点でも、網羅性自体はプラスだが即効性のあるAI引用には直結しない可能性（PV実績も現状ほぼゼロ、[[oyazuna_pv_growth_actions_20261003]]参照） |
| llms.txt未実装 | ○ 継続して問題なし（§3） |
| Bing Webmaster Tools未登録 | ✗ **要対応**。ChatGPT Search対策として独立価値あり（§4） |
| ブランド言及・第三者サイトでの露出施策 | 未着手。優先度の検討余地あり（§6） |

---

## 8. 出典一覧

**学術（査読付き・プレプリント含む）**:
- Aggarwal et al., "GEO: Generative Engine Optimization", KDD 2024 (arXiv:2311.09735)
- "Optimizing Visibility in Generative Engines: A Critical Survey of Generative Engine Optimization (2023-2026)" (arXiv:2607.14035, 2026年7月)

**公式発言（企業の公式見解・経営陣発言）**:
- Google Gary Illyes発言（2025年7月, llms.txt非対応表明）、John Mueller発言（keywordsメタタグとの比較）
- Anthropic "Writing for Agents"公式ガイダンス（llms.txt推奨、開発者向けエージェント用途）
- OpenAI Agents SDK / Agentic Commerce Protocol公式ドキュメント

**業者ブログ・調査レポート（数値は参考程度、出典間でばらつきあり）**:
- https://www.indexlab.ai/blog/llms-txt-does-it-actually-work-october-2025-updated
- https://www.getpassionfruit.com/blog/should-i-create-an-llms.txt-file-google-s-2026-guidance-explained
- https://limy.ai/blog/llms-txt-in-2026-the-full-guide
- https://promptwatch.com/blog/chatgpt-seo
- https://crawlsense.ai/blog/chatgpt-search-optimization
- https://www.erlin.ai/blog/chatgpt-search-optimization
- https://keyword.com/blog/perplexity-search-ranking-factors-seo-guide
- https://www.tryanalyze.ai/blog/how-to-rank-on-perplexity
- https://seoprofy.com/blog/how-to-rank-in-perplexity-ai
- https://www.omnibound.ai/blog/generative-engine-optimization-statistics（Ahrefs 75,000ブランド調査・Muck Rack調査の引用元）
- その他、本ファイル作成時のWeb検索結果に含まれる複数のSEO/GEO業界メディア記事
