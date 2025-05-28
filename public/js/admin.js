function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('minimized');
}

function toggleNotifBox() {
    const box = document.getElementById('notif-box');
    box.style.opacity = 0;
    box.style.display = (box.style.display === 'none') ? 'block' : 'none';
    setTimeout(() => box.style.opacity = 1, 50);

    fetchNewOrders(); // Fetch hanya bila user buka
}

async function fetchNewOrders() {
    try {
        const response = await fetch('/admin/orders/new/count'); // pastikan route ini betul
        const data = await response.json();
        updateNotifUI(data);
    } catch (error) {
        console.error('Notif fetch error:', error);
    }
}

async function fetchNewOrderCount() {
    try {
        const response = await fetch('/admin/orders/new/count');
        const data = await response.json();

        const badge = document.getElementById('order-badge');
        if (data.count > 0) {
            badge.innerText = data.count;
            badge.style.display = 'inline-block';
        } else {
            badge.innerText = '';
            badge.style.display = 'none';
        }
    } catch (error) {
        console.error('Fetch count error:', error);
    }
}

function updateNotifUI(data) {
    const badge = document.getElementById('order-badge');
    const notifList = document.getElementById('notif-list');

    if (data.count > 0) {
        badge.innerText = data.count;
        badge.style.display = 'inline-block';
    } else {
        badge.innerText = '';
        badge.style.display = 'none';
    }

    notifList.innerHTML = '';

    const recentOrders = data.orders.slice(0, 5);
    recentOrders.forEach(order => {
        const li = document.createElement('li');
        li.style.padding = '5px 0';
        li.innerHTML = `🛒 <strong>${order.customer_name || 'Unknown'}</strong><br>
                        <small>Order ID: ${order.id}</small>`;
        notifList.appendChild(li);
    });

    const viewAll = document.createElement('li');
    viewAll.style.textAlign = 'center';
    viewAll.style.marginTop = '10px';
    viewAll.innerHTML = `
        <a href="/admin/orders" 
           style="display: inline-block; padding: 6px 12px; background-color: #00bcd4; color: white;
                  border-radius: 6px; text-decoration: none; font-weight: bold; transition: background-color 0.3s;">
           View All
        </a>`;
    notifList.appendChild(viewAll);
}

function toggleProfileMenu() {
    const dropdown = document.getElementById('profileDropdown');
    dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
}

document.addEventListener('click', function (event) {
    const notifBox = document.getElementById('notif-box');
    const bellBtn = document.querySelector('.icon-btn');
    const profile = document.querySelector('.user-profile');
    const dropdown = document.getElementById('profileDropdown');

    if (!notifBox.contains(event.target) && !bellBtn.contains(event.target)) {
        notifBox.style.display = 'none';
    }

    if (!profile.contains(event.target)) {
        dropdown.style.display = 'none';
    }
});

// ✅ AUTO FETCH ON LOAD (if needed)
// window.onload = fetchNewOrderCount;
// Or disable for speed:
// setInterval(fetchNewOrderCount, 10000);
