<?php

return [
    'api_key' => env('ANTHROPIC_API_KEY'),
    'model' => env('ANTHROPIC_MODEL', 'claude-haiku-4-5-20251001'),

    // Ollama fallback (used when ANTHROPIC_API_KEY is empty or Claude fails)
    'ollama_url' => env('OLLAMA_URL', 'http://localhost:11434'),
    'ollama_model' => env('OLLAMA_MODEL', 'qwen2.5:7b'),
];
