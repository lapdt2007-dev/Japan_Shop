<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function reply(Request $request) {
        $request->validate([
            'message' => 'required|String|max:2000',
        ]);
        $apiKey = config('services.gemini.key');
        if (empty($apiKey)) {
            Log:warning('Chatbot: thiếu GEMINI_API_KEY trong .env');
            return response()->json([
                'reply' => 'Chatbot chưa được cấu hình API key'
            ]);
        }
        $prompt = "Bạn là trợ lý AI thân thiện, trả lời bằng tiếng Việt, ngắn gọn dễ hiểu."
                ."Bạn có thể trả lời mọi câu hỏi của người dùng, không chỉ về mua sắm.\n\n"
                ."câu hỏi: " .$request->message;

        try {
            $response = Http::timeout(15) // 3. Giới hạn thời gian chờ, tránh treo trang
                ->retry(2, 500) // 4. Tự thử lại 2 lần nếu lỗi mạng tạm thời
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]]
                    ],
                ]);

            // 5. Nếu Google trả về lỗi (sai key, hết quota, model sai tên...)
            if ($response->failed()) {
                Log::error('Chatbot API lỗi: ' . $response->body());

                $status = $response->status();
                $message = match (true) {
                    $status === 400 => 'Yêu cầu không hợp lệ, thử hỏi lại câu khác nhé.',
                    $status === 403 => 'API key không hợp lệ hoặc bị chặn quyền truy cập.',
                    $status === 429 => 'Hệ thống đang quá tải (vượt giới hạn miễn phí), thử lại sau ít phút nhé.',
                    default => 'AI hiện đang bận, vui lòng thử lại sau.',
                };

                return response()->json(['reply' => $message]);
            }

            $data = $response->json();

            // 6. Nếu Google trả về 200 nhưng cấu trúc dữ liệu bất thường (bị chặn nội dung, rỗng...)
            $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!$reply) {
                Log::warning('Chatbot: response rỗng hoặc bị lọc nội dung', $data);
                return response()->json([
                    'reply' => 'Xin lỗi, mình chưa trả lời được câu này. Bạn thử hỏi theo cách khác nhé!'
                ]);
            }

            return response()->json(['reply' => trim($reply)]);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // 7. Mất mạng / không kết nối được tới Google
            Log::error('Chatbot lỗi kết nối: ' . $e->getMessage());
            return response()->json([
                'reply' => 'Không thể kết nối tới máy chủ AI. Kiểm tra lại kết nối mạng nhé.'
            ]);

        } catch (\Throwable $e) {
            // 8. Bắt mọi lỗi khác chưa lường trước — luôn trả JSON, không bao giờ crash trắng trang
            Log::error('Chatbot lỗi không xác định: ' . $e->getMessage());
            return response()->json([
                'reply' => 'Đã có lỗi xảy ra, vui lòng thử lại sau.'
            ]);
        }
    }
}
