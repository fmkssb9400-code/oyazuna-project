<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * 日本の電話番号のみ許可（海外番号のボット対策）。
 * 全角数字・ハイフン類・スペース・括弧は無視して判定。0始まり10〜11桁、または+81始まり。
 */
class JapanesePhone implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $v = mb_convert_kana((string) $value, 'n');
        $v = preg_replace('/[\s\-‐‑–—―ー−－()（）]/u', '', $v);

        if (! preg_match('/^(?:0\d{9,10}|\+81\d{9,10})$/', $v)) {
            $fail('電話番号は日本国内の番号（例：03-1234-5678）で入力してください。');
        }
    }
}
