<?php
require_once 'session_config.php';
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickMed - Emergency Ambulance Services</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .animate-fade-in { animation: fadeIn 1s ease-in; }
        .animate-slide-up { animation: slideUp 0.8s ease-out; }
        .animate-slide-left { animation: slideLeft 0.8s ease-out; }
        .animate-slide-right { animation: slideRight 0.8s ease-out; }
        .animate-bounce-slow { animation: bounceSlow 2s infinite; }
        .animate-pulse-slow { animation: pulseSlow 3s infinite; }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        @keyframes slideLeft { from { transform: translateX(-50px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes slideRight { from { transform: translateX(50px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes bounceSlow { 0%, 20%, 50%, 80%, 100% { transform: translateY(0); } 40% { transform: translateY(-10px); } 60% { transform: translateY(-5px); } }
        @keyframes pulseSlow { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }

        .gradient-bg { background: linear-gradient(135deg,hsl(14, 72.20%, 50.60%) 0%, #B91C1C 100%); }
        .hero-gradient { background: linear-gradient(135deg, rgba(220, 38, 38, 0.9) 0%, rgba(185, 28, 28, 0.9) 100%); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }

        .scroll-reveal { opacity: 0; transform: translateY(30px); transition: all 0.6s ease; }
        .scroll-reveal.revealed { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="fixed w-full top-0 z-50 bg-white/95 backdrop-blur-sm shadow-sm">
        <nav class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-red-600 rounded-full flex items-center justify-center animate-pulse-slow">
                        <i class="fas fa-ambulance text-white text-lg"></i>
                    </div>
                    <span class="text-2xl font-bold text-red-600">QuickMed</span>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-gray-700 hover:text-red-600 transition-colors">Home</a>
                    <a href="#services" class="text-gray-700 hover:text-red-600 transition-colors">Services</a>
                    <a href="#about" class="text-gray-700 hover:text-red-600 transition-colors">About</a>
                    <a href="#contact" class="text-gray-700 hover:text-red-600 transition-colors">Contact</a>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="login.php" class="text-gray-700 hover:text-red-600 transition-colors">Login</a>
                    <a href="signup.php" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors">Sign Up</a>
                </div>

                <!-- Mobile menu button -->
                <button class="md:hidden text-gray-700" id="mobile-menu-btn">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <!-- Mobile menu -->
            <div class="md:hidden hidden mt-4 pb-4" id="mobile-menu">
                <div class="flex flex-col space-y-4">
                    <a href="#home" class="text-gray-700 hover:text-red-600 transition-colors">Home</a>
                    <a href="#services" class="text-gray-700 hover:text-red-600 transition-colors">Services</a>
                    <a href="#about" class="text-gray-700 hover:text-red-600 transition-colors">About</a>
                    <a href="#contact" class="text-gray-700 hover:text-red-600 transition-colors">Contact</a>
                    <a href="login.php" class="text-gray-700 hover:text-red-600 transition-colors">Login</a>
                    <a href="signup.php" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors text-center">Sign Up</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section id="home" class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-red-600/20 to-red-800/20"></div>
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="absolute inset-0" style="background-image: url('https://images.unsplash.com/photo-1559757148-5c350d0d3c56?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80'); background-size: cover; background-position: center; opacity: 0.3;"></div>

        <div class="relative z-10 container mx-auto px-4 text-center">
            <div class="animate-fade-in">
                <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 leading-tight">
                    Emergency Ambulance Services
                    <span class="block text-red-300">at Your Fingertips</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-200 mb-8 max-w-3xl mx-auto">
                    24/7 Fast, Reliable, and Professional Emergency Medical Transport
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="signup.php" class="bg-red-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-red-700 transition-colors animate-bounce-slow">
                        <i class="fas fa-phone-alt mr-2"></i>Book Now
                    </a>
                    <a href="#services" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-red-600 transition-colors">
                        <i class="fas fa-info-circle mr-2"></i>Learn More
                    </a>
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="absolute bottom-8 left-8 bg-red-600 text-white p-4 rounded-lg shadow-lg animate-pulse-slow">
            <div class="text-center">
                <i class="fas fa-phone-alt text-2xl mb-2"></i>
                <p class="font-semibold">Emergency</p>
                <p class="text-lg">+254 700 123 456</p>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 scroll-reveal">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Our Services</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Comprehensive emergency and non-emergency medical transport services
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white p-8 rounded-xl shadow-lg card-hover scroll-reveal">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-bolt text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Emergency Transport</h3>
                    <p class="text-gray-600 text-center">Immediate response for critical emergencies with trained medical personnel</p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-lg card-hover scroll-reveal">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-calendar-alt text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Non-Emergency Transport</h3>
                    <p class="text-gray-600 text-center">Scheduled medical appointments and routine patient transfers</p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-lg card-hover scroll-reveal">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-hospital text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Inter-Hospital Transfer</h3>
                    <p class="text-gray-600 text-center">Safe patient transfers between medical facilities</p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-lg card-hover scroll-reveal">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-heartbeat text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Specialized Care</h3>
                    <p class="text-gray-600 text-center">ICU, Neonatal, and Cardiac care transport with specialized equipment</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 scroll-reveal">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">How It Works</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Simple steps to get emergency medical transport when you need it most
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center scroll-reveal">
                    <div class="w-20 h-20 bg-red-600 rounded-full flex items-center justify-center mb-6 mx-auto text-white text-2xl font-bold">1</div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Book Online</h3>
                    <p class="text-gray-600">Easy online booking system available 24/7</p>
                </div>

                <div class="text-center scroll-reveal">
                    <div class="w-20 h-20 bg-red-600 rounded-full flex items-center justify-center mb-6 mx-auto text-white text-2xl font-bold">2</div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Provider Assigned</h3>
                    <p class="text-gray-600">Nearest available ambulance assigned automatically</p>
                </div>

                <div class="text-center scroll-reveal">
                    <div class="w-20 h-20 bg-red-600 rounded-full flex items-center justify-center mb-6 mx-auto text-white text-2xl font-bold">3</div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Track & Arrive</h3>
                    <p class="text-gray-600">Real-time tracking and estimated arrival time</p>
                </div>

                <div class="text-center scroll-reveal">
                    <div class="w-20 h-20 bg-red-600 rounded-full flex items-center justify-center mb-6 mx-auto text-white text-2xl font-bold">4</div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Safe Transport</h3>
                    <p class="text-gray-600">Professional medical transport to destination</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 scroll-reveal">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Why Choose QuickMed</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Professional, reliable, and compassionate emergency medical services
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="flex items-start space-x-4 scroll-reveal">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-clock text-red-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">24/7 Availability</h3>
                        <p class="text-gray-600">Round-the-clock emergency response service</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4 scroll-reveal">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-map-marker-alt text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">GPS Tracking</h3>
                        <p class="text-gray-600">Real-time ambulance location tracking</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4 scroll-reveal">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user-md text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Professional Staff</h3>
                        <p class="text-gray-600">Trained medical personnel on every call</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4 scroll-reveal">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-tools text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Modern Equipment</h3>
                        <p class="text-gray-600">State-of-the-art medical equipment</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4 scroll-reveal">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-shield-alt text-yellow-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Insurance Accepted</h3>
                        <p class="text-gray-600">Multiple insurance coverage options</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4 scroll-reveal">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-dollar-sign text-indigo-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Transparent Pricing</h3>
                        <p class="text-gray-600">No hidden costs or surprise fees</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-20 gradient-bg">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <div class="scroll-reveal">
                    <div class="text-4xl md:text-5xl font-bold text-white mb-2">5+</div>
                    <div class="text-xl text-red-100">Years of Service</div>
                </div>

                <div class="scroll-reveal">
                    <div class="text-4xl md:text-5xl font-bold text-white mb-2">50+</div>
                    <div class="text-xl text-red-100">Ambulances Available</div>
                </div>

                <div class="scroll-reveal">
                    <div class="text-4xl md:text-5xl font-bold text-white mb-2">25+</div>
                    <div class="text-xl text-red-100">Cities Covered</div>
                </div>

                <div class="scroll-reveal">
                    <div class="text-4xl md:text-5xl font-bold text-white mb-2">10K+</div>
                    <div class="text-xl text-red-100">Happy Customers</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 scroll-reveal">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">What Our Customers Say</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Real stories from people who trust QuickMed for their emergency transport needs
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-8 rounded-xl scroll-reveal">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">"QuickMed saved my father's life. Their response time was incredible and the medical staff was highly professional."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center text-white font-semibold">JD</div>
                        <div class="ml-4">
                            <div class="font-semibold text-gray-800">John Doe</div>
                            <div class="text-gray-600">Nairobi</div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-8 rounded-xl scroll-reveal">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">"The GPS tracking feature gave us peace of mind knowing exactly when the ambulance would arrive."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">SJ</div>
                        <div class="ml-4">
                            <div class="font-semibold text-gray-800">Sarah Johnson</div>
                            <div class="text-gray-600">Mombasa</div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-8 rounded-xl scroll-reveal">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">"Professional service from start to finish. The medical equipment was top-notch and the staff was very caring."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center text-white font-semibold">MW</div>
                        <div class="ml-4">
                            <div class="font-semibold text-gray-800">Michael Wilson</div>
                            <div class="text-gray-600">Kisumu</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 gradient-bg">
        <div class="container mx-auto px-4 text-center">
            <div class="scroll-reveal">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Ready to Get Started?</h2>
                <p class="text-xl text-red-100 mb-8 max-w-2xl mx-auto">
                    Join thousands of satisfied customers who trust QuickMed for their emergency transport needs
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="signup.php" class="bg-white text-red-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-100 transition-colors">
                        <i class="fas fa-user-plus mr-2"></i>Sign Up Now
                    </a>
                    <a href="contact.php" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-red-600 transition-colors">
                        <i class="fas fa-phone mr-2"></i>Contact Us
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="scroll-reveal">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-red-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-ambulance text-white"></i>
                        </div>
                        <span class="text-2xl font-bold">QuickMed</span>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Professional emergency medical transport services available 24/7 across Kenya.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-red-600 transition-colors">
                            <i class="fab fa-facebook text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-red-600 transition-colors">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-red-600 transition-colors">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                    </div>
                </div>

                <div class="scroll-reveal">
                    <h3 class="text-lg font-semibold mb-6">Quick Links</h3>
                    <ul class="space-y-3">
                        <li><a href="#home" class="text-gray-400 hover:text-red-600 transition-colors">Home</a></li>
                        <li><a href="#services" class="text-gray-400 hover:text-red-600 transition-colors">Services</a></li>
                        <li><a href="about.php" class="text-gray-400 hover:text-red-600 transition-colors">About Us</a></li>
                        <li><a href="contact.php" class="text-gray-400 hover:text-red-600 transition-colors">Contact</a></li>
                    </ul>
                </div>

                <div class="scroll-reveal">
                    <h3 class="text-lg font-semibold mb-6">Services</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-red-600 transition-colors">Emergency Transport</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-red-600 transition-colors">Non-Emergency Transport</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-red-600 transition-colors">Inter-Hospital Transfer</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-red-600 transition-colors">Specialized Care</a></li>
                    </ul>
                </div>

                <div class="scroll-reveal">
                    <h3 class="text-lg font-semibold mb-6">Contact Info</h3>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-phone text-red-600"></i>
                            <span class="text-gray-400">+254 700 123 456</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-red-600"></i>
                            <span class="text-gray-400">info@quickmed.co.ke</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-map-marker-alt text-red-600"></i>
                            <span class="text-gray-400">Nairobi, Kenya</span>
                        </div>
                    </div>

                    <!-- Newsletter Signup -->
                    <div class="mt-6">
                        <h4 class="text-sm font-semibold mb-3">Newsletter</h4>
                        <div class="flex">
                            <input type="email" placeholder="Your email" class="flex-1 px-3 py-2 bg-gray-800 border border-gray-700 rounded-l-lg focus:outline-none focus:border-red-600">
                            <button class="bg-red-600 px-4 py-2 rounded-r-lg hover:bg-red-700 transition-colors">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 text-center">
                <p class="text-gray-400">
                    © 2024 QuickMed. All rights reserved. |
                    <a href="privacy-policy.php" class="text-red-600 hover:underline">Privacy Policy</a> |
                    <a href="terms-of-service.php" class="text-red-600 hover:underline">Terms of Service</a>
                </p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Scroll reveal animation
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.scroll-reveal').forEach(el => {
            observer.observe(el);
        });

        // Counter animation for statistics
        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 100;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current) + '+';
            }, 20);
        }

        // Trigger counter animation when statistics section is visible
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counters = entry.target.querySelectorAll('.scroll-reveal');
                    counters.forEach((counter, index) => {
                        const target = [5, 50, 25, 10];
                        setTimeout(() => {
                            animateCounter(counter.querySelector('div'), target[index]);
                        }, index * 200);
                    });
                    statsObserver.unobserve(entry.target);
                }
            });
        });

        const statsSection = document.querySelector('.gradient-bg');
        if (statsSection) {
            statsObserver.observe(statsSection);
        }
    </script>
</body>
</html>
