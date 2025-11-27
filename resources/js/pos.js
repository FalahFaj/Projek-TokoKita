import * as bootstrap from 'bootstrap';

class POSSystem {
    constructor() {
        this.cart = [];
        this.init();
    }

    init() {
        this.initializeSelect2();
        this.bindEvents();
        this.loadCartFromStorage();
    }

    initializeSelect2() {
        $("#categoryFilter").select2({
            placeholder: "Pilih kategori...",
            allowClear: true,
        });
    }

    bindEvents() {
        // Search functionality
        $("#productSearch").on("input", () => this.filterProducts());

        // Category filter
        $("#categoryFilter").on("change", () => this.filterProducts());

        // Cash amount input
        $("#cashAmount").on("input", () => {
            this.calculateChange();
            this.updateCheckoutButton();
        });

        // Payment method change
        $("#paymentMethod").on("change", () => {
            if ($("#paymentMethod").val() === "cash") {
                $("#cashInputSection").show();
            } else {
                $("#cashInputSection").hide();
                $("#changeSection").hide();
            }
            this.updateCheckoutButton();
        });

        // Enter key for product search
        $("#productSearch").on("keypress", (e) => {
            if (e.which === 13) {
                e.preventDefault();
                this.addFirstVisibleProduct();
            }
        });

        // Keyboard shortcuts
        $(document).on("keydown", (e) => this.handleKeyboardShortcuts(e));
    }

    filterProducts() {
        const searchTerm = ($("#productSearch").val() || "").toLowerCase();
        const categoryId = $("#categoryFilter").val();

        $(".product-item").each(function () {
            const productElement = $(this);
            const matchesSearch =
                !searchTerm ||
                String(productElement.data("name")).indexOf(searchTerm) > -1 ||
                String(productElement.data("sku") || "").indexOf(searchTerm) >
                    -1 ||
                String(productElement.data("barcode") || "").indexOf(
                    searchTerm
                ) > -1;

            const matchesCategory =
                !categoryId || productElement.data("category") == categoryId;

            if (matchesSearch && matchesCategory) {
                productElement.show();
            } else {
                productElement.hide();
            }
        });
    }

    clearFilters() {
        $("#productSearch").val("");
        $("#categoryFilter").val("").trigger("change");
        $(".product-item").show();
    }

    addToCart(productId) {
        const product = window.posProducts.find((p) => p.id === productId);

        if (!product || product.stock <= 0) {
            this.showAlert("error", "Produk tidak tersedia atau stok habis");
            return;
        }

        const existingItem = this.cart.find((item) => item.id === productId);

        if (existingItem) {
            if (existingItem.quantity < product.stock) {
                existingItem.quantity++;
                this.showAlert(
                    "success",
                    `${product.name} ditambahkan ke keranjang`
                );
            } else {
                this.showAlert(
                    "error",
                    `Stok ${product.name} tidak mencukupi! Stok tersedia: ${product.stock}`
                );
                return;
            }
        } else {
            this.cart.push({
                id: product.id,
                name: product.name,
                price: product.selling_price,
                stock: product.stock,
                quantity: 1,
                unit: product.unit,
                image: product.image,
            });
            this.showAlert(
                "success",
                `${product.name} ditambahkan ke keranjang`
            );
        }

        this.updateCartDisplay();
        this.saveCartToStorage();

        // Auto-focus cash input if it's the first item
        if (this.cart.length === 1 && $("#paymentMethod").val() === 'cash') {
            $("#cashAmount").focus();
        }
    }

    addFirstVisibleProduct() {
        const firstProduct = $(".product-item:visible").first();
        if (firstProduct.length) {
            const productId = firstProduct
                .find(".product-card")
                .attr("onclick")
                ?.match(/\d+/)?.[0];
            if (productId) {
                this.addToCart(parseInt(productId));
            }
        }
    }

