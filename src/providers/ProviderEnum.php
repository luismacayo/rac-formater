<?php

namespace luismacayo\RacFormater\providers;

enum ProviderEnum
{
    case WEATHER_API;
    case WTTR_API;
    case WEATHER_DECORATED_API;

    case DUCK_API;
    case DUCK_FALLBACK_API;

}