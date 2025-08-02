<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Rafifintech|Home</title>

    <!-- libraries used -->

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="gsap.min.js"></script>
    <script src="scroll.js"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat+Alternates:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
      rel="stylesheet"
    />
    <script src="lottie.js"></script>
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Playwrite+DE+SAS+Guides&display=swap");
      @import url("https://fonts.googleapis.com/css2?family=Delicious+Handrawn&display=swap");
      @import url("https://fonts.googleapis.com/css2?family=Edu+AU+VIC+WA+NT+Arrows:wght@400..700&display=swap");
      @import url("https://fonts.googleapis.com/css2?family=Syncopate:wght@400;700&display=swap");
      .syncopate-regular {
        font-family: "Syncopate", serif;
        font-weight: 400;
        font-style: normal;
      }

      .syncopate-bold {
        font-family: "Syncopate", serif;
        font-weight: 700;
        font-style: normal;
      }
      .playwrite-de-sas-guides-regular {
        font-family: "Playwrite DE SAS Guides", serif;
        font-weight: 400;
        font-style: normal;
      }
      .edu-au-vic-wa-nt-arrows {
        font-family: "Edu AU VIC WA NT Arrows", serif;
        font-optical-sizing: auto;
        font-weight: 700;
        font-style: normal;
        font-size: 2rem;
      }
      .hand-draw {
        font-family: "Delicious Handrawn", cursive;
        font-weight: 700;
        font-style: normal;
      }
    </style>
<style>
  .quote {
  font-family: "Delicious Handrawn", cursive;
  font-size: 3.8rem; /* Main heading size for larger screens */
  color: #600649;
  text-align: center;
  padding: 20px;
  max-width: 80%;
  margin: 50px auto;
  font-weight: bold;
  position: relative;
  text-transform: uppercase;
  letter-spacing: 2px;
}

/* Styling heading-style symbols */
.quote::before,
.quote::after {
  font-size: 3rem;
  color: #e54646;
  position: absolute;
  font-weight: bold;
}

.quote::before {
  content: "⎯⎯";
  left: -30px;
  top: 50%;
  transform: translateY(-50%);
}

.quote::after {
  content: "⎯⎯";
  right: -30px;
  top: 50%;
  transform: translateY(-50%);
}

/* Subtitle styling */
.subtitle {
  font-family: "Delicious Handrawn", cursive;
  font-size: 2rem;
  color: #1e293b;
  text-align: center;
  margin-top: 10px;
}

/* Responsive styles for mobile devices */
@media (max-width: 768px) {
  .quote {
    font-size: 2.8rem; /* smaller font size */
    padding: 15px;
    max-width: 90%; /* wider on mobile */
    margin: 30px auto;
    letter-spacing: 1px; /* reduced letter spacing */
  }
  .quote::before,
  .quote::after {
    font-size: 2rem; /* adjust symbol size */
    left: -20px;
    right: -20px;
  }
  .quote::before {
    top: 45%;
  }
  .quote::after {
    top: 45%;
  }
  .subtitle {
    font-size: 1.5rem; /* smaller subtitle */
    margin-top: 8px;
  }
}

/* Extra tweaks for very small screens */
@media (max-width: 480px) {
  .quote {
    font-size: 2.2rem;
    padding: 10px;
    max-width: 95%;
    margin: 20px auto;
    letter-spacing: 0.5px;
  }
  .quote::before,
  .quote::after {
    font-size: 1.8rem;
    left: -15px;
    right: -15px;
  }
  .subtitle {
    display: none;
  }
}

</style>
<style>
  body {
    margin: 0;
    padding: 0;
    min-height: 100vh;
    background-color: #f3f4f6; /* Light gray background */
    position: relative;
    font-family: "Montserrat Alternates", sans-serif;
    color: #1e293b;
    overflow-x: hidden;
  }
  
  /* Light gray background */
  body::before {
    content: "";
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #ffffff; /* Light gray */
    pointer-events: none;
    z-index: -2;
  }
  
  /* Graph background */
  .graph-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    overflow: hidden;
  }
  
  /* Horizontal grid lines */
  .grid-lines {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: linear-gradient(rgba(30, 58, 138, 0.05) 1px, transparent 1px);
    background-size: 100% 40px;
    z-index: -1;
  }
  
  /* Vertical grid lines */
  .grid-columns {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: linear-gradient(90deg, rgba(30, 58, 138, 0.05) 1px, transparent 1px);
    background-size: 40px 100%;
    z-index: -1;
  }
  
  /* Graph line 1 */
  .graph-line-1 {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 2px;
    background-color: rgba(59, 130, 246, 0.3);
    transform-origin: left center;
    z-index: 1;
    filter: drop-shadow(0 0 3px rgba(59, 130, 246, 0.2));
    animation: moveGraph1 10s ease-in-out infinite;
  }
  
  /* Graph line 2 */
  .graph-line-2 {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 2px;
    background-color: rgba(30, 58, 138, 0.3);
    transform-origin: left center;
    z-index: 1;
    filter: drop-shadow(0 0 3px rgba(30, 58, 138, 0.2));
    animation: moveGraph2 14s ease-in-out infinite;
  }
  
  /* Graph line 3 */
  .graph-line-3 {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 2px;
    background-color: rgba(147, 197, 253, 0.3);
    transform-origin: left center;
    z-index: 1;
    filter: drop-shadow(0 0 3px rgba(147, 197, 253, 0.2));
    animation: moveGraph3 12s ease-in-out infinite;
  }
  
  /* Graph points */
  .graph-line-1::before,
  .graph-line-2::before,
  .graph-line-3::before {
    content: "";
    position: absolute;
    width: 6px;
    height: 6px;
    background-color: white;
    border-radius: 50%;
    top: -2px;
    right: 0;
    box-shadow: 0 0 8px rgba(255, 255, 255, 0.6);
    animation: pulsePoint 2s ease-in-out infinite;
  }
  
  /* Horizontal light rays */
  .horizontal-rays {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
  }
  
  .h-ray {
    position: absolute;
    height: 2px;
    width: 80px;
    background: linear-gradient(90deg, rgba(59, 130, 246, 0), rgba(59, 130, 246, 0.4), rgba(59, 130, 246, 0));
    filter: drop-shadow(0 0 2px rgba(59, 130, 246, 0.2));
    animation: moveHorizontal 10s linear infinite;
    opacity: 0.4;
  }
  
  .h-ray:nth-child(2) {
    background: linear-gradient(90deg, rgba(239, 68, 68, 0), rgba(239, 68, 68, 0.4), rgba(239, 68, 68, 0));
    filter: drop-shadow(0 0 2px rgba(239, 68, 68, 0.2));
    animation-delay: -2s;
    animation-duration: 12s;
  }
  
  .h-ray:nth-child(3) {
    background: linear-gradient(90deg, rgba(16, 185, 129, 0), rgba(16, 185, 129, 0.4), rgba(16, 185, 129, 0));
    filter: drop-shadow(0 0 2px rgba(16, 185, 129, 0.2));
    animation-delay: -5s;
    animation-duration: 13s;
  }
  
  .h-ray:nth-child(4) {
    background: linear-gradient(90deg, rgba(139, 92, 246, 0), rgba(139, 92, 246, 0.4), rgba(139, 92, 246, 0));
    filter: drop-shadow(0 0 2px rgba(139, 92, 246, 0.2));
    animation-delay: -7s;
    animation-duration: 15s;
  }
  
  .h-ray:nth-child(5) {
    background: linear-gradient(90deg, rgba(245, 158, 11, 0), rgba(245, 158, 11, 0.4), rgba(245, 158, 11, 0));
    filter: drop-shadow(0 0 2px rgba(245, 158, 11, 0.2));
    animation-delay: -9s;
    animation-duration: 11s;
  }
  
  /* Vertical light rays */
  .vertical-rays {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
  }
  
  .v-ray {
    position: absolute;
    width: 2px;
    height: 80px;
    background: linear-gradient(180deg, rgba(59, 130, 246, 0), rgba(59, 130, 246, 0.4), rgba(59, 130, 246, 0));
    filter: drop-shadow(0 0 2px rgba(59, 130, 246, 0.2));
    animation: moveVertical 12s linear infinite;
    opacity: 0.4;
  }
  
  .v-ray:nth-child(2) {
    background: linear-gradient(180deg, rgba(239, 68, 68, 0), rgba(239, 68, 68, 0.4), rgba(239, 68, 68, 0));
    filter: drop-shadow(0 0 2px rgba(239, 68, 68, 0.2));
    animation-delay: -3s;
    animation-duration: 14s;
  }
  
  .v-ray:nth-child(3) {
    background: linear-gradient(180deg, rgba(16, 185, 129, 0), rgba(16, 185, 129, 0.4), rgba(16, 185, 129, 0));
    filter: drop-shadow(0 0 2px rgba(16, 185, 129, 0.2));
    animation-delay: -6s;
    animation-duration: 11s;
  }
  
  .v-ray:nth-child(4) {
    background: linear-gradient(180deg, rgba(139, 92, 246, 0), rgba(139, 92, 246, 0.4), rgba(139, 92, 246, 0));
    filter: drop-shadow(0 0 2px rgba(139, 92, 246, 0.2));
    animation-delay: -8s;
    animation-duration: 16s;
  }
  
  .v-ray:nth-child(5) {
    background: linear-gradient(180deg, rgba(245, 158, 11, 0), rgba(245, 158, 11, 0.4), rgba(245, 158, 11, 0));
    filter: drop-shadow(0 0 2px rgba(245, 158, 11, 0.2));
    animation-delay: -10s;
    animation-duration: 13s;
  }
  /* Header styling */
  header {
    padding: 0.3rem 0;
    text-align: center;
    margin-bottom: 2rem;
  }
  @keyframes moveGraph1 {
    0% {
      top: 20%;
      transform: scaleY(0.5);
    }
    25% {
      top: 30%;
      transform: scaleY(1.5);
    }
    50% {
      top: 60%;
      transform: scaleY(0.8);
    }
    75% {
      top: 40%;
      transform: scaleY(1.2);
    }
    100% {
      top: 20%;
      transform: scaleY(0.5);
    }
  }
  
  @keyframes moveGraph2 {
    0% {
      top: 70%;
      transform: scaleY(0.8);
    }
    33% {
      top: 30%;
      transform: scaleY(1.3);
    }
    66% {
      top: 50%;
      transform: scaleY(0.5);
    }
    100% {
      top: 70%;
      transform: scaleY(0.8);
    }
  }
  
  @keyframes moveGraph3 {
    0% {
      top: 45%;
      transform: scaleY(1);
    }
    20% {
      top: 25%;
      transform: scaleY(0.6);
    }
    40% {
      top: 65%;
      transform: scaleY(1.4);
    }
    60% {
      top: 35%;
      transform: scaleY(0.7);
    }
    80% {
      top: 55%;
      transform: scaleY(1.2);
    }
    100% {
      top: 45%;
      transform: scaleY(1);
    }
  }
  
  @keyframes pulsePoint {
    0%, 100% {
      transform: scale(1);
      opacity: 1;
    }
    50% {
      transform: scale(1.5);
      opacity: 0.7;
    }
  }
  
  /* Light Ray Animations */
  @keyframes moveHorizontal {
    0% {
      left: -80px;
    }
    100% {
      left: 100%;
    }
  }
  
  @keyframes moveVertical {
    0% {
      top: -80px;
    }
    100% {
      top: 100%;
    }
  }
  </style>
    
    <style>
      video {
        position: fixed;
        right: 0;
        bottom: 0;
        min-width: 100%;
        min-height: 100%;
        width: auto;
        height: auto;
        z-index: -1;
      }

      #hero-content > * {
        opacity: 0;
        transform: translateY(20px);
      }
    </style>

    <style>
      /* Base header styles */
      header {
        transition: all 0.3s ease;
      }

      header.scrolled {
        background-color: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(8px);
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.3);
      }

      header:not(.scrolled) {
        background-color: rgb(17, 24, 39); /* dark gray-900 */
      }

      /* Text color transitions */
      header:not(.scrolled) .nav-link {
        color: rgb(229, 231, 235); /* text-gray-200 */
      }

      header:not(.scrolled) .nav-link:hover {
        color: rgb(255, 255, 255);
      }

      header:not(.scrolled) .logo {
        color: rgb(255, 255, 255);
      }

      /* Preserve existing hover effects but adjust for dark theme */
      header:not(.scrolled) a::after {
        background: rgba(255, 255, 255, 0.3);
      }

      /* Mobile menu adjustments for dark theme */
      header:not(.scrolled) #mobile-menu-button {
        color: rgb(229, 231, 235);
      }

      header:not(.scrolled) #mobile-menu-button:hover {
        color: rgb(255, 255, 255);
      }

      /* Keep your existing styles below */
      body.sidebar-open {
        overflow: hidden;
      }

      #mobile-sidebar {
        border-radius: 0 0 0 20px;
      }

      a {
        position: relative;
        display: inline-block;
      }

      a::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: -2px;
        width: 100%;
        height: 2px;
        transform: scaleX(0);
        transition: transform 0.3s ease;
      }

      a:hover::after {
        transform: scaleX(1);
      }

      #mobile-sidebar {
        transition: transform 0.3s ease-in-out;
      }

      #close-sidebar {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        padding: 0.5rem;
        transition: background 0.3s ease;
      }

      #close-sidebar:hover {
        background: rgba(255, 255, 255, 0.4);
      }

      .group:hover .absolute {
        transform: translateY(0);
        opacity: 1;
      }

      /* .absolute {
    transform: translateY(-10px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
} */

      .mobile-dropdown .transform {
        transition: transform 0.2s ease;
      }

      .mobile-dropdown.active .transform {
        transform: rotate(180deg);
      }

      /* .absolute {
    backdrop-filter: blur(2px);
    background: rgba(255, 255, 255, 0);
} */

      .hover\:bg-gray-50:hover {
        background: linear-gradient(
          to right,
          rgba(249, 250, 251, 0.5),
          rgba(249, 250, 251, 0.8)
        );
      }
    </style>

    <style>
      #social_sidebar {
        transition: transform 0.3s ease;
      }

      /* Mobile responsiveness */
      @media (max-width: 768px) {
        #social_sidebar {
          padding: 10px;
        }

        .social-icon {
          font-size: 1.2rem;
        }
      }

      /* Hover glow effect */
      .social-icon:hover {
        text-shadow: 0 0 10px currentColor;
      }
    </style>

    <style>
    .features-section {
      position: relative;
      height: 800vh; /* 8 times viewport height for all features */
      /* background: linear-gradient(135deg, #f0f0f7 0%, #e6e6ef 100%); */
    }
    
    .features-wrapper {
      position: sticky;
      top: 0;
      left: 0;
      width: 100%;
      height: 100vh;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    
    .section-title {
      text-align: center;
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 2rem;
      color: #0d002c;
      position: absolute;
      top: 10%;
      left: 0;
      right: 0;
      z-index: 10;
    }
    
    .section-subtitle {
      text-align: center;
      font-size: 1.2rem;
      font-weight: 400;
      margin-bottom: 3rem;
      color: #666;
      position: absolute;
      top: 17%;
      left: 0;
      right: 0;
      z-index: 10;
      max-width: 700px;
      margin: 0 auto;
    }
    
    .feature-items {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0 1.5rem;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.5s ease, transform 0.5s ease;
      transform: translateY(20px);
    }
    
    .feature-items.active {
      opacity: 1;
      pointer-events: auto;
      transform: translateY(0);
    }
    
    .feature-container {
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      gap: 2rem;
    }
    
    .feature-card {
      flex: 1;
      background: white;
      border-radius: 1.5rem;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      padding: 2rem;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      overflow: hidden;
      max-width: 500px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 400px;
      position: relative;
    }
    
    .feature-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }
    
    .feature-badge {
      position: absolute;
      top: 1rem;
      right: 1rem;
      background: linear-gradient(135deg, #f72585 0%, #4361ee 100%);
      color: white;
      padding: 0.3rem 0.8rem;
      border-radius: 2rem;
      font-size: 0.8rem;
      font-weight: 600;
    }
    
    .feature-icon {
      width: 100px;
      height: 100px;
      margin-bottom: 1.5rem;
      background-color: #f0f0f5;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .feature-card:hover .feature-icon {
      background-color: #e6e0ff;
    }
    
    .feature-icon svg, .feature-icon i {
      width: 50px;
      height: 50px;
      fill: #3a0ca3;
      color: #3a0ca3;
      transition: transform 0.3s ease;
    }
    
    .feature-card:hover .feature-icon svg, .feature-card:hover .feature-icon i {
      transform: scale(1.1);
    }
    
    .feature-title {
      font-size: 1.8rem;
      font-weight: 600;
      color: #0d002c;
      margin-bottom: 1rem;
      text-align: center;
    }
    
    .feature-content {
      flex: 1;
      padding: 2rem;
      max-width: 600px;
    }
    
    .feature-description {
      font-family: "Poppins", sans-serif;
      font-size: 1.1rem;
      line-height: 1.6;
      color: #333;
      margin-bottom: 1.5rem;
    }
    
    .feature-benefits {
      margin-top: 1.5rem;
    }
    
    .feature-benefit-item {
      display: flex;
      align-items: center;
      margin-bottom: 0.8rem;
    }
    
    .benefit-icon {
      margin-right: 0.8rem;
      color: #3a0ca3;
    }
    
    .navigation {
      position: fixed;
      right: 2rem;
      top: 50%;
      transform: translateY(-50%);
      display: flex;
      flex-direction: column;
      gap: 0.8rem;
      z-index: 10;
    }
    
    .feature-nav {
      width: 0.75rem;
      height: 0.75rem;
      border-radius: 50%;
      background: rgba(58, 12, 163, 0.2);
      border: none;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .feature-nav.active {
      background-color: #3a0ca3;
      transform: scale(1.3);
    }
    
    .feature-cta {
      margin-top: 1.5rem;
      display: flex;
      gap: 1rem;
    }
    
    .btn {
      display: inline-block;
      padding: 0.8rem 1.5rem;
      border-radius: 0.5rem;
      font-size: 1rem;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.3s ease;
      cursor: pointer;
      border: none;
    }
    
    .btn-primary {
      background: linear-gradient(135deg, #3a0ca3 0%, #4361ee 100%);
      color: white;
    }
    
    .btn-primary:hover {
      background: linear-gradient(135deg, #2d0a7a 0%, #3b54d9 100%);
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(58, 12, 163, 0.3);
    }
    
    .btn-secondary {
      background: transparent;
      color: #3a0ca3;
      border: 1px solid #3a0ca3;
    }
    
    .btn-secondary:hover {
      background: rgba(58, 12, 163, 0.05);
      transform: translateY(-3px);
    }
    
    .feature-metric {
      display: flex;
      align-items: center;
      margin-top: 1rem;
      padding: 0.5rem 1rem;
      background: rgba(58, 12, 163, 0.05);
      border-radius: 0.5rem;
    }
    
    .metric-value {
      font-size: 1.8rem;
      font-weight: 700;
      color: #3a0ca3;
      margin-right: 0.8rem;
    }
    
    .metric-label {
      font-size: 0.9rem;
      color: #666;
    }
    
    .footer {
      background: #0d002c;
      color: white;
      padding: 2rem 0;
      text-align: center;
    }
    
    .footer-content {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 1.5rem;
    }
    
    .progress-bar {
      position: fixed;
      top: 0;
      left: 0;
      height: 5px;
      background: linear-gradient(90deg, #3a0ca3, #4361ee);
      z-index: 100;
      width: 0%;
      transition: width 0.2s ease;
    }
    </style>

<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          primary: {
            50: '#eef2ff',
            100: '#e0e7ff',
            200: '#c7d2fe',
            300: '#a5b4fc',
            400: '#818cf8',
            500: '#6366f1',
            600: '#4f46e5',
            700: '#4338ca',
            800: '#3730a3',
            900: '#1e3a8a',
          },
          secondary: {
            50: '#f0fdf4',
            100: '#dcfce7',
            200: '#bbf7d0',
            300: '#86efac',
            400: '#4ade80',
            500: '#22c55e',
            600: '#16a34a',
            700: '#15803d',
            800: '#166534',
            900: '#14532d',
          }
        }
      }
    }
  }
</script>
<style>
  @keyframes pulse-soft {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
  }
  
  .gradient-text {
    @apply bg-gradient-to-r from-primary-800 to-secondary-500 text-transparent bg-clip-text;
  }
  
  .card-gradient-top {
    @apply before:absolute before:top-0 before:left-0 before:right-0 before:h-1.5 before:bg-gradient-to-r before:from-primary-800 before:to-secondary-500 before:rounded-t-lg;
  }
  
  .scrollbar-hide::-webkit-scrollbar {
    display: none;
  }
  
  .scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
  }
  
  .appear-animate {
    opacity: 0;
    transform: translateY(20px);
  }
