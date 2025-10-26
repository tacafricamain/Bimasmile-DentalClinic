# WhatsApp API Configuration Guide

## Setup Options

You now have both PHP and Node.js options for WhatsApp integration:

### PHP Version (send-whatsapp.php)
- Ready to use with most web hosting
- Multiple API integrations included
- No additional server setup required

### Node.js Version (whatsapp-server.js)
- Modern JavaScript environment
- Better async handling
- Requires Node.js installation

## Quick Start

### For PHP Version:
1. Upload `send-whatsapp.php` to your server
2. Configure API credentials in the file
3. Your form is already configured to use it

### For Node.js Version:
1. Navigate to the api folder:
   ```bash
   cd api
   ```

2. Install dependencies:
   ```bash
   npm install
   ```

3. Configure environment variables:
   ```bash
   # Create .env file
   TWILIO_ACCOUNT_SID=your_twilio_sid
   TWILIO_AUTH_TOKEN=your_twilio_token
   ULTRAMSG_TOKEN=your_ultramsg_token
   PORT=3000
   ```

4. Start the server:
   ```bash
   npm start
   ```

## API Configuration Options

### Option 1: Twilio WhatsApp API (Recommended)
1. Sign up at https://www.twilio.com/
2. Get your Account SID and Auth Token
3. Enable WhatsApp sandbox for testing
4. For production, apply for WhatsApp Business API

**Configuration:**
- Replace `YOUR_TWILIO_ACCOUNT_SID` with your Account SID
- Replace `YOUR_TWILIO_AUTH_TOKEN` with your Auth Token

### Option 2: WhatsApp Business Cloud API (Free)
1. Go to https://developers.facebook.com/
2. Create a Meta App
3. Add WhatsApp product
4. Get your access token and phone number ID

**Configuration:**
- Replace `YOUR_WHATSAPP_ACCESS_TOKEN` with your token
- Replace `YOUR_PHONE_NUMBER_ID` with your phone number ID

### Option 3: Third-Party APIs
Popular options:
- **UltraMsg**: https://ultramsg.com/
- **WhatsApp API**: https://whatsapp-api.com/
- **Chat API**: https://chat-api.com/

## Testing

1. **Test the API endpoint directly:**
   ```bash
   curl -X POST http://localhost:3000/api/send-whatsapp \
     -H "Content-Type: application/json" \
     -d '{
       "to": "1234567890",
       "message": "Test appointment booking",
       "formData": {"name": "Test User"}
     }'
   ```

2. **Test through your booking form:**
   - Fill out the form
   - Submit and check browser console for logs
   - Check server logs for API responses

## Deployment

### For PHP:
- Upload to any PHP hosting (shared hosting works)
- Ensure cURL is enabled
- Set proper file permissions

### For Node.js:
- Deploy to services like:
  - Heroku
  - Vercel
  - DigitalOcean
  - AWS
  - Railway

## Troubleshooting

### Common Issues:
1. **CORS errors**: Ensure CORS is properly configured
2. **API credential errors**: Double-check your tokens
3. **Phone number format**: Use international format (+1234567890)
4. **Rate limits**: Respect API rate limits for your service

### Debug Steps:
1. Check browser console for JavaScript errors
2. Check server logs for API responses
3. Test API endpoints directly
4. Verify phone number formats
5. Check API service status pages

## Production Checklist

- [ ] Configure proper API credentials
- [ ] Test with real phone numbers
- [ ] Set up error logging
- [ ] Configure rate limiting
- [ ] Add input validation
- [ ] Set up monitoring
- [ ] Configure HTTPS
- [ ] Add API key authentication (optional)

## Support

For WhatsApp API issues:
- Twilio: https://support.twilio.com/
- Meta/Facebook: https://developers.facebook.com/support/
- Third-party services: Check their documentation

Your booking form will automatically fall back to the WhatsApp redirect method if the API is unavailable.