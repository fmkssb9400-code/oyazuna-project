#!/usr/bin/env python3
"""記事・画面の「過去にユーザーから指摘されたルール」を、書き込み内容から機械的に検査する。

使い方: article-rules-check.py pre|post  (stdinにhookのJSON)
  pre : Write/Edit の直前。確定ルール違反はexit 2でブロック。
  post: Write/Edit の直後。傾向系(ヘッジ表現の多用・角丸)をexit 2でClaudeにフィードバック。
検査対象は「今回書き込む新しい内容」のみ(Write=content / Edit=new_string)。既存箇所の遡及修正はしない。
"""
import json
import math
import re
import sys

mode = sys.argv[1] if len(sys.argv) > 1 else "pre"
try:
    data = json.load(sys.stdin)
except Exception:
    sys.exit(0)

ti = data.get("tool_input") or {}
path = ti.get("file_path") or ""
text = ti.get("content") if "content" in ti else ti.get("new_string")
if not text or "/.claude/" in path or "/docs/" in path or path.endswith((".md", ".json")):
    sys.exit(0)

problems = []

if mode == "pre":
    # feedback_no_yellow_highlight_span: 黄色背景ハイライトspanは使わない(2026-09-08)
    if re.search(r"background(-color)?\s*:\s*#?f1c40f", text, re.I):
        problems.append(
            "黄色背景ハイライト(background-color:#f1c40f)が含まれています。記事本文では使わない方針です。"
            "強調は<strong>か色付き文字(color:#e03e2d)、コールアウトボックスで代替してください。"
        )
    if problems:
        print("【書き込み中止】\n- " + "\n- ".join(problems), file=sys.stderr)
        sys.exit(2)
    sys.exit(0)

# --- post: 傾向系の検査(書き込みは済んでいる。修正を促すフィードバック) ---
n = len(text)

# feedback_avoid_ai_hedge_phrases: 6000字あたり各5回を超えたら書き直す(2026-10-03)
if n >= 1500:
    limit = max(3, math.ceil(5 * n / 6000))
    for phrase in ("あります。", "とされています。", "と考えられます。", "といえます。"):
        c = text.count(phrase)
        if c > limit:
            problems.append(
                f"「{phrase}」が{c}回({n}字あたりの目安は{limit}回まで)。確定した事実は言い切り、"
                "ヘッジは推測・断定できない事項に限定してください。"
            )

# feedback_no_rounded_corners: 画面のUI要素にrounded-*を付けない
if re.search(r"/resources/(views|css)/", path) and re.search(r"(?<![\w-])rounded(-[a-z0-9]+)?(?![\w-])", text):
    problems.append(
        "rounded系クラスが含まれています。UI要素は角丸なしがデフォルトの方針です"
        "(既存デザインを踏襲する意図的な箇所なら無視してよい)。"
    )

if problems:
    print("【ルール確認】書き込み内容に次の点があります:\n- " + "\n- ".join(problems), file=sys.stderr)
    sys.exit(2)
sys.exit(0)