</style>

    <style>
      #thanks {
        /* background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)); */
        background-size: 100% 200px;
        background-position: 0% 100%;
        animation: gradient 3s ease infinite;
      }

      @keyframes gradient {
        0% {
          background-position: 0% 100%;
        }
        50% {
          background-position: 100% 100%;
        }
        100% {
          background-position: 0% 100%;
        }
      }
    </style>
  </head>
  <body>
    <!-- Theme - glass-effect theme -->
    <div class="graph-container">
      <div class="grid-lines"></div>
      <div class="grid-columns"></div>
      
      <!-- Horizontal light rays -->
      <div class="horizontal-rays">
        <div class="h-ray" style="top: 40px;"></div>
        <div class="h-ray" style="top: 120px;"></div>
        <div class="h-ray" style="top: 200px;"></div>
        <div class="h-ray" style="top: 280px;"></div>
        <div class="h-ray" style="top: 360px;"></div>
      </div>
      
      <!-- Vertical light rays -->
      <div class="vertical-rays">
        <div class="v-ray" style="left: 40px;"></div>
        <div class="v-ray" style="left: 120px;"></div>
        <div class="v-ray" style="left: 200px;"></div>
        <div class="v-ray" style="left: 280px;"></div>
        <div class="v-ray" style="left: 360px;"></div>
      </div>
      
      <!-- <div class="graph-line-1"></div>
      <div class="graph-line-2"></div>
      <div class="graph-line-3"></div> -->
    </div>
    <div class="progress-bar" id="progressBar"></div>
    <!-- Loader -->
    <div
      id="loader"
      class="fixed inset-0 flex items-center justify-center bg-white z-50"
    >
      <div
        class="w-16 h-16 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"
      ></div>
    </div>

    <script>
      // Hide the loader when the page fully loads
      window.addEventListener("load", function () {
        document.getElementById("loader").style.display = "none";
      });
    </script>

    <!-- side stuffes  -->
    <!-- social sidebar with social media icons , fixed on the left side  -->
    <!-- Quotes to introduce the section , with a symbolic notation  -->

    <!-- Header content here -->
    <header
    class="fixed px-4 md:px-40 top-0 left-0 w-full z-50 bg-white/95 backdrop-blur-sm"
  >
    <div class="container mx-auto px-2 md:px-6 py-1">
      <nav class="flex items-center justify-between h-16">
        <!-- Logo -->
        <div class="logo text-xl md:text-2xl font-semibold syncopate-regular">
          Rafifintech
        </div>

        <!-- Mobile Menu Button -->
        <button
          id="mobile-menu-button"
          class="lg:hidden text-gray-600 hover:text-gray-900"
        >
          <svg
            class="w-6 h-6"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 6h16M4 12h16m-7 6h7"
            />
          </svg>
        </button>

        <!-- Desktop Navigation -->
        <div class="hidden lg:flex items-center space-x-8">
          <a href="/index.html" class="text-blue-500 hover:text-blue-500 transition-colors">Home</a>
          <a href="/about.html" class="text-blue-500 hover:text-blue-500 transition-colors">About</a>
          <a href="/blog.html" class="text-blue-500 hover:text-blue-500 transition-colors">Blog</a>

          <!-- Features Dropdown -->
          <div class="relative group">
            <a
              href="#features"
              class="text-amber-500 hover:text-blue-500 transition-colors py-2"
            >
              Products
              <svg
                class="w-4 h-4 inline-block ml-1"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7"
                />
              </svg>
            </a>
            <div
              class="absolute invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all duration-300 w-56 bg-white shadow-xl rounded-xl mt-2 py-2 px-4 space-y-2"
            >
              <a href="/payouts.html" class="block text-gray-700 hover:text-blue-500 py-2">Payouts</a>
              <a href="/qr.html" class="block text-gray-700 hover:text-blue-500 py-2">QR</a>
              <a href="/upi.html" class="block text-gray-700 hover:text-blue-500 py-2">UPI</a>
              <a href="/utility.html" class="block text-gray-700 hover:text-blue-500 py-2">Utility</a>
            </div>
          </div>

          <!-- Solutions Dropdown -->
          <div class="relative group">
            <a
              href="#solutions"
              class="text-amber-500 hover:text-blue-500 transition-colors py-2"
            >
              Solutions
              <svg
                class="w-4 h-4 inline-block ml-1"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7"
                />
              </svg>
            </a>
            <div
              class="absolute invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all duration-300 w-64 bg-white shadow-xl rounded-xl mt-2 py-2 px-4 space-y-2"
            >
              <a href="/ecommerce.html" class="block text-gray-700 hover:text-blue-500 py-2">E-commerce</a>
              <a href="/logistics" class="block text-gray-700 hover:text-blue-500 py-2">Logistics</a>
              <a href="/insurance" class="block text-gray-700 hover:text-blue-500 py-2">Insurance</a>
            </div>
          </div>
        </div>
      </nav>
    </div>

    <!-- Mobile Sidebar -->
    <div
      id="mobile-sidebar"
      class="fixed top-0 -right-full w-[80%] h-screen bg-gray-200 shadow-2xl transform transition-transform duration-300 ease-in-out z-50"
    >
      <div class="p-6">
        <div class="space-y-4">
          <a href="/index.html" class="block text-gray-800 py-2">Home</a>
          <a href="/about.html" class="block text-gray-800 py-2">About</a>
          <a href="/blog.html" class="block text-gray-800 py-2">Blog</a>

          <!-- Mobile Products Dropdown -->
          <div class="mobile-dropdown">
            <button
              class="flex items-center justify-between w-full text-gray-800 py-2"
              onclick="toggleMobileDropdown(this)"
            >
              <span>Products</span>
            </button>
            <div class="hidden pl-4 space-y-2 mt-2 bg-gray-200">
              <a href="/payouts.html" class="block text-gray-600 hover:text-blue-500 py-2">Payouts</a>
              <a href="/qr.html" class="block text-gray-600 hover:text-blue-500 py-2">QR</a>
              <a href="/upi.html" class="block text-gray-600 hover:text-blue-500 py-2">UPI</a>
              <a href="/utility.html" class="block text-gray-600 hover:text-blue-500 py-2">Utility</a>
            </div>
          </div>

          <!-- Mobile Solutions Dropdown -->
          <div class="mobile-dropdown">
            <button
              class="flex items-center justify-between w-full text-gray-800 py-2"
              onclick="toggleMobileDropdown(this)"
            >
              <span>Solutions</span>
            </button>
            <div class="hidden pl-4 space-y-2 mt-2 bg-gray-200">
              <a href="/ecommerce" class="block text-gray-600 hover:text-blue-500 py-2">E-commerce</a>
              <a href="/logistics" class="block text-gray-600 hover:text-blue-500 py-2">Logistics</a>
              <a href="/insurance" class="block text-gray-600 hover:text-blue-500 py-2">Insurance</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>


  <!-- <div id="social_sidebar" class="fixed hidden lg:block left-0 top-1/2 -translate-y-1/2 z-40">
    <div class="bg-gray-800/80 backdrop-blur-md border-r border-white/30 shadow-lg py-6 px-3 rounded-r-lg">
        <div class="flex flex-col gap-6">
            <a href="#" class="social-icon text-white hover:text-pink-500 transform hover:scale-125 transition-all duration-300">
                <i class="fab fa-instagram text-xl"></i>
            </a>
            <a href="#" class="social-icon text-white hover:text-blue-500 transform hover:scale-125 transition-all duration-300">
                <i class="fab fa-facebook text-xl"></i>
            </a>
            <a href="#" class="social-icon text-white hover:text-gray-500 transform hover:scale-125 transition-all duration-300">
                <i class="fab fa-x-twitter text-xl"></i>
            </a>
            <a href="#" class="social-icon text-white hover:text-blue-600 transform hover:scale-125 transition-all duration-300">
                <i class="fab fa-linkedin text-xl"></i>
            </a>
            <a href="#" class="social-icon text-white hover:text-red-600 transform hover:scale-125 transition-all duration-300">
                <i class="fab fa-youtube text-xl"></i>
            </a>
        </div>
    </div>