    updateCartDisplay() {
        const cartItems = $("#cartItems");
        const cartCount = $("#cartCount");
        const subtotalElement = $("#subtotal");
        const totalElement = $("#total");
        const checkoutBtn = $("#checkoutBtn");

        if (this.cart.length === 0) {
            cartItems.html(`
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                    <p>Keranjang belanja kosong</p>
                    <small class="text-muted">Klik produk untuk menambahkannya ke keranjang</small>
                </div>
            `);
            subtotalElement.text("Rp 0");
            totalElement.text("Rp 0");
            cartCount.text("0");
            checkoutBtn.prop("disabled", true);
            return;
        }

        let html = "";
        let subtotal = 0;

        this.cart.forEach((item) => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;

            html += `
                <div class="cart-item">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="grow">
                            <h6 class="mb-1" style="font-size: 0.9rem;">${
                                item.name
                            }</h6>
                            <p class="mb-1 text-success fw-bold">Rp ${this.formatNumber(
                                item.price
                            )}</p>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="pos.removeFromCart(${
                            item.id
                        })">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="quantity-controls">
                            <button class="quantity-btn" onclick="pos.updateQuantity(${
                                item.id
                            }, -1)">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" class="quantity-input" value="${
                                item.quantity
                            }"
                                   onchange="pos.setQuantity(${
                                       item.id
                                   }, this.value)" min="1" max="${item.stock}">
                            <button class="quantity-btn" onclick="pos.updateQuantity(${
                                item.id
                            }, 1)">
                                <i class="fas fa-plus"></i>
                            </button>
                            <span class="ms-2 text-muted small">${
                                item.unit
                            }</span>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-success">Rp ${this.formatNumber(
                                itemTotal
                            )}</div>
                            <small class="text-muted">${
                                item.quantity
                            } x Rp ${this.formatNumber(item.price)}</small>
                        </div>
                    </div>
                </div>
            `;
        });

        cartItems.html(html);
        subtotalElement.text(`Rp ${this.formatNumber(subtotal)}`);
        totalElement.text(`Rp ${this.formatNumber(subtotal)}`);
        cartCount.text(this.cart.length);

