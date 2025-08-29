// CRUD System for Products Management
class ProductCRUD {
    constructor() {
        this.products = this.loadProducts();
        this.currentProduct = null;
        this.init();
    }

    // Initialize CRUD system
    init() {
        this.bindEvents();
        this.renderProducts();
        this.setupFormValidation();
    }

    // Load products from localStorage
    loadProducts() {
        const stored = localStorage.getItem('products');
        return stored ? JSON.parse(stored) : [
            {
                id: 1,
                title: 'Sample Product',
                description: 'This is a sample product description',
                price: 29.99,
                category: 'Design',
                features: ['Feature 1', 'Feature 2', 'Feature 3'],
                image: 'img/content/product-1.jpg',
                status: 'active',
                createdAt: new Date().toISOString(),
                views: 1250,
                likes: 89,
                comments: 12
            }
        ];
    }

    // Save products to localStorage
    saveProducts() {
        localStorage.setItem('products', JSON.stringify(this.products));
    }

    // Generate unique ID
    generateId() {
        return Date.now() + Math.random().toString(36).substr(2, 9);
    }

    // Create new product
    createProduct(productData) {
        const newProduct = {
            id: this.generateId(),
            ...productData,
            status: 'active',
            createdAt: new Date().toISOString(),
            views: 0,
            likes: 0,
            comments: 0
        };
        
        this.products.push(newProduct);
        this.saveProducts();
        this.renderProducts();
        return newProduct;
    }

    // Read product by ID
    getProduct(id) {
        return this.products.find(product => product.id == id);
    }

    // Read all products
    getAllProducts() {
        return this.products;
    }

    // Update product
    updateProduct(id, productData) {
        const index = this.products.findIndex(product => product.id == id);
        if (index !== -1) {
            this.products[index] = { ...this.products[index], ...productData };
            this.saveProducts();
            this.renderProducts();
            return this.products[index];
        }
        return null;
    }

    // Delete product
    deleteProduct(id) {
        const index = this.products.findIndex(product => product.id == id);
        if (index !== -1) {
            this.products.splice(index, 1);
            this.saveProducts();
            this.renderProducts();
            return true;
        }
        return false;
    }

    // Render products in dashboard
    renderProducts() {
        const container = document.querySelector('.products-list');
        if (!container) return;

        container.innerHTML = this.products.map(product => `
            <div class="product-item" data-id="${product.id}">
                <div class="product-image">
                    <img src="${product.image || 'img/content/product-placeholder.jpg'}" alt="${product.title}">
                </div>
                <div class="product-info">
                    <h3>${product.title}</h3>
                    <p>${product.description.substring(0, 100)}...</p>
                    <div class="product-meta">
                        <span class="price">$${product.price}</span>
                        <span class="category">${product.category}</span>
                        <span class="status ${product.status}">${product.status}</span>
                    </div>
                    <div class="product-stats">
                        <span>👁️ ${product.views}</span>
                        <span>❤️ ${product.likes}</span>
                        <span>💬 ${product.comments}</span>
                    </div>
                </div>
                <div class="product-actions">
                    <button class="btn-edit" onclick="productCRUD.editProduct(${product.id})">Edit</button>
                    <button class="btn-delete" onclick="productCRUD.deleteProduct(${product.id})">Delete</button>
                </div>
            </div>
        `).join('');
    }

    // Edit product
    editProduct(id) {
        const product = this.getProduct(id);
        if (product) {
            this.currentProduct = product;
            this.populateForm(product);
            window.location.href = 'products-add.html';
        }
    }

    // Populate form with product data
    populateForm(product) {
        const form = document.querySelector('#product-form');
        if (!form) return;

        form.querySelector('[name="title"]').value = product.title || '';
        form.querySelector('[name="description"]').value = product.description || '';
        form.querySelector('[name="price"]').value = product.price || '';
        form.querySelector('[name="category"]').value = product.category || '';
        
        // Populate features
        const featureInputs = form.querySelectorAll('[name^="feature"]');
        if (product.features) {
            product.features.forEach((feature, index) => {
                if (featureInputs[index]) {
                    featureInputs[index].value = feature;
                }
            });
        }
    }

    // Handle form submission
    handleFormSubmit(event) {
        event.preventDefault();
        
        const formData = new FormData(event.target);
        const productData = {
            title: formData.get('title'),
            description: formData.get('description'),
            price: parseFloat(formData.get('price')),
            category: formData.get('category'),
            features: [
                formData.get('feature1'),
                formData.get('feature2'),
                formData.get('feature3'),
                formData.get('feature4')
            ].filter(feature => feature.trim() !== ''),
            image: formData.get('image') || 'img/content/product-placeholder.jpg'
        };

        if (this.currentProduct) {
            // Update existing product
            this.updateProduct(this.currentProduct.id, productData);
            this.showNotification('Product updated successfully!', 'success');
        } else {
            // Create new product
            this.createProduct(productData);
            this.showNotification('Product created successfully!', 'success');
        }

        // Reset form and redirect
        event.target.reset();
        this.currentProduct = null;
        setTimeout(() => {
            window.location.href = 'products-dashboard.html';
        }, 1500);
    }