</div> -->

    <!-- Hero Section with a fixed video background and gsap scale down effect on scroll and vice versa on scale up -->

    <!-- <div class="quote"></div> -->
    <section id="hero" class="relative min-h-screen shadow-md rounded-md w-full overflow-hidden">
      <!-- Fixed Video Background -->
      <div class="fixed top-0 left-0 w-full h-full -z-10">
        <div class="absolute inset-0 bg-black/50 z-10"></div>
        <video
          class="w-full h-full object-cover"
          autoplay
          muted
          loop
          playsinline
          id="bg-video"
        >
          <source src="1.mp4" type="video/mp4" />
        </video>
      </div>

      <!-- Social Sidebar -->

      <!-- Hero Content -->
      <div
        class="relative z-20 container mx-auto px-6 min-h-screen flex items-center"
      >
        <div
          class="max-w-3xl flex flex-col justify-center items-center text-center mx-auto h-screen"
          id="hero-content"
        >
          <h1
            class="text-3xl pt-16 md:pt-0 md:text-5xl font-semibold text-white mb-6 syncopate-regular"
          >
            Next Generation
            <span
              class="block mt-2 text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-400"
            >
              Banking Solutions
            </span>
          </h1>
          <p class="text-xl text-gray-200 mb-8">
            Transform your financial journey with our cutting-edge payment
            solutions. Secure, fast, and designed for the modern world.
          </p>
          <div class="flex flex-wrap justify-center gap-4">
              
            <a href="https://rafifintech.in/login"> <button
              class="px-8 py-4 bg-white text-purple-900 rounded-full font-semibold hover:bg-opacity-90 transition-all"
            >
              Get Started
            </button>
          </a>
            <button
              class="px-8 py-4 border-2 border-white text-white rounded-full font-semibold hover:bg-white hover:text-purple-900 transition-all"
            >
              Watch Demo
            </button>
          </div>
          <div class="flex flex-row gap-8 pt-10">
            <a
              href="#"
              class="social-icon text-white hover:text-pink-500 transform hover:scale-125 transition-all duration-300"
            >
              <div
                class="w-12 h-12 flex items-center justify-center bg-blue-900 rounded-full"
              >
                <i class="fab fa-instagram text-2xl"></i>
              </div>
            </a>
            <a
              href="#"
              class="social-icon text-white hover:text-blue-500 transform hover:scale-125 transition-all duration-300"
            >
              <div
                class="w-12 h-12 flex items-center justify-center bg-blue-900 rounded-full"
              >
                <i class="fab fa-facebook text-2xl"></i>
              </div>
            </a>
            <a
              href="#"
              class="social-icon text-white hover:text-gray-500 transform hover:scale-125 transition-all duration-300"
            >
              <div
                class="w-12 h-12 flex items-center justify-center bg-blue-900 rounded-full"
              >
                <i class="fab fa-x-twitter text-2xl"></i>
              </div>
            </a>
            <a
              href="#"
              class="social-icon text-white hover:text-blue-600 transform hover:scale-125 transition-all duration-300"
            >
              <div
                class="w-12 h-12 flex items-center justify-center bg-blue-900 rounded-full"
              >
                <i class="fab fa-linkedin text-2xl"></i>
              </div>
            </a>
            <!-- <a href="#" class="social-icon text-white hover:text-red-600 transform hover:scale-125 transition-all duration-300">
                        <div class="w-12 h-12 flex items-center justify-center bg-blue-900 rounded-full">
                            <i class="fab fa-youtube text-xl"></i>
                        </div>
                    </a> -->
          </div>
        </div>
      </div>
    </section>
    <script>
      gsap.registerPlugin(ScrollTrigger);
      gsap.to("#social_sidebar", {
        scrollTrigger: {
          trigger: "#hero",
          start: "bottom top",
          end: "+=300",
          scrub: true,
        },
        y: "-50vh",
      });
    </script>
    <div class="flex items-center justify-center gap-4 my-12 mx-12">
      <div
        class="h-px w-full bg-gradient-to-r from-transparent via-purple-800 to-transparent"
      ></div>
      <div class="flex gap-1">
        <div class="w-1 h-1 rounded-full bg-purple-800"></div>
        <div class="w-1 h-1 rounded-full bg-purple-800"></div>
        <div class="w-1 h-1 rounded-full bg-purple-800"></div>
      </div>
      <div
        class="h-px w-full bg-gradient-to-r from-transparent via-purple-800 to-transparent"
      ></div>
    </div>
    <!-- Features section , with a gsap horizontal scroll effect  , showing 4-5 features of website rafifintech , and scale up effect on scroll and coming into view , and scale down when getting out of the view -->
    <div class="quote">
      Features That Just Get You!
      <div class="subtitle">Empowering Innovation for a Brighter Tomorrow</div>
    </div>

    <!-- <div class="scroll-spacer"></div> -->

    <section class="features-section hidden lg:block" id="features">
        <div class="features-wrapper">
          <!-- <h2 class="section-title">Our Premium Features</h2>
          <p class="section-subtitle">Discover how our cutting-edge banking technology can transform your financial experience</p> -->
          
          <!-- Feature 1 -->
          <div class="feature-items" id="feature-1">
            <div class="feature-container">
              <div class="feature-card">
                <div class="feature-badge">Most Popular</div>
                <div class="feature-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                  </svg>
                </div>
                <h3 class="feature-title">Secure Transactions</h3>
                <div class="feature-metric">
                  <span class="metric-value">99.9%</span>
                  <span class="metric-label">Security Rating</span>
                </div>
                <div class="feature-cta">
                  <a href="#" class="btn btn-primary">Learn More</a>
                  <a href="#" class="btn btn-secondary">See Demo</a>
                </div>
              </div>
              <div class="feature-content">
                <p class="feature-description">
                  Experience worry-free banking with our state-of-the-art encryption and multi-factor authentication system. Your security is our top priority. We employ advanced biometric validation and real-time fraud detection to ensure every transaction is protected.
                </p>
                <div class="feature-benefits">
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>Military-grade 256-bit encryption</span>
                  </div>
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>AI-powered fraud detection</span>
                  </div>
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>Zero-liability protection policy</span>
                  </div>
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>Instant transaction alerts</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Feature 2 -->
          <div class="feature-items" id="feature-2">
            <div class="feature-container">
              <div class="feature-card">
                <div class="feature-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M9.01 14H2v2h7.01v3L13 15l-3.99-4v3zm5.98-1v-3H22V8h-7.01V5L11 9l3.99 4z"/>
                  </svg>
                </div>
                <h3 class="feature-title">Instant Transfers</h3>
                <div class="feature-metric">
                  <span class="metric-value">&lt;5s</span>
                  <span class="metric-label">Transfer Time</span>
                </div>
                <div class="feature-cta">
                  <a href="#" class="btn btn-primary">Start Transfer</a>
                  <a href="#" class="btn btn-secondary">View Rates</a>
                </div>
              </div>
              <div class="feature-content">
                <p class="feature-description">
                  Send and receive money instantly, anywhere in the world. Our lightning-fast transfer system ensures your money moves at the speed of life. Whether it's paying friends for dinner or sending money to family overseas, transfers are completed in seconds, not days.
                </p>
                <div class="feature-benefits">
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>Global transfers in under 5 seconds</span>
                  </div>
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>Competitive exchange rates</span>
                  </div>
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>Minimal fees for international transactions</span>
                  </div>
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>Schedule recurring transfers</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Feature 3 -->
          <div class="feature-items" id="feature-3">
            <div class="feature-container">
              <div class="feature-card">
                <div class="feature-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                  </svg>
                </div>
                <h3 class="feature-title">Smart Analytics</h3>
                <div class="feature-metric">
                  <span class="metric-value">25%</span>
                  <span class="metric-label">Avg. Savings Increase</span>
                </div>
                <div class="feature-cta">
                  <a href="#" class="btn btn-primary">View Dashboard</a>
                  <a href="#" class="btn btn-secondary">Try Demo</a>
                </div>
              </div>
              <div class="feature-content">
                <p class="feature-description">
                  Gain valuable insights into your financial habits with our advanced analytics tools. Make informed decisions with real-time data visualization. Our AI-powered system categorizes your spending automatically and identifies patterns to help you save more.
                </p>
                <div class="feature-benefits">
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>Personalized spending insights</span>
                  </div>
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>AI-powered budget recommendations</span>
                  </div>
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>Interactive financial reports</span>
                  </div>
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>Future spending projections</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Feature 4 -->
          <div class="feature-items" id="feature-4">
            <div class="feature-container">
              <div class="feature-card">
                <div class="feature-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M19 2H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h4l3 3 3-3h4c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-6 16h-2v-2h2v2zm0-4h-2V8h2v6z"/>
                  </svg>
                </div>
                <h3 class="feature-title">24/7 Support</h3>
                <div class="feature-metric">
                  <span class="metric-value">&lt;30s</span>
                  <span class="metric-label">Response Time</span>
                </div>
                <div class="feature-cta">
                  <a href="#" class="btn btn-primary">Contact Support</a>
                  <a href="#" class="btn btn-secondary">FAQ</a>
                </div>
              </div>
              <div class="feature-content">
                <p class="feature-description">
                  Our dedicated support team is always here to help. Get assistance anytime, anywhere through our multiple support channels. Connect via live chat, video call, or phone with minimal wait times.
                </p>
                <div class="feature-benefits">
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>24/7 multi-channel support</span>
                  </div>
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>AI-assisted instant answers</span>
                  </div>
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>Dedicated financial advisors</span>
                  </div>
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>Multi-language support</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Feature 5 -->
          <div class="feature-items" id="feature-5">
            <div class="feature-container">
              <div class="feature-card">
                <div class="feature-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M21 18v1c0 1.1-.9 2-2 2H5c-1.11 0-2-.9-2-2V5c0-1.1.89-2 2-2h14c1.1 0 2 .9 2 2v1h-9c-1.11 0-2 .9-2 2v8c0 1.1.89 2 2 2h9zm-9-2h10V8H12v8zm4-2.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                  </svg>
                </div>
                <h3 class="feature-title">Customizable Alerts</h3>
                <div class="feature-metric">
                  <span class="metric-value">100+</span>
                  <span class="metric-label">Alert Options</span>
                </div>
                <div class="feature-cta">
                  <a href="#" class="btn btn-primary">Setup Alerts</a>
                  <a href="#" class="btn btn-secondary">Preferences</a>
                </div>
              </div>
              <div class="feature-content">
                <p class="feature-description">
                  Stay in control with personalized notifications that matter to you. Set custom alerts for deposits, withdrawals, bill payments, and unusual account activity. Choose how you want to be notified—SMS, email, or push notifications.
                </p>
                <div class="feature-benefits">
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>Real-time transaction notifications</span>
                  </div>
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>Threshold-based spending alerts</span>
                  </div>
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>Suspicious activity warnings</span>
                  </div>
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>Bill payment reminders</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Feature 6 (NEW) -->
          <div class="feature-items" id="feature-6">
            <div class="feature-container">
              <div class="feature-card">
                <div class="feature-badge">New</div>
                <div class="feature-icon">
                  <i class="fas fa-piggy-bank" style="font-size: 50px;"></i>
                </div>
                <h3 class="feature-title">Smart Savings</h3>
                <div class="feature-metric">
                  <span class="metric-value">3.5%</span>
                  <span class="metric-label">Interest Rate</span>
                </div>
                <div class="feature-cta">
                  <a href="#" class="btn btn-primary">Start Saving</a>
                  <a href="#" class="btn btn-secondary">Calculator</a>
                </div>
              </div>
              <div class="feature-content">
                <p class="feature-description">
                  Maximize your savings with our intelligent automated system. Our Smart Savings feature analyzes your income and spending patterns to automatically set aside the perfect amount each month without affecting your lifestyle.
                </p>
                <div class="feature-benefits">
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>AI-driven automatic savings</span>
                  </div>
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>Round-up purchase deposits</span>
                  </div>
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>High-interest savings accounts</span>
                  </div>
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>Customizable savings goals</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Feature 7 (NEW) -->
          <div class="feature-items" id="feature-7">
            <div class="feature-container">
              <div class="feature-card">
                <div class="feature-icon">
                  <i class="fas fa-chart-line" style="font-size: 50px;"></i>
                </div>
                <h3 class="feature-title">Investment Hub</h3>
                <div class="feature-metric">
                  <span class="metric-value">12.4%</span>
                  <span class="metric-label">Avg. Annual Return</span>
                </div>
                <div class="feature-cta">
                  <a href="#" class="btn btn-primary">Invest Now</a>
                  <a href="#" class="btn btn-secondary">Portfolio</a>
                </div>
              </div>
              <div class="feature-content">
                <p class="feature-description">
                  Take control of your financial future with our comprehensive investment platform. From stocks and bonds to ETFs and cryptocurrency, our Investment Hub offers diverse options with personalized recommendations based on your risk profile.
                </p>
                <div class="feature-benefits">
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>Commission-free trading</span>
                  </div>
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>AI-powered investment recommendations</span>
                  </div>
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>Real-time market tracking</span>
                  </div>
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>Automated portfolio rebalancing</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Feature 8 (NEW) -->
          <div class="feature-items" id="feature-8">
            <div class="feature-container">
              <div class="feature-card">
                <div class="feature-icon">
                  <i class="fas fa-credit-card" style="font-size: 50px;"></i>
                </div>
                <h3 class="feature-title">Rewards Program</h3>
                <div class="feature-metric">
                  <span class="metric-value">5%</span>
                  <span class="metric-label">Cashback Rate</span>
                </div>
                <div class="feature-cta">
                  <a href="#" class="btn btn-primary">Join Program</a>
                  <a href="#" class="btn btn-secondary">View Benefits</a>
                </div>
              </div>
              <div class="feature-content">
                <p class="feature-description">
                  Earn while you spend with our innovative rewards program. Our tiered system offers increasing benefits based on your banking activity, including cashback on purchases, travel perks, premium experiences, and exclusive partner discounts.
                </p>
                <div class="feature-benefits">
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>Up to 5% cashback on purchases</span>
                  </div>
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>Travel insurance and lounge access</span>
                  </div>
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>Exclusive partner offers</span>
                  </div>
                  <div class="feature-benefit-item">
                    <i class="fas fa-check-circle benefit-icon"></i>
                    <span>Tiered benefits system</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="navigation opacity-0">
          <button class="feature-nav active" data-index="0"></button>
          <button class="feature-nav" data-index="1"></button>
          <button class="feature-nav" data-index="2"></button>
          <button class="feature-nav" data-index="3"></button>
          <button class="feature-nav" data-index="4"></button>
          <button class="feature-nav" data-index="5"></button>
          <button class="feature-nav" data-index="6"></button>
          <button class="feature-nav" data-index="7"></button>
        </div>
        
      </section>
