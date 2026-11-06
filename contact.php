<?php
require_once 'session_config.php';
include 'config.php';

$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $subject = trim($_POST["subject"]);
    $message = trim($_POST["message"]);
    
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_message = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email address.";
    } else {
        // Insert message into database
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        
        if ($stmt->execute()) {
            $success_message = "Thank you for your message. We'll get back to you soon!";
            // Clear form data
            $name = $email = $subject = $message = '';
        } else {
            $error_message = "Error sending message. Please try again.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - QuickMed</title>
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
                    <a href="about.php" class="text-gray-700 hover:text-red-600 transition-colors">About</a>
                    <a href="contact.php" class="text-red-600 font-semibold">Contact</a>
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
                <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">Contact Us</h1>
                <p class="text-xl text-red-100 max-w-3xl mx-auto">
                    Get in touch with us for any questions, support, or emergency assistance
                </p>
            </div>
        </div>
    </section>

    <!-- Emergency Contact -->
    <section class="py-12 bg-red-600">
        <div class="container mx-auto px-4 text-center">
            <div class="animate-slide-up">
                <h2 class="text-3xl font-bold text-white mb-4">Emergency Contact</h2>
                <p class="text-xl text-red-100 mb-6">For immediate emergency assistance</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <div class="bg-white/20 backdrop-blur-sm p-6 rounded-lg">
                        <i class="fas fa-phone-alt text-white text-3xl mb-2"></i>
                        <p class="text-white font-semibold">Emergency Hotline</p>
                        <p class="text-white text-2xl font-bold">+254 700 123 456</p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm p-6 rounded-lg">
                        <i class="fas fa-clock text-white text-3xl mb-2"></i>
                        <p class="text-white font-semibold">Available 24/7</p>
                        <p class="text-white">Round the clock service</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form & Info -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Send Us a Message</h2>
                    <p class="text-gray-600 mb-8">
                        Have a question or need assistance? Fill out the form below and we'll get back to you as soon as possible.
                    </p>
                    
                    <?php if (!empty($success_message)): ?>
                        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                            <?php echo $success_message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($error_message)): ?>
                        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="contact.php" class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-gray-700 font-medium mb-2">Full Name *</label>
                                <input type="text" id="name" name="name" required 
                                       value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent"
                                       placeholder="Your full name">
                            </div>
                            <div>
                                <label for="email" class="block text-gray-700 font-medium mb-2">Email Address *</label>
                                <input type="email" id="email" name="email" required 
                                       value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent"
                                       placeholder="your@email.com">
                            </div>
                        </div>
                        
                        <div>
                            <label for="subject" class="block text-gray-700 font-medium mb-2">Subject *</label>
                            <input type="text" id="subject" name="subject" required 
                                   value="<?php echo isset($subject) ? htmlspecialchars($subject) : ''; ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent"
                                   placeholder="What is this about?">
                        </div>
                        
                        <div>
                            <label for="message" class="block text-gray-700 font-medium mb-2">Message *</label>
                            <textarea id="message" name="message" rows="6" required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent"
                                      placeholder="Please describe your inquiry or concern..."><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
                        </div>
                        
                        <button type="submit" 
                                class="w-full bg-red-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-red-700 transition-colors">
                            <i class="fas fa-paper-plane mr-2"></i>Send Message
                        </button>
                    </form>
                </div>
                
                <!-- Contact Information -->
                <div class="scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Get in Touch</h2>
                    <p class="text-gray-600 mb-8">
                        We're here to help and answer any questions you might have. We look forward to hearing from you.
                    </p>
                    
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone text-red-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Phone Numbers</h3>
                                <p class="text-gray-600 mb-1">Emergency: +254 700 123 456</p>
                                <p class="text-gray-600 mb-1">General: +254 700 123 457</p>
                                <p class="text-gray-600">Support: +254 700 123 458</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Email Addresses</h3>
                                <p class="text-gray-600 mb-1">General: info@quickmed.co.ke</p>
                                <p class="text-gray-600 mb-1">Support: support@quickmed.co.ke</p>
                                <p class="text-gray-600">Emergency: emergency@quickmed.co.ke</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-green-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Head Office</h3>
                                <p class="text-gray-600">
                                    QuickMed House<br>
                                    Westlands Road<br>
                                    Nairobi, Kenya<br>
                                    P.O. Box 12345-00100
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-clock text-purple-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Business Hours</h3>
                                <p class="text-gray-600 mb-1">Emergency Services: 24/7</p>
                                <p class="text-gray-600 mb-1">Office Hours: Mon-Fri 8:00 AM - 6:00 PM</p>
                                <p class="text-gray-600">Weekend: Sat-Sun 9:00 AM - 5:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Office Locations -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 scroll-reveal">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Our Office Locations</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Find our offices across major cities in Kenya
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-xl shadow-lg card-hover scroll-reveal">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-building text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Nairobi</h3>
                    <p class="text-gray-600 text-center mb-4">Head Office</p>
                    <p class="text-gray-600 text-center text-sm">
                        QuickMed House<br>
                        Westlands Road<br>
                        Nairobi, Kenya
                    </p>
                </div>
                
                <div class="bg-white p-8 rounded-xl shadow-lg card-hover scroll-reveal">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-building text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Mombasa</h3>
                    <p class="text-gray-600 text-center mb-4">Coastal Region</p>
                    <p class="text-gray-600 text-center text-sm">
                        QuickMed Mombasa<br>
                        Moi Avenue<br>
                        Mombasa, Kenya
                    </p>
                </div>
                
                <div class="bg-white p-8 rounded-xl shadow-lg card-hover scroll-reveal">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-building text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Kisumu</h3>
                    <p class="text-gray-600 text-center mb-4">Western Region</p>
                    <p class="text-gray-600 text-center text-sm">
                        QuickMed Kisumu<br>
                        Oginga Odinga Street<br>
                        Kisumu, Kenya
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
                        <li><a href="contact.php" class="text-red-600">Contact</a></li>
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