<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\UserAction;
use App\Enums\PlatformType;
use Illuminate\Validation\Rules\Enum;



class UserInteractionStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer'],
            'action' => ['required', 'integer', new Enum(UserAction::class)],
            'platform' => ['required', 'integer', new Enum(PlatformType::class)],
            'target_type' => ['required', 'string'],
            'target_id' => ['required', 'integer'],
            'metadata' => ['nullable', 'array'],
            'metadata.page' => ['nullable', 'string'],
            'metadata.device' => ['nullable', 'string'],
            'metadata.duration' => ['nullable', 'integer'],
            'metadata.referrer' => ['nullable', 'string'],
            'metadata.utm_source' => ['nullable', 'string'],
            'metadata.scroll_percentage' => ['nullable', 'integer'],
            'metadata.location' => ['nullable', 'string'],
        ];
    }
    /**
     * Get custom body parameters for Scribe.
     *
     * @return array
     */
    public function bodyParameters(): array
    {
        return [
            'user_id' => [
                'description' => 'İşlemi gerçekleştiren kullanıcının ID bilgisi.',
                'example' => 42,
            ],
            'action' => [
                'description' => 'Kullanıcı eyleminin tipi. Olası değerler: 1 (tıklama), 2 (beğeni), 3 (okuma).',
                'example' => 1,
            ],
            'platform' => [
                'description' => 'Eylemin gerçekleştirildiği platform. Olası değerler: 1 (web), 2 (android), 3 (ios).',
                'example' => 2,
            ],
            'target_type' => [
                'description' => 'Hedef varlık tipi (örneğin: "Post", "Comment").',
                'example' => 'Post',
            ],
            'target_id' => [
                'description' => 'Eylemin ilişkilendirildiği hedef varlığın ID bilgisi.',
                'example' => 101,
            ],
            'metadata' => [
                'description' => 'Kullanıcı etkileşimi hakkında ek metadata bilgileri.',
                'example' => [
                    'page' => 'home',
                    'device' => 'iPhone 15',
                    'duration' => 5,
                    'referrer' => 'homepage',
                    'utm_source' => 'instagram',
                    'scroll_percentage' => 80,
                    'location' => 'İstanbul, Türkiye',
                ],
            ],
            'metadata.page' => [
                'description' => 'Etkileşimin gerçekleştiği sayfa.',
                'example' => 'home',
            ],
            'metadata.device' => [
                'description' => 'Kullanıcının cihaz bilgisi.',
                'example' => 'iPhone 15',
            ],
            'metadata.duration' => [
                'description' => 'Sayfada geçirilen süre (saniye cinsinden).',
                'example' => 5,
            ],
            'metadata.referrer' => [
                'description' => 'Kullanıcının geldiği önceki sayfa.',
                'example' => 'homepage',
            ],
            'metadata.utm_source' => [
                'description' => 'Takip için kullanılan UTM kaynağı.',
                'example' => 'instagram',
            ],
            'metadata.scroll_percentage' => [
                'description' => 'Sayfanın ne kadar aşağı kaydırıldığı (yüzde olarak).',
                'example' => 80,
            ],
            'metadata.location' => [
                'description' => 'Kullanıcının konumu.',
                'example' => 'İstanbul, Türkiye',    
            ]
        ];
    }
    
}
