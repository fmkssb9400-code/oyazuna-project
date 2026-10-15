#!/usr/bin/env python3
"""官公庁・自治体の公式サイトだけを取得してテキスト化する(記事リサーチ用)。
許可ドメイン: *.go.jp / *.lg.jp のみ(e-Gov法令検索・国交省・自治体公式)。それ以外は拒否。
使い方: python3 scripts/fetch-official.py <https://...> [最大文字数(既定60000)]
"""
import json
import re
import subprocess
import sys
import urllib.request
from urllib.parse import urlparse

ALLOWED_SUFFIXES = (".go.jp", ".lg.jp")
MAX_BYTES = 8_000_000


def flatten_law(node, out):
    # e-Gov法令API v2(JSON)の本文ツリーを、条・項の改行を保って平文化する
    if isinstance(node, str):
        out.append(node)
    elif isinstance(node, list):
        for n in node:
            flatten_law(n, out)
    elif isinstance(node, dict):
        tag = node.get("tag", "")
        flatten_law(node.get("children", []), out)
        if tag in ("Article", "Paragraph", "Item", "ArticleTitle", "ParagraphNum", "Sentence", "ArticleCaption"):
            out.append("\n" if tag in ("Article", "Paragraph", "Item") else " ")


def main():
    if len(sys.argv) < 2:
        sys.exit("usage: fetch-official.py URL [max_chars]")
    url = sys.argv[1]
    limit = int(sys.argv[2]) if len(sys.argv) > 2 else 60000
    u = urlparse(url)
    host = (u.hostname or "").lower()
    if u.scheme != "https" or not host.endswith(ALLOWED_SUFFIXES):
        sys.exit(f"REFUSED: 許可ドメイン(*.go.jp, *.lg.jp)以外・https以外は取得しません: {host}")

    req = urllib.request.Request(url, headers={"User-Agent": "Mozilla/5.0 (oyazuna-research)"})
    try:
        with urllib.request.urlopen(req, timeout=30) as r:
            ctype = r.headers.get("Content-Type", "")
            data = r.read(MAX_BYTES)
    except Exception as e:
        sys.exit(f"取得失敗: {e}")

    if host == "laws.e-gov.go.jp" and u.path.startswith("/api/2/law_data/"):
        try:
            j = json.loads(data.decode("utf-8"))
            out = []
            flatten_law(j.get("law_full_text", j), out)
            print(f"# source: {url}")
            print(re.sub(r"\n\s*\n+", "\n", "".join(out))[:limit])
            return
        except Exception as e:
            sys.exit(f"法令JSONの解析に失敗: {e}")

    if "pdf" in ctype or url.lower().endswith(".pdf"):
        p = subprocess.run(["pdftotext", "-layout", "-", "-"], input=data, capture_output=True)
        if p.returncode != 0:
            sys.exit("PDFの抽出に失敗(pdftotext未導入の可能性)。Chromeで開いて確認してください")
        text = p.stdout.decode("utf-8", "ignore")
    else:
        m = re.search(r"charset=([\w-]+)", ctype, re.I) or re.search(rb"charset=[\"']?([\w-]+)", data[:3000], re.I)
        enc = (m.group(1).decode() if isinstance(m.group(1), bytes) else m.group(1)) if m else "utf-8"
        html = data.decode(enc, "ignore")
        html = re.sub(r"(?is)<(script|style|noscript)\b.*?</\1>", " ", html)
        html = re.sub(r"(?s)<[^>]+>", " ", html)
        text = re.sub(r"[ \t　]+", " ", html)
        text = re.sub(r"\n\s*\n+", "\n", text)

    print(f"# source: {url}")
    print(text[:limit])


if __name__ == "__main__":
    main()