<!-- Features Section -->
<section id="features" class="features-sections lg:hidden" style=" padding: 4rem 0;">
 
    
   
   
    
 <!-- Feature 2 -->
    <div class="feature-item" id="feature-2" style="margin-bottom: 2rem;">
      <div class="feature-container" style="display: flex; flex-wrap: wrap; align-items: center;">
        <div class="feature-card" style="flex: 1; min-width: 280px; padding: 1.5rem; background: #fff; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); display: flex; align-items: center;">
          <div class="feature-icon" style="width: 60px; height: 60px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; background-color: #e8f5e9; border-radius: 50%; margin-right: 1rem;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="fill: #388e3c; width: 36px; height: 36px;">
              <path d="M9.01 14H2v2h7.01v3L13 15l-3.99-4v3zm5.98-1v-3H22V8h-7.01V5L11 9l3.99 4z"/>
            </svg>
          </div>
          <h3 class="feature-title" style="font-size: 1.75rem; color: #222; margin: 0;">Instant Money Transfers</h3>
        </div>
        <div class="feature-content" style="flex: 2; min-width: 280px; padding: 1.5rem;">
          <p class="feature-description" style="font-size: 1.125rem; color: #333; line-height: 1.6;">
            Enjoy lightning-fast transfers with minimal fees. Whether sending funds locally or internationally, our system ensures your money reaches its destination within seconds.
          </p>
        </div>
      </div>
    </div>
    
    <!-- Feature 3 -->
    <div class="feature-item" id="feature-3" style="margin-bottom: 2rem;">
      <div class="feature-container" style="display: flex; flex-wrap: wrap; align-items: center;">
        <div class="feature-card" style="flex: 1; min-width: 280px; padding: 1.5rem; background: #fff; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); display: flex; align-items: center;">
          <div class="feature-icon" style="width: 60px; height: 60px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; background-color: #ede7f6; border-radius: 50%; margin-right: 1rem;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="fill: #5e35b1; width: 36px; height: 36px;">
              <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
            </svg>
          </div>
          <h3 class="feature-title" style="font-size: 1.75rem; color: #222; margin: 0;">Smart Financial Analytics</h3>
        </div>
        <div class="feature-content" style="flex: 2; min-width: 280px; padding: 1.5rem;">
          <p class="feature-description" style="font-size: 1.125rem; color: #333; line-height: 1.6;">
            Unlock detailed insights into your spending and saving habits with our intuitive analytics dashboard. Make smarter decisions with real-time data and personalized recommendations.
          </p>
        </div>
      </div>
    </div>
    
    <!-- Feature 4 -->
    <div class="feature-item" id="feature-4" style="margin-bottom: 2rem;">
      <div class="feature-container" style="display: flex; flex-wrap: wrap; align-items: center;">
        <div class="feature-card" style="flex: 1; min-width: 280px; padding: 1.5rem; background: #fff; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); display: flex; align-items: center;">
          <div class="feature-icon" style="width: 60px; height: 60px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; background-color: #fff3e0; border-radius: 50%; margin-right: 1rem;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="fill: #f57c00; width: 36px; height: 36px;">
              <path d="M19 2H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h4l3 3 3-3h4c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-9 16h10V8H12v8zm4-2.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
            </svg>
          </div>
          <h3 class="feature-title" style="font-size: 1.75rem; color: #222; margin: 0;">Around-the-Clock Support</h3>
        </div>
        <div class="feature-content" style="flex: 2; min-width: 280px; padding: 1.5rem;">
          <p class="feature-description" style="font-size: 1.125rem; color: #333; line-height: 1.6;">
            Our expert support team is available 24/7 to help you with any issue. Get assistance via live chat, phone, or email whenever you need it.
          </p>
        </div>
      </div>
    </div>
    
    <!-- Navigation Buttons -->
    <!-- <div class="navigation" style="text-align: center; margin-top: 2rem;">
      <button class="feature-nav active" data-index="0" style="width: 12px; height: 12px; margin: 0 4px; border-radius: 50%; background-color: #222; border: none;"></button>
      <button class="feature-nav" data-index="1" style="width: 12px; height: 12px; margin: 0 4px; border-radius: 50%; background-color: #888; border: none;"></button>
      <button class="feature-nav" data-index="2" style="width: 12px; height: 12px; margin: 0 4px; border-radius: 50%; background-color: #888; border: none;"></button>
      <button class="feature-nav" data-index="3" style="width: 12px; height: 12px; margin: 0 4px; border-radius: 50%; background-color: #888; border: none;"></button>
    </div> -->
  </div>
