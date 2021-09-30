<?php

if (!function_exists('setActiveLink')) {
    function setActiveLink(mixed $route): string
    {
        return request()->routeIs($route) ? 'active' : '';
    }
}
