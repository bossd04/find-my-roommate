<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Find My Roommate - Perfect Roommate Matching</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .hero-bg {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1556911220-bff31c812dba?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }
        
        .btn-outline {
            border: 2px solid #4F46E5;
            transition: all 0.3s ease;
        }
        
        .btn-outline:hover {
            background-color: #4F46E5;
            color: white;
        }
    </style>
</head>
<body class="font-['Poppins'] bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <span class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            Find My Roommate
                        </span>
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center space-x-8">
                    <a href="#features" class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Features</a>
                    <a href="#how-it-works" class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium">How It Works</a>
                    <a href="#testimonials" class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Testimonials</a>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="ml-4 btn-primary text-white px-6 py-2 rounded-full font-medium">Sign Up</a>
                            @endif
                        @endauth
                    @endif
                </div>
                <!-- Mobile menu button -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <!-- Icon when menu is closed -->
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <!-- Icon when menu is open -->
                        <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state -->
        <div class="sm:hidden hidden" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1">
                <a href="#features" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Features</a>
                <a href="#how-it-works" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">How It Works</a>
                <a href="#testimonials" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Testimonials</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="block px-3 py-2 text-base font-medium text-indigo-600 hover:bg-indigo-50">Sign up</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-bg text-white py-20 md:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">Find Your Perfect Roommate</h1>
                <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">Connect with compatible roommates based on lifestyle, habits, and preferences. Your ideal living situation starts here.</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-primary text-white px-8 py-3 rounded-full text-lg font-semibold inline-block text-center">Get Started</a>
                    @endif
                    <a href="#how-it-works" class="btn-outline text-indigo-600 bg-white px-8 py-3 rounded-full text-lg font-semibold inline-block text-center">Learn More</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">Why Choose Find My Roommate?</h2>
                <p class="mt-4 text-xl text-gray-600">We make finding the perfect roommate easy and stress-free.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card bg-white p-8 rounded-xl shadow-md">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Smart Matching</h3>
                    <p class="text-gray-600">Our advanced algorithm matches you with compatible roommates based on your lifestyle, habits, and preferences.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="feature-card bg-white p-8 rounded-xl shadow-md">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Verified Profiles</h3>
                    <p class="text-gray-600">All users go through a verification process to ensure a safe and trustworthy community.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="feature-card bg-white p-8 rounded-xl shadow-md">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Easy Scheduling</h3>
                    <p class="text-gray-600">Schedule viewings and meet potential roommates with our built-in messaging and calendar system.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div id="how-it-works" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">How It Works</h2>
                <p class="mt-4 text-xl text-gray-600">Find your perfect roommate in just a few simple steps</p>
            </div>
            
            <div class="relative">
                <!-- Timeline -->
                <div class="hidden md:block absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-gradient-to-b from-indigo-500 to-purple-500"></div>
                
                <!-- Step 1 -->
                <div class="flex flex-col md:flex-row items-center mb-16">
                    <div class="md:w-1/2 md:pr-16 mb-8 md:mb-0 text-center md:text-right">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-100 text-indigo-600 text-2xl font-bold mb-4 md:mx-auto md:ml-auto">1</div>
                        <h3 class="text-2xl font-semibold mb-3">Create Your Profile</h3>
                        <p class="text-gray-600">Sign up and create a detailed profile with your preferences, lifestyle, and what you're looking for in a roommate.</p>
                    </div>
                    <div class="md:w-1/2 md:pl-16">
                        <div class="bg-white p-6 rounded-xl shadow-md">
                            <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" alt="Create Profile" class="rounded-lg w-full h-64 object-cover">
                        </div>
                    </div>
                </div>
                
                <!-- Step 2 -->
                <div class="flex flex-col md:flex-row items-center mb-16">
                    <div class="md:w-1/2 md:order-last md:pl-16 mb-8 md:mb-0 text-center md:text-left">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-purple-100 text-purple-600 text-2xl font-bold mb-4 md:mx-auto md:mr-auto">2</div>
                        <h3 class="text-2xl font-semibold mb-3">Find Matches</h3>
                        <p class="text-gray-600">Our smart algorithm suggests compatible roommates based on your profile and preferences.</p>
                    </div>
                    <div class="md:w-1/2 md:pr-16 md:order-first">
                        <div class="bg-white p-6 rounded-xl shadow-md">
                            <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" alt="Find Matches" class="rounded-lg w-full h-64 object-cover">
                        </div>
                    </div>
                </div>
                
                <!-- Step 3 -->
                <div class="flex flex-col md:flex-row items-center">
                    <div class="md:w-1/2 md:pr-16 mb-8 md:mb-0 text-center md:text-right">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 text-2xl font-bold mb-4 md:mx-auto md:ml-auto">3</div>
                        <h3 class="text-2xl font-semibold mb-3">Connect & Move In</h3>
                        <p class="text-gray-600">Message your matches, schedule meetups, and find your perfect living situation.</p>
                    </div>
                    <div class="md:w-1/2 md:pl-16">
                        <div class="bg-white p-6 rounded-xl shadow-md">
                            <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" alt="Connect & Move In" class="rounded-lg w-full h-64 object-cover">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div id="testimonials" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">What Our Users Say</h2>
                <p class="mt-4 text-xl text-gray-600">Join thousands of happy roommates who found their perfect match</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-gray-50 p-8 rounded-xl">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400 flex">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 italic mb-6">"I found my perfect roommate within a week! The matching system really understands compatibility."</p>
                    <div class="flex items-center">
                        <img class="h-12 w-12 rounded-full object-cover" src="https://randomuser.me/api/portraits/women/32.jpg" alt="Sarah M.">
                        <div class="ml-4">
                            <p class="font-semibold">Sarah M.</p>
                            <p class="text-sm text-gray-500">BSBA-3rd Year Sudent</p>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="bg-gray-50 p-8 rounded-xl">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400 flex">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 italic mb-6">"Moving to a new city was stressful, but finding a great roommate made all the difference. Highly recommend!"</p>
                    <div class="flex items-center">
                        <img class="h-12 w-12 rounded-full object-cover" src="https://randomuser.me/api/portraits/men/46.jpg" alt="James L.">
                        <div class="ml-4">
                            <p class="font-semibold">James L.</p>
                            <p class="text-sm text-gray-500">BSIT-4th Year Student</p>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 3 -->
                <div class="bg-gray-50 p-8 rounded-xl">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400 flex">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 italic mb-6">"The verification process gave me peace of mind. I knew I was meeting real, vetted people."</p>
                    <div class="flex items-center">
                        <img class="h-12 w-12 rounded-full object-cover" src="https://randomuser.me/api/portraits/women/68.jpg" alt="Priya K.">
                        <div class="ml-4">
                            <p class="font-semibold">Priya K.</p>
                            <p class="text-sm text-gray-500">BSA-2nd Year Student</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-indigo-700">
        <div class="max-w-7xl mx-auto px-4 py-16 sm:px-6 sm:py-24 lg:px-8 lg:flex lg:items-center lg:justify-between">
            <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                <span class="block">Ready to find your perfect roommate?</span>
                <span class="block text-indigo-200">Join thousands of happy users today.</span>
            </h2>
            <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
                <div class="inline-flex rounded-md shadow">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 md:py-4 md:text-lg md:px-10">
                            Get Started
                        </a>
                    @endif
                </div>
                <div class="ml-3 inline-flex rounded-md shadow">
                    <a href="#how-it-works" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 bg-opacity-60 hover:bg-opacity-70 md:py-4 md:text-lg md:px-10">
                        Learn more
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="col-span-2">
                    <h3 class="text-2xl font-bold mb-4">Roommate Match</h3>
                    <p class="text-gray-400">Finding your perfect roommate shouldn't be hard. We make it easy, safe, and fun!</p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-300 tracking-wider uppercase">Company</h4>
                    <ul class="mt-4 space-y-2">
                        <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-white transition-colors duration-200">About Us</a></li>
                        <li><a href="{{ route('careers') }}" class="text-gray-400 hover:text-white transition-colors duration-200">Careers</a></li>
                        <li><a href="{{ route('press') }}" class="text-gray-400 hover:text-white transition-colors duration-200">Press</a></li>
                        <li><a href="{{ route('blog') }}" class="text-gray-400 hover:text-white transition-colors duration-200">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-300 tracking-wider uppercase">Support</h4>
                    <ul class="mt-4 space-y-2">
                        <li><a href="{{ route('help') }}" class="text-gray-400 hover:text-white transition-colors duration-200">Help Center</a></li>
                        <li><a href="{{ route('safety') }}" class="text-gray-400 hover:text-white transition-colors duration-200">Safety Tips</a></li>
                        <li><a href="{{ route('guidelines') }}" class="text-gray-400 hover:text-white transition-colors duration-200">Community Guidelines</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-white transition-colors duration-200">Contact Us</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-800 pt-8 md:flex md:items-center md:justify-between">
                <div class="flex space-x-6">
                    <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white transition-colors duration-200">
                        <span class="sr-only">Facebook</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white transition-colors duration-200">
                        <span class="sr-only">Instagram</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.415-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.976.045-1.505.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.352-.3.88-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.976.207 1.505.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.352.137.88.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.857-.344.466-.182.8-.399 1.15-.748.35-.35.566-.683.748-1.15.137-.352.3-.88.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.857a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.881-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="https://x.com" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white transition-colors duration-200">
                        <span class="sr-only">Twitter (X)</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M13.6823 10.6218L20.2391 3H18.6854L12.9921 9.61788L8.44486 3H3.2002L10.0765 13.0074L3.2002 21H4.75404L10.7663 14.0115L15.5685 21H20.8131L13.6819 10.6218H13.6823ZM11.5541 13.0956L10.8574 12.0991L5.31391 4.16971H7.70053L12.1742 10.5689L12.8709 11.5655L18.6861 19.8835H16.2995L11.5541 13.096V13.0956Z"/>
                        </svg>
                    </a>
                </div>
                <p class="mt-8 text-base text-gray-400 md:mt-0 md:order-1">
                    &copy; {{ date('Y') }} Universidad de Dagupan | Capstone Project Find My Roommate. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.querySelector('button[aria-controls="mobile-menu"]');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuButton.addEventListener('click', function() {
            const expanded = this.getAttribute('aria-expanded') === 'true' || false;
            this.setAttribute('aria-expanded', !expanded);
            mobileMenu.classList.toggle('hidden');
            
            // Toggle between menu and close icons
            const menuIcon = this.querySelector('svg:not(.hidden)');
            const closeIcon = this.querySelector('svg.hidden');
            menuIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                    
                    // Close mobile menu if open
                    if (!mobileMenu.classList.contains('hidden')) {
                        mobileMenuButton.click();
                    }
                }
            });
        });
    </script>
    </body>
</html>
        