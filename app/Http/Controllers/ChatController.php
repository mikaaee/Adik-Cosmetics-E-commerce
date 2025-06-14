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

        // Define allowed keywords
        $allowed_keywords = ['product', 'cosmetic', 'pay', 'checkout', 'account', 'register', 'promotion', 'discount', 'order', 'address', 'category', 'contact','problem'];

        // Keyword check (case-insensitive)
        $is_relevant = collect($allowed_keywords)->contains(function ($keyword) use ($message) {
            return stripos($message, $keyword) !== false;
        });

        if (!$is_relevant) {
            return response()->json([
                'reply' => '🤖 I’m AdikBot – here to help with anything about Adik Cosmetics 🛍️. Please ask about our products, promotions, or how to use this website.'
            ]);
        }

        // Fetch promotional products from Firestore
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
                    'content' => "You are AdikBot, the official chatbot of Adik Cosmetics. Respond only with information about the site such as products, categories, promotions, payment, checkout, or registration.

Here are important website instructions you must always follow:

- To register, users must click the 'Sign Up' button.
- After registration, users can browse promotional products by clicking on 'Shop Now'.
- Promotional products are listed below. Only these are eligible for discounts.
- After confirming the order in the product cart, you can proceed to checkout, then if your address same as registered just clicked the checkbox but if you want to put another address just unticked the checkbox and filled another address manually then click button 'proceed to payment'
- 5 Payment Methods that provided are : 1)ToyyibPay, 2) Bank Transfer, 3) Cash on Delivery (COD) and 4) Input Credit Card details
- any problem occur you can contact this number +601151233262 via WhatsApp (not a support team actually)

Here are the current promotional product names:

{$promoListText}

Only mention the product names when asked about current promotions. Do not add product descriptions unless the user specifically asks for details."

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

                if (!isset($fields['is_promo']) || !($fields['is_promo']['booleanValue'] ?? false)) {
                    continue;
                }

                $name = $fields['name']['stringValue'] ?? 'Unnamed product';
                $promoProducts[] = $name;
            }
        }

        return $promoProducts;
    }
}
