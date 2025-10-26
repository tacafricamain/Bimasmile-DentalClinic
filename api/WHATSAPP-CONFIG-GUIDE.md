# 🔧 WhatsApp Business Cloud API Configuration

## Your Credentials from Meta Developers Dashboard

After setting up your WhatsApp Business app at developers.facebook.com, you'll get these values:

### 1. Access Token
```
Your Access Token: EAAxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

### 2. Phone Number ID  
```
Your Phone Number ID: 123456789012345
```

### 3. Test Phone Number (provided by Meta)
```
Test Number: +1 555-0199 (or similar)
```

---

## 📝 Configuration Steps

### Step 1: Edit your API file
Open `api/send-whatsapp-free.php` and replace:

```php
// Line ~17-18: Replace these with your actual values
$whatsappAccessToken = 'EAAxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; // Your actual token
$phoneNumberId = '123456789012345'; // Your actual phone number ID
```

### Step 2: Add recipient number to your form
Make sure this phone number in your `booking.html` is correct:
```javascript
// Line ~342: This should be your business WhatsApp number
to: '2349031741426', // Your business WhatsApp number
```

---

## 🧪 Testing Your Setup

### Test 1: Send Test Message from Meta Dashboard
1. In your WhatsApp Business API setup page
2. Add your phone number to "To" field (with country code)
3. Type a test message
4. Click "Send Message"
5. Check if you receive it on your phone

### Test 2: Test Your Booking Form
1. Go to your booking page
2. Fill out the form completely
3. Click "Send Appointment via WhatsApp"
4. Check if you receive the booking message

---

## ✅ Success Indicators

**✅ API Working**: You'll see in browser console: "✅ WhatsApp sent via: WhatsApp Cloud API (FREE)"

**❌ API Failed**: You'll see: "🔄 API not configured, using redirect fallback"

---

## 📞 Important Notes

- **Free Tier**: 1,000 messages per month (perfect for a dental clinic)
- **Test Numbers**: Meta provides test numbers for development
- **Production**: For your own number, you need to verify your business
- **Rate Limits**: No rate limits on free tier for normal usage

---

## 🚨 Common Issues & Solutions

### Issue: "Invalid access token"
**Solution**: Double-check your access token from Meta dashboard

### Issue: "Phone number not found"  
**Solution**: Verify your Phone Number ID is correct

### Issue: Messages not received
**Solution**: 
1. Check if recipient number is added to test numbers in Meta dashboard
2. Verify phone number format (+234xxxxxxxxx)
3. Check if WhatsApp is installed on recipient phone

### Issue: CORS errors in browser
**Solution**: Make sure your API file has proper CORS headers (already included)

---

## 🎯 Next Steps After Configuration

1. Replace the placeholder values with your actual credentials
2. Upload the updated file to your web server
3. Test the booking form
4. Monitor your usage in Meta dashboard
5. Apply for production access when ready

Your dental clinic will have professional WhatsApp booking completely FREE! 🦷✨