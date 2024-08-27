<?php

class HomeController
{
    public function index()
    {
        $fullName = (isset($_SESSION['nombres']) ? $_SESSION['nombres'] : '') . ' ' .
                    (isset($_SESSION['apellidos']) ? $_SESSION['apellidos'] : '');
    
        $userData = [
            'isLoggedIn' => isset($_SESSION['usuario_id']),
            'username' => $_SESSION['username'] ?? '',
            'profilePicture' => $this->getProfilePictureUrl($_SESSION['profile_picture'] ?? ''),
            'fullName' => $fullName,
            'apodo' => isset($_SESSION['apodo']) ? $_SESSION['apodo'] : 'Usuario', // Nota: 'apodo' en minúsculas
        ];
    
        // Para depuración, puedes agregar esto:
        error_log("userData en HomeController: " . print_r($userData, true));
    
        return $userData;
    }

    private function getProfilePictureUrl($profilePicture)
    {
        $defaultImage = 'Public/dist/img/profile.jpg';
        if ($profilePicture && $profilePicture !== $defaultImage) {
            return '/uploads/' . $profilePicture;
        }
        return $profilePicture ?: $defaultImage;
    }
}