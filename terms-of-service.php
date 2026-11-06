<?php
require_once 'session_config.php';
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service - QuickMed</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .animate-fade-in { animation: fadeIn 1s ease-in; }
        .scroll-reveal { opacity: 0; transform: translateY(30px); transition: all 0.6s ease; }
        .scroll-reveal.revealed { opacity: 1; transform: translateY(0); }
        
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        
        .gradient-bg { background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%); }
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
                    <a href="about.php" class="text-gray-700 hover:text-red-600 transition-colors">About</a>
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
                <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">Terms of Service</h1>
                <p class="text-xl text-red-100 max-w-3xl mx-auto">
                    Please read these terms carefully before using our services
                </p>
            </div>
        </div>
    </section>

    <!-- Terms Content -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="prose prose-lg max-w-none">
                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">1. Acceptance of Terms</h2>
                    <p class="text-gray-600 mb-6">
                        By accessing and using QuickMed's website and services, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.
                    </p>
                </div>

                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">2. Description of Service</h2>
                    <p class="text-gray-600 mb-4">
                        QuickMed provides emergency and non-emergency medical transport services through our online platform. Our services include:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                        <li>Emergency ambulance booking and dispatch</li>
                        <li>Non-emergency medical transport</li>
                        <li>Inter-hospital patient transfers</li>
                        <li>Specialized medical transport services</li>
                        <li>Real-time tracking and communication</li>
                        <li>Online account management</li>
                    </ul>
                </div>

                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">3. User Accounts</h2>
                    <p class="text-gray-600 mb-4">
                        To access certain features of our service, you must create an account. You agree to:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                        <li>Provide accurate, current, and complete information</li>
                        <li>Maintain and update your account information</li>
                        <li>Keep your password secure and confidential</li>
                        <li>Accept responsibility for all activities under your account</li>
                        <li>Notify us immediately of any unauthorized use</li>
                        <li>Be at least 18 years old or have parental consent</li>
                    </ul>
                </div>

                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">4. Emergency Services</h2>
                    <p class="text-gray-600 mb-4">
                        Our emergency services are subject to the following terms:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                        <li>Emergency calls are prioritized based on medical urgency</li>
                        <li>Response times may vary based on location and availability</li>
                        <li>We reserve the right to redirect to emergency services when appropriate</li>
                        <li>Medical decisions are made by qualified medical personnel</li>
                        <li>We are not liable for delays due to circumstances beyond our control</li>
                    </ul>
                </div>

                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">5. Booking and Cancellation</h2>
                    <p class="text-gray-600 mb-4">
                        Booking and cancellation policies:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                        <li>Bookings can be made online, by phone, or through our mobile app</li>
                        <li>Emergency bookings take priority over scheduled bookings</li>
                        <li>Cancellations must be made at least 2 hours before scheduled pickup</li>
                        <li>Late cancellations may incur charges</li>
                        <li>We reserve the right to cancel bookings due to operational constraints</li>
                    </ul>
                </div>

                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">6. Payment Terms</h2>
                    <p class="text-gray-600 mb-4">
                        Payment and billing terms:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                        <li>Payment is due upon service completion unless otherwise arranged</li>
                        <li>We accept various payment methods including cash, card, and mobile money</li>
                        <li>Prices are subject to change with prior notice</li>
                        <li>Additional charges may apply for special services or equipment</li>
                        <li>Insurance claims are processed according to policy terms</li>
                        <li>Unpaid accounts may result in service suspension</li>
                    </ul>
                </div>

                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">7. User Conduct</h2>
                    <p class="text-gray-600 mb-4">
                        You agree not to:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                        <li>Use our services for any unlawful purpose</li>
                        <li>Provide false or misleading information</li>
                        <li>Interfere with or disrupt our services</li>
                        <li>Attempt to gain unauthorized access to our systems</li>
                        <li>Harass, abuse, or harm our staff or other users</li>
                        <li>Use our services to transport illegal substances or contraband</li>
                    </ul>
                </div>

                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">8. Privacy and Data Protection</h2>
                    <p class="text-gray-600 mb-6">
                        Your privacy is important to us. Our collection and use of personal information is governed by our Privacy Policy, which is incorporated into these terms by reference. By using our services, you consent to our collection and use of information as described in our Privacy Policy.
                    </p>
                </div>

                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">9. Intellectual Property</h2>
                    <p class="text-gray-600 mb-4">
                        All content on our website and platform, including but not limited to:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                        <li>Text, graphics, logos, and images</li>
                        <li>Software, code, and applications</li>
                        <li>Design, layout, and user interface</li>
                        <li>Trademarks and service marks</li>
                    </ul>
                    <p class="text-gray-600 mb-6">
                        Are owned by QuickMed or our licensors and are protected by copyright, trademark, and other intellectual property laws.
                    </p>
                </div>

                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">10. Limitation of Liability</h2>
                    <p class="text-gray-600 mb-4">
                        To the maximum extent permitted by law, QuickMed shall not be liable for:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                        <li>Indirect, incidental, special, or consequential damages</li>
                        <li>Loss of profits, data, or business opportunities</li>
                        <li>Damages resulting from delays or service interruptions</li>
                        <li>Medical outcomes or patient conditions</li>
                        <li>Acts of third parties or force majeure events</li>
                    </ul>
                    <p class="text-gray-600 mb-6">
                        Our total liability shall not exceed the amount paid for the specific service in question.
                    </p>
                </div>

                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">11. Indemnification</h2>
                    <p class="text-gray-600 mb-6">
                        You agree to indemnify and hold harmless QuickMed, its officers, directors, employees, and agents from and against any claims, damages, losses, and expenses arising from your use of our services or violation of these terms.
                    </p>
                </div>

                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">12. Dispute Resolution</h2>
                    <p class="text-gray-600 mb-4">
                        Any disputes arising from these terms or our services shall be resolved through:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                        <li>Good faith negotiation between parties</li>
                        <li>Mediation if negotiation fails</li>
                        <li>Arbitration in accordance with Kenyan law</li>
                        <li>Court proceedings as a last resort</li>
                    </ul>
                </div>

                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">13. Termination</h2>
                    <p class="text-gray-600 mb-4">
                        We may terminate or suspend your account and access to our services:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                        <li>For violation of these terms</li>
                        <li>For fraudulent or illegal activities</li>
                        <li>For non-payment of fees</li>
                        <li>At your request</li>
                        <li>For operational or business reasons</li>
                    </ul>
                </div>

                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">14. Governing Law</h2>
                    <p class="text-gray-600 mb-6">
                        These terms shall be governed by and construed in accordance with the laws of Kenya. Any legal proceedings shall be brought in the courts of Kenya.
                    </p>
                </div>

                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">15. Changes to Terms</h2>
                    <p class="text-gray-600 mb-6">
                        We reserve the right to modify these terms at any time. Changes will be effective immediately upon posting. Your continued use of our services after changes constitutes acceptance of the modified terms.
                    </p>
                </div>

                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">16. Contact Information</h2>
                    <p class="text-gray-600 mb-4">
                        If you have questions about these terms, please contact us:
                    </p>
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <p class="text-gray-600 mb-2"><strong>Email:</strong> legal@quickmed.co.ke</p>
                        <p class="text-gray-600 mb-2"><strong>Phone:</strong> +254 700 123 456</p>
                        <p class="text-gray-600 mb-2"><strong>Address:</strong> QuickMed House, Westlands Road, Nairobi, Kenya</p>
                    </div>
                </div>

                <div class="scroll-reveal mt-12 p-6 bg-blue-50 rounded-lg">
                    <p class="text-blue-800 text-sm">
                        <strong>Last Updated:</strong> January 15, 2024
                    </p>
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
                        <li><a href="about.php" class="text-gray-400 hover:text-red-600 transition-colors">About Us</a></li>
                        <li><a href="contact.php" class="text-gray-400 hover:text-red-600 transition-colors">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-6">Legal</h3>
                    <ul class="space-y-3">
                        <li><a href="privacy-policy.php" class="text-gray-400 hover:text-red-600 transition-colors">Privacy Policy</a></li>
                        <li><a href="terms-of-service.php" class="text-red-600">Terms of Service</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-red-600 transition-colors">Cookie Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-red-600 transition-colors">Data Protection</a></li>
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
                    <a href="terms-of-service.php" class="text-red-600">Terms of Service</a>
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