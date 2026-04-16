<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Deactivated - Find My Roommate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        .shake-animation {
            animation: shake 0.8s ease-in-out;
        }
        @keyframes pulse-red {
            0%, 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            50% { box-shadow: 0 0 0 20px rgba(239, 68, 68, 0); }
        }
        .pulse-red {
            animation: pulse-red 2s infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Card -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-red-500 to-red-600 p-8 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full pulse-red mb-4">
                    <i class="fas fa-ban text-4xl text-red-500"></i>
                </div>
                <h1 class="text-2xl font-bold text-white mb-2">Account Deactivated</h1>
                <p class="text-red-100 text-sm">Access Denied</p>
            </div>
            
            <!-- Content -->
            <div class="p-8 text-center">
                <div class="mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-red-50 rounded-full mb-4 shake-animation">
                        <i class="fas fa-lock text-2xl text-red-500"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">Your account has been locked</h2>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        @if(session('deactivation_message'))
                            {{ session('deactivation_message') }}
                        @else
                            Your account was deactivated by an administrator.
                        @endif
                    </p>
                </div>
                
                <div class="bg-red-50 border border-red-100 rounded-xl p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-red-500 mt-0.5 mr-3"></i>
                        <div class="text-left">
                            <p class="text-sm text-red-700 font-medium mb-1">What does this mean?</p>
                            <ul class="text-sm text-red-600 space-y-1">
                                <li>• You cannot access any features</li>
                                <li>• All your data is preserved</li>
                                <li>• Contact support for assistance</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <a href="mailto:support@findmyroommate.com" 
                       class="inline-flex items-center justify-center w-full px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-medium rounded-xl transition-colors duration-200">
                        <i class="fas fa-envelope mr-2"></i>
                        Contact Support
                    </a>
                    
                    <a href="{{ route('home') }}" 
                       class="inline-flex items-center justify-center w-full px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-colors duration-200">
                        <i class="fas fa-home mr-2"></i>
                        Back to Home
                    </a>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 text-center">
                    <i class="fas fa-shield-alt mr-1"></i>
                    This action was performed for security and compliance reasons.
                </p>
            </div>
        </div>
        
        <!-- Timestamp -->
        <p class="text-center text-xs text-gray-400 mt-6">
            Detected: {{ now()->format('F j, Y g:i A') }}
        </p>
    </div>
</body>
</html>