</section>


    <div class="flex items-center justify-center gap-4 my-12 mx-12">
      <div
        class="h-px w-full bg-gradient-to-r from-transparent via-purple-800 to-transparent"
      ></div>
      <div class="flex gap-1">
        <div class="w-1 h-1 rounded-full bg-purple-800"></div>
        <div class="w-1 h-1 rounded-full bg-purple-800"></div>
        <div class="w-1 h-1 rounded-full bg-purple-800"></div>
      </div>
      <div
        class="h-px w-full bg-gradient-to-r from-transparent via-purple-800 to-transparent"
      ></div>
    </div>
    <!-- Payin and payout section , with a gsap horizontal scroll effect  , showing totally teaching the way to payin and payout in website rafifintech , and scale up effect on scroll and coming into view , and scale down when getting out of the view -->
    <div class="quote">Wondering how it works? It's simple!</div>
    <div class="subtitle">Follow these easy steps to get started.</div>

    
    


    <section id="payin-payout" class="relative w-full overflow-hidden  py-16 lg:px-16 ">
      <!-- Header -->
      <!-- <div class="container mx-auto px-4 text-center mb-12" data-aos="fade-up" data-aos-duration="800">
        <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-primary-700 to-secondary-500">Transaction Workflow</h2>
        <p class="text-slate-600 max-w-xl mx-auto text-base md:text-lg">Seamless financial management at your fingertips</p>
      </div> -->
      
      <!-- Tab Navigation -->
      <div class="container mx-auto px-4 mb-8">
        <div class="flex flex-wrap justify-center gap-2 md:gap-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
          <button class="tab-btn active py-2 px-5 md:px-8 rounded-full text-sm md:text-base font-semibold transition-all duration-300 bg-primary-600 text-white shadow-md hover:shadow-lg" data-tab="payin">
            Pay In
          </button>
          <button class="tab-btn py-2 px-5 md:px-8 rounded-full text-sm md:text-base font-semibold transition-all duration-300 bg-white text-primary-700 border border-primary-200 hover:bg-primary-50" data-tab="payout">
            Pay Out
          </button>
          <button class="tab-btn py-2 px-5 md:px-8 rounded-full text-sm md:text-base font-semibold transition-all duration-300 bg-white text-primary-700 border border-primary-200 hover:bg-primary-50" data-tab="security">
            Security
          </button>
        </div>
      </div>
      
      <!-- Content Sections -->
      <div class="container mx-auto px-4">
        <!-- Pay In Section -->
        <div id="payin-content" class="tab-content active">
          <div class="bg-white rounded-sm shadow-md border border-slate-100 overflow-hidden">
            <!-- Card Header -->
            <div class=" p-6 md:p-8">
              <h3 class="text-2xl md:text-3xl font-semibold text-gray-900 flex items-center text-gray-900" data-aos="fade-right">
                <span class="bg-gray-800/20 w-10 h-10 text-gray-900 rounded-full flex items-center justify-center mr-3">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                </span>
                Pay In Process
              </h3>
            </div>
            
            <!-- Card Content -->
            <div class="p-6 md:p-8">
              <!-- Timeline steps -->
              <div class="space-y-6 md:space-y-0 md:grid md:grid-cols-2 md:gap-6 lg:gap-8">
                <!-- Step 01 -->
                <div class="relative pl-8 md:pl-0 md:pb-0" data-aos="fade-up" data-aos-delay="100">
                  <!-- Mobile Timeline Line -->
                  <div class="absolute top-0 left-0 h-full w-0.5 bg-primary-100 md:hidden">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-6 h-6 rounded-full border-2 border-primary-500 bg-white"></div>
                  </div>
                  
                  <!-- Card -->
                  <div class="bg-white rounded-xl shadow-md hover:shadow-lg border border-slate-100 p-5 transition-all duration-300 hover:-translate-y-1 h-full">
                    <div class="flex md:flex-col lg:flex-row items-start gap-4 mb-3">
                      <div class="flex-shrink-0 hidden md:flex w-12 h-12 rounded-full bg-primary-50 text-primary-600 items-center justify-center text-xl font-bold">01</div>
                      <h4 class="text-lg md:text-xl font-bold text-primary-800">Select Amount</h4>
                    </div>
                    <p class="text-slate-600">Enter the amount you want to deposit into your account with just a few taps.</p>
                  </div>
                </div>
                
                <!-- Step 02 -->
                <div class="relative pl-8 md:pl-0 md:pb-0" data-aos="fade-up" data-aos-delay="200">
                  <!-- Mobile Timeline Line -->
                  <div class="absolute top-0 left-0 h-full w-0.5 bg-primary-100 md:hidden">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-6 h-6 rounded-full border-2 border-primary-500 bg-white"></div>
                  </div>
                  
                  <!-- Card -->
                  <div class="bg-white rounded-xl shadow-md hover:shadow-lg border border-slate-100 p-5 transition-all duration-300 hover:-translate-y-1 h-full">
                    <div class="flex md:flex-col lg:flex-row items-start gap-4 mb-3">
                      <div class="flex-shrink-0 hidden md:flex w-12 h-12 rounded-full bg-primary-50 text-primary-600 items-center justify-center text-xl font-bold">02</div>
                      <h4 class="text-lg md:text-xl font-bold text-primary-800">Choose Method</h4>
                    </div>
                    <p class="text-slate-600">Select your preferred payment method: Card, Bank Transfer, or Digital Wallet.</p>
                  </div>
                </div>
                
                <!-- Step 03 -->
                <div class="relative pl-8 md:pl-0 md:pb-0" data-aos="fade-up" data-aos-delay="300">
                  <!-- Mobile Timeline Line -->
                  <div class="absolute top-0 left-0 h-full w-0.5 bg-primary-100 md:hidden">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-6 h-6 rounded-full border-2 border-primary-500 bg-white"></div>
                  </div>
                  
                  <!-- Card -->
                  <div class="bg-white rounded-xl shadow-md hover:shadow-lg border border-slate-100 p-5 transition-all duration-300 hover:-translate-y-1 h-full">
                    <div class="flex md:flex-col lg:flex-row items-start gap-4 mb-3">
                      <div class="flex-shrink-0 hidden md:flex w-12 h-12 rounded-full bg-primary-50 text-primary-600 items-center justify-center text-xl font-bold">03</div>
                      <h4 class="text-lg md:text-xl font-bold text-primary-800">Verify Details</h4>
                    </div>
                    <p class="text-slate-600">Review your transaction details and confirm the payment with a single tap.</p>
                  </div>
                </div>
                
                <!-- Step 04 -->
                <div class="relative pl-8 md:pl-0 md:pb-0" data-aos="fade-up" data-aos-delay="400">
                  <!-- Mobile Timeline Line -->
                  <div class="absolute top-0 left-0 h-full w-0.5 bg-primary-100 md:hidden">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-6 h-6 rounded-full border-2 border-primary-500 bg-white"></div>
                  </div>
                  
                  <!-- Card -->
                  <div class="bg-white rounded-xl shadow-md hover:shadow-lg border border-slate-100 p-5 transition-all duration-300 hover:-translate-y-1 h-full">
                    <div class="flex md:flex-col lg:flex-row items-start gap-4 mb-3">
                      <div class="flex-shrink-0 hidden md:flex w-12 h-12 rounded-full bg-primary-50 text-primary-600 items-center justify-center text-xl font-bold">04</div>
                      <h4 class="text-lg md:text-xl font-bold text-primary-800">Instant Credit</h4>
                    </div>
                    <p class="text-slate-600">Your account is credited instantly upon successful payment, no waiting required.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Pay Out Section -->
        <div id="payout-content" class="tab-content hidden">
          <div class="bg-white rounded-sm shadow-md border border-slate-100 overflow-hidden">
            <!-- Card Header -->
            <div class=" p-6 md:p-8">
                <h3 class="text-2xl md:text-3xl font-semibold text-gray-900 flex items-center text-gray-900"  data-aos="fade-right">
                <span class="bg-green-900/20 w-10 h-10 rounded-full flex items-center justify-center mr-3">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 text-green-900 w-5 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </span>
                Pay Out Process
              </h3>
            </div>
            
            <!-- Card Content -->
            <div class="p-6 md:p-8">
              <!-- Timeline steps -->
              <div class="space-y-6 md:space-y-0 md:grid md:grid-cols-2 md:gap-6 lg:gap-8">
                <!-- Step 01 -->
                <div class="relative pl-8 md:pl-0 md:pb-0" data-aos="fade-up" data-aos-delay="100">
                  <!-- Mobile Timeline Line -->
                  <div class="absolute top-0 left-0 h-full w-0.5 bg-secondary-100 md:hidden">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-6 h-6 rounded-full border-2 border-secondary-500 bg-white"></div>
                  </div>
                  
                  <!-- Card -->
                  <div class="bg-white rounded-xl shadow-md hover:shadow-lg border border-slate-100 p-5 transition-all duration-300 hover:-translate-y-1 h-full">
                    <div class="flex md:flex-col lg:flex-row items-start gap-4 mb-3">
                      <div class="flex-shrink-0 hidden md:flex w-12 h-12 rounded-full bg-secondary-50 text-secondary-600 items-center justify-center text-xl font-bold">01</div>
                      <h4 class="text-lg md:text-xl font-bold text-primary-800">Add Recipient</h4>
                    </div>
                    <p class="text-slate-600">Enter recipient's bank details or select from saved beneficiaries with ease.</p>
                  </div>
                </div>
                
                <!-- Step 02 -->
                <div class="relative pl-8 md:pl-0 md:pb-0" data-aos="fade-up" data-aos-delay="200">
                  <!-- Mobile Timeline Line -->
                  <div class="absolute top-0 left-0 h-full w-0.5 bg-secondary-100 md:hidden">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-6 h-6 rounded-full border-2 border-secondary-500 bg-white"></div>
                  </div>
                  
                  <!-- Card -->
                  <div class="bg-white rounded-xl shadow-md hover:shadow-lg border border-slate-100 p-5 transition-all duration-300 hover:-translate-y-1 h-full">
                    <div class="flex md:flex-col lg:flex-row items-start gap-4 mb-3">
                      <div class="flex-shrink-0 hidden md:flex w-12 h-12 rounded-full bg-secondary-50 text-secondary-600 items-center justify-center text-xl font-bold">02</div>
                      <h4 class="text-lg md:text-xl font-bold text-primary-800">Enter Amount</h4>
                    </div>
                    <p class="text-slate-600">Specify the amount you want to transfer to the recipient using our intuitive interface.</p>
                  </div>
                </div>
                
                <!-- Step 03 -->
                <div class="relative pl-8 md:pl-0 md:pb-0" data-aos="fade-up" data-aos-delay="300">
                  <!-- Mobile Timeline Line -->
                  <div class="absolute top-0 left-0 h-full w-0.5 bg-secondary-100 md:hidden">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-6 h-6 rounded-full border-2 border-secondary-500 bg-white"></div>
                  </div>
                  
                  <!-- Card -->
                  <div class="bg-white rounded-xl shadow-md hover:shadow-lg border border-slate-100 p-5 transition-all duration-300 hover:-translate-y-1 h-full">
                    <div class="flex md:flex-col lg:flex-row items-start gap-4 mb-3">
                      <div class="flex-shrink-0 hidden md:flex w-12 h-12 rounded-full bg-secondary-50 text-secondary-600 items-center justify-center text-xl font-bold">03</div>
                      <h4 class="text-lg md:text-xl font-bold text-primary-800">Confirm Transfer</h4>
                    </div>
                    <p class="text-slate-600">Review the transfer details and authorize the transaction with secure verification.</p>
                  </div>
                </div>
                
                <!-- Step 04 -->
                <div class="relative pl-8 md:pl-0 md:pb-0" data-aos="fade-up" data-aos-delay="400">
                  <!-- Mobile Timeline Line -->
                  <div class="absolute top-0 left-0 h-full w-0.5 bg-secondary-100 md:hidden">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-6 h-6 rounded-full border-2 border-secondary-500 bg-white"></div>
                  </div>
                  
                  <!-- Card -->
                  <div class="bg-white rounded-xl shadow-md hover:shadow-lg border border-slate-100 p-5 transition-all duration-300 hover:-translate-y-1 h-full">
                    <div class="flex md:flex-col lg:flex-row items-start gap-4 mb-3">
                      <div class="flex-shrink-0 hidden md:flex w-12 h-12 rounded-full bg-secondary-50 text-secondary-600 items-center justify-center text-xl font-bold">04</div>
                      <h4 class="text-lg md:text-xl font-bold text-primary-800">Track Status</h4>
                    </div>
                    <p class="text-slate-600">Monitor your transfer status in real-time through our mobile-friendly platform.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Security Section -->
        <div id="security-content" class="tab-content hidden">
          <div class="bg-white rounded-sm shadow-md border border-slate-100 overflow-hidden">
            <!-- Card Header -->
            <div class=" p-6 md:p-8">
                <h3 class="text-2xl md:text-3xl font-semibold text-gray-900 flex items-center text-gray-900"   data-aos="fade-right">
                <span class="bg-red-900/20 w-10 h-10 rounded-full flex items-center justify-center mr-3">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                  </svg>
                </span>
                Security & Support
              </h3>
            </div>
            
            <!-- Card Content -->
            <div class="p-6 md:p-8">
              <div class="space-y-6 md:space-y-0 md:grid md:grid-cols-2 md:gap-6 lg:gap-8">
                <!-- Feature 01 -->
                <div class="relative" data-aos="fade-up" data-aos-delay="100">              
                  <!-- Card -->
                  <div class="bg-white rounded-xl shadow-md hover:shadow-lg border border-slate-100 p-5 transition-all duration-300 hover:-translate-y-1 h-full">
                    <div class="w-14 h-14 flex items-center justify-center rounded-full bg-slate-100 text-slate-700 mb-4">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                      </svg>
                    </div>
                    <h4 class="text-lg md:text-xl font-bold text-primary-800 mb-2">Transaction Security</h4>
                    <p class="text-slate-600">All transactions are protected with end-to-end encryption and multi-factor authentication on every device.</p>
                  </div>
                </div>
                
                <!-- Feature 02 -->
                <div class="relative" data-aos="fade-up" data-aos-delay="200">
                  <!-- Card -->
                  <div class="bg-white rounded-xl shadow-md hover:shadow-lg border border-slate-100 p-5 transition-all duration-300 hover:-translate-y-1 h-full">
                    <div class="w-14 h-14 flex items-center justify-center rounded-full bg-slate-100 text-slate-700 mb-4">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                      </svg>
                    </div>
                    <h4 class="text-lg md:text-xl font-bold text-primary-800 mb-2">24/7 Support</h4>
                    <p class="text-slate-600">Our dedicated team is always available to assist you with any transaction issues via chat, call, or email.</p>
                  </div>
                </div>
                
                <!-- Feature 03 -->
                <div class="relative" data-aos="fade-up" data-aos-delay="300">
                  <!-- Card -->
                  <div class="bg-white rounded-xl shadow-md hover:shadow-lg border border-slate-100 p-5 transition-all duration-300 hover:-translate-y-1 h-full">
                    <div class="w-14 h-14 flex items-center justify-center rounded-full bg-slate-100 text-slate-700 mb-4">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    </div>
                    <h4 class="text-lg md:text-xl font-bold text-primary-800 mb-2">Transaction History</h4>
                    <p class="text-slate-600">Access detailed transaction history and generate reports anytime on your mobile or tablet device.</p>
                  </div>
                </div>
                
                <!-- Feature 04 -->
                <div class="relative" data-aos="fade-up" data-aos-delay="400">
                  <!-- Card -->
                  <div class="bg-white rounded-xl shadow-md hover:shadow-lg border border-slate-100 p-5 transition-all duration-300 hover:-translate-y-1 h-full">
                    <div class="w-14 h-14 flex items-center justify-center rounded-full bg-slate-100 text-slate-700 mb-4">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                      </svg>
                    </div>
                    <h4 class="text-lg md:text-xl font-bold text-primary-800 mb-2">Secure Storage</h4>
                    <p class="text-slate-600">Your financial data is stored securely with bank-grade encryption across all your devices.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Floating Action Button (Mobile Only) -->
      <div class="fixed bottom-6 right-6 md:hidden z-50">
        <button class="bg-primary-600 text-white w-14 h-14 rounded-full shadow-lg flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </button>
      </div>
      
      <!-- Decorative Elements -->
      <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-24 -left-24 w-64 h-64 rounded-full bg-primary-500/5 blur-3xl"></div>
        <div class="absolute top-1/3 -right-32 w-80 h-80 rounded-full bg-secondary-500/5 blur-3xl"></div>
        <div class="absolute -bottom-48 left-1/4 w-96 h-96 rounded-full bg-primary-600/5 blur-3xl"></div>
      </div>
      
      <!-- AOS and Tab Functionality JavaScript -->
      <script>
        // Initialize AOS
        document.addEventListener('DOMContentLoaded', function() {
          // Initialize AOS if available
          if (typeof AOS !== 'undefined') {
            AOS.init({
              duration: 800,
              easing: 'ease-in-out',
              once: true
            });
          }
          
          // Tab Functionality
          const tabBtns = document.querySelectorAll('.tab-btn');
          const tabContents = document.querySelectorAll('.tab-content');
          
          tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
              // Remove active class from all buttons and contents
              tabBtns.forEach(b => b.classList.remove('active', 'bg-primary-600', 'text-white', 'shadow-md'));
              tabBtns.forEach(b => b.classList.add('bg-white', 'text-primary-700', 'border', 'border-primary-200'));
              tabContents.forEach(content => content.classList.add('hidden'));
              
              // Add active class to clicked button
              btn.classList.add('active', 'bg-primary-600', 'text-white', 'shadow-md');
              btn.classList.remove('bg-white', 'text-primary-700', 'border', 'border-primary-200');
              
              // Show corresponding content
              const tabId = btn.getAttribute('data-tab');
              document.getElementById(`${tabId}-content`).classList.remove('hidden');
              
              // Refresh AOS
              if (typeof AOS !== 'undefined') {
                AOS.refresh();
              }
            });
          });
        });
      </script>
    </section>

    <div class="flex items-center justify-center gap-4 my-12 mx-12">
      <div
        class="h-px w-full bg-gradient-to-r from-transparent via-purple-800 to-transparent"
      ></div>
      <div class="flex gap-1">
        <div class="w-1 h-1 rounded-full bg-purple-800"></div>
        <div class="w-1 h-1 rounded-full bg-purple-800"></div>
        <div class="w-1 h-1 rounded-full bg-purple-800"></div>
      </div>
      <div
        class="h-px w-full bg-gradient-to-r from-transparent via-purple-800 to-transparent"
      ></div>
    </div>

    <!-- contact section , with a lottie animation , with very animazing and attrative input froms to contact us , and scale up effect on scroll and coming into view , and scale down when getting out of the view -->
    <div class="quote" id="contact_quote">
      Lost? Confused? Just hit us up!
      <div class="subtitle text-2xl">
        Got questions? Shoot us a message—we promise we don’t bite (usually)!
      </div>
    </div>

    <section
      id="contact"
      class="min-h-screen py-16 px-4 relative overflow-hidden"
    >
      <!-- Enhanced background with subtle patterns -->
      <div
        class="absolute inset-0 bg-gradient-to-r from-white/40 via-purple-50/30 to-white/40 backdrop-blur-sm -z-10"
      >
        <div
          class="absolute inset-0 opacity-30"
          style="
            background-image: radial-gradient(
              circle at 1px 1px,
              rgba(0, 0, 0, 0.1) 1px,
              transparent 0
            );
            background-size: 40px 40px;
          "
        ></div>
      </div>

      <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          <!-- Animation container with enhanced styling -->
          <div class="h-96 relative" id="contactAnimation">
            <div
              class="absolute inset-0 bg-gradient-to-tr from-purple-100/50 to-transparent rounded-2xl"
            ></div>
          </div>

          <!-- Enhanced contact form with improved visual hierarchy -->
          <div
            class="bg-white/90 backdrop-blur-md rounded-2xl p-8 shadow-lg transform transition-all duration-300 hover:scale-[1.01] border border-gray-100"
          >
            <h3 class="text-2xl font-semibold text-gray-800 mb-6 relative">
              Get in Touch
              <span
                class="absolute bottom-0 left-0 w-20 h-1 bg-gradient-to-r from-purple-600 to-indigo-600"
              ></span>
            </h3>

            <form class="space-y-6">
              <div class="space-y-2">
                <label
                  class="text-sm font-medium text-gray-700 block"
                  for="name"
                  >Name</label
                >
                <input
                  type="text"
                  id="name"
                  class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 bg-white/80"
                  placeholder="Your name"
                />
              </div>

              <div class="space-y-2">
                <label
                  class="text-sm font-medium text-gray-700 block"
                  for="email"
                  >Email</label
                >
                <input
                  type="email"
                  id="email"
                  class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 bg-white/80"
                  placeholder="your@email.com"
                />
              </div>

              <div class="space-y-2">
                <label
                  class="text-sm font-medium text-gray-700 block"
                  for="subject"
                  >Subject</label
                >
                <select
                  id="subject"
                  class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 bg-white/80"
                >
                  <option value="">Select a subject</option>
                  <option value="general">General Inquiry</option>
                  <option value="support">Technical Support</option>
                  <option value="billing">Billing Question</option>
                  <option value="partnership">Partnership Opportunity</option>
                </select>
              </div>

              <div class="space-y-2">
                <label
                  class="text-sm font-medium text-gray-700 block"
                  for="message"
                  >Message</label
                >
                <textarea
                  id="message"
                  rows="4"
                  class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 bg-white/80"
                  placeholder="Your message here..."
                ></textarea>
              </div>

              <button
                type="submit"
                class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-medium py-3 px-6 rounded-lg hover:opacity-95 transform transition-all duration-300 hover:scale-[1.02] focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 shadow-md hover:shadow-lg"
              >
                Send Message
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>

    <div class="flex items-center justify-center gap-4 my-12 mx-12">
      <div
        class="h-px w-full bg-gradient-to-r from-transparent via-purple-800 to-transparent"
      ></div>
      <div class="flex gap-1">
        <div class="w-1 h-1 rounded-full bg-purple-800"></div>
        <div class="w-1 h-1 rounded-full bg-purple-800"></div>
        <div class="w-1 h-1 rounded-full bg-purple-800"></div>
      </div>
      <div
        class="h-px w-full bg-gradient-to-r from-transparent via-purple-800 to-transparent"
      ></div>
    </div>

    <section
      id="thanks"
      class="relative h-screen overflow-hidden flex flex-col justify-center items-center text-center px-4"
    >
      <div
        id="thanks-text"
        class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-gray-800 opacity-90"
      >
        <span>Thanks for visiting</span>
        <span class="ml-1 sm:ml-2 block sm:inline">RafiFintech</span>
      </div>
      <div
        class="absolute bottom-20 sm:bottom-40 left-0 right-0 text-center text-base sm:text-lg text-blue-900 opacity-70"
      >
        We're glad you stopped by!
      </div>
    </section>

    <div class="flex items-center justify-center gap-4 my-12 mx-12">
      <div
        class="h-px w-full bg-gradient-to-r from-transparent via-purple-800 to-transparent"
      ></div>
      <div class="flex gap-1">
        <div class="w-1 h-1 rounded-full bg-purple-800"></div>
        <div class="w-1 h-1 rounded-full bg-purple-800"></div>
        <div class="w-1 h-1 rounded-full bg-purple-800"></div>
      </div>
      <div
        class="h-px w-full bg-gradient-to-r from-transparent via-purple-800 to-transparent"
      ></div>
    </div>

    <!-- Footer section , with a lottie animation , with very animazing and attrative design and some cool animation effect on the rafifintech logo  , and scale up effect on scroll and coming into view , and scale down when getting out of the view -->
    <section id="footer" class="relative pt-20 pb-10 bg-light-gray">
        <!-- Lottie animation container -->
        <div
          id="footerAnimation"
          class="absolute top-0 left-1/2 transform -translate-x-1/2 w-40 h-40 -mt-20"
        ></div>
  
        <!-- Main footer content -->
        <div class="max-w-7xl mx-auto px-4">
          <!-- Footer grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
            <!-- Company info -->
            <div class="space-y-6">
              <div class="flex items-center space-x-2">
                <span class="text-3xl font-bold text-purple-900 font-semibold syncopate-regular">Rafifintech</span>
              </div>
              <p class="text-gray-700 max-w-xs">
                Empowering your financial journey with innovative solutions and secure transactions.
              </p>
              <div class="flex space-x-4">
                <a href="#" class="text-gray-700 hover:text-blue-900 transition-colors"><i class="fab fa-twitter text-xl"></i></a>
                <a href="#" class="text-gray-700 hover:text-blue-900 transition-colors"><i class="fab fa-facebook text-xl"></i></a>
                <a href="#" class="text-gray-700 hover:text-blue-900 transition-colors"><i class="fab fa-instagram text-xl"></i></a>
                <a href="#" class="text-gray-700 hover:text-blue-900 transition-colors"><i class="fab fa-linkedin text-xl"></i></a>
              </div>
            </div>
  
            <!-- Quick Links -->
            <div>
              <h3 class="text-blue-900 font-semibold mb-6">Quick Links</h3>
              <ul class="space-y-4">
                <li><a href="about.html" class="text-gray-700 hover:text-blue-900 transition-colors">About Us</a></li>
                <li><a href="blog.html" class="text-gray-700 hover:text-blue-900 transition-colors">Blog</a></li>
                <li><a href="terms.html" class="text-gray-700 hover:text-blue-900 transition-colors">Terms of Service</a></li>
                <li><a href="privacy.html" class="text-gray-700 hover:text-blue-900 transition-colors">Privacy Policy</a></li>
              </ul>
            </div>
  
            <!-- Services -->
            <div>
              <h3 class="text-blue-900 font-semibold mb-6">Services</h3>
              <ul class="space-y-4">
                <li><a href="payout.html" class="text-gray-700 hover:text-blue-900 transition-colors">Pay Out</a></li>
                <li><a href="qr.html" class="text-gray-700 hover:text-blue-900 transition-colors">QR Payments</a></li>
                <li><a href="upi.html" class="text-gray-700 hover:text-blue-900 transition-colors">UPI Payments</a></li>
                <li><a href="utility.html" class="text-gray-700 hover:text-blue-900 transition-colors">Utility Payments</a></li>
              </ul>
            </div>
  
            <!-- Contact Info -->
            <div>
              <h3 class="text-blue-900 font-semibold mb-6">Contact Info</h3>
              <ul class="space-y-4">
                <li class="flex items-center space-x-3 text-gray-700">
                  <i class="fas fa-map-marker-alt text-blue-900"></i>
                  <span>RAFI FINTECH PRIVATE LIMITED

