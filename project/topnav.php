<nav class="navbar navbar-expand-lg bg-dark">
    <div class="container-fluid">
        <div class="d-flex justify-content-start">
            <a href="index.php"><img src="images/logo.png" height="50px"></a>
        </div>
        <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav text-center">
                <li class="nav-item">
                    <a class="nav-link text-white" aria-current="page" href="index.php">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Product
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item text-center" href="product_create.php">Create Product</a></li>
                        <li><a class="dropdown-item text-center" href="product_read.php">Product List</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Customer
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item text-center" href="customer_create.php">Create Customer</a></li>
                        <li><a class="dropdown-item text-center" href="customer_read.php">Customer List</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Order
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item text-center" href="order_create.php">Order Form</a></li>
                        <li><a class="dropdown-item text-center" href="order_summary.php">Order Summary</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="contact_us.php">Contact Us</a>
                </li>
                <li class="nav-item d-xxl-none d-xl-none d-lg-none">
                    <a class="nav-link text-white" href="logout.php"><i class="fa-solid fa-right-from-bracket"></i></a>
                </li>
            </ul>
        </div>
        <div class="d-none d-lg-block d-xl-block d-xxl-block">
            <a class="nav-link text-white" href="logout.php"><i class="fa-solid fa-right-from-bracket"></i></a>
        </div>
    </div>
</nav>