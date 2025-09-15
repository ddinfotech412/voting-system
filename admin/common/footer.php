            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Wait for CanvasJS to be available
        function waitForCanvasJS(callback) {
            if (typeof CanvasJS !== 'undefined') {
                callback();
            } else {
                setTimeout(function() {
                    waitForCanvasJS(callback);
                }, 100);
            }
        }
        
        // Initialize all charts when CanvasJS is ready
        document.addEventListener('DOMContentLoaded', function() {
            waitForCanvasJS(function() {
                // Trigger chart initialization events
                window.dispatchEvent(new CustomEvent('canvasjsReady'));
            });
        });
        
        // Handle sidebar navigation
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarItems = document.querySelectorAll('.sidebar-item');
            
            sidebarItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remove active class from all items
                    sidebarItems.forEach(si => si.classList.remove('active'));
                    
                    // Add active class to clicked item
                    this.classList.add('active');
                    
                    // Get the page name from href
                    const page = this.getAttribute('href').replace('.php', '');
                    
                    // Redirect to admin.php with page parameter
                    window.location.href = `admin.php?page=${page}`;
                });
            });
            
            // Set active state based on current page
            const urlParams = new URLSearchParams(window.location.search);
            const currentPage = urlParams.get('page') || 'dashboard';
            
            sidebarItems.forEach(item => {
                const href = item.getAttribute('href').replace('.php', '');
                if (href === currentPage) {
                    item.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
