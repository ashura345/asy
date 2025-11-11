<div x-data="themeToggle()" class="flex items-center space-x-2">
    <button 
        @click="toggleTheme"
        class="px-4 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600 transition"
    >
        <span x-text="isDark ? '☀️ Light Mode' : '🌙 Dark Mode'"></span>
    </button>
</div>

<script>
    function themeToggle() {
        return {
            isDark: localStorage.theme === 'dark',
            toggleTheme() {
                this.isDark = !this.isDark;
                document.documentElement.classList.toggle('dark', this.isDark);
                localStorage.theme = this.isDark ? 'dark' : 'light';
            },
            init() {
                if (this.isDark) {
                    document.documentElement.classList.add('dark');
                }
            }
        }
    }
</script>