310, 3rd Floor, Royal Gold, Opp. City Center, Y.N. Road, Indore (MP) 452003</span>
                </li>
                <li class="flex items-center space-x-3 text-gray-700">
                  <i class="fas fa-phone text-blue-900"></i>
                  <span>+91 731-3684745</span>
                </li>
                <li class="flex items-center space-x-3 text-gray-700">
                  <i class="fas fa-envelope text-blue-900"></i>
                  <span>info@rafifintech.com
</span>
                </li>
              </ul>
            </div>
          </div>
  
          <!-- Bottom bar -->
          <div class="border-t border-gray-300 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
              <p class="text-gray-700 text-sm">© 2024 Rafifintech. All rights reserved.</p>
              <div class="flex space-x-6">
                <a href="privacy.html" class="text-gray-700 hover:text-blue-900 text-sm transition-colors">Privacy Policy</a>
                <a href="terms.html" class="text-gray-700 hover:text-blue-900 text-sm transition-colors">Terms of Service</a>
              </div>
            </div>
          </div>
        </div>
      </section>
  
      <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.0/ScrollToPlugin.min.js"></script>
  
    <script>
      
      // GSAP Animations
      gsap.to("#hero-content > *", {
        opacity: 1,
        y: 0,
        duration: 1,
        stagger: 0.2,
        ease: "power2.out",
      });

      // Scale effect on scroll
      gsap.to("#hero", {
        scrollTrigger: {
          trigger: "#hero",
          start: "top top",
          end: "bottom top",
          scrub: true,
        },
        scale: 0.8,
        opacity: 0.8,
      });
    </script>

    <script>
      document.addEventListener("DOMContentLoaded", () => {
        // Element selectors
        const mobileMenuButton = document.getElementById("mobile-menu-button");
        const closeSidebarButton = document.getElementById("close-sidebar");
        const mobileSidebar = document.getElementById("mobile-sidebar");
        const header = document.querySelector("header");
        const navLinks = document.querySelectorAll(
          ".nav-link, .dropdown > a, .dropdown div a"
        ); // Includes dropdown links
        const logo = document.querySelector(".logo");

        let previousScrollPosition = 0;
// alert(closeSidebarButton);
// alert(mobileSidebar);
        // Mobile menu functionality
        if (mobileMenuButton && closeSidebarButton && mobileSidebar) {
          alert('yo');
          mobileMenuButton.addEventListener("click", () => {
         
            mobileSidebar.classList.remove("-right-full");
            mobileSidebar.classList.add("right-0");
            document.body.classList.add("sidebar-open");
          });

          closeSidebarButton.addEventListener("click", closeMobileSidebar);

          document.addEventListener("click", (e) => {
            if (
              !mobileSidebar.contains(e.target) &&
              !mobileMenuButton.contains(e.target)
            ) {
              closeMobileSidebar();
            }
          });
        }

        // Initialize header state on page load
        handleScroll();

        // Header scroll effect
        if (header) {
          window.addEventListener("scroll", handleScroll);
        }

        function closeMobileSidebar() {
          mobileSidebar.classList.remove("right-0");
          mobileSidebar.classList.add("-right-full");
          document.body.classList.remove("sidebar-open");
        }

        function handleScroll() {
          const currentScroll = window.pageYOffset;

          if (currentScroll <= 0) {
            // At top of the page
            header.classList.remove("shadow-lg", "scrolled");
            header.style.background = "rgb(17, 24, 39)"; // Dark background
            updateHeaderColors(false);
          } else {
            // When scrolled down
            header.classList.add("shadow-lg", "scrolled");
            header.style.background = "rgba(255, 255, 255, 0.98)"; // Light background
            updateHeaderColors(true);
          }

          previousScrollPosition = currentScroll;
        }

        function updateHeaderColors(isScrolled) {
          // Update all nav links
          navLinks.forEach((link) => {
            link.classList.remove(
              "text-gray-100",
              "text-gray-200",
              "hover:text-white",
              "hover:text-blue-500"
            );
            if (isScrolled) {
              // link.classList.add("text-gray-800", "hover:text-blue-500");
            } else {
              // link.classList.add("text-gray-200", "hover:text-white");
            }
          });

          // Update logo color
          if (logo) {
            logo.classList.toggle("text-white", !isScrolled);
            logo.classList.toggle("text-purple-900", isScrolled);
          }
        }

        // Mobile dropdown toggle
        window.toggleMobileDropdown = (button) => {
          if (!button) return;

          const dropdown = button.parentElement;
          const content = button.nextElementSibling;

          dropdown.classList.toggle("active");

          if (content.style.maxHeight) {
            content.style.maxHeight = null;
            content.classList.add("hidden");
          } else {
            content.classList.remove("hidden");
            content.style.maxHeight = content.scrollHeight + "px";
          }
        };
      });
    </script>

