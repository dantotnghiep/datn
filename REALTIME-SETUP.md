# Real-Time Updates Setup

This application has real-time update capabilities for order statuses and inventory receipts. Follow these steps to enable real-time updates:

## 1. Pusher Account Setup
1. Create a free account at [Pusher](https://pusher.com)
2. Create a new Channels app
3. Note your app ID, key, secret, and cluster

## 2. Environment Configuration
Update your `.env` file with the following values:
```
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=your_cluster (e.g., ap1, us2, eu, etc.)
```

## 3. Cache Configuration
After updating your `.env` file, clear your configuration cache:
```bash
php artisan config:clear
php artisan cache:clear
```

## 4. Compile Assets
If you've made changes to JavaScript files or are setting up for the first time:
```bash
npm install
npm run dev
```

## 5. Test Real-Time Updates
1. Open two browser windows - one for viewing orders/inventory and another for updating them
2. Update a status in one window and observe the real-time update in the other window
3. You should see the status change without refreshing the page
4. Browser notifications will also appear if you've granted notification permissions

## Troubleshooting
If real-time updates are not working:

### Check Configuration
1. Make sure your Pusher credentials are correct in the `.env` file
2. Ensure `App\Providers\BroadcastServiceProvider::class` is uncommented in `config/app.php`
3. Verify `channels.php` has the proper channel definitions

### Check Browser Console
1. Open your browser's developer tools (F12 or right-click and select "Inspect")
2. Go to the Console tab to see detailed error messages
3. Look for "Pusher" related messages indicating connection issues

### Common Issues:
- **Invalid Credentials**: If you see "Invalid auth credentials" in the console, double-check your Pusher app key and cluster.
- **No Events Received**: Make sure the event is being dispatched properly from the controller.
- **DOM Element Not Found**: Check that the HTML structure hasn't changed and the JavaScript selectors still work.
- **CORS Issues**: If you see CORS errors, make sure your Pusher app settings allow your domain.

### Still Having Issues?
If you're still experiencing problems:
1. Try a different browser to rule out browser-specific issues
2. Temporarily enable more detailed Pusher logging by setting `Pusher.logToConsole = true;` in the JavaScript
3. Check your server's PHP error logs for any issues with event dispatching

## Features
- Real-time order status updates
- Real-time inventory receipt status updates
- Browser notifications for status changes
- Visual highlighting of updated rows
- Console debugging information 