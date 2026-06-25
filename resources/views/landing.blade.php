<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome — Video Media Registry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #09090b; 
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #e4e4e7;
        }

        .custom-navbar {
            background: #09090b;
            border-bottom: 1px solid #27272a;
        }

        .hero-section {
            background: #09090b;
            color: #fff;
            padding: 5rem 2rem 4rem 2rem;
            text-align: center;
        }

        .hero-section h1 {
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 1rem;
        }

        .hero-section p {
            opacity: 0.8;
            font-size: 1.15rem;
            max-width: 600px;
            margin: 0 auto;
        }

        /* ── Gateway Action Cards ── */
        .custom-card {
            border: 1px solid #27272a;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.4);
            background: #18181b; 
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .custom-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6);
            border-color: #3f3f46;
        }

        .icon-box {
            width: 44px;
            height: 44px;
            background: #27272a;
            color: #fff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 1.25rem;
        }

        /* ── Horizontal Features Section ── */
        .feature-box {
            background: #18181b;
            border: 1px solid #27272a;
            border-radius: 12px;
            padding: 1.5rem;
            height: 100%;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: #27272a;
            color: #fff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 1rem;
        }

        footer {
            margin-top: auto;
            background: #09090b;
            border-top: 1px solid #27272a;
            padding: 1.5rem 0;
            text-align: center;
            font-size: 13px;
            color: #a1a1aa;
        }
    </style>
</head>

<body>

    <nav class="navbar custom-navbar navbar-dark navbar-expand px-4">
        <div class="container">
            <a class="navbar-brand fw-bold font-monospace" href="{{ url('/') }}">📼 VIDEOSTORE</a>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container">
            <h1>📼 Video Store Management Hub</h1>
            <p>Welcome to the central media registry gateway. Authenticate your terminal credentials or request portal privileges below.</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="custom-card card-body p-4 h-100 d-flex flex-column">
                            <div class="icon-box"><i class="bi bi-box-arrow-in-right"></i></div>
                            <h5 class="fw-bold text-white">Sign In to Workspace</h5>
                            <p class="text-white-50 mb-4 small">Access your personalized dashboard node, view authorized archival media indices, and track current system asset logs.</p>
                            <a href="{{ route('login') }}" class="btn btn-light px-4 mt-auto w-100 text-dark fw-bold">Sign In</a>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="custom-card card-body p-4 h-100 d-flex flex-column">
                            <div class="icon-box"><i class="bi bi-person-plus-fill"></i></div>
                            <h5 class="fw-bold text-white">Register Account</h5>
                            <p class="text-white-50 mb-4 small">Submit a new personnel profile registration request to receive access credentials and system authorization parameters.</p>
                            <a href="{{ route('register') }}" class="btn btn-outline-light px-4 mt-auto w-100 fw-bold">Create Account</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container"><hr style="border-color: #27272a; margin: 2rem auto; max-width: 85%;"></div>

    <div class="container my-4 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="feature-box">
                            <div class="feature-icon"><i class="bi bi-folder2-open"></i></div>
                            <h6 class="fw-bold text-white">Centralized Archive</h6>
                            <p class="text-white small mb-0">Unified indexing systems monitoring cataloged media assets, stock distribution metrics, and asset properties data records.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-box">
                            <div class="feature-icon"><i class="bi bi-shield-lock-fill"></i></div>
                            <h6 class="fw-bold text-white">Terminal Security</h6>
                            <p class="text-white small mb-0">Accounts are isolated based on specific permission clearances, ensuring confidential repository parameters remain safe.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-box">
                            <div class="feature-icon"><i class="bi bi-terminal-fill"></i></div>
                            <h6 class="fw-bold text-white">Real-Time Integrity</h6>
                            <p class="text-white small mb-0">Every modification, system sync state, and inventory modification action logs directly to an isolated system journal stream.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            &copy; {{ date('Y') }} Video Store Management Systems Corporation. All rights reserved.
        </div>
    </footer>

</body>
</html>