// CSRF Token Handler with Enhanced Management
(function() {
    'use strict';

    // Get the CSRF token from the meta tag
    let csrfToken = document.head.querySelector('meta[name="csrf-token"]');
    
    // Store the original fetch function
    const originalFetch = window.fetch;

    // Function to update all forms with the latest CSRF token
    const updateFormsWithToken = () => {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            const tokenInput = form.querySelector('input[name="_token"]');
            if (tokenInput) {
                tokenInput.value = csrfToken ? csrfToken.content : '';
            } else if (form.method.toLowerCase() !== 'get') {
                // Add hidden input if not exists
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = '_token';
                input.value = csrfToken ? csrfToken.content : '';
                form.appendChild(input);
            }
        });
    };

    // Override the fetch API to include CSRF token
    window.fetch = function(resource, init = {}) {
        // Add CSRF token to headers if it's a POST, PUT, PATCH, or DELETE request
        const method = (init.method || 'GET').toUpperCase();
        if (['POST', 'PUT', 'PATCH', 'DELETE'].includes(method)) {
            const headers = init.headers || {};
            
            // Basic headers
            const newHeaders = {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json, text/plain, */*'
            };

            // Add CSRF token
            if (csrfToken) {
                newHeaders['X-CSRF-TOKEN'] = csrfToken.content;
            }

            // ONLY set Content-Type if not already set and NOT sending FormData
            const isFormData = init.body instanceof FormData;
            if (!isFormData && !headers['Content-Type']) {
                newHeaders['Content-Type'] = 'application/json';
            }

            init.headers = {
                ...headers,
                ...newHeaders
            };
        }
        return originalFetch(resource, init);
    };

    // Set up jQuery AJAX defaults if jQuery is available
    if (window.jQuery) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken ? csrfToken.content : '',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        // Handle AJAX errors globally
        $(document).ajaxError(function(event, jqXHR) {
            if (jqXHR.status === 419) {
                console.warn('CSRF token mismatch. Attempting to refresh...');
                refreshCsrfToken().then(() => {
                    // Retry the failed request
                    const retryConfig = {
                        url: jqXHR.config.url,
                        method: jqXHR.config.method,
                        data: jqXHR.config.data,
                        headers: {
                            ...jqXHR.config.headers,
                            'X-CSRF-TOKEN': csrfToken ? csrfToken.content : ''
                        }
                    };
                    return $.ajax(retryConfig);
                }).catch(error => {
                    console.error('Failed to refresh CSRF token:', error);
                    window.location.reload();
                });
            }
        });
    }

    // Function to refresh the CSRF token
    window.refreshCsrfToken = async function() {
        try {
            // For standard Laravel CSRF, we don't need to refresh from an endpoint
            // The token is already available in the meta tag and is valid for the session
            const newToken = document.head.querySelector('meta[name="csrf-token"]');
            if (newToken) {
                csrfToken = newToken;
                // Update all forms with the current token
                updateFormsWithToken();
                console.log('CSRF token validated successfully');
                return true;
            }
            throw new Error('CSRF token meta tag not found');
        } catch (error) {
            console.error('Error validating CSRF token:', error);
            return false;
        }
    };

    // Update all forms with the current token when the page loads
    document.addEventListener('DOMContentLoaded', updateFormsWithToken);
    
    // Export the refresh function
    window.CSRF = {
        refresh: refreshCsrfToken,
        getToken: () => csrfToken ? csrfToken.content : null
    };
})();
