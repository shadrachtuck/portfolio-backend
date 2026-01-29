# Fix: CORS Error - Production Deployment

## Problem
Production frontend (`https://shadrach-tuck.dev`) cannot access backend GraphQL API (`https://backend.shadrach-tuck.dev/graphql`) due to CORS policy:

```
Access to fetch at 'https://backend.shadrach-tuck.dev/graphql' from origin 'https://shadrach-tuck.dev' 
has been blocked by CORS policy: Response to preflight request doesn't pass access control check: 
No 'Access-Control-Allow-Origin' header is present on the requested resource.
```

## Root Cause
The WordPress backend is not sending CORS (Cross-Origin Resource Sharing) headers to allow cross-origin requests from the frontend domain.

## Solution Applied
Added CORS header handling to `mishap-creative-works.php` plugin:
1. Added `mishap_enable_graphql_cors()` function to handle CORS headers
2. Added `mishap_graphql_send_cors_headers()` function as backup
3. Allows requests from `https://shadrach-tuck.dev` and other frontend domains
4. Handles OPTIONS preflight requests properly

## Deployment Steps

### Option 1: SSH Update (Recommended)
If you have SSH access to production:

```bash
# Navigate to your local plugin directory
cd "/Users/shadrachtuck/Local Sites/portfolio/portfolio-backend/app/public/wp-content/plugins/mishap-creative-works"

# Upload the updated plugin file
scp mishap-creative-works.php shadrach@backend.shadrach-tuck.dev:/var/www/html/portfolio-backend/app/public/wp-content/plugins/mishap-creative-works/
```

### Option 2: SSH Edit
SSH into the server and edit the file:

```bash
ssh shadrach@backend.shadrach-tuck.dev
nano /var/www/html/portfolio-backend/app/public/wp-content/plugins/mishap-creative-works/mishap-creative-works.php
```

Go to the end of the file (line ~885) and add the CORS code after the last `}, 10, 3);`

### Option 3: WordPress Admin Upload
1. Zip the `mishap-creative-works` plugin folder
2. Go to WordPress Admin → Plugins → Add New → Upload Plugin
3. Upload and replace the existing plugin

## After Deployment

### Verify CORS Headers
Test the GraphQL endpoint with curl:

```bash
curl -H "Origin: https://shadrach-tuck.dev" \
     -H "Access-Control-Request-Method: POST" \
     -H "Access-Control-Request-Headers: Content-Type" \
     -X OPTIONS \
     https://backend.shadrach-tuck.dev/graphql \
     -v
```

You should see headers like:
```
Access-Control-Allow-Origin: https://shadrach-tuck.dev
Access-Control-Allow-Methods: GET, POST, OPTIONS
Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept, Origin
```

### Test from Browser
1. Open browser console on `https://shadrach-tuck.dev`
2. The CORS errors should be gone
3. GraphQL requests should work

## Allowed Origins
The plugin allows requests from:
- `https://shadrach-tuck.dev`
- `http://shadrach-tuck.dev`
- `https://www.shadrach-tuck.dev`
- `http://www.shadrach-tuck.dev`
- `http://localhost:5173` (Vite dev server)
- `http://localhost:3000` (Alternative dev port)
- `http://portfolio-backend.local` (Local development)

## Files Changed
- `app/public/wp-content/plugins/mishap-creative-works/mishap-creative-works.php`
  - Added CORS header handling functions
  - Added hooks to set headers early in request lifecycle

## Notes
- CORS headers are set early using `init` and `send_headers` hooks
- OPTIONS preflight requests are handled and return 200 status
- Headers are only set for GraphQL endpoint requests
- Security: Only allowed origins receive CORS headers
