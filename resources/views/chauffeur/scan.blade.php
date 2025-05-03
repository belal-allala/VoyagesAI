@extends('layouts.app')

@section('title', 'Scanner le billet')

@section('content')
    <div class="container mx-auto py-6 px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="mb-6 text-center">
                <h1 class="text-2xl font-bold text-gray-800">Scanner le billet</h1>
                <p class="text-gray-600 mt-2">Utilisez la caméra pour scanner le code QR du billet</p>
            </div>

            <!-- Selecteur de trajet -->
            <div class="mb-4">
                <label for="trajet_id" class="block text-sm font-medium text-gray-700">Sélectionner le trajet</label>
                <select id="trajet_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="">Sélectionner un trajet</option>
                    @foreach($trajets as $trajet)
                        <option value="{{ $trajet->id }}">{{ $trajet->name }} - {{ $trajet->firstDepartureTime()->format('d/m/Y') }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Scanner Interface -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                <!-- Camera View -->
                <div class="relative">
                    <div class="bg-black relative">
                        <video id="qr-video" class="w-full" style="max-height: 70vh;" playsinline></video>
                        
                        <!-- Scanning Overlay -->
                        <div id="scanner-overlay" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <div class="w-64 h-64 border-2 border-green-500 rounded-lg relative">
                                <div class="absolute top-0 left-0 w-4 h-4 border-t-2 border-l-2 border-green-500"></div>
                                <div class="absolute top-0 right-0 w-4 h-4 border-t-2 border-r-2 border-green-500"></div>
                                <div class="absolute bottom-0 left-0 w-4 h-4 border-b-2 border-l-2 border-green-500"></div>
                                <div class="absolute bottom-0 right-0 w-4 h-4 border-b-2 border-r-2 border-green-500"></div>
                            </div>
                            <div id="scanning-line" class="absolute w-64 h-0.5 bg-green-500 opacity-75" style="top: 50%; animation: scan 2s linear infinite;"></div>
                        </div>
                        
                        <!-- Camera Controls -->
                        <div class="absolute bottom-4 right-4 flex space-x-2">
                            <button id="toggle-flash" class="bg-white bg-opacity-75 p-2 rounded-full shadow-md hover:bg-opacity-100 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                            </button>
                            <button id="switch-camera" class="bg-white bg-opacity-75 p-2 rounded-full shadow-md hover:bg-opacity-100 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Status Indicator -->
                    <div id="scanner-status" class="bg-gray-800 text-white text-center py-2 text-sm">
                        Prêt à scanner
                    </div>
                </div>
                
                <!-- Scan Result -->
                <div id="qr-result" class="p-4 hidden">
                    <!-- Le résultat du scan sera affiché ici -->
                </div>
                
                <!-- Manual Entry -->
                <div class="p-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row gap-2">
                        <div class="flex-grow">
                            <input type="text" id="manual-code" placeholder="Entrer le code manuellement" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                        <button id="validate-manual" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                            Valider
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Recent Scans -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Scans récents</h2>
                </div>
                <div id="recent-scans" class="divide-y divide-gray-200 max-h-64 overflow-y-auto">
                    <div class="p-4 text-gray-500 text-center">
                        Aucun scan récent
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes scan {
            0% {
                transform: translateY(-32px);
            }
            50% {
                transform: translateY(32px);
            }
            100% {
                transform: translateY(-32px);
            }
        }
        
        .ticket-valid {
            animation: fadeInGreen 0.5s ease-out;
        }
        
        .ticket-invalid {
            animation: fadeInRed 0.5s ease-out;
        }
        
        @keyframes fadeInGreen {
            from { background-color: rgba(16, 185, 129, 0); }
            to { background-color: rgba(16, 185, 129, 0.1); }
        }
        
        @keyframes fadeInRed {
            from { background-color: rgba(239, 68, 68, 0); }
            to { background-color: rgba(239, 68, 68, 0.1); }
        }
        
        .pulse {
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Elements
            const video = document.getElementById('qr-video');
            const qrResult = document.getElementById('qr-result');
            const scannerStatus = document.getElementById('scanner-status');
            const toggleFlashBtn = document.getElementById('toggle-flash');
            const switchCameraBtn = document.getElementById('switch-camera');
            const manualCodeInput = document.getElementById('manual-code');
            const validateManualBtn = document.getElementById('validate-manual');
            const recentScansContainer = document.getElementById('recent-scans');
            const trajetSelect = document.getElementById('trajet_id');
            
            // Variables
            let scanning = false;
            let currentStream = null;
            let currentFacingMode = 'environment'; // 'environment' pour la caméra arrière, 'user' pour la caméra avant
            let flashOn = false;
            let recentScans = [];
            let scanTimeout = null;
            
            // Initialiser la caméra
            initCamera();
            
            // Fonction pour initialiser la caméra
            function initCamera() {
                // Arrêter le flux actuel s'il existe
                if (currentStream) {
                    currentStream.getTracks().forEach(track => track.stop());
                }
                
                // Options de la caméra
                const constraints = {
                    video: { 
                        facingMode: currentFacingMode,
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    }
                };
                
                // Demander l'accès à la caméra
                navigator.mediaDevices.getUserMedia(constraints)
                    .then(function (stream) {
                        currentStream = stream;
                        video.srcObject = stream;
                        video.setAttribute('playsinline', true); // Required by Safari
                        video.play();
                        
                        // Vérifier si la torche est disponible
                        const track = stream.getVideoTracks()[0];
                        const capabilities = track.getCapabilities();
                        if (capabilities.torch) {
                            toggleFlashBtn.classList.remove('hidden');
                        } else {
                            toggleFlashBtn.classList.add('hidden');
                        }
                        
                        // Démarrer le scan
                        scanning = true;
                        updateStatus('Caméra activée, recherche de code QR...');
                        requestAnimationFrame(scan);
                    })
                    .catch(function (error) {
                        console.error('Erreur accès caméra:', error);
                        updateStatus('Erreur: impossible d\'accéder à la caméra', true);
                    });
            }
            
            // Fonction pour scanner
            function scan() {
                if (!scanning) return;
                
                if (video.readyState === video.HAVE_ENOUGH_DATA) {
                    const canvasElement = document.createElement("canvas");
                    const canvas = canvasElement.getContext("2d");
                    canvasElement.height = video.videoHeight;
                    canvasElement.width = video.videoWidth;
                    canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
                    const imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);

                    const code = jsQR(imageData.data, imageData.width, imageData.height, {
                        inversionAttempts: "dontInvert",
                    });

                    if (code) {
                        // Code QR détecté
                        console.log("Code QR détecté:", code.data);
                        updateStatus('Code QR détecté! Validation en cours...');
                        
                        // Jouer un son de notification
                        const beep = new Audio('/sounds/beep.mp3');
                        beep.play();
                        
                        // Arrêter temporairement le scan
                        scanning = false;
                        
                        // Valider le code
                        const trajetId = trajetSelect.value;
                        if (!trajetId) {
                            showResult({ success: false, message: 'Veuillez sélectionner un trajet.' }, code.data);
                            scanning = true; // Reprendre le scan
                            return;
                        }
                        validateTicket(code.data, trajetId);
                    } else {
                        // Aucun code QR détecté, on réessaie
                        requestAnimationFrame(scan);
                    }
                } else {
                    requestAnimationFrame(scan);
                }
            }
            
            // Fonction pour valider le billet
            function validateTicket(ticketCode, trajetId) {
                fetch('/chauffeur/billet/valider', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ numero_billet: ticketCode, trajet_id: trajetId })
                })
                .then(response => response.json())
                .then(data => {
                    // Afficher le résultat
                    showResult(data, ticketCode);
                    
                    // Ajouter aux scans récents
                    addToRecentScans(data, ticketCode);
                    
                    // Reprendre le scan après un délai
                    clearTimeout(scanTimeout);
                    scanTimeout = setTimeout(() => {
                        qrResult.classList.add('hidden');
                        scanning = true;
                        updateStatus('Prêt à scanner');
                        requestAnimationFrame(scan);
                    }, 3000);
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showResult({
                        success: false,
                        message: 'Erreur réseau. Veuillez réessayer.'
                    }, ticketCode);
                    
                    // Reprendre le scan après un délai
                    clearTimeout(scanTimeout);
                    scanTimeout = setTimeout(() => {
                        qrResult.classList.add('hidden');
                        scanning = true;
                        updateStatus('Prêt à scanner');
                        requestAnimationFrame(scan);
                    }, 3000);
                });
            }
            
            // Fonction pour afficher le résultat
            function showResult(data, ticketCode) {
                qrResult.classList.remove('hidden');
                
                if (data.success) {
                    qrResult.innerHTML = `
                        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 ticket-valid">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="font-medium">Billet valide</p>
                                    <p class="text-sm">${data.message}</p>
                                    ${data.passenger ? `<p class="text-sm mt-1">Passager: ${data.passenger}</p>` : ''}
                                    ${data.seat ? `<p class="text-sm">Siège: ${data.seat}</p>` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                    updateStatus('Billet valide!', false, 'bg-green-700');
                } else {
                    qrResult.innerHTML = `
                        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 ticket-invalid">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="font-medium">Billet invalide</p>
                                    <p class="text-sm">${data.message}</p>
                                    <p class="text-sm mt-1">Code: ${ticketCode}</p>
                                </div>
                            </div>
                        </div>
                    `;
                    updateStatus('Billet invalide!', false, 'bg-red-700');
                }
            }
            
            // Fonction pour ajouter aux scans récents
            function addToRecentScans(data, ticketCode) {
                const timestamp = new Date().toLocaleTimeString();
                const scanItem = {
                    code: ticketCode,
                    success: data.success,
                    message: data.message,
                    timestamp: timestamp,
                    passenger: data.passenger || null,
                    seat: data.seat || null
                };
                
                // Ajouter au début du tableau
                recentScans.unshift(scanItem);
                
                // Limiter à 10 scans récents
                if (recentScans.length > 10) {
                    recentScans.pop();
                }
                
                // Mettre à jour l'affichage
                updateRecentScans();
            }
            
            // Fonction pour mettre à jour l'affichage des scans récents
            function updateRecentScans() {
                if (recentScans.length === 0) {
                    recentScansContainer.innerHTML = `
                        <div class="p-4 text-gray-500 text-center">
                            Aucun scan récent
                        </div>
                    `;
                    return;
                }
                
                recentScansContainer.innerHTML = '';
                
                recentScans.forEach(scan => {
                    const scanElement = document.createElement('div');
                    scanElement.className = `p-4 ${scan.success ? 'border-l-4 border-green-500' : 'border-l-4 border-red-500'}`;
                    
                    scanElement.innerHTML = `
                        <div class="flex justify-between">
                            <div>
                                <p class="font-medium ${scan.success ? 'text-green-700' : 'text-red-700'}">
                                    ${scan.success ? 'Valide' : 'Invalide'}
                                </p>
                                <p class="text-sm text-gray-600">${scan.message}</p>
                                ${scan.passenger ? `<p class="text-sm text-gray-600">Passager: ${scan.passenger}</p>` : ''}
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">${scan.timestamp}</p>
                                <p class="text-xs text-gray-400 mt-1 truncate" style="max-width: 120px;">${scan.code}</p>
                            </div>
                        </div>
                    `;
                    
                    recentScansContainer.appendChild(scanElement);
                });
            }
            
            // Fonction pour mettre à jour le statut
            function updateStatus(message, isError = false, bgColor = null) {
                scannerStatus.textContent = message;
                
                if (bgColor) {
                    scannerStatus.className = `${bgColor} text-white text-center py-2 text-sm`;
                } else {
                    scannerStatus.className = `${isError ? 'bg-red-600' : 'bg-gray-800'} text-white text-center py-2 text-sm`;
                }
            }
            
            // Événement pour le bouton de flash
            toggleFlashBtn.addEventListener('click', function() {
                if (!currentStream) return;
                
                const track = currentStream.getVideoTracks()[0];
                
                // Vérifier si la torche est disponible
                const capabilities = track.getCapabilities();
                if (!capabilities.torch) {
                    updateStatus('La torche n\'est pas disponible sur cet appareil', true);
                    return;
                }
                
                // Inverser l'état de la torche
                flashOn = !flashOn;
                
                // Appliquer le changement
                track.applyConstraints({
                    advanced: [{ torch: flashOn }]
                })
                .then(() => {
                    toggleFlashBtn.innerHTML = flashOn ? 
                        `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>` : 
                        `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>`;
                    
                    if (flashOn) {
                        toggleFlashBtn.classList.add('bg-yellow-400', 'text-gray-900');
                        toggleFlashBtn.classList.remove('bg-white', 'bg-opacity-75');
                    } else {
                        toggleFlashBtn.classList.remove('bg-yellow-400', 'text-gray-900');
                        toggleFlashBtn.classList.add('bg-white', 'bg-opacity-75');
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de l\'activation de la torche:', error);
                    updateStatus('Erreur lors de l\'activation de la torche', true);
                });
            });
            
            // Événement pour le bouton de changement de caméra
            switchCameraBtn.addEventListener('click', function() {
                currentFacingMode = currentFacingMode === 'environment' ? 'user' : 'environment';
                initCamera();
            });
            
            // Événement pour la validation manuelle
            validateManualBtn.addEventListener('click', function() {
                const code = manualCodeInput.value.trim();
                 const trajetId = trajetSelect.value;
                if (!trajetId) {
                    showResult({ success: false, message: 'Veuillez sélectionner un trajet.' }, code);
                    return;
                }

                if (code) {
                    validateTicket(code,trajetId);
                    manualCodeInput.value = '';
                }
            });
            
            // Événement pour la touche Entrée dans le champ de saisie manuelle
            manualCodeInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                     const trajetId = trajetSelect.value;
                      if (!trajetId) {
                        showResult({ success: false, message: 'Veuillez sélectionner un trajet.' }, "");
                            return;
                        }
                    const code = manualCodeInput.value.trim();
                    if (code) {
                        validateTicket(code,trajetId);
                        manualCodeInput.value = '';
                    }
                }
            });

            function sendData(qrCodeData) {
                 const trajetId = trajetSelect.value;
                  if (!trajetId) {
                     showResult({ success: false, message: 'Veuillez sélectionner un trajet.' }, qrCodeData);
                       scanning = true; // Reprendre le scan
                       requestAnimationFrame(scan);
                      return;
                  }
                fetch('/chauffeur/billet/valider', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ numero_billet: qrCodeData , trajet_id: trajetId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        qrResult.innerHTML = `<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">Billet valide: ${data.message}</div>`;
                    } else {
                        qrResult.innerHTML = `<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">Erreur: ${data.message}</div>`;
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    qrResult.innerHTML = `<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">Erreur réseau. Veuillez réessayer.</div>`;
                });
            }
        });
    </script>
@endsection



