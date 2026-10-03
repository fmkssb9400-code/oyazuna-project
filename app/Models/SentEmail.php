<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SentEmail extends Model
{
    public const TYPE_CONTACT = 'contact';
    public const TYPE_QUOTE = 'quote';
    public const TYPE_OTHER = 'other';

    public const TYPE_LABELS = [
        self::TYPE_CONTACT => '問い合わせ',
        self::TYPE_QUOTE => '見積もり',
        self::TYPE_OTHER => 'その他',
    ];

    protected $fillable = [
        'type',
        'from_address',
        'from_name',
        'to_address',
        'subject',
        'body',
    ];

    /**
     * 件名から種別を判定する(問い合わせ: ContactController、見積もり: QuoteController が付ける件名)。
     */
    public static function detectType(string $subject): string
    {
        if (str_contains($subject, 'お問い合わせ')) {
            return self::TYPE_CONTACT;
        }

        if (str_contains($subject, '見積')) {
            return self::TYPE_QUOTE;
        }

        return self::TYPE_OTHER;
    }
}