<script>
    // Feature scrolling functionality
    const section = document.querySelector('.features-section');
    const features = document.querySelectorAll('.feature-items');
    const navButtons = document.querySelectorAll('.feature-nav');
    const progressBar = document.getElementById('progressBar');
    
    // Set the first feature as active initially
    features[0].classList.add('active');
    
    // Update active feature based on scroll position
    function updateActiveFeature() {
      const sectionHeight = section.offsetHeight;
      const viewportHeight = window.innerHeight;
      const scrollPosition = window.scrollY;
      const scrollRatio = scrollPosition / (sectionHeight - viewportHeight);
      const progress = Math.min(scrollRatio * 100, 100);
      
      // Update progress bar
      progressBar.style.width = `${progress}%`;
      
      // Calculate which feature should be active
      const numFeatures = features.length;
      const featureIndex = Math.min(
        Math.floor(scrollRatio * numFeatures),
        numFeatures - 1
      );
      
      // Update active classes
      features.forEach((feature, index) => {
        if (index === featureIndex) {
          feature.classList.add('active');
          navButtons[index].classList.add('active');
        } else {
          feature.classList.remove('active');
          navButtons[index].classList.remove('active');
        }
      });
    }
    
    // Initialize
    updateActiveFeature();
    
    // Listen for scroll events
    window.addEventListener('scroll', updateActiveFeature);
    
    // Navigation button click handling
    navButtons.forEach((button, index) => {
      button.addEventListener('click', () => {
        const scrollTarget = (section.offsetHeight - window.innerHeight) * (index / (features.length - 1));
        window.scrollTo({
          top: scrollTarget,
          behavior: 'smooth'
        });
      });
    });
    
    // Add hover effects for clickable elements
    const interactiveElements = document.querySelectorAll('.btn, .feature-card, .footer-links a, .social-links a');
    interactiveElements.forEach(element => {
      if (element.classList.contains('feature-card')) {
        element.addEventListener('mouseenter', () => {
          element.style.transform = 'translateY(-10px)';
          element.style.boxShadow = '0 15px 40px rgba(0, 0, 0, 0.15)';
        });
        element.addEventListener('mouseleave', () => {
          element.style.transform = '';
          element.style.boxShadow = '';
        });
      } else if (element.classList.contains('btn-primary')) {
        element.addEventListener('mouseenter', () => {
          element.style.transform = 'translateY(-3px)';
          element.style.boxShadow = '0 5px 15px rgba(58, 12, 163, 0.3)';
        });
        element.addEventListener('mouseleave', () => {
          element.style.transform = '';
          element.style.boxShadow = '';
        });
      } else if (element.classList.contains('social-links')) {
        element.addEventListener('mouseenter', () => {
          element.style.transform = 'scale(1.2)';
        });
        element.addEventListener('mouseleave', () => {
          element.style.transform = '';
        });
      } else if (element.parentElement.classList.contains('footer-links')) {
        element.addEventListener('mouseenter', () => {
          element.style.color = '#fff';
        });
        element.addEventListener('mouseleave', () => {
          element.style.color = '#ccc';
        });
      }
    });
    
    // Add an additional feature - "Get Started" CTA
    const ctaSection = document.createElement('div');
    ctaSection.className = 'cta-section';
    ctaSection.style.padding = '5rem 1.5rem';
    ctaSection.style.background = 'linear-gradient(135deg, #3a0ca3 0%, #4361ee 100%)';
    ctaSection.style.color = 'white';
    ctaSection.style.textAlign = 'center';
    
    ctaSection.innerHTML = `
      <div style="max-width: 800px; margin: 0 auto;">
        <h2 style="font-size: 2.5rem; margin-bottom: 1.5rem;">Ready to Experience Modern Banking?</h2>
        <p style="font-size: 1.2rem; margin-bottom: 2.5rem; opacity: 0.9;">Join thousands of satisfied customers who have transformed their financial life with Rafifintech Banking</p>
        <div style="display: flex; justify-content: center; gap: 1.5rem; flex-wrap: wrap;">
          <a href="#" style="display: inline-block; padding: 1rem 2.5rem; background: white; color: #3a0ca3; font-weight: 600; border-radius: 0.5rem; text-decoration: none; font-size: 1.2rem; transition: all 0.3s ease; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);">Get Started</a>
          <a href="#" style="display: inline-block; padding: 1rem 2.5rem; background: transparent; color: white; font-weight: 600; border-radius: 0.5rem; text-decoration: none; font-size: 1.2rem; transition: all 0.3s ease; border: 2px solid white;">Schedule Demo</a>
        </div>
        <div style="margin-top: 3rem; display: flex; justify-content: center; align-items: center; flex-wrap: wrap; gap: 2rem;">
          <div style="display: flex; align-items: center; gap: 0.5rem;">
            <div style="font-size: 2.5rem; font-weight: 700;">4.9</div>
            <div>
              <div style="color: #FFD700;">★★★★★</div>
              <div style="font-size: 0.9rem;">App Store Rating</div>
            </div>
          </div>
          <div style="display: flex; align-items: center; gap: 0.5rem;">
            <div style="font-size: 2.5rem; font-weight: 700;">1M+</div>
            <div style="font-size: 0.9rem;">Active Users</div>
          </div>
          <div style="display: flex; align-items: center; gap: 0.5rem;">
            <div style="font-size: 2.5rem; font-weight: 700;">₹1 Cr+</div>
            <div style="font-size: 0.9rem;">Transactions Monthly</div>
          </div>
        </div>
      </div>
    `;
    
    // Insert CTA section before the footer
    document.body.insertBefore(ctaSection, document.querySelector('#thanks'));
    
    // Add testimonials section
    const testimonialsSection = document.createElement('div');
    testimonialsSection.className = 'testimonials-section';
    testimonialsSection.style.padding = '5rem 1.5rem';
    testimonialsSection.style.background = '';
    testimonialsSection.style.textAlign = 'center';
    
    testimonialsSection.innerHTML = `
      <div style="max-width: 1200px; margin: 0 auto;">
        <h2 style="font-size: 2.5rem; margin-bottom: 1rem; color: #0d002c;">What Our Customers Say</h2>
        <p style="font-size: 1.2rem; margin-bottom: 3rem; color: #666; max-width: 700px; margin-left: auto; margin-right: auto;">Don't just take our word for it. Here's what people who use Rafifintech Banking have to say about their experience.</p>
        
        <div style="display: flex; gap: 2rem; flex-wrap: wrap; justify-content: center;">
          <!-- Testimonial 1 -->
          <div style="background: white; border-radius: 1rem; padding: 2rem; box-shadow: 0 5px 20px rgba(0,0,0,0.05); max-width: 350px; text-align: left;">
            <div style="color: #3a0ca3; font-size: 1.5rem; margin-bottom: 1rem;">★★★★★</div>
            <p style="font-style: italic; font-size: 1.1rem; margin-bottom: 1.5rem;">"The Smart Analytics feature completely changed how I manage my money. I'm saving 30% more each month without even thinking about it!"</p>
            <div style="display: flex; align-items: center;">
              <div style="width: 50px; height: 50px; border-radius: 50%; background: #eee; margin-right: 1rem; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width: 30px; height: 30px; fill: #999;">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
              </div>
              <div>
                <div style="font-weight: 600; color: #333;">User</div>
                <div style="font-size: 0.9rem; color: #666;">Small Business Owner</div>
              </div>
            </div>
          </div>
          
          <!-- Testimonial 2 -->
          <div style="background: white; border-radius: 1rem; padding: 2rem; box-shadow: 0 5px 20px rgba(0,0,0,0.05); max-width: 350px; text-align: left;">
            <div style="color: #3a0ca3; font-size: 1.5rem; margin-bottom: 1rem;">★★★★★</div>
            <p style="font-style: italic; font-size: 1.1rem; margin-bottom: 1.5rem;">"Instant Transfers has been a game-changer for my international business. I save thousands in fees every month compared to my old bank."</p>
            <div style="display: flex; align-items: center;">
              <div style="width: 50px; height: 50px; border-radius: 50%; background: #eee; margin-right: 1rem; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width: 30px; height: 30px; fill: #999;">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
              </div>
              <div>
                <div style="font-weight: 600; color: #333;">User</div>
                <div style="font-size: 0.9rem; color: #666;">E-commerce Entrepreneur</div>
              </div>
            </div>
          </div>
          
          <!-- Testimonial 3 -->
          <div style="background: white; border-radius: 1rem; padding: 2rem; box-shadow: 0 5px 20px rgba(0,0,0,0.05); max-width: 350px; text-align: left;">
            <div style="color: #3a0ca3; font-size: 1.5rem; margin-bottom: 1rem;">★★★★★</div>
            <p style="font-style: italic; font-size: 1.1rem; margin-bottom: 1.5rem;">"The 24/7 support team helped me resolve a complicated issue at 2 AM. I've never experienced service like this from any other bank."</p>
            <div style="display: flex; align-items: center;">
              <div style="width: 50px; height: 50px; border-radius: 50%; background: #eee; margin-right: 1rem; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width: 30px; height: 30px; fill: #999;">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
              </div>
              <div>
                <div style="font-weight: 600; color: #333;">User</div>
                <div style="font-size: 0.9rem; color: #666;">Digital Nomad</div>
              </div>
            </div>
          </div>
        </div>
        
        <a href="#" style="display: inline-block; margin-top: 3rem; padding: 0.8rem 2rem; background: transparent; color: #3a0ca3; font-weight: 600; border-radius: 0.5rem; text-decoration: none; border: 1px solid #3a0ca3; transition: all 0.3s ease;">Read More Success Stories</a>
      </div>
    `;
    
    // Insert testimonials section before the CTA section
    document.body.insertBefore(testimonialsSection, document.querySelector('#contact_quote'));
    
    // Add FAQ section
    const faqSection = document.createElement('div');
    faqSection.className = 'faq-section';
    faqSection.style.padding = '5rem 1.5rem';
    faqSection.style.background = 'white';
    
    faqSection.innerHTML = `
      <div style="max-width: 1000px; margin: 0 auto;">
        <h2 style="font-size: 2.5rem; margin-bottom: 1rem; color: #0d002c; text-align: center;">Frequently Asked Questions</h2>
        <p style="font-size: 1.2rem; margin-bottom: 3rem; color: #666; text-align: center; max-width: 700px; margin-left: auto; margin-right: auto;">Got questions? We've got answers. If you can't find what you're looking for, our support team is always ready to help.</p>
        
        <div class="faq-container" style="display: flex; flex-direction: column; gap: 1rem;">
          <!-- FAQ Item 1 -->
          <div class="faq-item" style="border: 1px solid #eee; border-radius: 0.8rem; overflow: hidden;">
            <div class="faq-question" style="padding: 1.5rem; background: #f8f9fa; font-weight: 600; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
              <span>How secure is Rafifintech Banking?</span>
              <span class="faq-icon">+</span>
            </div>
            <div class="faq-answer" style="padding: 0 1.5rem; max-height: 0; overflow: hidden; transition: all 0.3s ease;">
              <p style="padding-bottom: 1.5rem;">Rafifintech Banking employs military-grade 256-bit encryption, multi-factor authentication, and continuous security monitoring. We're compliant with all international banking security standards and undergo regular third-party security audits. Your funds are FDIC insured up to $250,000.</p>
            </div>
          </div>
          
          <!-- FAQ Item 2 -->
          <div class="faq-item" style="border: 1px solid #eee; border-radius: 0.8rem; overflow: hidden;">
            <div class="faq-question" style="padding: 1.5rem; background: #f8f9fa; font-weight: 600; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
              <span>Are there any hidden fees?</span>
              <span class="faq-icon">+</span>
            </div>
            <div class="faq-answer" style="padding: 0 1.5rem; max-height: 0; overflow: hidden; transition: all 0.3s ease;">
              <p style="padding-bottom: 1.5rem;">No. We believe in transparent banking. Our fee structure is clearly outlined during the account opening process. Most everyday banking activities, including transfers, withdrawals, and account management, are completely free. Premium features may have associated costs, but these are always clearly communicated upfront.</p>
            </div>
          </div>
          
          <!-- FAQ Item 3 -->
          <div class="faq-item" style="border: 1px solid #eee; border-radius: 0.8rem; overflow: hidden;">
            <div class="faq-question" style="padding: 1.5rem; background: #f8f9fa; font-weight: 600; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
              <span>How quickly can I open an account?</span>
              <span class="faq-icon">+</span>
            </div>
            <div class="faq-answer" style="padding: 0 1.5rem; max-height: 0; overflow: hidden; transition: all 0.3s ease;">
              <p style="padding-bottom: 1.5rem;">Our streamlined digital onboarding process allows most customers to open and start using their accounts within 5 minutes. All you need is a government-issued ID and proof of address. Our advanced verification systems work in real-time to validate your information.</p>
            </div>
          </div>
          
          <!-- FAQ Item 4 -->
          <div class="faq-item" style="border: 1px solid #eee; border-radius: 0.8rem; overflow: hidden;">
            <div class="faq-question" style="padding: 1.5rem; background: #f8f9fa; font-weight: 600; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
              <span>Can I access ATMs with Rafifintech Banking?</span>
              <span class="faq-icon">+</span>
            </div>
            <div class="faq-answer" style="padding: 0 1.5rem; max-height: 0; overflow: hidden; transition: all 0.3s ease;">
              <p style="padding-bottom: 1.5rem;">Yes! Rafifintech Banking provides free access to over 55,000 ATMs worldwide. Our app includes an ATM locator to help you find the nearest fee-free ATM wherever you are. If you need to use an out-of-network ATM, we reimburse up to 5 ATM fees per month with our Premium account.</p>
            </div>
          </div>
          
          <!-- FAQ Item 5 -->
          <div class="faq-item" style="border: 1px solid #eee; border-radius: 0.8rem; overflow: hidden;">
            <div class="faq-question" style="padding: 1.5rem; background: #f8f9fa; font-weight: 600; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
              <span>Is my money FDIC insured?</span>
              <span class="faq-icon">+</span>
            </div>
            <div class="faq-answer" style="padding: 0 1.5rem; max-height: 0; overflow: hidden; transition: all 0.3s ease;">
              <p style="padding-bottom: 1.5rem;">Absolutely. All Rafifintech Banking deposits are FDIC insured up to $250,000 per depositor. This means your money is protected by the full faith and credit of the United States government, giving you peace of mind about the safety of your funds.</p>
            </div>
          </div>
        </div>
        
        <div style="text-align: center; margin-top: 3rem;">
          <a href="#" style="display: inline-block; padding: 0.8rem 2rem; background: #3a0ca3; color: white; font-weight: 600; border-radius: 0.5rem; text-decoration: none; transition: all 0.3s ease;">View All FAQs</a>
        </div>
      </div>
    `;
    
    // Insert FAQ section before the testimonials section
    document.body.insertBefore(faqSection, ctaSection);
    
    // Add FAQ functionality
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
      const question = item.querySelector('.faq-question');
      const answer = item.querySelector('.faq-answer');
      const icon = item.querySelector('.faq-icon');
      
      question.addEventListener('click', () => {
        // Toggle the current FAQ item
        const isOpen = answer.style.maxHeight !== '0px' && answer.style.maxHeight;
        
        if (!isOpen) {
          answer.style.maxHeight = answer.scrollHeight + 'px';
          icon.textContent = '−';
          question.style.color = '#3a0ca3';
        } else {
          answer.style.maxHeight = '0px';
          icon.textContent = '+';
          question.style.color = '';
        }
      });
    });
  </script>





