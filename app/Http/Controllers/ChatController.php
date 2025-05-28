<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function ask(Request $request)
    {
        $message = $request->input('message');
        $allowed_keywords = ['product', 'cosmetic', 'pay', 'checkout', 'account', 'register', 'promotion', 'discount', 'order', 'address', 'category'];
        $is_relevant = false;
        foreach ($allowed_keywords as $keyword) {
            if (stripos($message, $keyword) !== false) {
                $is_relevant = true;
                break;
            }
        }
        if (!$is_relevant) {
            return response()->json([
                'reply' => '🤖 I’m AdikBot – here to help with anything about Adik Cosmetics 🛍️. Please ask about our products, promotions, or how to use this website.'
            ]);
        }
        // fetch product from firestore
        $promoProducts = $this->getPromotionalProductsFromFirestore();
        $promoListText = count($promoProducts)
            ? implode("\n", array_map(fn($p, $i) => ($i + 1) . ". " . $p, $promoProducts, array_keys($promoProducts)))
            : "There are currently no active product promotions.";
        // Send to GPT
        $response = OpenAI::chat()->create([
            'model' => 'gpt-4',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "You are AdikBot, the official chatbot of Adik Cosmetics. Respond only with information about the site such as products, categories, promotions, payment, or registration. These are the current promotional products:\n\n{$promoListText}\n\nAlways use this list if the user asks about current promotions."
                ],
                [
                    'role' => 'user',
                    'content' => $message
                ]
            ],
            'temperature' => 0.5,
        ]);
        return response()->json([
            'reply' => $response['choices'][0]['message']['content'] ?? 'Sorry, I can’t answer right now.'
        ]);
    }

    private function getPromotionalProductsFromFirestore()
    {
        $url = 'https://firestore.googleapis.com/v1/projects/adikcosmetics-1518b/databases/(default)/documents/products';
        $response = Http::get($url);
        $promoProducts = [];
        if ($response->successful()) {
            $documents = $response->json()['documents'] ?? [];
            foreach ($documents as $doc) {
                $fields = $doc['fields'] ?? [];
                $isPromo = $fields['is_promo']['booleanValue'] ?? false;
                if ($isPromo) {
                    $name = $fields['name']['stringValue'] ?? 'Unnamed product';
                    $price = $fields['price']['doubleValue'] ?? $fields['price']['integerValue'] ?? 'N/A';
                    $desc = $fields['description']['stringValue'] ?? '';

                    $promoProducts[] = "{$name} (RM{$price}) - {$desc}";
                }
            }
        }
        return $promoProducts;
    }


}
