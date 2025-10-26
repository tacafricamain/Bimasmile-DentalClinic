# 🔑 How to Get Permanent WhatsApp Access Token

## 🎯 Current Issue
Your token expires every 1-2 hours because it's a **temporary user access token**. For production, you need a **permanent system user token**.

## 🚀 Solution 1: System User Token (Permanent)

### Step 1: Create System User
1. Go to [business.facebook.com](https://business.facebook.com)
2. Go to **Business Settings** 
3. Click **Users** → **System Users**
4. Click **Add** to create a system user
5. Name it "WhatsApp API Bot" 
6. Set role to **Admin**

### Step 2: Generate Permanent Token
1. Click on your system user
2. Click **Add Assets**
3. Select **Apps** → Choose your WhatsApp app
4. Set permissions to **Manage**
5. Click **Generate New Token**
6. Select scopes:
   - `whatsapp_business_messaging`
   - `whatsapp_business_management`
7. **This token never expires!** ✨

### Step 3: Update Your Code
Replace your token in `send-whatsapp-free.php`:


---

## 🔄 Solution 2: Auto-Refresh Temporary Tokens

If you can't create a system user, use this auto-refresh method:

### Step 1: Get App Secret
1. In your Meta app dashboard
2. Go to **App Settings** → **Basic**
3. Copy your **App Secret**

### Step 2: Add Auto-Refresh Function
```php
function refreshAccessToken($appId, $appSecret, $shortToken) {
    $url = "https://graph.facebook.com/oauth/access_token";
    $params = [
        'grant_type' => 'fb_exchange_token',
        'client_id' => $appId,
        'client_secret' => $appSecret,
        'fb_exchange_token' => $shortToken
    ];
    
    $response = file_get_contents($url . '?' . http_build_query($params));
    $data = json_decode($response, true);
    
    return $data['access_token']; // Valid for 60 days
}
```

---

## 🏆 Solution 3: Use UltraMsg (Alternative FREE API)

Skip Meta tokens entirely and use UltraMsg:

### Setup UltraMsg:
1. Go to [ultramsg.com](https://ultramsg.com)
2. Register free account
3. Get **1,000 FREE messages/month**
4. Get your instance ID and token
5. Update your PHP file:

```php
$ultraMsgToken = 'your_ultramsg_token';
$ultraMsgInstance = 'instance12345';
```

**Advantages:**
- ✅ No token expiration issues
- ✅ 1,000 free messages/month  
- ✅ Easier setup
- ✅ Better for small businesses

---

## 🎯 Recommendation

**For Your Dental Clinic:**

1. **Start with UltraMsg** (easiest, no token issues)
2. **Or get a System User Token** (official Meta solution)
3. **Avoid temporary tokens** (they're only for testing)

## ⚡ Quick Fix Right Now

**Option A: Use UltraMsg**
1. Sign up at ultramsg.com
2. Get free token and instance
3. Update your PHP file
4. Test immediately

**Option B: Get System User Token**
1. Go to business.facebook.com
2. Create system user
3. Generate permanent token
4. Replace in your PHP file

Both options give you **permanent, never-expiring** access!

Which option would you prefer to try first?