<script>
  document.addEventListener("DOMContentLoaded", () => {
    // Initialize Lottie animation (always)
    const contactAnimation = lottie.loadAnimation({
      container: document.getElementById("contactAnimation"),
      renderer: "svg",
      loop: true,
      autoplay: true,
      path: "about_lottie.json",
      rendererSettings: {
        progressiveLoad: true,
        preserveAspectRatio: "xMidYMid slice",
      },
    });

    // Only run GSAP animations if the screen is large
    if (window.innerWidth > 768) {
      // Smooth scroll-triggered animation for text
      gsap.from("#contact .text-2xl", {
        scrollTrigger: {
          trigger: "#contact",
          start: "top 80%",
          toggleActions: "play none none reverse",
        },
        y: 30,
        opacity: 0,
        duration: 0.8,
        ease: "power2.out",
      });

      // Form appearance animation
      gsap.from("#contact form", {
        scrollTrigger: {
          trigger: "#contact form",
          start: "top 85%",
        },
        y: 20,
        opacity: 0,
        duration: 0.8,
        delay: 0.2,
        ease: "power2.out",
      });

      // Refined form field animations
      const formFields = document.querySelectorAll("input, select, textarea");
      formFields.forEach((field) => {
        field.addEventListener("focus", () => {
          gsap.to(field, {
            scale: 1.01,
            duration: 0.2,
            ease: "power2.out",
          });
        });

        field.addEventListener("blur", () => {
          gsap.to(field, {
            scale: 1,
            duration: 0.2,
            ease: "power2.out",
          });
        });
      });
    }
  });
</script>


    <script>
       if (window.innerWidth > 768) {
      // GSAP animations for the thanks section
      gsap.registerPlugin(ScrollTrigger);

      // Create timeline for initial animation
      const thanksTimeline = gsap.timeline({
        scrollTrigger: {
          trigger: "#thanks",
          start: "top center",
          end: "bottom center",
          toggleActions: "play none none reverse",
        },
      });

      // Add animations to timeline
      thanksTimeline
        .from("#thanksTitle", {
          y: 100,
          opacity: 0,
          duration: 1,
          ease: "power3.out",
        })
        .from(
          "#separator",
          {
            scale: 0,
            opacity: 0,
            duration: 0.7,
            ease: "back.out(1.7)",
          },
          "-=0.5"
        )
        .from(
          "#thanksSubtitle",
          {
            y: 30,
            opacity: 0,
            duration: 0.7,
            ease: "power2.out",
          },
          "-=0.3"
        )
        .from(
          "#thanksMessage",
          {
            y: 30,
            opacity: 0,
            duration: 0.7,
            ease: "power2.out",
          },
          "-=0.5"
        )
        .from(
          "#thanksButton",
          {
            y: 30,
            opacity: 0,
            duration: 0.7,
            ease: "power2.out",
          },
          "-=0.5"
        );

      // Parallax effect on scroll
      gsap.to("#thanks", {
        scrollTrigger: {
          trigger: "#thanks",
          start: "top top",
          end: "bottom top",
          scrub: true,
        },
        backgroundPosition: "50% 100%",
        ease: "none",
      });

      // Text floating animation
      gsap.to("#thanksTitle", {
        y: "10px",
        duration: 2,
        repeat: -1,
        yoyo: true,
        ease: "power1.inOut",
      });

      // Separator pulsing animation
      gsap.to("#separator", {
        scale: 1.1,
        duration: 1.5,
        repeat: -1,
        yoyo: true,
        ease: "power1.inOut",
      });
    }
    else
    {
      gsap.registerPlugin(ScrollTrigger);
    }
    </script>
    <script>
        if (window.innerWidth > 768) {
      // GSAP animations for the thanks section
      // gsap.registerPlugin(ScrollTrigger);
      // Initialize Lottie animation
      const footerAnimation = lottie.loadAnimation({
        container: document.getElementById("footerAnimation"),
        renderer: "svg",
        loop: true,
        autoplay: true,
        path: "lottie.json", // Logo animation
      });

      // GSAP animations
      gsap.registerPlugin(ScrollTrigger);

      // Animate grid items
      gsap.from("#footer .grid > div", {
        scrollTrigger: {
          trigger: "#footer",
          start: "top bottom",
          toggleActions: "play none none reverse",
        },
        y: 50,
        opacity: 0,
        duration: 1,
        stagger: 0.2,
      });

      // Animate bottom bar
      gsap.from("#footer .border-t", {
        scrollTrigger: {
          trigger: "#footer .border-t",
          start: "top bottom",
          toggleActions: "play none none reverse",
        },
        y: 30,
        opacity: 0,
        duration: 1,
        delay: 0.5,
      });

      // Logo hover animation
      const logo = document.querySelector("#footer .text-3xl");
      logo.addEventListener("mouseenter", () => {
        gsap.to(logo, {
          scale: 1.05,
          duration: 0.3,
        });
      });

      logo.addEventListener("mouseleave", () => {
        gsap.to(logo, {
          scale: 1,
          duration: 0.3,
        });
      });

      // Social icons hover effect
      const socialIcons = document.querySelectorAll("#footer .fab");
      socialIcons.forEach((icon) => {
        icon.addEventListener("mouseenter", () => {
          gsap.to(icon, {
            y: -3,
            duration: 0.3,
          });
        });

        icon.addEventListener("mouseleave", () => {
          gsap.to(icon, {
            y: 0,
            duration: 0.3,
          });
        });
      });
    }
    else
    {
        gsap.registerPlugin(ScrollTrigger);  // gsap.registerPlugin(ScrollTrigger);
    }
    </script>
    <script>
        if (window.innerWidth > 768) {
      // Register ScrollTrigger
      gsap.registerPlugin(ScrollTrigger);

      gsap.fromTo(
        "#thanks-text",
        { scale: 0.4, opacity: 0.5 }, // Initial state (small & transparent)
        {
          scale: 1,
          opacity: 1, // Target state (normal size & visible)
          duration: 1.5,
          ease: "power2.out",
          scrollTrigger: {
            trigger: "#thanks",
            start: "top 80%", // Start animation when section is 80% in view
            end: "top 20%", // Reverse animation when scrolled past 20%
            scrub: true, // Smooth animation while scrolling
          },
        }
      );
        }
        else
        {
          gsap.registerPlugin(ScrollTrigger);
        }
    </script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const horizontalContainer = document.querySelector('.horizontal-rays');
    const verticalContainer = document.querySelector('.vertical-rays');
    const windowHeight = window.innerHeight;
    const windowWidth = window.innerWidth;

    // Dark blue and deep indigo colors for a stronger effect
    const colors = [
        'rgba(10, 25, 75, 0.6)',   // deep dark blue
        'rgba(15, 32, 92, 0.5)',   // darker navy blue
        'rgba(20, 50, 120, 0.4)',  // dark royal blue
        'rgba(30, 60, 140, 0.3)',  // deep indigo blue
        'rgba(40, 70, 160, 0.35)'  // vibrant dark indigo
    ];

    // Create horizontal rays at 40px intervals
    for (let i = 0; i < windowHeight / 40; i++) {
        if (i % 2 === 0) { // Add rays to every other grid line
            const ray = document.createElement('div');
            ray.className = 'h-ray';
            ray.style.top = (i * 40) + 'px';
            ray.style.animationDelay = (-Math.random() * 10) + 's';
            ray.style.animationDuration = (10 + Math.random() * 8) + 's';

            // Randomly choose a dark blue color
            const colorIndex = Math.floor(Math.random() * colors.length);
            ray.style.background = `linear-gradient(90deg, rgba(0, 0, 0, 0), ${colors[colorIndex]}, rgba(0, 0, 0, 0))`;
            ray.style.filter = `drop-shadow(0 0 4px ${colors[colorIndex]})`;

            horizontalContainer.appendChild(ray);
        }
    }

    // Create vertical rays at 40px intervals
    for (let i = 0; i < windowWidth / 40; i++) {
        if (i % 2 === 0) { // Add rays to every other grid line
            const ray = document.createElement('div');
            ray.className = 'v-ray';
            ray.style.left = (i * 40) + 'px';
            ray.style.animationDelay = (-Math.random() * 10) + 's';
            ray.style.animationDuration = (10 + Math.random() * 8) + 's';

            // Randomly choose a dark indigo color
            const colorIndex = Math.floor(Math.random() * colors.length);
            ray.style.background = `linear-gradient(180deg, rgba(0, 0, 0, 0), ${colors[colorIndex]}, rgba(0, 0, 0, 0))`;
            ray.style.filter = `drop-shadow(0 0 4px ${colors[colorIndex]})`;

            verticalContainer.appendChild(ray);
        }
    }
});

</script>
  <script>
    // Animate the progress bar width based on scrolling the features section.
    gsap.to(".progress-bar", {
      width: "100%",
      ease: "none",
      scrollTrigger: {
        trigger: ".features-section", // adjust selector if needed
        start: "top top",
        end: "bottom top",
        scrub: true
      }
    });
  
    // Toggle visibility of the progress bar based on the features section
    ScrollTrigger.create({
      trigger: ".features-section",
      start: "top top",
      end: "bottom top",
      onEnter: () => document.querySelector(".progress-bar").style.opacity = 1,
      onLeave: () => document.querySelector(".progress-bar").style.opacity = 0,
      onEnterBack: () => document.querySelector(".progress-bar").style.opacity = 1,
      onLeaveBack: () => document.querySelector(".progress-bar").style.opacity = 0
    });
  </script>
<script>
  const video = document.getElementById("bg-video");
  video.playbackRate = 0.5; // Adjust the speed (1.0 is normal, 0.5 is half speed)
</script>
  </body>
</html>
