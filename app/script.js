function updateTime() {
    const now = new Date();
    const options = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
    document.getElementById('time').textContent = now.toLocaleTimeString('en-US', options);
}

// Update time immediately and set an interval to update every second
updateTime();
setInterval(updateTime, 1000);
