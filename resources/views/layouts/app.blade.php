<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($settings) ? $settings->app_name : 'Pengumuman Kelulusan' }}</title>
    @if(isset($settings) && $settings->logo_image)
    <link rel="icon" href="{{ asset($settings->logo_image) }}" type="image/x-icon">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @if(isset($settings))
    <style>
        :root {
            --primary: {{ $settings->primary_color }};
            --secondary: {{ $settings->secondary_color }};
        }
        body {
            background: linear-gradient(135deg, {{ $settings->bg_gradient_start }} 0%, {{ $settings->bg_gradient_end }} 100%);
        }
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-color: var(--primary);
            opacity: 0.1;
            z-index: -2;
            transition: background-image 1s ease-in-out;
        }
    </style>
    @if(!empty($settings->slider_images))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const images = {!! json_encode($settings->slider_images) !!};
            const interval = {{ $settings->slider_interval ?? 5000 }};
            const animationType = '{{ $settings->slider_animation ?? "fade" }}';
            let currentIndex = 0;
            
            if (images.length > 0) {
                // Create container
                const container = document.createElement('div');
                container.style.position = 'fixed';
                container.style.top = '0';
                container.style.left = '0';
                container.style.width = '100%';
                container.style.height = '100%';
                container.style.zIndex = '-2';
                container.style.opacity = '0.15';
                container.style.overflow = 'hidden';
                document.body.appendChild(container);

                const bg1 = document.createElement('div');
                const bg2 = document.createElement('div');
                
                [bg1, bg2].forEach(bg => {
                    bg.style.position = 'absolute';
                    bg.style.width = '100%';
                    bg.style.height = '100%';
                    bg.style.backgroundSize = 'cover';
                    bg.style.backgroundPosition = 'center';
                    if (animationType === 'fade') {
                        bg.style.transition = 'opacity 1s ease-in-out';
                    } else if (animationType === 'slide') {
                        bg.style.transition = 'transform 1s ease-in-out';
                    }
                    container.appendChild(bg);
                });

                bg1.style.backgroundImage = `url('{{ asset('') }}${images[0]}')`;
                
                if (animationType === 'fade') {
                    bg2.style.opacity = '0';
                } else if (animationType === 'slide') {
                    bg2.style.transform = 'translateX(100%)';
                } else {
                    bg2.style.display = 'none';
                }

                let activeBg = bg1;
                let nextBg = bg2;

                function changeBackground() {
                    currentIndex = (currentIndex + 1) % images.length;
                    nextBg.style.backgroundImage = `url('{{ asset('') }}${images[currentIndex]}')`;
                    
                    if (animationType === 'fade') {
                        nextBg.style.opacity = '1';
                        activeBg.style.opacity = '0';
                    } else if (animationType === 'slide') {
                        nextBg.style.transform = 'translateX(0)';
                        activeBg.style.transform = 'translateX(-100%)';
                        
                        // Reset the old active after transition
                        setTimeout(() => {
                            activeBg.style.transition = 'none';
                            activeBg.style.transform = 'translateX(100%)';
                            setTimeout(() => {
                                activeBg.style.transition = 'transform 1s ease-in-out';
                            }, 50);
                        }, 1000);
                    } else {
                        // none
                        nextBg.style.display = 'block';
                        activeBg.style.display = 'none';
                    }

                    // Swap references
                    let temp = activeBg;
                    activeBg = nextBg;
                    nextBg = temp;
                }
                
                if (images.length > 1) {
                    setInterval(changeBackground, interval);
                }
            }
        });
    </script>
    @endif
    @endif
</head>
<body>
    @php $fgAnim = $settings->foreground_animation ?? 'balls'; @endphp
    
    @if($fgAnim == 'wave')
    <div class="waves-container">
        <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
            <defs>
                <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
            </defs>
            <g class="parallax">
                <use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7)" />
                <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.5)" />
                <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)" />
                <use xlink:href="#gentle-wave" x="48" y="7" fill="rgba(255,255,255,0.1)" />
            </g>
        </svg>
    </div>
    @elseif($fgAnim == 'geometric')
    <div class="geometric-container">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
        <div class="shape shape-5"></div>
        <div class="shape shape-6"></div>
    </div>
    @else
    <div class="background-animation {{ $fgAnim }}"></div>
    @endif

    <div class="container">
        @yield('content')
    </div>
</body>
</html>
