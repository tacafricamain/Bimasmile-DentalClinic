# 🆓 FREE WhatsApp API Setup Guide

## 🎯 Recommended: WhatsApp Business Cloud API (100% FREE)

**Why This is the Best Option:**
- ✅ **Completely FREE** for up to 1,000 conversations/month
- ✅ **Official WhatsApp API** from Meta/Facebook
- ✅ **No credit card required** for setup
- ✅ **No expiration** - permanently free tier
- ✅ **Production ready** - used by millions of businesses

---

## 📋 Step-by-Step Setup (10 minutes)

### Step 1: Create Meta Developer Account
1. Go to [developers.facebook.com](https://developers.facebook.com/)
2. Click "Get Started" 
3. Log in with your Facebook account (or create one)
4. Verify your account if needed

### Step 2: Create a New App
1. Click "Create App"
2. Select "Business" as app type
3. Fill in app details:
   - **App Name**: "Bimasmile Dental Booking"
   - **Contact Email**: Your email
4. Click "Create App"

### Step 3: Add WhatsApp Product
1. In your app dashboard, find "WhatsApp"
2. Click "Set up" under WhatsApp
3. This will add WhatsApp to your app

### Step 4: Get Your Credentials
1. In WhatsApp settings, you'll see:
   - **Access Token**: `EAAxxxxxx...` (copy this)
   - **Phone Number ID**: `123456789` (copy this)
2. You'll also get a **test phone number** for free

### Step 5: Configure Your PHP File
Open `api/send-whatsapp-free.php` and replace:

```php
// Replace these with your actual values from Meta
$whatsappAccessToken = 'EAAxxxxxx...'; // Your access token
$phoneNumberId = '123456789'; // Your phone number ID
```

### Step 6: Update Your Website
Update the API endpoint in your `booking.html`:

```javascript
// Change this line in your booking.html
const response = await fetch('/api/send-whatsapp-free', {
```

---

## 🧪 Testing Your Setup

### Test 1: Send a Test Message
1. In Meta's WhatsApp dashboard, there's a "Send Message" section
2. Add your phone number (with country code)
3. Send a test message to verify it works

### Test 2: Test Your Website
1. Fill out your booking form
2. Submit it
3. Check if you receive the WhatsApp message

---

## 🔄 Alternative FREE Options

### Option 2: UltraMsg (1,000 FREE messages/month)
1. Go to [ultramsg.com](https://ultramsg.com/)
2. Register for free account
3. Get your instance ID and token
4. Update the PHP file with your credentials

### Option 3: WhatsApp Web.js (Unlimited FREE)
- Uses your personal WhatsApp
- Requires server to stay online
- Best for developers comfortable with Node.js

---

## 💰 Cost Comparison

| Service | Free Tier | After Free Tier |
|---------|-----------|-----------------|
| **WhatsApp Cloud API** | 1,000 messages/month | $0.005/message |
| **UltraMsg** | 1,000 messages/month | $10/month |
| **Twilio** | $15 trial credit | $0.005/message |
| **WhatsApp Web.js** | Unlimited FREE | Always FREE |

---

## 🚀 Production Deployment

### For Web Hosting (Shared Hosting)
1. Upload `send-whatsapp-free.php` to your website
2. Make sure the `api` folder is accessible
3. Test the endpoint

### For Your Own Server
1. Ensure PHP and cURL are installed
2. Set proper file permissions
3. Configure HTTPS (recommended)

---

## 🛠️ Troubleshooting

### Common Issues:
1. **"Invalid access token"**: Double-check your token from Meta
2. **"Phone number not found"**: Verify your phone number ID
3. **CORS errors**: Make sure CORS headers are set
4. **Messages not received**: Check if recipient's number is verified

### Debug Steps:
1. Check browser console for errors
2. Check server logs for API responses
3. Test API endpoint directly with curl
4. Verify phone number format (+234xxxxxxxxx)

---

## 📞 Next Steps

1. **Set up WhatsApp Business Cloud API** (recommended)
2. **Test with your phone number**
3. **Update your website to use the new endpoint**
4. **Monitor your usage** in Meta's dashboard

**Need Help?**
- Meta WhatsApp API Docs: [developers.facebook.com/docs/whatsapp](https://developers.facebook.com/docs/whatsapp)
- UltraMsg Docs: [docs.ultramsg.com](https://docs.ultramsg.com)

Your booking system will have **professional WhatsApp integration** completely **FREE**! 🎉