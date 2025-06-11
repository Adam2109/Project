document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.wishlist-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            const isActive = this.classList.contains('active');
            const url = isActive ? 'remove_from_wishlist.php' : 'add_to_wishlist.php';
            fetch(url, {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'product_id=' + encodeURIComponent(productId)
            })
            .then(response => response.json())
            .then(data => {
                showWishlistAlert(data.message, data.success);
                if (data.success) {
                    if (isActive) {
                        this.classList.remove('active');
                        
                        const card = this.closest('.product');
                        if (card && window.location.pathname.includes('wishlist.php')) {
                            card.remove();
                            
                            if (document.querySelectorAll('.product').length === 0) {
                                location.reload();
                            }
                        }
                    } else {
                        this.classList.add('active');
                    }
                }
            });
        });
    });
});

function showWishlistAlert(message, success) {
    let alert = document.createElement('div');
    alert.className = 'alert ' + (success ? 'alert-success' : 'alert-danger');
    alert.style.position = 'fixed';
    alert.style.top = '20px';
    alert.style.right = '20px';
    alert.style.zIndex = 9999;
    alert.innerText = message;
    document.body.appendChild(alert);
    setTimeout(() => alert.remove(), 2000);
}