        this.calculateChange();
        this.updateCheckoutButton();
    }

    updateQuantity(productId, change) {
        const item = this.cart.find((item) => item.id === productId);
        if (item) {
            const newQuantity = item.quantity + change;
            if (newQuantity >= 1 && newQuantity <= item.stock) {
                item.quantity = newQuantity;
                this.updateCartDisplay();
                this.saveCartToStorage();
            } else if (newQuantity > item.stock) {
                this.showAlert(
                    "error",
                    `Stok tidak mencukupi! Stok tersedia: ${item.stock}`
                );
            }
        }
    }

    setQuantity(productId, quantity) {
        const item = this.cart.find((item) => item.id === productId);
        if (item) {
            const newQuantity = parseInt(quantity);
            if (newQuantity >= 1 && newQuantity <= item.stock) {
                item.quantity = newQuantity;
                this.updateCartDisplay();
                this.saveCartToStorage();
            } else if (newQuantity > item.stock) {
                this.showAlert(
                    "error",
                    `Stok tidak mencukupi! Stok tersedia: ${item.stock}`
                );
                this.updateCartDisplay();
            } else {
                this.updateCartDisplay();
            }
        }
    }

    removeFromCart(productId) {
        const item = this.cart.find((item) => item.id === productId);
        if (item) {
            this.cart = this.cart.filter((item) => item.id !== productId);
            this.showAlert("info", `${item.name} dihapus dari keranjang`);
            this.updateCartDisplay();
            this.saveCartToStorage();
        }
    }

    clearCart(showConfirmation = true) {
    if (this.cart.length === 0) {
        if (showConfirmation) {
            this.showAlert('info', 'Keranjang sudah kosong');
        }
        return;
    }

    if (showConfirmation) {
        // Hitung total dan tampilkan konfirmasi
        const total = this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const itemCount = this.cart.reduce((sum, item) => sum + item.quantity, 0);

        const confirmationMessage =
            `Apakah Anda yakin ingin mengosongkan keranjang?\n\n` +
            `Detail Keranjang:\n` +
            `- ${this.cart.length} item berbeda\n` +
            `- ${itemCount} total barang\n` +
            `- Total: Rp ${this.formatNumber(total)}\n\n` +
            `Semua item akan dihapus dari keranjang.`;

        if (!confirm(confirmationMessage)) {
            return;
        }
    }

    // Simpan informasi keranjang untuk feedback
    const cartInfo = {
        itemCount: this.cart.length,
        totalItems: this.cart.reduce((sum, item) => sum + item.quantity, 0),
        totalAmount: this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0)
    };

    // Kosongkan keranjang
    this.cart = [];
    this.updateCartDisplay();
    this.saveCartToStorage();

    // Reset form pembayaran
    this.resetPaymentForm();

    if (showConfirmation) {
        this.showAlert('info',
            `Keranjang dikosongkan: ${cartInfo.itemCount} item, ${cartInfo.totalItems} barang, Total Rp ${this.formatNumber(cartInfo.totalAmount)}`
        );
    }
}

    calculateChange() {
        const total = this.cart.reduce(
            (sum, item) => sum + item.price * item.quantity,
            0
        );
        const cashAmount = parseFloat($("#cashAmount").val()) || 0;
        const change = cashAmount - total;

        if (change >= 0 && total > 0) {
            $("#changeAmount").text(`Rp ${this.formatNumber(change)}`);
            $("#changeSection").show();
        } else {
            $("#changeSection").hide();
        }
    }

    updateCheckoutButton() {
        const total = this.cart.reduce(
            (sum, item) => sum + item.price * item.quantity,
            0
        );
        const paymentMethod = $("#paymentMethod").val();
        const cashAmount = parseFloat($("#cashAmount").val()) || 0;

        let isEnabled = total > 0;

        if (paymentMethod === "cash") {
            isEnabled = isEnabled && cashAmount >= total;
        }

        $("#checkoutBtn").prop("disabled", !isEnabled);
    }

    async processPayment() {
        // Validasi keranjang tidak kosong
        if (this.cart.length === 0) {
            this.showAlert("error", "Keranjang belanja kosong!");
            return;
        }

        // Validasi stok untuk semua item di keranjang
        for (const item of this.cart) {
            const product = window.posProducts.find((p) => p.id === item.id);
            if (!product) {
                this.showAlert("error", `Produk ${item.name} tidak ditemukan!`);
                return;
            }
            if (product.stock < item.quantity) {
                this.showAlert(
                    "error",
                    `Stok ${item.name} tidak mencukupi! Stok tersedia: ${product.stock}, diminta: ${item.quantity}`
                );
                return;
            }
        }

        const total = this.cart.reduce(
            (sum, item) => sum + item.price * item.quantity,
            0
        );
        const paymentMethod = $("#paymentMethod").val();
        const cashAmount = parseFloat($("#cashAmount").val()) || 0;
        const customerName = $("#customerName").val() || "Pelanggan Umum";

        // Validasi pembayaran tunai
        if (paymentMethod === "cash" && cashAmount < total) {
            this.showAlert(
                "error",
                `Jumlah bayar kurang! Total: Rp ${this.formatNumber(
                    total
                )}, Bayar: Rp ${this.formatNumber(cashAmount)}`
            );
            return;
        }

        // Validasi jumlah bayar untuk non-tunai
        if (paymentMethod !== "cash" && cashAmount > 0) {
            this.showAlert(
                "warning",
                "Untuk pembayaran non-tunai, jumlah bayar tidak perlu diisi."
            );
            return;
        }

        // Konfirmasi transaksi
        if (
            !confirm(
                `Konfirmasi Transaksi:\nTotal: Rp ${this.formatNumber(
                    total
                )}\nMetode: ${this.getPaymentMethodName(
                    paymentMethod
                )}\nLanjutkan?`
            )
        ) {
            return;
        }

        // Prepare data untuk API
        const saleData = {
            items: this.cart,
            total_amount: total,
            payment_method: paymentMethod,
            cash_amount: paymentMethod === "cash" ? cashAmount : total,
            customer_name: customerName,
            discount_amount: 0, // Bisa ditambahkan fitur diskon nanti
            tax_amount: 0, // Bisa ditambahkan fitur pajak nanti
        };

        // Tampilkan loading
        this.showLoading(true);

        try {
            const response = await $.ajax({ // NOSONAR
                url: window.posRoutes.store,
                method: "POST",
                data: {
                    _token: window.csrfToken,
                    ...saleData,
                },
                timeout: 30000, // 30 detik timeout
            });

            if (response.success) {
                // Transaksi berhasil
                this.showAlert("success", "✅ " + response.message);

                // Reset form dan keranjang
                this.clearCartAfterPayment();

                // Tampilkan detail transaksi
                this.showTransactionSuccess(response.transaction);

                // Buka struk di tab baru
                if (response.receipt_url) {
                    setTimeout(() => {
                        window.open(response.receipt_url, "_blank");
                    }, 1000);
                }

                // Refresh data produk (optional)
                setTimeout(() => {
                    this.refreshProductData();
                }, 2000);
            } else {
                this.showAlert("error", "❌ " + response.message);
            }
        } catch (error) {
            console.error("Payment error:", error);
            this.handlePaymentError(error);
        } finally {
            this.showLoading(false);
        }
    }

    formatNumber(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    showAlert(type, message) {
        const alertClass =
            type === "error"
                ? "alert-danger"
                : type === "success"
                ? "alert-success"
                : "alert-info";

        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        // Gunakan metode close dari Bootstrap untuk menghindari konflik
        const existingAlert = bootstrap.Alert.getInstance($('.alert-dismissible')[0]);
        if (existingAlert) existingAlert.close();

        $(".card-header").after(alertHtml);
    }

    handleKeyboardShortcuts(e) {
        // Ctrl + F to focus search
        if (e.ctrlKey && e.key === "f") {
            e.preventDefault();
            $("#productSearch").focus();
        }

        // F2 to clear cart
        if (e.key === "F2") {
            e.preventDefault();
            this.clearCart();
        }

        // F9 to checkout
        if (e.key === "F9") {
            e.preventDefault();
            this.processPayment();
        }
    }

    saveCartToStorage() {
        localStorage.setItem("pos_cart", JSON.stringify(this.cart));
    }

    loadCartFromStorage() {
        const savedCart = localStorage.getItem("pos_cart");
        if (savedCart) {
            this.cart = JSON.parse(savedCart);
            this.updateCartDisplay();
        }
    }

    getPaymentMethodName(method) {
    const methods = {
        'cash': 'Tunai',
        'transfer': 'Transfer Bank',
        'debit': 'Kartu Debit',
        'credit': 'Kartu Kredit'
    };
    return methods[method] || method;
}
    showTransactionSuccess(transaction) {
    const successHtml = `
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <h5><i class="fas fa-check-circle me-2"></i>Transaksi Berhasil!</h5>
            <hr>
            <div class="row">
                <div class="col-6">
                    <strong>No. Transaksi:</strong><br>
                    ${transaction.transaction_code}
                </div>
                <div class="col-6">
                    <strong>Total:</strong><br>
                    Rp ${this.formatNumber(transaction.total_amount)}
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-6">
                    <strong>Metode:</strong><br>
                    ${this.getPaymentMethodName(transaction.payment_method)}
                </div>
                <div class="col-6">
                    <strong>Kembalian:</strong><br>
                    Rp ${this.formatNumber(transaction.change_amount)}
                </div>
            </div>
            <div class="mt-2">
                <small class="text-muted">Struk akan terbuka otomatis...</small>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    // Gunakan metode close dari Bootstrap untuk menghindari konflik
    const existingAlert = bootstrap.Alert.getInstance($('.alert-dismissible')[0]);
    if (existingAlert) existingAlert.close();

    $('.card-header').after(successHtml);
}

handlePaymentError(error) {
    let message = 'Terjadi kesalahan pada server';

    if (error.status === 422 && error.responseJSON) {
        // Validation errors
        message = error.responseJSON.message || 'Data tidak valid';
    } else if (error.status === 500) {
        message = 'Terjadi kesalahan internal server';
    } else if (error.status === 404) {
        message = 'Endpoint tidak ditemukan';
    } else if (error.status === 0) {
        message = 'Tidak dapat terhubung ke server';
    } else if (error.responseJSON && error.responseJSON.message) {
        message = error.responseJSON.message;
    }

    this.showAlert('error', '❌ ' + message);
}

clearCartAfterPayment() {
    // Kosongkan keranjang tanpa konfirmasi
    this.cart = [];
    this.updateCartDisplay();
    this.saveCartToStorage();
    this.resetPaymentForm();
}

resetPaymentForm() {
    $('#cashAmount').val('');
    $('#customerName').val('Pelanggan Umum');
    $('#changeSection').hide();
    $('#paymentMethod').val('cash').trigger('change');
}

refreshProductData() {
    // Optional: Refresh data produk dari server
    // Ini bisa digunakan untuk update stok real-time
    $.get(window.posRoutes.productsRefresh || '/pos/products')
        .done(function(products) {
            window.posProducts = products;
            // Update UI jika diperlukan
        })
        .fail(function() {
            console.log('Gagal refresh data produk');
        });
}

// Update method showLoading untuk lebih informatif
showLoading(show, message = 'Memproses...') { // NOSONAR
    const checkoutBtn = $('#checkoutBtn');
    const loadingOverlay = $('#loadingOverlay');

    if (show) {
        checkoutBtn.html(`<i class="fas fa-spinner fa-spin me-2"></i>${message}`).prop('disabled', true);
        if (loadingOverlay.length) loadingOverlay.show();
    } else {
        checkoutBtn.html('<i class="fas fa-check me-2"></i>PROSES PEMBAYARAN');
        if (loadingOverlay.length) loadingOverlay.hide();
        this.updateCheckoutButton(); // Update status button
    }
}
}



// Initialize POS system when document is ready
$(document).ready(function () {
    window.pos = new POSSystem();
});
