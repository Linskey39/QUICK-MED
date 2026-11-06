<?php
require_once 'session_config.php';
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - QuickMed</title>
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
                <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">Privacy Policy</h1>
                <p class="text-xl text-red-100 max-w-3xl mx-auto">
                    How we collect, use, and protect your personal information
                </p>
            </div>
        </div>
    </section>

    <!-- Privacy Policy Content -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="prose prose-lg max-w-none">
                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">1. Information We Collect</h2>
                    <p class="text-gray-600 mb-4">
                        We collect information you provide directly to us, such as when you create an account, book an ambulance, or contact us for support.
                    </p>
                    <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                        <li>Personal identification information (name, email address, phone number)</li>
                        <li>Account credentials and preferences</li>
                        <li>Emergency contact information</li>
                        <li>Medical information relevant to transport services</li>
                        <li>Payment and billing information</li>
                        <li>Communication records and support requests</li>
                    </ul>
                </div>

                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">2. How We Use Your Information</h2>
                    <p class="text-gray-600 mb-4">
                        We use the information we collect to provide, maintain, and improve our services:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                        <li>Process and fulfill ambulance booking requests</li>
                        <li>Provide customer support and respond to inquiries</li>
                        <li>Send important service updates and notifications</li>
                        <li>Verify your identity and prevent fraud</li>
                        <li>Improve our services and develop new features</li>
                        <li>Comply with legal obligations and regulations</li>
                    </ul>
                </div>

                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">3. Information Sharing</h2>
                    <p class="text-gray-600 mb-4">
                        We do not sell, trade, or otherwise transfer your personal information to third parties except in the following circumstances:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                        <li>With your explicit consent</li>
                        <li>To emergency medical personnel when necessary for patient care</li>
                        <li>To comply with legal requirements or court orders</li>
                        <li>To protect our rights, property, or safety</li>
                        <li>With trusted service providers who assist in operating our platform</li>
                    </ul>
                </div>

                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">4. Data Security</h2>
                    <p class="text-gray-600 mb-4">
                        We implement appropriate technical and organizational measures to protect your personal information:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                        <li>Encryption of sensitive data in transit and at rest</li>
                        <li>Regular security assessments and updates</li>
                        <li>Access controls and authentication measures</li>
                        <li>Employee training on data protection practices</li>
                        <li>Incident response procedures</li>
                    </ul>
                </div>

                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">5. Your Rights</h2>
                    <p class="text-gray-600 mb-4">
                        You have the following rights regarding your personal information:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                        <li><strong>Access:</strong> Request a copy of your personal information</li>
                        <li><strong>Correction:</strong> Update or correct inaccurate information</li>
                        <li><strong>Deletion:</strong> Request deletion of your personal information</li>
                        <li><strong>Portability:</strong> Receive your data in a structured format</li>
                        <li><strong>Objection:</strong> Object to certain processing activities</li>
                        <li><strong>Withdrawal:</strong> Withdraw consent where applicable</li>
                    </ul>
                </div>

                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">6. Cookies and Tracking</h2>
                    <p class="text-gray-600 mb-4">
                        We use cookies and similar technologies to enhance your experience on our website:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                        <li><strong>Essential cookies:</strong> Required for basic website functionality</li>
                        <li><strong>Analytics cookies:</strong> Help us understand how visitors use our site</li>
                        <li><strong>Preference cookies:</strong> Remember your settings and preferences</li>
                        <li><strong>Security cookies:</strong> Help protect against fraud and abuse</li>
                    </ul>
                    <p class="text-gray-600 mb-6">
                        You can control cookie settings through your browser preferences.
                    </p>
                </div>

                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">7. Data Retention</h2>
                    <p class="text-gray-600 mb-4">
                        We retain your personal information for as long as necessary to:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                        <li>Provide our services to you</li>
                        <li>Comply with legal obligations</li>
                        <li>Resolve disputes and enforce agreements</li>
                        <li>Maintain business records</li>
                    </ul>
                    <p class="text-gray-600 mb-6">
                        When we no longer need your information, we will securely delete or anonymize it.
                    </p>
                </div>

                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">8. Children's Privacy</h2>
                    <p class="text-gray-600 mb-6">
                        Our services are not intended for children under 13 years of age. We do not knowingly collect personal information from children under 13. If you believe we have collected information from a child under 13, please contact us immediately.
                    </p>
                </div>

                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">9. International Transfers</h2>
                    <p class="text-gray-600 mb-6">
                        Your personal information may be transferred to and processed in countries other than your own. We ensure appropriate safeguards are in place to protect your information in accordance with this privacy policy.
                    </p>
                </div>

                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">10. Changes to This Policy</h2>
                    <p class="text-gray-600 mb-6">
                        We may update this privacy policy from time to time. We will notify you of any material changes by posting the new policy on our website and updating the "Last Updated" date. Your continued use of our services after such changes constitutes acceptance of the updated policy.
                    </p>
                </div>

                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">11. Contact Us</h2>
                    <p class="text-gray-600 mb-4">
                        If you have any questions about this privacy policy or our data practices, please contact us:
                    </p>
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <p class="text-gray-600 mb-2"><strong>Email:</strong> privacy@quickmed.co.ke</p>
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
                        <li><a href="privacy-policy.php" class="text-red-600">Privacy Policy</a></li>
                        <li><a href="terms-of-service.php" class="text-gray-400 hover:text-red-600 transition-colors">Terms of Service</a></li>
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
                    <a href="privacy-policy.php" class="text-red-600">Privacy Policy</a> | 
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