# Google SEO方針 2026年版（記事作成ガイドライン）

> 作成日: 2026-09-04。Web検索による調査（Google公式ブログ・ドキュメント＋SEO業界メディア多数）の要約。
> **このファイルはoyazunaの業界知識ではなく、Google検索エンジン側の一般的な方針・アルゴリズム動向。** 記事・ガイドページの執筆方針を決める際に参照する。
> 表記ルール: 「公式」＝Google Search Central公式ブログ/ドキュメントで確認済みの一次情報。「業界分析」＝SEO専門メディア・コンサル会社による二次情報（数値・因果関係の断定は業者ブログ発が多く、割り引いて読むこと）。[[feedback_no_speculation_in_content]]の方針に準拠。

---

## 1. 最重要：Google公式「生成AI最適化ガイド」（2026年5月公開）【公式】

Googleが2026年5月15日、Search Central公式ドキュメントに新設した「Generative AI fundamentals」セクションで、AI Overviews等の生成AI機能向け最適化について初めて包括的な公式見解を出した。
出典: https://developers.google.com/search/docs/fundamentals/ai-optimization-guide

### 1-1. 核心メッセージ：「AEO/GEOは結局SEOと同じ」

- 生成AI検索機能はGoogleの通来のランキング・品質システムを土台にしている（RAG・Query fan-outも既存のSearchランキングシステムに依存）。
- 「AEO」「GEO」という新語が使われているが、Googleは実質的に「it's still SEO」と位置づけている。
- **通常のSEO対策（クロール可能性・インデックス登録・Search技術要件の充足）を満たしていることが、生成AI機能に表示される前提条件。**「indexed and eligible to be shown in Google Search with a snippet」でなければAI機能にも出ない。

### 1-2. Googleが明確に否定した「俗説」（AEO/GEO界隈でよく言われるが誤り）

記事作成で「AI向けに特別な書き方をしなければ」と気負う必要はない、とGoogleが名指しで否定している項目：

| 俗説 | Googleの回答 |
|---|---|
| `llms.txt`のような機械可読ファイルを作るべき | 不要。「Google Search ignores them」と明言（他サービス向けに作る分には問題ないが効果はない） |
| コンテンツを細切れ（チャンク）に分割すべき | 不要。「There's no requirement to break your content into tiny pieces」。1ページ内の複数トピックも理解できる |
| 理想的なページ長がある | ない。「There's no ideal page length」 |
| AI向けに特殊な文体・キーワード完全一致で書くべき | 不要。「AI systems can understand synonyms and general meanings」 |
| Web上で不自然な言及（サイテーション）を量産すべき | 「seeking inauthentic mentions across the web isn't as helpful」と否定（スパム扱いのリスクもある） |
| 構造化データがないとAI機能に出ない | 誤り。「Structured data isn't required for generative AI search」（ただしリッチリザルト等、通常のSEO効果としては引き続き有用なので実装は推奨） |

→ **oyazunaへの示唆**: 現状「地名×工法×建物種別」ページ等で構造化データやページ分割の細かい最適化に投資するより、コンテンツの中身（一次情報・独自性）に投資する優先度の方が高いとGoogleが公式に示している。

### 1-3. 「価値ある・唯一無二の・コモディティでないコンテンツ」の定義【公式】

Googleが例示している対比が象徴的：

- ❌ コモディティコンテンツの例: 「7 Tips for First-Time Homebuyers」（どこにでもある一般論）
- ✅ 評価されるコンテンツの例: 「Why We Waived the Inspection & Saved Money」（実体験に基づく一次情報・具体的な意思決定の顛末）

「don't just recycle what others on the internet have already said（ネット上で他者がすでに言っていることを焼き直すな）」と明言。firsthand review（一次体験に基づくレビュー）が「unique perspective」を生むとしている。

→ **oyazunaへの示唆**: 一般論の「相場・費用」解説記事だけでなく、oyazuna掲載企業への直接取材・施工事例・具体的なトラブル顛末など一次情報を組み込む記事が今後より重要になる。[[oyazuna_seo_gsc_findings_20260904]]で「業者選び方型は不振」と分かっているのも、この「コモディティコンテンツは評価されにくい」という流れと整合的。

### 1-4. AIエージェント対応【公式・優先度は低め】

