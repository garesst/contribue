<?php
?>

<div class="hidden md:flex md:flex-row flex-col items-center justify-start md:space-x-1 pb-3 md:pb-0 navigation-menu">
    <a href="#" class="py-2 px-3 block transition-all duration-200 hover:bg-gray-100 hover:text-purple-600">Inicio</a>
    <!-- Dropdown menu -->
    <div class="relative">
        <button type="button"
                class="dropdown-toggle py-2 px-3 flex items-center gap-2 rounded transition-all duration-200 hover:bg-gray-100 hover:text-purple-600">
            <span class="pointer-events-none select-none">Adminitracion</span>
            <svg class="w-3 h-3 pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
            </svg>
        </button>
        <div class="dropdown-menu absolute hidden bg-white/80 text-gray-900 rounded-b-lg pb-2 w-48">
            <a href="#"
               class="block px-6 py-2 transition-all duration-200 hover:bg-gray-100 hover:text-purple-600">Web
                Design</a>
            <a href="#"
               class="block px-6 py-2 transition-all duration-200 hover:bg-gray-100 hover:text-purple-600">Web
                Development</a>
            <a href="#"
               class="block px-6 py-2 transition-all duration-200 hover:bg-gray-100 hover:text-purple-600">SEO</a>
        </div>
    </div>
    <!-- Dropdown menu -->
    <div class="relative">
        <button type="button"
                class="dropdown-toggle py-2 px-3 flex items-center gap-2 rounded transition-all duration-200 hover:bg-gray-100 hover:text-purple-600">
            <span class="pointer-events-none select-none">Ventas</span>
            <svg class="w-3 h-3 pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
            </svg>
        </button>
        <div class="dropdown-menu absolute hidden bg-white/80 text-gray-900 rounded-b-lg pb-2 w-48">
            <a href="#"
               class="block px-6 py-2 transition-all duration-200 hover:bg-gray-100 hover:text-purple-600">Web
                Design</a>
            <a href="#"
               class="block px-6 py-2 transition-all duration-200 hover:bg-gray-100 hover:text-purple-600">Web
                Development</a>
            <a href="#"
               class="block px-6 py-2 transition-all duration-200 hover:bg-gray-100 hover:text-purple-600">SEO</a>
        </div>
    </div>
    <a href="#" class="py-2 px-3 block transition-all duration-200 hover:bg-gray-100">Ajustes</a>

    <div class="group inline-block">
        <button class="outline-none focus:outline-none px-3 py-1 flex items-center min-w-32 transition-all duration-200 hover:bg-gray-100">
            <span class="pr-1 font-semibold flex-1 hover:text-purple-600">Dropdown</span>
            <span>
            <svg class="fill-current h-4 w-4 transform group-hover:-rotate-180 transition duration-150 ease-in-out" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
            </svg>
        </span>
        </button>
        <ul class="bg-white border rounded-sm transform scale-0 group-hover:scale-100 absolute transition duration-150 ease-in-out origin-top min-w-32">
            <li class="rounded-sm px-3 py-1 hover:bg-gray-100">Programming</li>
            <li class="rounded-sm px-3 py-1 hover:bg-gray-100">DevOps</li>
            <li class="rounded-sm relative px-3 py-1 hover:bg-gray-100">
                <button class="w-full text-left flex items-center outline-none focus:outline-none">
                    <span class="pr-1 flex-1">Langauges</span>
                    <span class="mr-auto">
                    <svg class="fill-current h-4 w-4 transition duration-150 ease-in-out" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                    </svg>
                </span>
                </button>
                <ul class="bg-white border rounded-sm absolute top-0 right-0 transition duration-150 ease-in-out origin-top-left min-w-32">
                    <li class="px-3 py-1 hover:bg-gray-100">Javascript</li>
                    <li class="rounded-sm relative px-3 py-1 hover:bg-gray-100">
                        <button class="w-full text-left flex items-center outline-none focus:outline-none">
                            <span class="pr-1 flex-1">Python</span>
                            <span class="mr-auto">
                            <svg class="fill-current h-4 w-4 transition duration-150 ease-in-out" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                            </svg>
                        </span>
                        </button>
                        <ul class="bg-white border rounded-sm absolute top-0 right-0 transition duration-150 ease-in-out origin-top-left min-w-32">
                            <li class="px-3 py-1 hover:bg-gray-100">2.7</li>
                            <li class="px-3 py-1 hover:bg-gray-100">3+</li>
                        </ul>
                    </li>
                    <li class="px-3 py-1 hover:bg-gray-100">Go</li>
                    <li class="px-3 py-1 hover:bg-gray-100">Rust</li>
                </ul>
            </li>
            <li class="rounded-sm px-3 py-1 hover:bg-gray-100">Testing</li>
        </ul>
    </div>


</div>

