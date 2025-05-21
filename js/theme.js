document.addEventListener('DOMContentLoaded', function() 
{
    const themeToggle = document.getElementById('theme-toggle');
    
    // Function to set theme and save preference
    function setTheme(isDark) 
    {
        // Apply the theme to both html and body elements
        if (isDark) 
        {
            document.documentElement.classList.add('dark-theme');
            document.body.classList.add('dark-theme');
            if (themeToggle) themeToggle.textContent = 'Light Mode';
        } 
        else 
        {
            document.documentElement.classList.remove('dark-theme');
            document.body.classList.remove('dark-theme');
            if (themeToggle) themeToggle.textContent = 'Dark Mode';
        }
        
        // Save to localStorage and cookie
        const theme = isDark ? 'dark' : 'light';
        localStorage.setItem('theme', theme);
        
        // Set cookie with path=/ to ensure it's available across all pages
        document.cookie = `theme=${theme}; path=/; max-age=31536000`; // 1 year expiry
        
        // Dispatch a custom event that other scripts can listen for
        const event = new CustomEvent('themeChanged', { detail: { theme: theme } });
        document.dispatchEvent(event);
        
        console.log(`Theme changed to: ${theme}`);
    }
    
    // Initialize theme from storage
    function initializeTheme() 
    {
        // Check localStorage first
        let savedTheme = localStorage.getItem('theme');
        
        // If not in localStorage, check cookie
        if (!savedTheme) 
        {
            const cookieMatch = document.cookie.match(/theme=([^;]+)/);
            if (cookieMatch) 
            {
                savedTheme = cookieMatch[1];
            }
        }
        
        // Apply the theme
        const isDark = savedTheme === 'dark';
        setTheme(isDark);
        
        console.log(`Theme initialized: ${savedTheme || 'light (default)'}`);
    }
    
    // Initialize the theme when page loads
    initializeTheme();
    
    // Toggle theme on button click
    if (themeToggle) 
    {
        themeToggle.addEventListener('click', function() 
        {
            console.log('Theme toggle button clicked');
            const isDark = !document.documentElement.classList.contains('dark-theme');
            setTheme(isDark);
        });
    } 
    else 
    {
        console.warn('Theme toggle button not found in the DOM');
    }

    // Debug theme status
    console.log('Current theme:', localStorage.getItem('theme'));
    console.log('Dark theme class on html:', document.documentElement.classList.contains('dark-theme'));
    console.log('Dark theme class on body:', document.body.classList.contains('dark-theme'));
});