「AI agents」（予約代行・商品比較などを自動で行う自律システム）向けの言及もあるが、Google自身「if this is something that's relevant to your business（自社に関連する場合は）」という条件付きの言及にとどまり、緊急性は低いと位置づけられている。oyazuna規模では現時点で対応不要。

---

## 2. スパムポリシー：生成AIの回答操作も正式にスパム扱い（2026年5月15日改訂）【公式】

出典: https://developers.google.com/search/docs/essentials/spam-policies

Googleのスパムの定義に「生成AIの回答を操作しようとする試み」が明記された：

> techniques used to deceive users or manipulate our Search systems into featuring content prominently, such as attempting to manipulate Search systems into ranking content highly **or attempting to manipulate generative AI responses in Google Search**

これまで通常検索のランキング操作に適用されていたスパムポリシー（scaled content abuse＝大量生成コンテンツの乱用、cloaking、site reputation abuseなど）が、**AI Overviewsの回答を狙った操作にもそのまま適用される**という整理。

**Scaled Content Abuse（大量生成コンテンツ乱用）の具体例（公式定義）**:
- 生成AIツールで大量ページを作り、ユーザー価値を追加しない
- フィード・検索結果・他サイトのコンテンツをスクレイピングして大量生成
- 複数サイトを作って規模を隠す
- 検索キーワードは含むが意味不明な大量ページ

→ **oyazunaへの示唆**: AIで記事を量産する運用（ガイドページテンプレートの機械的複製など）は、質を伴わなければ2026年はより明確にスパム扱いのリスクがある。[[oyazuna_hub_pages_pending]]のハブページ拡充も、テンプレートの機械的複製ではなく個別の一次情報を伴わせる必要性が増している。

---

## 3. 2026年のコアアップデート履歴と傾向【公式＋業界分析】

| 時期 | 名称 | 概要 |
|---|---|---|
| 2026年2月 | Discover Core Update【公式確認済】 | Discover面の表示アルゴリズム更新。センセーショナルな釣りタイトルを減らし、専門性のあるサイトの深みのある・独自性の高い・鮮度の高いコンテンツをより表示 |
| 2026年3月27日〜4月8日 | 3月コアアップデート＋3月スパムアップデート | SE Rankingの計測で上位3位の79.5%が順位変動、上位10位の約4分の1がトップ100圏外に脱落という、観測史上最大級の変動（業界分析） |
| 2026年4月 | 「back button hijacking」対象の新スパムポリシー【公式確認済】 | 不正なブラウザバックボタン制御に対するスパム扱いを明文化。施行開始は6月15日 |
| 2026年5月15日 | 生成AI最適化ガイド公開＋スパムポリシーにAI操作を明記【公式確認済】 | 上記1・2の内容 |
| 2026年5月21日〜 | 5月コアアップデート | 業界分析では3月を上回る変動幅（上位3位のうち元の順位を保ったのは20.5%のみ）。ロールアウトに約2週間 |
| 2026年6月 | 6月スパムアップデート | scaled content abuse・cloaking・keyword stuffing等が対象中心（link spam・site reputation abuseは対象外と公式が明言） |
| 2026年6月 | Search Console「生成AIパフォーマンスレポート」導入【公式確認済】 | AI Overviews等でのサイト露出をSearch Console上で計測可能に |
| 2026年8月 | サイト評判ポリシーの更新【公式確認済】 | EU欧州委員会との協議を経て、EEA域内での運用方針を調整・明確化 |

**「Information Gain（情報の新規性）」説について**: 一部SEO業界メディアが「3月コアアップデートの中心シグナル」として2020年のGoogle特許（US20200349181A1等）を根拠に主張しているが、**Google自身はこのシグナルの重み付けを公式には確認していない**。「上位結果の言い換えでは評価が上がらず、一次データ・独自フレームワーク・専門家の関与があるコンテンツが評価される」という方向性自体は§1-3の公式ガイダンス（一次情報重視）と整合するため参考にはなるが、「Information Gainという名前のシグナルが存在する」という言い方は業界の推測と理解しておくこと。

---

## 4. E-E-A-T（経験・専門性・権威性・信頼性）【公式方針＋業界分析】

