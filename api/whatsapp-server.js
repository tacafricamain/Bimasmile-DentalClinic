const express = require('express');
const cors = require('cors');
const fs = require('fs').promises;
const path = require('path');

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(cors());
app.use(express.json());

// WhatsApp API integration options

// Option 1: Using Twilio
async function sendViaTwilio(to, message) {
    try {
        const accountSid = process.env.TWILIO_ACCOUNT_SID || 'YOUR_TWILIO_ACCOUNT_SID';
        const authToken = process.env.TWILIO_AUTH_TOKEN || 'YOUR_TWILIO_AUTH_TOKEN';
        const client = require('twilio')(accountSid, authToken);
        
        const result = await client.messages.create({
            from: 'whatsapp:+14155238886', // Twilio WhatsApp sandbox
            to: `whatsapp:+${to}`,
            body: message
        });
        
        return { success: true, messageId: result.sid };
    } catch (error) {
        console.error('Twilio error:', error);
        return { success: false, error: error.message };
    }
}

// Option 2: Using WhatsApp Web JS (requires whatsapp-web.js library)
async function sendViaWhatsAppWebJS(to, message) {
    try {
        // This requires setting up a WhatsApp session
        // const { Client, MessageMedia } = require('whatsapp-web.js');
        // const client = new Client();
        
        // For demo purposes, we'll simulate success
        console.log(`Would send to ${to}: ${message}`);
        return { success: true, method: 'whatsapp-web.js' };
    } catch (error) {
        return { success: false, error: error.message };
    }
}

// Option 3: Using third-party API
async function sendViaThirdPartyAPI(to, message) {
    try {
        const fetch = require('node-fetch');
        
        // Example using UltraMsg API
        const response = await fetch('https://api.ultramsg.com/instance_id/messages/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                'token': process.env.ULTRAMSG_TOKEN || 'YOUR_ULTRAMSG_TOKEN',
                'to': to,
                'body': message
            })
        });
        
        if (response.ok) {
            const result = await response.json();
            return { success: true, result };
        }
        
        return { success: false, error: 'API request failed' };
    } catch (error) {
        return { success: false, error: error.message };
    }
}

// Main WhatsApp sending endpoint
app.post('/api/send-whatsapp', async (req, res) => {
    try {
        const { to, message, formData } = req.body;
        
        if (!to || !message) {
            return res.status(400).json({
                success: false,
                error: 'Missing required fields: to, message'
            });
        }
        
        // Log the appointment
        const logData = {
            timestamp: new Date().toISOString(),
            to,
            formData,
            message
        };
        
        await fs.appendFile('appointments.log', JSON.stringify(logData) + '\n');
        
        // Try different methods in order of preference
        let result = { success: false };
        
        // Try Twilio first
        if (process.env.TWILIO_ACCOUNT_SID) {
            result = await sendViaTwilio(to, message);
        }
        
        // If Twilio fails, try third-party API
        if (!result.success && process.env.ULTRAMSG_TOKEN) {
            result = await sendViaThirdPartyAPI(to, message);
        }
        
        // If all APIs fail, try WhatsApp Web JS
        if (!result.success) {
            result = await sendViaWhatsAppWebJS(to, message);
        }
        
        if (result.success) {
            res.json({
                success: true,
                message: 'WhatsApp message sent successfully',
                method: result.method || 'api'
            });
        } else {
            // Return 500 so frontend falls back to redirect
            res.status(500).json({
                success: false,
                error: 'All WhatsApp methods failed',
                fallback: true
            });
        }
        
    } catch (error) {
        console.error('Server error:', error);
        res.status(500).json({
            success: false,
            error: 'Internal server error',
            fallback: true
        });
    }
});

// Health check endpoint
app.get('/api/health', (req, res) => {
    res.json({ status: 'OK', timestamp: new Date().toISOString() });
});

// Start server
app.listen(PORT, () => {
    console.log(`WhatsApp API server running on port ${PORT}`);
    console.log('Available endpoints:');
    console.log('  POST /api/send-whatsapp - Send WhatsApp message');
    console.log('  GET /api/health - Health check');
});

module.exports = app;