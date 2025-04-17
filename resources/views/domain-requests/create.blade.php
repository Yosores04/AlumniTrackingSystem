<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Request a Domain</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/domain-request.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1 class="title">Request a Domain</h1>
                <a href="{{ url('/') }}" class="back-link">
                    <i class="bi bi-arrow-left"></i>
                    Back to home
                </a>
            </div>

            <p class="form-hint mb-6">
                Fill out this form to request your own customized domain. Once approved, you'll receive an email with your login credentials.
            </p>
            
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i>
                    <div>
                        <strong>Success!</strong>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="bi bi-exclamation-circle"></i>
                    <div>
                        <strong>Error!</strong>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('domain-requests.store') }}">
                @csrf

                <div class="form-container">
                    <div class="form-group">
                        <label for="admin_name" class="form-label">Full Name</label>
                        <input type="text" name="admin_name" id="admin_name" 
                               class="form-input @error('admin_name') error @enderror" 
                               value="{{ old('admin_name') }}" required>
                        @error('admin_name')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="admin_email" class="form-label">Email Address</label>
                        <input type="email" name="admin_email" id="admin_email" 
                               class="form-input @error('admin_email') error @enderror" 
                               value="{{ old('admin_email') }}" required>
                        <p class="form-hint">This email will be used for communication and login credentials.</p>
                        @error('admin_email')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="domain_prefix" class="form-label">Desired Domain Prefix</label>
                        <div class="input-group">
                            <input type="text" name="domain_prefix" id="domain_prefix" 
                                   class="form-input @error('domain_prefix') error @enderror" 
                                   value="{{ old('domain_prefix') }}" 
                                   required 
                                   placeholder="myschool"
                                   style="border-radius: 8px 0 0 8px;">
                            <span class="domain-suffix">.localhost</span>
                        </div>
                        <p class="form-hint">Only letters, numbers, and hyphens are allowed.</p>
                        @error('domain_prefix')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="button-container">
                        <button type="submit" class="btn-primary">
                            <i class="bi bi-check2-circle me-2"></i>Submit Request
                        </button>
                    </div>
                </div>
            </form>

            <div class="info-box">
                <h3 class="info-box-title">What happens next?</h3>
                <ul class="info-list">
                    <li>Your request will be reviewed by our administrators</li>
                    <li>Once approved, we'll create your custom domain</li>
                    <li>You'll receive an email with login credentials</li>
                    <li>Your temporary password will expire in 24 hours, so make sure to log in and change it promptly</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