- E-E-A-Tの4要素自体はGoogleの検索品質評価ガイドライン（Search Quality Rater Guidelines）に基づく既存の枠組みで、2026年に新設されたものではない。
- 業界分析では「2026年は特にE（Experience＝一次体験）の比重が増した」という論調が多いが、これは§1-3で確認した公式ガイド（firsthand review・一次情報の重視）とも方向性が一致するため、一定の裏付けがあると見てよい。
- AI生成コンテンツ自体への評価: Googleは「AIで作ったかどうかではなく、品質で判断する」という従来方針を維持（公式ガイドにも明記なし＝スタンスに変化なし）。ただし§2の通り「大量生成による低品質化」は明確にスパム扱いが強化されている。**「AIを使うこと」自体はNGではないが、「AIで大量生産して価値を薄めること」がNG、という区別が重要。**

---

## 5. AI Overviews／AI Modeの検索結果への影響【業界分析（数値は複数ソースで傾向は一致するが、一次統計ではない）】

- 日本国内でのAI Overviews表示率は2026年4月時点で約47〜48%（業界分析、Ahrefs等の計測ベース）。クエリ種別で大きく差があり、定義・知識系クエリでは87%、ローカル・地域系クエリでは5%程度と、**表示率はクエリの種類に強く依存する**（業界分析）。
- 米国データでは、AI Overviewsが表示されるとクリック率（CTR）が15〜46%程度低下するという調査結果が複数ある（業界分析、動画・調査元により幅あり）。
- oyazunaが扱う「地名×工法×建物種別」のようなローカル・BtoB系クエリは、上記のクエリ種別分類だと相対的にAI Overviews露出が低いカテゴリに近い可能性がある（推測。実際の検索行動確認が必要）。GSCの生成AIパフォーマンスレポート（§3参照、2026年6月導入）が使えるようになれば、oyazunaの主要記事群でAI Overviewsにどの程度露出しているか実測できるので、確認を推奨。

---

## 6. oyazunaの記事作成方針への反映（まとめ）

既存の[[oyazuna_content_seo_status]]・[[oyazuna_seo_gsc_findings_20260904]]・[[feedback_no_speculation_in_content]]と合わせて、今後の記事・ガイドページ作成で意識すべき点：

1. **一次情報を伴わないテンプレート記事の量産は避ける**（§1-3・§2・§3）。ガイドページを7サービス分横展開する際も、機械的な複製ではなく、各サービス固有の具体的な事例・数値・掲載企業への取材内容を最低限盛り込む。
2. **構造化データ・チャンク分割などの技術的な「AI対策」に過剰投資しない**（§1-2）。Googleが公式に「不要」と否定している施策。技術要件（クロール可能・インデックス登録）を満たす基礎を優先。
3. **「業者選び方」のような一般論型コンテンツより、「地名×工法×建物種別」のような具体・一次情報に近いコンテンツの方が、今のGoogleの方向性（コモディティ排除）とも合致する**。既存のGSC実測傾向（[[oyazuna_seo_gsc_findings_20260904]]）とも整合するため優先度を上げてよい。
4. **AI生成での執筆自体は問題ないが、事実確認と一次情報の追加は必須**。[[feedback_no_speculation_in_content]]の「憶測を書かない」方針は、Googleのスパムポリシー・品質評価の観点からも理にかなっている。
5. Search Console の生成AIパフォーマンスレポート（2026年6月〜利用可能）を一度確認し、oyazunaの記事がAI Overviewsにどの程度露出・引用されているか実測すること（現状未確認）。

---

## 7. 出典一覧

**公式（Google Search Central）**:
- https://developers.google.com/search/docs/fundamentals/ai-optimization-guide
- https://developers.google.com/search/docs/essentials/spam-policies
- https://developers.google.com/search/blog/2026/02/discover-core-update
- https://developers.google.com/search/blog/2026/04/back-button-hijacking
- https://developers.google.com/search/blog/2026/08/update-site-reputation-policy
- https://developers.google.com/search/docs/appearance/spam-updates

**業界分析（数値・因果関係は参考程度に）**:
- https://www.digitalapplied.com/blog/information-gain-google-ranking-signal-april-2026
- https://keywordmap.jp/academy/ai-overviews/
- https://0120.co.jp/blog/aio-46/
- https://www.tryvizup.com/blog/google-spam-policies-for-generative-ai-2026-rules
- その他、本ファイル作成時のWeb検索結果に含まれる複数のSEO業界メディア記事
