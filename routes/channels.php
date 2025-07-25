<?php

// Canal privé pour une conversation entre deux utilisateurs
Broadcast::channel('chat.{userOne}.{userTwo}', function ($user, $userOne, $userTwo) {
    // Autorise si l'utilisateur courant est l'un des deux membres de la conversation
    return (int) $user->id === (int) $userOne || (int) $user->id === (int) $userTwo;
});