    // Setup form validation
    setupFormValidation() {
        const form = document.querySelector('#product-form');
        if (!form) return;

        // Real-time validation
        const inputs = form.querySelectorAll('input[required], textarea[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', () => this.clearFieldError(input));
        });

        // Form submission
        form.addEventListener('submit', (e) => this.handleFormSubmit(e));
    }

    // Validate individual field
    validateField(field) {
        const value = field.value.trim();
        const fieldName = field.name;
        
        if (!value) {
            this.showFieldError(field, `${fieldName.charAt(0).toUpperCase() + fieldName.slice(1)} is required`);
            return false;
        }

        if (fieldName === 'title' && value.length > 100) {
            this.showFieldError(field, 'Title must be less than 100 characters');
            return false;
        }

        if (fieldName === 'price' && (isNaN(value) || value <= 0)) {
            this.showFieldError(field, 'Price must be a positive number');
            return false;
        }

        return true;
    }

    // Show field error
    showFieldError(field, message) {
        this.clearFieldError(field);
        field.classList.add('field__input--error');
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field__error';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }

    // Clear field error
    clearFieldError(field) {
        field.classList.remove('field__input--error');
        const errorDiv = field.parentNode.querySelector('.field__error');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    // Show notification
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification--${type}`;
        notification.innerHTML = `
            <div class="notification__content">
                <span>${message}</span>
                <button class="notification__close">&times;</button>
            </div>
        `;

        document.body.appendChild(notification);

        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);

        // Manual close
        notification.querySelector('.notification__close').addEventListener('click', () => {
            notification.remove();
        });
    }

    // Bind events
    bindEvents() {
        // Search functionality
        const searchInput = document.querySelector('.search__input');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                this.searchProducts(e.target.value);
            });
        }

        // Filter functionality
        const filterSelects = document.querySelectorAll('.select');
        filterSelects.forEach(select => {
            select.addEventListener('change', (e) => {
                this.filterProducts(e.target.value, e.target.name);
            });
        });
    }

    // Search products
    searchProducts(query) {
        const filtered = this.products.filter(product => 
            product.title.toLowerCase().includes(query.toLowerCase()) ||
            product.description.toLowerCase().includes(query.toLowerCase()) ||
            product.category.toLowerCase().includes(query.toLowerCase())
        );
        this.renderFilteredProducts(filtered);
    }

    // Filter products
    filterProducts(value, filterType) {
        let filtered = this.products;

        switch (filterType) {
            case 'category':
                filtered = value === 'all' ? this.products : this.products.filter(p => p.category === value);
                break;
            case 'status':
                filtered = value === 'all' ? this.products : this.products.filter(p => p.status === value);
                break;
            case 'price':
                filtered = this.products.sort((a, b) => {
                    if (value === 'low-high') return a.price - b.price;
                    if (value === 'high-low') return b.price - a.price;
                    return 0;
                });
                break;
        }

        this.renderFilteredProducts(filtered);
    }

    // Render filtered products
    renderFilteredProducts(products) {
        const container = document.querySelector('.products-list');
        if (!container) return;

        if (products.length === 0) {
            container.innerHTML = '<div class="no-products">No products found</div>';
            return;
        }

        container.innerHTML = products.map(product => `
            <div class="product-item" data-id="${product.id}">
                <div class="product-image">
                    <img src="${product.image || 'img/content/product-placeholder.jpg'}" alt="${product.title}">
                </div>
                <div class="product-info">
                    <h3>${product.title}</h3>
                    <p>${product.description.substring(0, 100)}...</p>
                    <div class="product-meta">
                        <span class="price">$${product.price}</span>
                        <span class="category">${product.category}</span>
                        <span class="status ${product.status}">${product.status}</span>
                    </div>
                    <div class="product-stats">
                        <span>👁️ ${product.views}</span>
                        <span>❤️ ${product.likes}</span>
                        <span>💬 ${product.comments}</span>
                    </div>
                </div>
                <div class="product-actions">
                    <button class="btn-edit" onclick="productCRUD.editProduct(${product.id})">Edit</button>
                    <button class="btn-delete" onclick="productCRUD.deleteProduct(${product.id})">Delete</button>
                </div>
            </div>
        `).join('');
    }

    // Get statistics
    getStats() {
        const totalProducts = this.products.length;
        const totalViews = this.products.reduce((sum, p) => sum + p.views, 0);
        const totalLikes = this.products.reduce((sum, p) => sum + p.likes, 0);
        const totalRevenue = this.products.reduce((sum, p) => sum + (p.price * p.views * 0.01), 0);

        return {
            totalProducts,
            totalViews,
            totalLikes,
            totalRevenue: totalRevenue.toFixed(2)
        };
    }

    // Update dashboard stats
    updateDashboardStats() {
        const stats = this.getStats();
        
        // Update counters
        const counters = document.querySelectorAll('.overview__counter, .stock__counter');
        counters.forEach(counter => {
            const text = counter.textContent.toLowerCase();
            if (text.includes('products')) counter.textContent = stats.totalProducts;
            if (text.includes('views')) counter.textContent = stats.totalViews.toLocaleString();
            if (text.includes('likes')) counter.textContent = stats.totalLikes.toLocaleString();
            if (text.includes('revenue') || text.includes('earning')) counter.textContent = `$${stats.totalRevenue}`;
        });
    }

    // Save draft
    saveDraft() {
        const form = document.querySelector('#product-form');
        if (!form) return;

        const formData = new FormData(form);
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });
        
        localStorage.setItem('productFormDraft', JSON.stringify(data));
        this.showNotification('Draft saved successfully!', 'success');
        
        // Update last saved time
        const lastSavedElement = document.getElementById('last-saved');
        if (lastSavedElement) {
            const now = new Date();
            lastSavedElement.textContent = now.toLocaleString();
        }
    }
}

// Initialize CRUD system
let productCRUD;

document.addEventListener('DOMContentLoaded', function() {
    productCRUD = new ProductCRUD();
    
    // Update dashboard stats if on dashboard page
    if (window.location.pathname.includes('products-dashboard')) {
        productCRUD.updateDashboardStats();
    }
    
    // Check if we're editing a product
    const urlParams = new URLSearchParams(window.location.search);
    const editId = urlParams.get('edit');
    if (editId) {
        productCRUD.editProduct(editId);
    }
});