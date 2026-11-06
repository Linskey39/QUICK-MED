<?php
require_once 'session_config.php';
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - QuickMed</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .animate-fade-in { animation: fadeIn 1s ease-in; }
        .animate-slide-up { animation: slideUp 0.8s ease-out; }
        .scroll-reveal { opacity: 0; transform: translateY(30px); transition: all 0.6s ease; }
        .scroll-reveal.revealed { opacity: 1; transform: translateY(0); }
        
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        
        .gradient-bg { background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <nav class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-red-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-ambulance text-white"></i>
                    </div>
                    <span class="text-2xl font-bold text-red-600">QuickMed</span>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="index.php" class="text-gray-700 hover:text-red-600 transition-colors">Home</a>
                    <a href="index.php#services" class="text-gray-700 hover:text-red-600 transition-colors">Services</a>
                    <a href="about.php" class="text-red-600 font-semibold">About</a>
                    <a href="contact.php" class="text-gray-700 hover:text-red-600 transition-colors">Contact</a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="login.php" class="text-gray-700 hover:text-red-600 transition-colors">Login</a>
                    <a href="signup.php" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors">Sign Up</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="py-20 gradient-bg">
        <div class="container mx-auto px-4 text-center">
            <div class="animate-fade-in">
                <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">About QuickMed</h1>
                <p class="text-xl text-red-100 max-w-3xl mx-auto">
                    Leading emergency medical transport service provider in Kenya, committed to saving lives and providing exceptional care
                </p>
            </div>
        </div>
    </section>

    <!-- Mission & Vision -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="scroll-reveal">
                    <h2 class="text-4xl font-bold text-gray-800 mb-6">Our Mission</h2>
                    <p class="text-lg text-gray-600 mb-6">
                        To provide immediate, reliable, and professional emergency medical transport services that save lives and ensure the highest quality of care for patients across Kenya.
                    </p>
                    <p class="text-lg text-gray-600">
                        We are committed to reducing response times, improving patient outcomes, and making emergency medical transport accessible to everyone.
                    </p>
                </div>
                
                <div class="scroll-reveal">
                    <h2 class="text-4xl font-bold text-gray-800 mb-6">Our Vision</h2>
                    <p class="text-lg text-gray-600 mb-6">
                        To become the most trusted and preferred emergency medical transport service provider in East Africa, setting the standard for excellence in pre-hospital care.
                    </p>
                    <p class="text-lg text-gray-600">
                        We envision a future where every emergency receives immediate, professional response, regardless of location or circumstance.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Company History -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 scroll-reveal">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Our Story</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    From humble beginnings to becoming a leading emergency medical transport provider
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center scroll-reveal">
                    <div class="w-20 h-20 bg-red-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold">2019</div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Foundation</h3>
                    <p class="text-gray-600">QuickMed was founded with a vision to revolutionize emergency medical transport in Kenya</p>
                </div>
                
                <div class="text-center scroll-reveal">
                    <div class="w-20 h-20 bg-red-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold">2021</div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Expansion</h3>
                    <p class="text-gray-600">Expanded services to 15 major cities across Kenya with 25 ambulances</p>
                </div>
                
                <div class="text-center scroll-reveal">
                    <div class="w-20 h-20 bg-red-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold">2024</div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Leadership</h3>
                    <p class="text-gray-600">Now serving 25+ cities with 50+ ambulances and 10,000+ satisfied customers</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Values -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 scroll-reveal">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Our Core Values</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    The principles that guide everything we do
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center scroll-reveal">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-heart text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Compassion</h3>
                    <p class="text-gray-600">We treat every patient with care, empathy, and respect</p>
                </div>
                
                <div class="text-center scroll-reveal">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-bolt text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Speed</h3>
                    <p class="text-gray-600">Every second counts in emergencies - we respond quickly</p>
                </div>
                
                <div class="text-center scroll-reveal">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-shield-alt text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Safety</h3>
                    <p class="text-gray-600">Patient and staff safety is our top priority</p>
                </div>
                
                <div class="text-center scroll-reveal">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-star text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Excellence</h3>
                    <p class="text-gray-600">We strive for excellence in every aspect of our service</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 scroll-reveal">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Our Leadership Team</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Meet the dedicated professionals leading QuickMed
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-xl shadow-lg card-hover scroll-reveal">
                    <div class="w-24 h-24 bg-red-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold">JD</div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2 text-center">Dr. John Doe</h3>
                    <p class="text-red-600 text-center mb-4">CEO & Founder</p>
                    <p class="text-gray-600 text-center">Emergency medicine specialist with 15+ years of experience in pre-hospital care</p>
                </div>
                
                <div class="bg-white p-8 rounded-xl shadow-lg card-hover scroll-reveal">
                    <div class="w-24 h-24 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold">SJ</div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2 text-center">Sarah Johnson</h3>
                    <p class="text-red-600 text-center mb-4">Operations Director</p>
                    <p class="text-gray-600 text-center">Healthcare management expert with extensive experience in emergency services</p>
                </div>
                
                <div class="bg-white p-8 rounded-xl shadow-lg card-hover scroll-reveal">
                    <div class="w-24 h-24 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold">MW</div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2 text-center">Michael Wilson</h3>
                    <p class="text-red-600 text-center mb-4">Medical Director</p>
                    <p class="text-gray-600 text-center">Board-certified emergency physician leading our medical protocols and training</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Certifications -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 scroll-reveal">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Certifications & Awards</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Recognition of our commitment to excellence and safety
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center scroll-reveal">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-certificate text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">ISO 9001:2015</h3>
                    <p class="text-gray-600">Quality Management System</p>
                </div>
                
                <div class="text-center scroll-reveal">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-award text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Best Emergency Service</h3>
                    <p class="text-gray-600">Healthcare Awards 2023</p>
                </div>
                
                <div class="text-center scroll-reveal">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-shield-alt text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Safety Excellence</h3>
                    <p class="text-gray-600">National Safety Council</p>
                </div>
                
                <div class="text-center scroll-reveal">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-star text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Customer Choice</h3>
                    <p class="text-gray-600">Service Excellence Award</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
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
                
                <div>
                    <h3 class="text-lg font-semibold mb-6">Quick Links</h3>
                    <ul class="space-y-3">
                        <li><a href="index.php" class="text-gray-400 hover:text-red-600 transition-colors">Home</a></li>
                        <li><a href="index.php#services" class="text-gray-400 hover:text-red-600 transition-colors">Services</a></li>
                        <li><a href="about.php" class="text-red-600">About Us</a></li>
                        <li><a href="contact.php" class="text-gray-400 hover:text-red-600 transition-colors">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-6">Services</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-red-600 transition-colors">Emergency Transport</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-red-600 transition-colors">Non-Emergency Transport</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-red-600 transition-colors">Inter-Hospital Transfer</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-red-600 transition-colors">Specialized Care</a></li>
                    </ul>
                </div>
                
                <div>
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
    </script>
</body>
</html> 