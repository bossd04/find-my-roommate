# OpenRouter Free AI API Setup

## Get ChatGPT-like responses for FREE!

### Step 1: Get Free API Key
1. Go to https://openrouter.ai/
2. Sign up with Google/GitHub (completely free)
3. Click "Keys" in left sidebar
4. Click "Create Key"
5. Copy your API key (starts with `sk-or-...`)

### Step 2: Add to .env file
Open `.env` file and add:
```
OPENROUTER_API_KEY=sk-or-v1-your-key-here
```

### Step 3: Clear config cache
```bash
php artisan config:clear
```

### Step 4: Test it!
Go to http://127.0.0.1:8000/chatbot and ask any random question!

## Free Models Available:
- `meta-llama/llama-3.1-8b-instruct:free` - General questions
- `google/gemma-2-9b-it:free` - Fast responses
- `mistralai/mistral-7b-instruct:free` - Good reasoning

## What can you ask?
✅ "What happened in 2024?" - Current events
✅ "Explain quantum physics simply" - Complex topics
✅ "Paano gumawa ng resume?" - Tagalog questions
✅ "Compare iPhone vs Samsung" - Comparisons
✅ "What's the weather like?" - General questions

All for FREE with rate limits (20 requests/minute on free tier)
