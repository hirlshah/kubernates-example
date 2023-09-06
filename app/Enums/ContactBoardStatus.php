<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ContactBoardStatus extends Enum
{

    /**
     * DO NOT CHANGE VALUE IN PRODUCTION
     */
	const MESSAGE_SENT       = 1; // Message envoyé
	const MESSAGE_ANSWERED   = 2; // Message répondu
	const ZOOM_INVITE_SENT   = 3; // Invitation au Zoom envoyée
	const CONFIRMED_FOR_ZOOM = 4; // Présence au Zoom confirmée
	const ATTENDED_THE_ZOOM  = 5; // Présent au Zoom
	const NEW_DISTRIBUTOR    = 6; // Nouveau distributeur
	const NEW_CLIENT         = 7; // Nombre de nouveaux clients
	const FOLLOWUP           = 8; // Suivi organisé
	const NOT_INTERESTED     = 9; // Non intéressées
}
