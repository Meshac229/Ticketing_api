<?php

namespace App\Swagger;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Event",
 *     required={"event_id", "event_title", "event_description", "event_date", "event_image", "event_category", "event_city", "event_address", "event_status"},
 *     @OA\Property(property="event_id", type="integer"),
 *     @OA\Property(property="event_title", type="string", maxLength=30),
 *     @OA\Property(property="event_description", type="string", maxLength=16777215),
 *     @OA\Property(property="event_date", type="string", format="date"),
 *     @OA\Property(property="event_image", type="string", maxLength=200),
 *     @OA\Property(property="event_category", type="string", enum={"Autre", "Concert-Spectacle", "Diner Gala", "Festival", "Formation"}),
 *     @OA\Property(property="event_city", type="string", maxLength=100),
 *     @OA\Property(property="event_address", type="string", maxLength=200),
 *     @OA\Property(property="event_status", type="string", enum={"upcoming", "completed", "cancelled"})
 * )
 */

/**
 * @OA\Schema(
 *     schema="EventRequest",
 *     required={"event_title", "event_description", "event_date", "event_image", "event_category", "event_city", "event_address", "event_status"},
 *     @OA\Property(property="event_title", type="string", maxLength=30),
 *     @OA\Property(property="event_description", type="string", maxLength=16777215),
 *     @OA\Property(property="event_date", type="string", format="date"),
 *     @OA\Property(property="event_image", type="string", maxLength=200),
 *     @OA\Property(property="event_category", type="string", enum={"Autre", "Concert-Spectacle", "Diner Gala", "Festival", "Formation"}),
 *     @OA\Property(property="event_city", type="string", maxLength=100),
 *     @OA\Property(property="event_address", type="string", maxLength=200),
 *     @OA\Property(property="event_status", type="string", enum={"upcoming", "completed", "cancelled"})
 * )
 */

/**
 * @OA\Schema(
 *     schema="EventUpdateRequest",
 *     @OA\Property(property="event_title", type="string", maxLength=255),
 *     @OA\Property(property="event_description", type="string"),
 *     @OA\Property(property="event_date", type="string", format="date"),
 *     @OA\Property(property="event_category", type="string"),
 *     @OA\Property(property="event_image", type="string"),
 *     @OA\Property(property="event_city", type="string"),
 *     @OA\Property(property="event_address", type="string"),
 *     @OA\Property(property="event_status", type="string", enum={"upcoming", "completed", "cancelled"})
 * )
 */
class Schemas {}
