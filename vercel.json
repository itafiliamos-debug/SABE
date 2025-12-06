{
  "version": 2,
  "routes": [
    {
      "src": "/(.*\\.php)",
      "dest": "/api/index.php"
    },
    {
      "src": "/(.*)",
      "dest": "/api/index.php"
    }
  ],
  "functions": {
    "api/index.php": {
      "runtime": "@vercel/php@1.0.0",
      "maxDuration": 30
    }
  },
  "headers": [
    {
      "source": "/(.*)",
      "headers": [
        { "key": "Access-Control-Allow-Origin", "value": "*" },
        { "key": "Access-Control-Allow-Methods", "value": "GET, POST, OPTIONS" },
        { "key": "Access-Control-Allow-Headers", "value": "Content-Type" }
      ]
    }
  ]
}