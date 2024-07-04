<?php
include_once('Controller/session_controller.php');
use contribue\Controller\session_controller;
$session = new session_controller();
global $uri;
$pathSegments = explode('/', $uri);
$viewPages = 'View/Pages/'.$pathSegments[2].'.php';
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/www_public/css/output.css" rel="stylesheet">
    <style type="text/tailwindcss">
        @layer utilities {
            .content-auto {
                content-visibility: auto;
            }
        }
    </style>
    <style>
        /* since nested groupes are not supported we have to use
           regular css for the nested dropdowns
        */
        li > ul {
            transform: translatex(100%) scale(0)
        }

        li:hover > ul {
            transform: translatex(101%) scale(1)
        }

        li > button svg {
            transform: rotate(-90deg)
        }

        li:hover > button svg {
            transform: rotate(-270deg)
        }

        /* Below styles fake what can be achieved with the tailwind config
           you need to add the group-hover variant to scale and define your custom
           min width style.
             See https://codesandbox.io/s/tailwindcss-multilevel-dropdown-y91j7?file=/index.html
             for implementation with config file
        */
        .group:hover .group-hover\:scale-100 {
            transform: scale(1)
        }

        .group:hover .group-hover\:-rotate-180 {
            transform: rotate(180deg)
        }

        .scale-0 {
            transform: scale(0)
        }

        .min-w-32 {
            min-width: 8rem
        }
    </style>
    <title>Inicio</title>
</head>
<body>
<!-- text-gray-900 transition-all duration-200 hover:bg-gray-100 hover:text-purple-600 -->

<div class="lg:content-auto min-h-screen bg-gray-100 ">
    <nav class="bg-white/80 text-gray-900 border border-gray shadow backdrop-blur-lg">
        <div class="container mx-auto px-4 md:flex items-center gap-6">
            <!-- Logo -->
            <div class="flex items-center justify-between md:w-auto ">
                <img class="px-2 w-2/4 my-4" src="www_public/img/logo_contribue.svg" alt=""/>

                <!-- mobile menu icon -->
                <div class="md:hidden flex items-center">
                    <button type="button" class="mobile-menu-button">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <?php include_once('View/Components/Menu.php');?>
        </div>

    </nav>
    <div class="bg-white dark:bg-gray-900 py-2 flex flex-col justify-center sm:py-12">
        <?php include_once($viewPages);?>
    </div>
</div>
<script src="/www_public/scripts/contribue_form.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Select all dropdown toggle buttons
        const dropdownToggles = document.querySelectorAll(".dropdown-toggle")

        dropdownToggles.forEach((toggle) => {
            toggle.addEventListener("click", () => {
                // Find the next sibling element which is the dropdown menu
                const dropdownMenu = toggle.nextElementSibling

                // Toggle the 'hidden' class to show or hide the dropdown menu
                if (dropdownMenu.classList.contains("hidden")) {
                    // Hide any open dropdown menus before showing the new one
                    document.querySelectorAll(".dropdown-menu").forEach((menu) => {
                        menu.classList.add("hidden")
                    })

                    dropdownMenu.classList.remove("hidden")
                } else {
                    dropdownMenu.classList.add("hidden")
                }
            })
        })

        // Clicking outside of an open dropdown menu closes it
        window.addEventListener("click", function (e) {
            if (!e.target.matches(".dropdown-toggle")) {
                document.querySelectorAll(".dropdown-menu").forEach((menu) => {
                    if (!menu.contains(e.target)) {
                        menu.classList.add("hidden");
                    }
                })
            }
        })

        // Mobile menu toggle

        const mobileMenuButton = document.querySelector('.mobile-menu-button')
        const mobileMenu = document.querySelector('.navigation-menu')

        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden')
        })


    })

</script>
</body>
</html>
