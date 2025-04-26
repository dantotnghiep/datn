<nav class="flex flex-col h-full bg-white text-gray-700">
    <div class="p-4 border-b border-gray-200">
        <div class="flex items-center gap-3">
            <img src="{{ asset('img/logo.svg') }}" alt="Logo" class="w-8 h-8" onerror="this.src='data:image/svg+xml;charset=UTF-8,%3csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'%230ea5e9\'%3e%3cpath d=\'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z\'/%3e%3c/svg%3e'">
            <span class="text-xl font-bold text-blue-600">CoreAdmin</span>
        </div>
    </div>

    <div class="px-3 py-4 flex-1">
        <div class="space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600 font-medium shadow-sm border-l-4 border-blue-500' : 'hover:bg-gray-100 hover:text-blue-600' }} transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ request()->routeIs('admin.dashboard') ? 'text-blue-500' : 'text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Home</span>
            </a>

            <!-- Menu đa cấp Categories -->
            <div x-data="{ open: {{ request()->routeIs('admin.categories.*') ? 'true' : 'false' }} }">
                <a href="#" @click.prevent="open = !open" class="flex items-center justify-between px-4 py-3 text-sm rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-blue-50 text-blue-600 font-medium shadow-sm border-l-4 border-blue-500' : 'hover:bg-gray-100 hover:text-blue-600' }} transition-all duration-200">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ request()->routeIs('admin.categories.*') ? 'text-blue-500' : 'text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span>Categories</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform {{ request()->routeIs('admin.categories.*') ? 'text-blue-500' : 'text-gray-500' }}" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </a>
                
                <div x-show="open" x-collapse class="mt-1 mb-2">
                    <div class="pl-6 space-y-1 border-l border-gray-200 ml-4">
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm rounded-lg {{ request()->routeIs('admin.categories.index') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:bg-gray-100 hover:text-blue-600' }} transition-all duration-200">
                            <span>All Categories</span>
                        </a>
                        <a href="{{ route('admin.categories.create') }}" class="flex items-center gap-2 px-4 py-2 text-sm rounded-lg {{ request()->routeIs('admin.categories.create') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:bg-gray-100 hover:text-blue-600' }} transition-all duration-200">
                            <span>Add New</span>
                        </a>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm rounded-lg {{ request()->routeIs('admin.products.*') ? 'bg-blue-50 text-blue-600 font-medium shadow-sm border-l-4 border-blue-500' : 'hover:bg-gray-100 hover:text-blue-600' }} transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ request()->routeIs('admin.products.*') ? 'text-blue-500' : 'text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                </svg>
                <span>Broadcasts</span>
            </a>

            <a href="{{ route('admin.names.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm rounded-lg {{ request()->routeIs('admin.names.*') ? 'bg-blue-50 text-blue-600 font-medium shadow-sm border-l-4 border-blue-500' : 'hover:bg-gray-100 hover:text-blue-600' }} transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ request()->routeIs('admin.names.*') ? 'text-blue-500' : 'text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Settings</span>
            </a>
        </div>
    </div>
    
    <div class="p-3 border-t border-gray-200 mt-auto">
        <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 transition-all duration-200">
            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="User" class="w-10 h-10 rounded-full border-2 border-blue-300">
            <div class="min-w-0">
                <div class="text-sm font-medium truncate">Erica</div>
                <div class="text-xs text-gray-500 truncate">erica@example.com</div>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-auto text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
        </div>
    </div>
</nav>