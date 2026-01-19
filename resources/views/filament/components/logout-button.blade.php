<div class="py-2 border-t border-gray-200 dark:border-white/10 mt-auto">
    <form action="{{ filament()->getLogoutUrl() }}" method="post" class="w-full">
        @csrf
        <button type="submit"
            class="flex items-center gap-3 px-3 py-2 w-full text-sm font-medium transition-colors rounded-lg text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-white/5 group">
            <x-heroicon-o-arrow-left-on-rectangle
                class="w-5 h-5 text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-400" />
            <span>Logout</span>
        </button>
    </form>
</div>