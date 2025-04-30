/**
 * Real-time update functionality for admin dashboard
 */

// Register notification functionality
export function initializeNotifications() {
    // Request notification permission if not already granted
    if ("Notification" in window && Notification.permission !== "granted" && Notification.permission !== "denied") {
        Notification.requestPermission();
    }
}

// Show browser notifications
export function showNotification(title, message) {
    // Check if the browser supports notifications
    if (!("Notification" in window)) {
        console.log("This browser does not support desktop notification");
        return;
    }
    
    // Check if permission is already granted
    if (Notification.permission === "granted") {
        createNotification(title, message);
    }
    // Otherwise, ask for permission
    else if (Notification.permission !== "denied") {
        Notification.requestPermission().then(function (permission) {
            if (permission === "granted") {
                createNotification(title, message);
            }
        });
    }
    
    // Display toast notification if your UI has a toast component
    if (typeof toast !== 'undefined') {
        toast.success(message);
    }
}

// Create and display the notification
function createNotification(title, message) {
    const notification = new Notification(title, {
        body: message,
        icon: '/favicon.ico',
    });
    
    notification.onclick = function() {
        window.focus();
        notification.close();
    };
    
    // Auto close after 5 seconds
    setTimeout(() => {
        notification.close();
    }, 5000);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeNotifications();
}); 