@extends('base.base')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow-lg z-3" role="alert" style="min-width: 300px;">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@section('content')

    <style>
        /* Wishlist icon */
        .wishlist-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
        }

        .wishlist-checkbox {
            display: none;
        }

        .wishlist-btn {
            font-size: 20px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
        }

        .wishlist-btn i {
            color: white;
            -webkit-text-stroke: 1.5px #181b1e;
            text-stroke: 1.5px #181b1e;
        }

        .wishlist-checkbox:checked + .wishlist-btn i {
            color: red;
            -webkit-text-stroke: 0;
            text-stroke: 0;
        }

        /* Product card styling */
        .product-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .card-img-top {
            max-height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #ddd;
        }

        .card-body {
            display: flex;
            flex-direction: column;
            padding: 15px;
            text-align: center;
            flex-grow: 1;
        }

        .card-body .card-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card-body .card-text {
            font-size: 14px;
            color: #777;
            margin-bottom: 10px;
        }

        .card-body .card-price {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .card-body .card-discount-price {
            font-size: 16px;
            color: red;
        }

        /* Layout for Products Grid - FIXED */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        /* Filter Section Styles */
        .filter-container {
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            margin-right: 20px;
            position: sticky;
            top: 0;
        }

        .filter-container label {
            font-weight: bold;
            margin-right: 10px;
            font-size: 14px;
            color: #333;
        }

        .filter-container input[type="checkbox"],
        .filter-container input[type="radio"] {
            margin-right: 8px;
        }

        .filter-btn {
            padding: 12px 18px;
            background-color: black;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 20px;
            width: 100%;
        }

        .filter-btn:hover {
            background-color: black;
        }

        .filter-option {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .filter-section {
            margin-bottom: 20px;
        }

        .collapse-header {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
            cursor: pointer;
            color: black;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .collapse-header:hover {
            color: black;
        }

        .filter-option {
            margin-left: 20px;
        }

        .filter-section .collapse {
            margin-left: 20px;
        }
        
        /* Make it responsive while keeping 3 per row */
        @media (max-width: 991px) {
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 767px) {
            .product-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="container">
        <h2 class="mb-4">Products</h2>

        <div class="row">
            <!-- Filter Section -->
            <div class="col-md-3">
                <div class="filter-container">
                    <form method="GET" action="{{ route('products.filter') }}">

                        <!-- Gender Filter -->
                        <div class="filter-section">
                            <div class="collapse-header" data-bs-toggle="collapse" data-bs-target="#gender-filter" aria-expanded="false" aria-controls="gender-filter">
                                Jenis Kelamin
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div id="gender-filter" class="collapse">
                                <div class="filter-option">
                                    <input type="checkbox" name="gender[]" value="men" @if(in_array('men', request('gender', []))) checked @endif> <label>Men</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" name="gender[]" value="women" @if(in_array('women', request('gender', []))) checked @endif> <label>Women</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" name="gender[]" value="unisex" @if(in_array('unisex', request('gender', []))) checked @endif> <label>Unisex</label>
                                </div>
                            </div>
                        </div>

                        <!-- Color Filter -->
                        <div class="filter-section">
                            <div class="collapse-header" data-bs-toggle="collapse" data-bs-target="#color-filter" aria-expanded="false" aria-controls="color-filter">
                                Warna
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div id="color-filter" class="collapse">
                                <div class="filter-option">
                                    <input type="checkbox" name="color[]" value="black" @if(in_array('black', request('color', []))) checked @endif> <label>Black</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" name="color[]" value="white" @if(in_array('white', request('color', []))) checked @endif> <label>White</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" name="color[]" value="red" @if(in_array('red', request('color', []))) checked @endif> <label>Red</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" name="color[]" value="blue" @if(in_array('blue', request('color', []))) checked @endif> <label>Blue</label>
                                </div>
                            </div>
                        </div>

                        <!-- Size Filter -->
                        <div class="filter-section">
                            <div class="collapse-header" data-bs-toggle="collapse" data-bs-target="#size-filter" aria-expanded="false" aria-controls="size-filter">
                                Ukuran
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div id="size-filter" class="collapse">
                                <div class="filter-option">
                                    <input type="checkbox" name="size[]" value="35" @if(in_array('35', request('size', []))) checked @endif> <label>35</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" name="size[]" value="36" @if(in_array('36', request('size', []))) checked @endif> <label>36</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" name="size[]" value="37" @if(in_array('37', request('size', []))) checked @endif> <label>37</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" name="size[]" value="38" @if(in_array('38', request('size', []))) checked @endif> <label>38</label>
                                </div>
                            </div>
                        </div>

                        <!-- Brand Filter -->
                        <div class="filter-section">
                            <div class="collapse-header" data-bs-toggle="collapse" data-bs-target="#brand-filter" aria-expanded="false" aria-controls="brand-filter">
                                Brand
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div id="brand-filter" class="collapse">
                                <div class="filter-option">
                                    <input type="checkbox" name="brand[]" value="nike" @if(in_array('nike', request('brand', []))) checked @endif> <label>Nike</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" name="brand[]" value="adidas" @if(in_array('adidas', request('brand', []))) checked @endif> <label>Adidas</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" name="brand[]" value="new_balance" @if(in_array('new_balance', request('brand', []))) checked @endif> <label>New Balance</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" name="brand[]" value="puma" @if(in_array('puma', request('brand', []))) checked @endif> <label>Puma</label>
                                </div>
                            </div>
                        </div>

                        <!-- Sorting Options -->
                        <div class="filter-section">
                            <div class="collapse-header" data-bs-toggle="collapse" data-bs-target="#sort-filter" aria-expanded="false" aria-controls="sort-filter">
                                Sort By
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div id="sort-filter" class="collapse">
                                <div class="filter-option">
                                    <input type="radio" name="sort" value="low_high" @if(request('sort') == 'low_high') checked @endif> <label>Price: Low to High</label>
                                </div>
                                <div class="filter-option">
                                    <input type="radio" name="sort" value="high_low" @if(request('sort') == 'high_low') checked @endif> <label>Price: High to Low</label>
                                </div>
                            </div>
                        </div>

                        <div class="filter-btn-container">
                            <button type="submit" class="filter-btn">Apply Filters</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Product Grid Section -->
            <div class="col-md-9">
                <div class="product-grid">
                    @foreach ($products as $product)
                        <div class="product-card">
                            <!-- Wishlist Icon -->
                            <div class="wishlist-icon">
                                <input type="checkbox" id="wishlist-{{ $product['product_id'] }}" class="wishlist-checkbox">
                                <label for="wishlist-{{ $product['product_id'] }}" class="wishlist-btn">
                                    <i class="fas fa-heart"></i>
                                </label>
                            </div>

                            <div class="card-body">
                                <a href="{{ route('product.details', ['id' => $product['product_id']]) }}">
                                    @if ($product['image'])
                                        <img src="{{ $product['image'] }}" class="card-img-top product-image" alt="{{ $product['product_name'] }}">
                                    @else
                                        <p>No image available</p>
                                    @endif
                                </a>

                                <h5 class="card-title">{{ $product['product_name'] }}</h5>
                                <p class="card-text"><i>{{ $product['brand'] }} - {{ $product['gender'] }}</i></p>

                                <p class="card-price">
                                    @if ($product['discount'] > 0)
                                        <span style="text-decoration: line-through; color: gray;">Rp {{ number_format($product['price'], 0, ',', '.') }}</span> 
                                        <span class="card-discount-price">Rp {{ number_format($product['price'] * (1 - $product['discount'] / 100), 0, ',', '.') }}</span>
                                    @else
                                        Rp {{ number_format($product['price'], 0, ',', '.') }}
                                    @endif
                                </p>
                                <p class="card-text">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($product['rating_avg'])) 
                                            <i class="fas fa-star text-warning"></i> <!-- Full Star -->
                                        @elseif ($i - 0.5 <= $product['rating_avg'] && $product['rating_avg'] - floor($product['rating_avg']) >= 0.25) 
                                            <i class="fas fa-star-half-alt text-warning"></i> <!-- Half Star -->
                                        @else
                                            <i class="fas fa-star text-muted"></i> <!-- Empty Star -->
                                        @endif
                                    @endfor
                                    ({{ $product['total_reviews'] }} reviews)
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wishlistCheckboxes = document.querySelectorAll('.wishlist-checkbox');
            
            wishlistCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const productId = this.id.replace('wishlist-', '');
                    console.log('Product ID:', productId, 'Added to wishlist:', this.checked);
                });
            });
        });
    </script>
@endsection