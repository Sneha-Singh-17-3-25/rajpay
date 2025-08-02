<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NSDL Callback Response</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- AOS (Animate on Scroll) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="container mx-auto max-w-2xl">
        <div 
            data-aos="fade-up" 
            data-aos-duration="800" 
            class="bg-white shadow-2xl rounded-xl overflow-hidden border border-gray-100"
        >
            <div class="bg-blue-600 text-white p-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">NSDL Callback</h1>
                    <p class="text-blue-100 mt-2">Response Details</p>
                </div>
                <svg class="w-12 h-12 text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3.293 1.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414L7.586 10 5.293 7.707a1 1 0 010-1.414zM11 12a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                </svg>
            </div>

            <div class="p-6">
                @if($response === null || $response === '')
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4" data-aos="fade-in">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    No response data available. The response is empty or null.
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div 
                        data-aos="fade-left" 
                        data-aos-delay="100" 
                        class="bg-gray-50 rounded-lg p-5 border border-gray-200 hover:shadow-md transition-all duration-300"
                    >
                        <h3 class="text-xl font-semibold text-blue-800 mb-3">
                            Result  
                        </h3>
                        
                        <div class="text-gray-700 bg-white p-4 rounded-md shadow-inner">
                            <pre class="whitespace-pre-wrap break-words text-sm">{{ $response }}</pre>
                        </div>
                    </div>
                @endif
            </div>

            <div class="bg-gray-50 border-t border-gray-200 p-6 flex justify-between items-center">
             <a 
    href="javascript:void(0);" 
    onclick="window.history.back(); setTimeout(() => window.history.back(), 100);" 
    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300"
>
    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
    </svg>
    Go Back Twice
</a>



                <div class="text-gray-500 text-sm">
                    Response generated: {{ now()->format('F j, Y, h:i A') }}
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
</body>
</html>