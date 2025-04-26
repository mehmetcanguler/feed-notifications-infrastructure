<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function success(mixed $data = null, string $message = 'İşlem başarılı.', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function error(string $message = 'Bir hata oluştu.', mixed $errors = null, int $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

    /**
     * Exception'lardan otomatik JSON response üretir.
     */
    protected function fail(\Throwable $exception, int $status = 500): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $this->getErrorMessageForStatusCode($status),
            'errors' => [
                'exception' => class_basename($exception),
                'message' => $exception->getMessage(),
            ],
        ], $status);
    }

    /**
     * Hata koduna göre standart mesaj döner.
     */
    private function getErrorMessageForStatusCode(int $status): string
    {
        return match ($status) {
            400 => 'Geçersiz istek.',
            401 => 'Yetkilendirme başarısız.',
            403 => 'Bu işlemi yapmaya yetkiniz yok.',
            404 => 'Kaynak bulunamadı.',
            422 => 'Doğrulama hatası.',
            500 => 'Sunucu hatası.',
            default => 'Bir hata oluştu.',
        };
    }
}
