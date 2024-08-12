<?php

class HomeController {
    public function index() {
        $userData = [
            'isLoggedIn' => isset($_SESSION['usuario_id']),
            'username' => $_SESSION['username'] ?? '',
            'profilePicture' => $this->getProfilePictureUrl($_SESSION['profile_picture'] ?? '')
        ];

        // Aquí podrías agregar más lógica si es necesario

        return $userData;
    }

    private function getProfilePictureUrl($profilePicture) {
        $defaultImage = 'Public/dist/img/profile.jpg';
        if ($profilePicture && $profilePicture !== $defaultImage) {
            return '/uploads/' . $profilePicture;
        }
        return $profilePicture ?: $defaultImage;
    }
}