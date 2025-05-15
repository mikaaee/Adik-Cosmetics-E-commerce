<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function ask(Request $request)
    {
        $question = $request->input('message');
    
        $response = Http::withToken(env('OPENAI_API_KEY'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant for Adik Cosmetics.'],
                    ['role' => 'user', 'content' => $question],
                ],
            ]);
    
        return response()->json([
            'reply' => $response['choices'][0]['message']['content']
        ]);
    }
    

}
