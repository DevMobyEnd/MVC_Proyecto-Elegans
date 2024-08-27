// function initializeEditProfileForm() {
//     let currentStep = 1;
//     const totalSteps = 2;

//     function showStep(step) {
//         document.querySelectorAll('.register-step').forEach(s => s.style.display = 'none');
//         document.getElementById(`step${step}`).style.display = 'block';
        
//         document.getElementById('prevBtn').style.display = step > 1 ? 'inline-block' : 'none';
//         document.getElementById('nextBtn').style.display = step < totalSteps ? 'inline-block' : 'none';
//         document.getElementById('submitBtn').style.display = step === totalSteps ? 'inline-block' : 'none';
//     }

//     document.getElementById('nextBtn').addEventListener('click', () => {
//         if (currentStep < totalSteps) {
//             currentStep++;
//             showStep(currentStep);
//         }
//     });

//     document.getElementById('prevBtn').addEventListener('click', () => {
//         if (currentStep > 1) {
//             currentStep--;
//             showStep(currentStep);
//         }
//     });

//     // Inicializar mostrando el primer paso
//     showStep(1);
// }